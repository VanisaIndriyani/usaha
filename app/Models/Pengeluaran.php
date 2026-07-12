<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'nama_pengeluaran',
        'nominal',
        'kategori',
        'tanggal',
        'catatan',
        'bukti_path',
        'created_by',
        'periode_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
