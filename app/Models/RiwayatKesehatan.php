<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKesehatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id',
        'nama_hewan',
        'tanggal_pemeriksaan',
        'diagnosis',
        'tindakan',
        'dokter',
        'catatan',
        'jadwal_berikutnya',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'jadwal_berikutnya' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}