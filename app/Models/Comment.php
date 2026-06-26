<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'title',
        'image',
        'parent_id',
        'reply_to_id',
    ];

    /**
     * Pembuat post/comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Like (Many-to-Many via Pivot)
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'comment_user_likes',
            'comment_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Semua reply pada comment ini
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with([
                'user',
                'likes',
                'replyTo.user',
            ])
            ->orderBy('created_at');
    }

    /**
     * Parent thread
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Reply ditujukan ke comment mana
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'reply_to_id');
    }
}