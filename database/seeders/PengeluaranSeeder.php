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
            ['id' => 2, 'nama_pengeluaran' => 'Printer', 'nominal' => 241850, 'kategori' => 'Operasional', 'tanggal' => '2026-05-08', 'catatan' => 'beli printer di shopee', 'created_at' => '2026-05-08 16:32:38', 'updated_at' => '2026-05-08 16:32:38'],
            ['id' => 16, 'nama_pengeluaran' => 'Ongkir Lalamove', 'nominal' => 175000, 'kategori' => 'Transportasi', 'tanggal' => '2026-05-14', 'catatan' => 'ongkir lalamove', 'created_at' => '2026-05-14 15:39:05', 'updated_at' => '2026-05-14 15:39:05'],
            ['id' => 21, 'nama_pengeluaran' => 'Pembayaran QRIS', 'nominal' => 31500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-15', 'catatan' => 'BAYARRR QRIS', 'created_at' => '2026-05-15 11:05:14', 'updated_at' => '2026-05-15 11:05:32'],
            ['id' => 4, 'nama_pengeluaran' => 'Stiker', 'nominal' => 37100, 'kategori' => 'Operasional', 'tanggal' => '2026-05-09', 'catatan' => 'beli stiker di shopee', 'created_at' => '2026-05-09 15:17:53', 'updated_at' => '2026-05-09 15:17:53'],
            ['id' => 5, 'nama_pengeluaran' => 'HP + kartu + cas', 'nominal' => 685000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-13', 'catatan' => 'beli hp +kartu + cas an', 'created_at' => '2026-05-12 19:48:05', 'updated_at' => '2026-05-12 19:48:05'],
            ['id' => 6, 'nama_pengeluaran' => 'Semprotan', 'nominal' => 10500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-13', 'catatan' => 'beli semprotan di shopee', 'created_at' => '2026-05-13 07:13:02', 'updated_at' => '2026-05-13 07:13:02'],
            ['id' => 7, 'nama_pengeluaran' => 'Belanja Progo', 'nominal' => 302725, 'kategori' => 'Operasional', 'tanggal' => '2026-05-13', 'catatan' => 'beli di progo', 'created_at' => '2026-05-13 07:14:38', 'updated_at' => '2026-05-13 07:14:38'],
            ['id' => 8, 'nama_pengeluaran' => 'Plastik', 'nominal' => 123400, 'kategori' => 'Bahan baku', 'tanggal' => '2026-05-13', 'catatan' => 'beli plastik di enam puluh', 'created_at' => '2026-05-13 07:18:52', 'updated_at' => '2026-05-13 07:18:52'],
            ['id' => 9, 'nama_pengeluaran' => 'Belanja Tanjakan Tinwall', 'nominal' => 111000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-13', 'catatan' => 'beli di tanjakannn tinwall', 'created_at' => '2026-05-13 07:22:26', 'updated_at' => '2026-05-13 07:22:26'],
            ['id' => 10, 'nama_pengeluaran' => 'Etalase (DP)', 'nominal' => 100000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-13', 'catatan' => 'DP ETLASE', 'created_at' => '2026-05-13 07:52:20', 'updated_at' => '2026-05-14 03:55:24'],
            ['id' => 11, 'nama_pengeluaran' => 'Cat tembok', 'nominal' => 260500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-14', 'catatan' => 'belii cat tembok', 'created_at' => '2026-05-14 03:53:59', 'updated_at' => '2026-05-14 03:53:59'],
            ['id' => 12, 'nama_pengeluaran' => 'Belanja Metro Kampus', 'nominal' => 194000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-14', 'catatan' => 'METRO KAMPUS', 'created_at' => '2026-05-14 03:54:23', 'updated_at' => '2026-05-14 03:54:23'],
            ['id' => 13, 'nama_pengeluaran' => 'Etalase (Pelunasan)', 'nominal' => 650000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-14', 'catatan' => 'BAYAR ETALASE', 'created_at' => '2026-05-14 03:55:10', 'updated_at' => '2026-05-14 03:55:10'],
            ['id' => 14, 'nama_pengeluaran' => 'Serokan es', 'nominal' => 15000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-14', 'catatan' => 'BAYAR COD SEROKAN ES BELI DI TT', 'created_at' => '2026-05-14 03:56:35', 'updated_at' => '2026-05-14 03:56:35'],
            ['id' => 15, 'nama_pengeluaran' => 'Kulkas', 'nominal' => 1000000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-14', 'catatan' => 'beli kulkas', 'created_at' => '2026-05-14 15:38:45', 'updated_at' => '2026-05-14 15:38:45'],
            ['id' => 17, 'nama_pengeluaran' => 'Mixer', 'nominal' => 76500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-15', 'catatan' => 'BELI MIXER DI TIKTOK', 'created_at' => '2026-05-15 04:21:27', 'updated_at' => '2026-05-15 04:21:27'],
            ['id' => 18, 'nama_pengeluaran' => 'Botol', 'nominal' => 37000, 'kategori' => 'Bahan baku', 'tanggal' => '2026-05-15', 'catatan' => 'botoll shakkk di tiktok', 'created_at' => '2026-05-15 10:21:13', 'updated_at' => '2026-05-15 10:21:13'],
            ['id' => 19, 'nama_pengeluaran' => 'Standing kayu', 'nominal' => 65145, 'kategori' => 'Operasional', 'tanggal' => '2026-05-15', 'catatan' => 'standingg kayuu tiktok', 'created_at' => '2026-05-15 10:21:44', 'updated_at' => '2026-05-15 10:21:44'],
            ['id' => 20, 'nama_pengeluaran' => 'Box agar', 'nominal' => 44000, 'kategori' => 'Bahan baku', 'tanggal' => '2026-05-15', 'catatan' => 'box ager', 'created_at' => '2026-05-15 10:22:55', 'updated_at' => '2026-05-15 10:22:55'],
            ['id' => 22, 'nama_pengeluaran' => 'Meja', 'nominal' => 120000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'BELI MEJA', 'created_at' => '2026-05-16 02:49:11', 'updated_at' => '2026-05-16 02:49:11'],
            ['id' => 23, 'nama_pengeluaran' => 'Isi cupsiler', 'nominal' => 40500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'isi cupsiler di shopee', 'created_at' => '2026-05-16 02:49:39', 'updated_at' => '2026-05-16 02:49:39'],
            ['id' => 24, 'nama_pengeluaran' => 'Isi printer', 'nominal' => 38000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'isi printer di shopee', 'created_at' => '2026-05-16 02:50:06', 'updated_at' => '2026-05-16 02:50:06'],
            ['id' => 25, 'nama_pengeluaran' => 'Serokan', 'nominal' => 7000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'belii serokann', 'created_at' => '2026-05-16 02:50:32', 'updated_at' => '2026-05-16 02:50:32'],
            ['id' => 26, 'nama_pengeluaran' => 'Laci kasir', 'nominal' => 29000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'belii lacii kasir', 'created_at' => '2026-05-16 02:51:34', 'updated_at' => '2026-05-16 02:51:34'],
            ['id' => 27, 'nama_pengeluaran' => 'Hiasan dinding', 'nominal' => 49000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'BAYAR HIASANGAN DINGDING TIKTOK', 'created_at' => '2026-05-16 16:13:59', 'updated_at' => '2026-05-16 16:13:59'],
            ['id' => 28, 'nama_pengeluaran' => 'Kotak sampah', 'nominal' => 15500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'KOTAK SAMPAH SHOPEE', 'created_at' => '2026-05-16 16:14:54', 'updated_at' => '2026-05-16 16:14:54'],
            ['id' => 29, 'nama_pengeluaran' => 'Meja + kipas + belanja Pamela', 'nominal' => 463500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-16', 'catatan' => 'meja 120+ kipas 150 + belanja pamela 193500', 'created_at' => '2026-05-16 22:54:20', 'updated_at' => '2026-05-16 22:54:20'],
            ['id' => 30, 'nama_pengeluaran' => 'Bubuk creamer', 'nominal' => 34500, 'kategori' => 'Bahan baku', 'tanggal' => '2026-05-17', 'catatan' => 'bayar cod bubuk cremer di tiktok', 'created_at' => '2026-05-17 21:47:08', 'updated_at' => '2026-05-17 21:47:08'],
            ['id' => 31, 'nama_pengeluaran' => 'Cap stempel gelas', 'nominal' => 52500, 'kategori' => 'Operasional', 'tanggal' => '2026-05-17', 'catatan' => 'cap stampell gelasdi shopee', 'created_at' => '2026-05-17 21:54:33', 'updated_at' => '2026-05-17 21:54:33'],
            ['id' => 32, 'nama_pengeluaran' => 'Paku + doubletip', 'nominal' => 15000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-19', 'catatan' => 'beli paku sama doubeltip', 'created_at' => '2026-05-19 20:32:03', 'updated_at' => '2026-05-19 20:32:03'],
            ['id' => 33, 'nama_pengeluaran' => 'Cetak banner', 'nominal' => 88000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-19', 'catatan' => 'cetak banner', 'created_at' => '2026-05-19 20:32:56', 'updated_at' => '2026-05-19 20:32:56'],
            ['id' => 34, 'nama_pengeluaran' => 'Keranjang buah', 'nominal' => 10000, 'kategori' => 'Operasional', 'tanggal' => '2026-05-19', 'catatan' => 'beli keranjang buah', 'created_at' => '2026-05-19 20:33:12', 'updated_at' => '2026-05-19 20:33:12'],
            ['id' => 35, 'nama_pengeluaran' => 'Belanja bahan (gula, teh, susu)', 'nominal' => 325000, 'kategori' => 'Bahan baku', 'tanggal' => '2026-05-20', 'catatan' => 'pamela gula teh susu', 'created_at' => '2026-05-20 13:03:18', 'updated_at' => '2026-05-20 13:03:18'],
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
