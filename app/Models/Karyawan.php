<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'foto_path',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'jabatan',
        'gaji_harian',
        'tanggal_masuk',
        'status_kerja',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function gaji(): HasMany
    {
        return $this->hasMany(Gaji::class, 'karyawan_id');
    }
}
