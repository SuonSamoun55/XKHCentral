<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MagamentSystemModel\ChatMessage;
use App\Models\MagamentSystemModel\Notification;
use App\Models\MagamentSystemModel\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function userIndex(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $admins = User::query()
            ->where(function ($q) {
                $q->where('role', 'admin')
                    ->orWhereHas('roleRelation', function ($roleQ) {
                        $roleQ->where('name', 'admin');
                    });
            })
            ->orderBy('name')
            ->get();

        if ($admins->isEmpty()) {
            return back()->with('error', 'No admin account found.');
        }

        $adminId = (int) ($request->get('admin_id') ?: $admins->first()->id);
        if (!$admins->pluck('id')->contains($adminId)) {
            $adminId = (int) $admins->first()->id;
        }

        $this->markThreadAsRead($user->id, $adminId);

        $contacts = $this->buildContactCards($user->id, $admins);
        $activeContact = $contacts->firstWhere('id', $adminId);
        if (!$activeContact) {
            $activeContact = $contacts->first();
            $adminId = (int) ($activeContact->id ?? 0);
        }

        $messages = $adminId
            ? $this->threadMessages($user->id, $adminId)
            : collect();

        return view('POSViews.POSUserViews.ChatView', [
            'currentUser' => $user,
            'contacts' => $contacts,
            'activeContactId' => $adminId,
            'activeContact' => $activeContact,
            'messages' => $messages,
        ]);
    }

    public function userSend(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:30'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'voice' => ['nullable', 'file', 'mimetypes:audio/webm,video/webm,audio/ogg,video/ogg,audio/wav,audio/x-wav,audio/mpeg,audio/mp3,audio/mp4,audio/x-m4a,audio/aac', 'max:10240'],
        ]);

        $receiver = User::findOrFail($validated['receiver_id']);

        if (!$receiver->isAdmin()) {
            return back()->with('error', 'You can only message admin.');
        }

        $chatMessage = $this->buildAndStoreChatMessage($request, $validated, (int) $user->id, (int) $receiver->id);
        if (!$chatMessage) {
            return back()->with('error', 'Message cannot be empty.');
        }

        $this->upsertChatNotification(
            receiverId: (int) $receiver->id,
            sender: $user,
            type: 'user_contact',
            baseTitle: 'New chat message',
            messageText: $this->previewTextForNotification($chatMessage)
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $this->mapMessageForJson($chatMessage->load('sender'), $user->id),
            ]);
        }

        return redirect()->route('user.chat.index', ['admin_id' => $receiver->id]);
    }

    public function adminIndex(Request $request)
    {
        $admin = Auth::user();
        if (!$admin) {
            return redirect()->route('login');
        }
        if (!$admin->isAdmin()) {
            abort(403);
        }

        $contactIds = ChatMessage::query()
            ->where('sender_id', $admin->id)
            ->orWhere('receiver_id', $admin->id)
            ->get(['sender_id', 'receiver_id'])
            ->flatMap(function ($row) use ($admin) {
                return [
                    $row->sender_id == $admin->id ? $row->receiver_id : $row->sender_id,
                ];
            })
            ->unique()
            ->values();

        $contacts = User::query()
            ->whereIn('id', $contactIds)
            ->where('id', '!=', $admin->id)
            ->orderBy('name')
            ->get();

        $activeContactId = (int) $request->get('user_id');
        if ($activeContactId && !$contacts->pluck('id')->contains($activeContactId)) {
            $requestedUser = User::query()
                ->where('id', $activeContactId)
                ->where('id', '!=', $admin->id)
                ->first();

            if ($requestedUser) {
                $contacts->push($requestedUser);
            }
        }

        $contacts = $this->buildContactCards($admin->id, $contacts);

        if (!$activeContactId && $contacts->isNotEmpty()) {
            $activeContactId = (int) $contacts->first()->id;
        }

        if ($activeContactId && !$contacts->pluck('id')->contains($activeContactId)) {
            $activeContactId = 0;
        }

        if ($activeContactId) {
            $this->markThreadAsRead($admin->id, $activeContactId);
        }

        $messages = $activeContactId
            ? $this->threadMessages($admin->id, $activeContactId)
            : collect();
        $activeContact = $contacts->firstWhere('id', $activeContactId);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'contacts' => $contacts->map(fn ($contact) => $this->mapContactForJson($contact, $activeContactId))->values(),
                'active_contact_id' => (int) $activeContactId,
                'active_contact' => $activeContact ? $this->mapActiveContactForJson($activeContact) : null,
                'messages' => $messages->map(fn ($message) => $this->mapMessageForJson($message, (int) $admin->id))->values(),
                'shared_media' => $messages
                    ->where('message_type', 'image')
                    ->where('attachment_path', '!=', null)
                    ->map(function ($message) {
                        return [
                            'url' => '/storage/' . ltrim((string) $message->attachment_path, '/'),
                            'title' => optional($message->created_at)->format('M d, Y g:i A'),
                        ];
                    })
                    ->values(),
            ]);
        }

        return view('ManagementSystemViews.AdminViews.Layouts.Notifications.AdminChatView', [
            'currentUser' => $admin,
            'contacts' => $contacts,
            'activeContactId' => $activeContactId,
            'activeContact' => $activeContact,
            'messages' => $messages,
        ]);
    }

    public function adminSend(Request $request)
    {
        $admin = Auth::user();
        if (!$admin) {
            return redirect()->route('login');
        }
        if (!$admin->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:30'],
            'image' => ['nullable', 'file', 'image', 'max:10240'],
            'voice' => ['nullable', 'file', 'mimetypes:audio/webm,video/webm,audio/ogg,video/ogg,audio/wav,audio/x-wav,audio/mpeg,audio/mp3,audio/mp4,audio/x-m4a,audio/aac', 'max:10240'],
        ]);

        $receiver = User::findOrFail($validated['receiver_id']);
        if ($receiver->isAdmin()) {
            return back()->with('error', 'Please select a customer/user.');
        }

        $chatMessage = $this->buildAndStoreChatMessage($request, $validated, (int) $admin->id, (int) $receiver->id);
        if (!$chatMessage) {
            return back()->with('error', 'Message cannot be empty.');
        }

        $this->upsertChatNotification(
            receiverId: (int) $receiver->id,
            sender: $admin,
            type: 'admin_message',
            baseTitle: 'Message from admin',
            messageText: $this->previewTextForNotification($chatMessage)
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $this->mapMessageForJson($chatMessage->load('sender'), $admin->id),
            ]);
        }

        return redirect()->route('admin.chat.index', ['user_id' => $receiver->id]);
    }

    public function userMessages(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $adminId = (int) $request->get('admin_id');
        if ($adminId <= 0) {
            return response()->json(['message' => 'Missing admin_id'], 422);
        }

        $admin = User::find($adminId);
        if (!$admin || !$admin->isAdmin()) {
            return response()->json(['message' => 'Invalid admin'], 422);
        }

        $this->markThreadAsRead($user->id, $adminId);

        return $this->messagesJsonResponse(
            $user->id,
            $adminId,
            (int) $request->get('after_id', 0)
        );
    }

    public function adminMessages(Request $request)
    {
        $admin = Auth::user();
        if (!$admin) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if (!$admin->isAdmin()) {
            abort(403);
        }

        $userId = (int) $request->get('user_id');
        if ($userId <= 0) {
            return response()->json(['message' => 'Missing user_id'], 422);
        }

        $contact = User::find($userId);
        if (!$contact || (int) $contact->id === (int) $admin->id) {
            return response()->json(['message' => 'Invalid user'], 422);
        }

        $this->markThreadAsRead($admin->id, $userId);

        return $this->messagesJsonResponse(
            $admin->id,
            $userId,
            (int) $request->get('after_id', 0)
        );
    }

    private function threadMessages(int $userA, int $userB, int $afterId = 0)
    {
        return ChatMessage::query()
            ->with(['sender:id,name,profile_image,profile_image_url'])
            ->where(function ($q) use ($userA, $userB) {
                $q->where(function ($thread) use ($userA, $userB) {
                    $thread->where('sender_id', $userA)->where('receiver_id', $userB);
                })->orWhere(function ($thread) use ($userA, $userB) {
                    $thread->where('sender_id', $userB)->where('receiver_id', $userA);
                });
            })
            ->when($afterId > 0, function ($q) use ($afterId) {
                $q->where('id', '>', $afterId);
            })
            ->orderBy('created_at')
            ->get();
    }

    private function messagesJsonResponse(int $currentUserId, int $otherUserId, int $afterId = 0)
    {
        $messages = $this->threadMessages($currentUserId, $otherUserId, $afterId);
        $contact = User::find($otherUserId);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) use ($currentUserId) {
                return $this->mapMessageForJson($message, $currentUserId);
            })->values(),
            'last_id' => (int) ($messages->max('id') ?? $afterId),
            'contact_presence' => $contact ? [
                'is_online' => (bool) ($contact->is_online ?? false),
                'status_text' => (bool) ($contact->is_online ?? false)
                    ? 'Online'
                    : ((string) ($contact->offline_duration ?? 'Offline')),
            ] : null,
        ]);
    }

    private function mapMessageForJson(ChatMessage $message, int $currentUserId): array
    {
        $attachmentUrl = null;
        if (!empty($message->attachment_path)) {
            $attachmentUrl = '/storage/' . ltrim($message->attachment_path, '/');
        }

        return [
            'id' => (int) $message->id,
            'sender_id' => (int) $message->sender_id,
            'receiver_id' => (int) $message->receiver_id,
            'message' => (string) ($message->message ?? ''),
            'message_type' => (string) ($message->message_type ?? 'text'),
            'attachment_url' => $attachmentUrl,
            'attachment_mime' => $message->attachment_mime,
            'attachment_size' => $message->attachment_size,
            'voice_duration' => $message->voice_duration,
            'is_mine' => (int) $message->sender_id === $currentUserId,
            'sent_at' => optional($message->created_at)->format('d/m/Y h:i A'),
            'created_at' => optional($message->created_at)->toDateTimeString(),
        ];
    }

    private function mapContactForJson(User $contact, int $activeContactId = 0): array
    {
        return [
            'id' => (int) $contact->id,
            'name' => (string) ($contact->name ?? ''),
            'chat_avatar' => (string) ($contact->chat_avatar ?? $this->resolveUserAvatar($contact)),
            'last_message' => (string) ($contact->last_message ?? ''),
            'last_message_at' => $contact->last_message_at,
            'last_message_time' => $contact->last_message_at
                ? optional(\Carbon\Carbon::parse($contact->last_message_at))->format('g:i A')
                : '',
            'unread_count' => (int) ($contact->unread_count ?? 0),
            'is_online' => (bool) ($contact->is_online ?? false),
            'status_text' => (bool) ($contact->is_online ?? false)
                ? 'Online'
                : ((string) ($contact->offline_duration ?? 'Offline')),
            'is_active' => (int) $contact->id === (int) $activeContactId,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'location' => $contact->location,
        ];
    }

    private function mapActiveContactForJson(User $contact): array
    {
        return [
            'id' => (int) $contact->id,
            'name' => (string) ($contact->name ?? ''),
            'chat_avatar' => (string) ($contact->chat_avatar ?? $this->resolveUserAvatar($contact)),
            'is_online' => (bool) ($contact->is_online ?? false),
            'status_text' => (bool) ($contact->is_online ?? false)
                ? 'Online'
                : ((string) ($contact->offline_duration ?? 'Offline')),
            'phone' => $contact->phone,
            'email' => $contact->email,
            'location' => $contact->location,
        ];
    }

    private function buildContactCards(int $currentUserId, Collection $contacts): Collection
    {
        if ($contacts->isEmpty()) {
            return $contacts;
        }

        $contactIds = $contacts->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $threadRows = ChatMessage::query()
            ->where(function ($q) use ($currentUserId, $contactIds) {
                $q->where('sender_id', $currentUserId)
                    ->whereIn('receiver_id', $contactIds);
            })
            ->orWhere(function ($q) use ($currentUserId, $contactIds) {
                $q->whereIn('sender_id', $contactIds)
                    ->where('receiver_id', $currentUserId);
            })
            ->orderByDesc('id')
            ->get(['id', 'sender_id', 'receiver_id', 'message', 'message_type', 'created_at', 'is_read']);

        $meta = [];

        foreach ($threadRows as $row) {
            $otherId = (int) ($row->sender_id == $currentUserId ? $row->receiver_id : $row->sender_id);

            if (!isset($meta[$otherId])) {
                $meta[$otherId] = [
                    'last_message' => $this->messagePreviewForContactRow($row),
                    'last_message_at' => optional($row->created_at)->toDateTimeString(),
                    'last_message_at_ts' => optional($row->created_at)->timestamp ?? 0,
                    'unread_count' => 0,
                ];
            }

            if ((int) $row->sender_id === $otherId && !$row->is_read) {
                $meta[$otherId]['unread_count']++;
            }
        }

        return $contacts
            ->map(function ($contact) use ($meta) {
                $id = (int) $contact->id;
                $contact->chat_avatar = $this->resolveUserAvatar($contact);
                $contact->last_message = $meta[$id]['last_message'] ?? '';
                $contact->last_message_at = $meta[$id]['last_message_at'] ?? null;
                $contact->last_message_at_ts = (int) ($meta[$id]['last_message_at_ts'] ?? 0);
                $contact->unread_count = (int) ($meta[$id]['unread_count'] ?? 0);

                return $contact;
            })
            ->sort(function ($a, $b) {
                $timeCompare = ($b->last_message_at_ts <=> $a->last_message_at_ts);
                if ($timeCompare !== 0) {
                    return $timeCompare;
                }

                return strcmp((string) ($a->name ?? ''), (string) ($b->name ?? ''));
            })
            ->values();
    }

    private function resolveUserAvatar(User $user): string
    {
        if (!empty($user->profile_image)) {
            return asset('storage/' . ltrim($user->profile_image, '/'));
        }

        if (!empty($user->profile_image_url)) {
            return $user->profile_image_url;
        }

        if (!empty($user->bc_id)) {
            return route('users.bc-image', ['bcId' => $user->bc_id]);
        }

        return asset('images/pos/Rectangle 2.png');
    }

    private function buildAndStoreChatMessage(Request $request, array $validated, int $senderId, int $receiverId): ?ChatMessage
    {
        $messageText = trim((string) ($validated['message'] ?? ''));
        $iconText = trim((string) ($validated['icon'] ?? ''));

        $messageType = 'text';
        $attachmentPath = null;
        $attachmentMime = null;
        $attachmentSize = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $attachmentPath = $file->store('chat/media/images', 'public');
            $attachmentMime = $file->getClientMimeType();
            $attachmentSize = (int) $file->getSize();
            $messageType = 'image';
        } elseif ($request->hasFile('voice')) {
            $file = $request->file('voice');
            $attachmentPath = $file->store('chat/media/voices', 'public');
            $attachmentMime = $file->getClientMimeType();
            $attachmentSize = (int) $file->getSize();
            $messageType = 'voice';
        } elseif ($iconText !== '') {
            $messageType = 'icon';
            $messageText = $iconText;
        }

        if ($messageText === '' && $attachmentPath === null) {
            return null;
        }

        if ($messageText === '') {
            $messageText = $messageType === 'image' ? '[Image]' : '[Voice message]';
        }

        return ChatMessage::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $messageText,
            'message_type' => $messageType,
            'attachment_path' => $attachmentPath,
            'attachment_mime' => $attachmentMime,
            'attachment_size' => $attachmentSize,
            'is_read' => false,
        ]);
    }

    private function previewTextForNotification(ChatMessage $message): string
    {
        $type = (string) ($message->message_type ?? 'text');

        if ($type === 'image') {
            return '[Image] ' . (string) ($message->message ?? '');
        }

        if ($type === 'voice') {
            return '[Voice] ' . (string) ($message->message ?? '');
        }

        if ($type === 'icon') {
            return '[Icon] ' . (string) ($message->message ?? '');
        }

        return (string) ($message->message ?? '');
    }

    private function messagePreviewForContactRow(ChatMessage $row): string
    {
        $type = (string) ($row->message_type ?? 'text');

        if ($type === 'image') {
            return '[Image]';
        }

        if ($type === 'voice') {
            return '[Voice message]';
        }

        if ($type === 'icon') {
            return (string) ($row->message ?? '[Icon]');
        }

        return (string) ($row->message ?? '');
    }

    private function upsertChatNotification(
        int $receiverId,
        User $sender,
        string $type,
        string $baseTitle,
        string $messageText
    ): void {
        DB::transaction(function () use ($receiverId, $sender, $type, $baseTitle, $messageText) {
            $existing = Notification::query()
                ->where('user_id', $receiverId)
                ->where('sender_id', $sender->id)
                ->where('type', $type)
                ->latest('updated_at')
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $currentCount = max(0, (int) ($existing->unread_count ?? 0));
                $nextCount = $currentCount + 1;

                $existing->update([
                    'title' => $baseTitle . ' (' . $nextCount . ')',
                    'message' => $messageText,
                    'unread_count' => $nextCount,
                    'is_read' => false,
                ]);

                return;
            }

            Notification::create([
                'user_id' => $receiverId,
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'sender_profile_image' => $sender->profile_image_display ?? null,
                'type' => $type,
                'category' => 'inbox',
                'title' => $baseTitle,
                'message' => $messageText,
                'unread_count' => 1,
                'is_read' => false,
            ]);
        });
    }

    private function markThreadAsRead(int $currentUserId, int $otherUserId): void
    {
        ChatMessage::query()
            ->where('sender_id', $otherUserId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
