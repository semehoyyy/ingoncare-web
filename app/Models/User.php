<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'dob', 
        'password',
        'profile_photo',
        'address',
        'otp',
        'otp_expires_at',
        'fcm_token',

    ];

    protected $hidden = [
        'password', 
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Relasi ke postingan yang di-like user
    public function likes()
    {
        return $this->belongsToMany(Comment::class, 'comment_user_likes', 'user_id', 'comment_id')
                    ->withTimestamps();
    }

    // Relasi ke settings
    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    // Relasi follow
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Get or create settings - FIXED VERSION
     */
    public function getOrCreateSettings()
    {
        // Load settings relation jika belum di-load
        if (!$this->relationLoaded('settings')) {
            $this->load('settings');
        }

        // Jika settings masih null, create baru
        if (is_null($this->settings)) {
            return $this->settings()->create([]);
        }

        return $this->settings;
    }
}