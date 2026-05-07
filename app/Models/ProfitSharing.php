<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitSharing extends Model
{
    protected $table = 'profit_sharing';

    protected $fillable = [
        'periode_mulai',
        'periode_selesai',
        'total_modal',
        'laba_bersih',
        'owner_a_nominal',
        'owner_b_nominal',
        'owner_a_persen',
        'owner_b_persen',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
