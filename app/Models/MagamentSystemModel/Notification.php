<?php

namespace App\Models\MagamentSystemModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
