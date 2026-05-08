<?php
// app/Models/ChatbotHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'role',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}