<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CatatanStok extends Model
{
    protected $table = 'catatan_stok';

    protected $fillable = [
        'nama_item',
        'jenis',
        'jumlah',
        'satuan',
        'nominal',
        'sumber_dana',
        'tanggal',
        'catatan',
        'bukti_path',
        'created_by',
        'periode_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'nominal' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function utangOperasional(): HasOne
    {
        return $this->hasOne(UtangOperasional::class, 'referensi_id')
            ->where('referensi_type', self::class);
    }
}
