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
