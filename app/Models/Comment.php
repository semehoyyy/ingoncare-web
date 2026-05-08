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
        'title',        // Judul post
        'image',        // Path gambar
        'parent_id'
    ];

    /**
     * User yang membuat komentar/post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias author (optional)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi likes untuk komentar atau post
     * Pivot table: comment_user_likes
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_user_likes', 'comment_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Balasan komentar (NESTED RECURSIVE)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with(['user', 'likes', 'replies']) // Load nested replies recursively
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Parent comment
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Count all nested replies recursively
     */
    public function getTotalRepliesCountAttribute()
    {
        $count = $this->replies->count();
        
        foreach ($this->replies as $reply) {
            $count += $reply->total_replies_count;
        }
        
        return $count;
    }
}