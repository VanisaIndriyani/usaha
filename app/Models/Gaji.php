<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    protected $table = 'gaji';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'gaji_harian',
        'hari_kerja',
        'gaji_pokok',
        'bonus',
        'nominal',
        'status',
        'tanggal_bayar',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
