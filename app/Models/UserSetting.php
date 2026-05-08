<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'push_enabled',
        'notif_likes',
        'notif_comments',
        'notif_reminders',
        'email_weekly',
        'email_tips',
        'profile_public',
        'show_email',
        'show_online_status',
        'theme',
        'animations_enabled',
        'compact_mode',
        'language',
    ];

    protected $casts = [
        'push_enabled' => 'boolean',
        'notif_likes' => 'boolean',
        'notif_comments' => 'boolean',
        'notif_reminders' => 'boolean',
        'email_weekly' => 'boolean',
        'email_tips' => 'boolean',
        'profile_public' => 'boolean',
        'show_email' => 'boolean',
        'show_online_status' => 'boolean',
        'animations_enabled' => 'boolean',
        'compact_mode' => 'boolean',
    ];

    /**
     * Get the user that owns the settings
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}