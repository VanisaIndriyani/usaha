<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanStok extends Model
{
    protected $table = 'catatan_stok';

    protected $fillable = [
        'nama_item',
        'jenis',
        'jumlah',
        'satuan',
        'tanggal',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

