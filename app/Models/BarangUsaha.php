<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangUsaha extends Model
{
    protected $table = 'barang_usaha';

    protected $fillable = [
        'nama_barang',
        'kategori',
        'harga',
        'jumlah',
        'supplier',
        'tanggal_beli',
        'catatan',
        'foto_path',
        'created_by',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTotalAttribute(): int
    {
        return (int) $this->harga * (int) $this->jumlah;
    }
}
