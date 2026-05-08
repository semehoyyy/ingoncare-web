<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'dob', 
        'password',
        'profile_photo',
        'address',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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