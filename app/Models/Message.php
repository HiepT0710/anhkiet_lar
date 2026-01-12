<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'admin_reply',
        'replied_by',
        'replied_at',
        'is_auto_reply',
        'parent_id',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'is_auto_reply' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function autoReplies()
    {
        return $this->hasMany(Message::class, 'parent_id')->where('is_auto_reply', true);
    }
}

