<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtangOperasional extends Model
{
    protected $table = 'utang_operasional';

    protected $fillable = [
        'pihak',
        'sumber',
        'deskripsi',
        'nominal',
        'tanggal',
        'status',
        'referensi_type',
        'referensi_id',
        'catatan',
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
