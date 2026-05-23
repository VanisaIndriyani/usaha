<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemasukan extends Model
{
    protected $table = 'pemasukan';

    protected $fillable = [
        'tanggal',
        'nama_pemasukan',
        'nominal',
        'metode_pembayaran',
        'catatan',
        'bukti_path',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
