<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'gender',
        'birth_date',
        'weight',
        'special_marks',
        'is_steril',
        'allergies',
        'health_notes',
        'photo'
    ];

    protected $casts = [
        'is_steril' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        $birth = Carbon::parse($this->birth_date);
        $now = Carbon::now();

        $diff = $birth->diff($now);

        $parts = [];

        if ($diff->y > 0) {
            $parts[] = $diff->y . ' tahun';
        }

        if ($diff->m > 0) {
            $parts[] = $diff->m . ' bulan';
        }

        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }

        return implode(' ', $parts);
    }
}
