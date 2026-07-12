<?php

namespace Database\Seeders;

use App\Models\UtangOperasional;
use Illuminate\Database\Seeder;

class UangKasirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UtangOperasional::create([
            'pihak' => 'kasir',
            'deskripsi' => 'Uang kasir awal',
            'nominal' => 200000,
            'tanggal' => now(),
            'status' => 'belum_bayar',
            'sumber' => 'saldo_usaha',
        ]);
    }
}
