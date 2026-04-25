<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'message_type',
        'attachment_path',
        'attachment_mime',
        'attachment_size',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'attachment_size' => 'integer',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
