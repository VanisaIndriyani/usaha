<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::query()->where('email', 'vanisa@gmail.com')->first();
        if (! $creator) {
            return;
        }

        $rows = [
            ['id' => 1, 'nama_pengeluaran' => 'Sewa ruko', 'nominal' => 3000000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-08', 'catatan' => 'nyewa ruko', 'created_at' => '2026-05-08 16:31:38', 'updated_at' => '2026-05-12 06:36:47'],
            ['id' => 16, 'nama_pengeluaran' => 'Ongkir Lalamove', 'nominal' => 175000, 'kategori' => 'Transportasi', 'tanggal' => '2026-05-14', 'catatan' => 'ongkir lalamove', 'created_at' => '2026-05-14 15:39:05', 'updated_at' => '2026-05-14 15:39:05'],
            ['id' => 21, 'nama_pengeluaran' => 'Pembayaran QRIS', 'nominal' => 31500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-15', 'catatan' => 'BAYARRR QRIS', 'created_at' => '2026-05-15 11:05:14', 'updated_at' => '2026-05-15 11:05:32'],
        ];

        $payload = collect($rows)->map(function (array $row) use ($creator) {
            return [
                'id' => $row['id'],
                'nama_pengeluaran' => $row['nama_pengeluaran'],
                'nominal' => $row['nominal'],
                'kategori' => $row['kategori'],
                'tanggal' => $row['tanggal'],
                'catatan' => $row['catatan'],
                'created_by' => $creator->id,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ];
        })->all();

        DB::table('pengeluaran')->upsert(
            $payload,
            ['id'],
            ['nama_pengeluaran', 'nominal', 'kategori', 'tanggal', 'catatan', 'created_by', 'created_at', 'updated_at']
        );
    }
}

