<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengingat extends Model
{
    use HasFactory;

    protected $table = 'pengingats';

    protected $fillable = [
        'user_id',
        'nama_hewan',
        'kategori',
        'tanggal',
        'waktu',
        'deskripsi',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}