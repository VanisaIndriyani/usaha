<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        Periode::create([
            'nama' => 'Juni 2026',
            'tanggal_mulai' => '2026-06-01',
            'tanggal_selesai' => '2026-06-30',
            'is_active' => true,
        ]);
    }
}
