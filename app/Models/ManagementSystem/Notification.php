<?php

namespace App\Models\ManagementSystem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\POS\OrderItem;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'sender_name',
        'sender_profile_image',
        'order_id',
        'item_id',
        'type',
        'category',
        'group_key',
        'is_group_summary',
        'unread_count',
        'title',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_group_summary' => 'boolean',
        'unread_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\POS\Order::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function relatedOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id')
            ->with(['item', 'itemVariant']);
    }

    /**
     * Total unread count across all admin-facing notification tabs
     * (order, user_contact, out_of_stock, global_message), for the
     * sidebar/topbar red-dot indicator. Mirrors
     * AdminNotificationController::baseAdminNotificationQuery() +
     * unreadBadgeCount() so it matches exactly what the Notifications
     * page itself would show — kept here instead of calling into the
     * controller (whose equivalent methods are protected) so it stays
     * cheaply callable from the shared sidebar partial on every page.
     */
    public static function adminUnreadTotal($companyId = null): int
    {
        $query = static::query()
            ->where(function ($q) {
                $q->whereNull('group_key')->orWhere('is_group_summary', true);
            })
            ->whereIn('type', ['order', 'user_contact', 'out_of_stock', 'global_message'])
            ->when($companyId, function ($query) use ($companyId) {
                $query->where(function ($q) use ($companyId) {
                    $q->whereHas('user', function ($uq) use ($companyId) {
                        $uq->where('company_id', $companyId);
                    })->orWhere('is_group_summary', true);
                });
            });

        return (int) $query
            ->selectRaw('
                COALESCE(SUM(
                    CASE
                        WHEN is_read = 0 THEN
                            CASE
                                WHEN unread_count IS NULL OR unread_count < 1 THEN 1
                                ELSE unread_count
                            END
                        ELSE 0
                    END
                ), 0) AS unread_total
            ')
            ->value('unread_total');
    }

    /**
     * Turns a raw notification message into safe display HTML.
     *
     * Messages can arrive in a few shapes: plain text, sanitized rich-text
     * from AdminNotificationController::sanitizeNotificationMessage() (a
     * whitelist of tags like <a>/<b>/<div>), or the "[Image]"/"[Voice]"/
     * "[Icon]" bracket placeholders ChatController stores when a chat
     * message has no caption. None of those render well through Blade's
     * escaped {{ }} — rich text shows up as literal tag text, and the
     * bracket placeholders read as raw text instead of an icon.
     *
     * This always strips tags down to plain text (so truncating for a list
     * preview can never leave a dangling unclosed tag) and always escapes
     * that text before use — the only unescaped HTML this returns is the
     * fixed, hardcoded <i> icon markup below, never message content — so
     * the result is safe to output with {!! !!} regardless of which path
     * produced the original message.
     */
    public static function cleanMessagePreview(?string $message, ?int $limit = null): string
    {
        $text = (string) $message;

        // Block-level boundaries become line breaks before the tags are
        // stripped, so a multi-line/paragraph message doesn't get squashed
        // into one run-on line.
        $text = preg_replace('/<(br|\/p|\/div|\/li)\s*\/?>/i', "\n", $text) ?? $text;
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
        $text = trim(preg_replace('/\n{3,}/', "\n\n", $text) ?? $text);

        $badges = [
            '[Image]' => ['icon' => 'bi-image', 'label' => 'Photo'],
            '[Voice message]' => ['icon' => 'bi-mic-fill', 'label' => 'Voice message'],
            '[Voice]' => ['icon' => 'bi-mic-fill', 'label' => 'Voice message'],
            '[Icon]' => ['icon' => 'bi-emoji-smile', 'label' => ''],
        ];

        foreach ($badges as $prefix => $meta) {
            if ($text !== $prefix && !str_starts_with($text, $prefix . ' ') && !str_starts_with($text, $prefix . "\n")) {
                continue;
            }

            $caption = trim(substr($text, strlen($prefix)));
            // Guards against the historical "[Image] [Image]" double-prefix
            // bug, where the caption is itself just another placeholder.
            if (isset($badges[$caption])) {
                $caption = '';
            }

            $label = $caption !== '' ? $caption : $meta['label'];
            $html = '<i class="bi ' . $meta['icon'] . '"></i>';
            if ($label !== '') {
                $html .= ' ' . e($limit ? \Illuminate\Support\Str::limit($label, $limit) : $label);
            }

            return $html;
        }

        return nl2br(e($limit ? \Illuminate\Support\Str::limit($text, $limit) : $text));
    }
}