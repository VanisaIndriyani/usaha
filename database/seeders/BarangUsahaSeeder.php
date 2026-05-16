<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::query()->where('email', 'vanisa@gmail.com')->first();
        if (! $creator) {
            return;
        }

        $rows = [
            ['id' => 2, 'nama_barang' => 'Printer', 'kategori' => 'Elektronik', 'harga' => 241850, 'jumlah' => 1, 'supplier' => 'Shopee', 'tanggal_beli' => '2026-05-08', 'catatan' => 'beli printer di shopee', 'created_at' => '2026-05-08 16:32:38', 'updated_at' => '2026-05-08 16:32:38'],
            ['id' => 4, 'nama_barang' => 'Stiker', 'kategori' => 'Perintilan lainnya', 'harga' => 37100, 'jumlah' => 1, 'supplier' => 'Shopee', 'tanggal_beli' => '2026-05-09', 'catatan' => 'beli stiker di shopee', 'created_at' => '2026-05-09 15:17:53', 'updated_at' => '2026-05-09 15:17:53'],
            ['id' => 5, 'nama_barang' => 'HP + kartu + cas', 'kategori' => 'Elektronik', 'harga' => 685000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-13', 'catatan' => 'beli hp +kartu + cas an', 'created_at' => '2026-05-12 19:48:05', 'updated_at' => '2026-05-12 19:48:05'],
            ['id' => 6, 'nama_barang' => 'Semprotan', 'kategori' => 'Peralatan', 'harga' => 10500, 'jumlah' => 1, 'supplier' => 'Shopee', 'tanggal_beli' => '2026-05-13', 'catatan' => 'beli semprotan di shopee', 'created_at' => '2026-05-13 07:13:02', 'updated_at' => '2026-05-13 07:13:02'],
            ['id' => 7, 'nama_barang' => 'Belanja Progo', 'kategori' => 'Perintilan lainnya', 'harga' => 302725, 'jumlah' => 1, 'supplier' => 'Progo', 'tanggal_beli' => '2026-05-13', 'catatan' => 'beli di progo', 'created_at' => '2026-05-13 07:14:38', 'updated_at' => '2026-05-13 07:14:38'],
            ['id' => 8, 'nama_barang' => 'Plastik', 'kategori' => 'Bahan baku', 'harga' => 123400, 'jumlah' => 1, 'supplier' => 'Enam puluh', 'tanggal_beli' => '2026-05-13', 'catatan' => 'beli plastik di enam puluh', 'created_at' => '2026-05-13 07:18:52', 'updated_at' => '2026-05-13 07:18:52'],
            ['id' => 9, 'nama_barang' => 'Belanja Tanjakan Tinwall', 'kategori' => 'Perintilan lainnya', 'harga' => 111000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-13', 'catatan' => 'beli di tanjakannn tinwall', 'created_at' => '2026-05-13 07:22:26', 'updated_at' => '2026-05-13 07:22:26'],
            ['id' => 10, 'nama_barang' => 'Etalase (DP)', 'kategori' => 'Furniture', 'harga' => 100000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-13', 'catatan' => 'DP ETLASE', 'created_at' => '2026-05-13 07:52:20', 'updated_at' => '2026-05-14 03:55:24'],
            ['id' => 11, 'nama_barang' => 'Cat tembok', 'kategori' => 'Perintilan lainnya', 'harga' => 260500, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-14', 'catatan' => 'belii cat tembok', 'created_at' => '2026-05-14 03:53:59', 'updated_at' => '2026-05-14 03:53:59'],
            ['id' => 12, 'nama_barang' => 'Belanja Metro Kampus', 'kategori' => 'Perintilan lainnya', 'harga' => 194000, 'jumlah' => 1, 'supplier' => 'Metro Kampus', 'tanggal_beli' => '2026-05-14', 'catatan' => 'METRO KAMPUS', 'created_at' => '2026-05-14 03:54:23', 'updated_at' => '2026-05-14 03:54:23'],
            ['id' => 13, 'nama_barang' => 'Etalase (Pelunasan)', 'kategori' => 'Furniture', 'harga' => 650000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-14', 'catatan' => 'BAYAR ETALASE', 'created_at' => '2026-05-14 03:55:10', 'updated_at' => '2026-05-14 03:55:10'],
            ['id' => 14, 'nama_barang' => 'Serokan es', 'kategori' => 'Peralatan', 'harga' => 15000, 'jumlah' => 1, 'supplier' => 'TikTok', 'tanggal_beli' => '2026-05-14', 'catatan' => 'BAYAR COD SEROKAN ES BELI DI TT', 'created_at' => '2026-05-14 03:56:35', 'updated_at' => '2026-05-14 03:56:35'],
            ['id' => 15, 'nama_barang' => 'Kulkas', 'kategori' => 'Elektronik', 'harga' => 1000000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-14', 'catatan' => 'beli kulkas', 'created_at' => '2026-05-14 15:38:45', 'updated_at' => '2026-05-14 15:38:45'],
            ['id' => 17, 'nama_barang' => 'Mixer', 'kategori' => 'Elektronik', 'harga' => 76500, 'jumlah' => 1, 'supplier' => 'TikTok', 'tanggal_beli' => '2026-05-15', 'catatan' => 'BELI MIXER DI TIKTOK', 'created_at' => '2026-05-15 04:21:27', 'updated_at' => '2026-05-15 04:21:27'],
            ['id' => 18, 'nama_barang' => 'Botol', 'kategori' => 'Bahan baku', 'harga' => 37000, 'jumlah' => 1, 'supplier' => 'TikTok', 'tanggal_beli' => '2026-05-15', 'catatan' => 'botoll shakkk di tiktok', 'created_at' => '2026-05-15 10:21:13', 'updated_at' => '2026-05-15 10:21:13'],
            ['id' => 19, 'nama_barang' => 'Standing kayu', 'kategori' => 'Furniture', 'harga' => 65145, 'jumlah' => 1, 'supplier' => 'TikTok', 'tanggal_beli' => '2026-05-15', 'catatan' => 'standingg kayuu tiktok', 'created_at' => '2026-05-15 10:21:44', 'updated_at' => '2026-05-15 10:21:44'],
            ['id' => 20, 'nama_barang' => 'Box agar', 'kategori' => 'Bahan baku', 'harga' => 44000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-15', 'catatan' => 'box ager', 'created_at' => '2026-05-15 10:22:55', 'updated_at' => '2026-05-15 10:22:55'],
            ['id' => 22, 'nama_barang' => 'Meja', 'kategori' => 'Furniture', 'harga' => 120000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-16', 'catatan' => 'BELI MEJA', 'created_at' => '2026-05-16 02:49:11', 'updated_at' => '2026-05-16 02:49:11'],
            ['id' => 23, 'nama_barang' => 'Isi cupsiler', 'kategori' => 'Perintilan lainnya', 'harga' => 40500, 'jumlah' => 1, 'supplier' => 'Shopee', 'tanggal_beli' => '2026-05-16', 'catatan' => 'isi cupsiler di shopee', 'created_at' => '2026-05-16 02:49:39', 'updated_at' => '2026-05-16 02:49:39'],
            ['id' => 24, 'nama_barang' => 'Isi printer', 'kategori' => 'Perintilan lainnya', 'harga' => 38000, 'jumlah' => 1, 'supplier' => 'Shopee', 'tanggal_beli' => '2026-05-16', 'catatan' => 'isi printer di shopee', 'created_at' => '2026-05-16 02:50:06', 'updated_at' => '2026-05-16 02:50:06'],
            ['id' => 25, 'nama_barang' => 'Serokan', 'kategori' => 'Peralatan', 'harga' => 7000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-16', 'catatan' => 'belii serokann', 'created_at' => '2026-05-16 02:50:32', 'updated_at' => '2026-05-16 02:50:32'],
            ['id' => 26, 'nama_barang' => 'Laci kasir', 'kategori' => 'Furniture', 'harga' => 29000, 'jumlah' => 1, 'supplier' => null, 'tanggal_beli' => '2026-05-16', 'catatan' => 'belii lacii kasir', 'created_at' => '2026-05-16 02:51:34', 'updated_at' => '2026-05-16 02:51:34'],
        ];

        $payload = collect($rows)->map(function (array $row) use ($creator) {
            return [
                'id' => $row['id'],
                'nama_barang' => $row['nama_barang'],
                'kategori' => $row['kategori'],
                'harga' => $row['harga'],
                'jumlah' => $row['jumlah'],
                'supplier' => $row['supplier'],
                'tanggal_beli' => $row['tanggal_beli'],
                'catatan' => $row['catatan'],
                'foto_path' => null,
                'created_by' => $creator->id,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ];
        })->all();

        DB::table('barang_usaha')->upsert(
            $payload,
            ['id'],
            ['nama_barang', 'kategori', 'harga', 'jumlah', 'supplier', 'tanggal_beli', 'catatan', 'foto_path', 'created_by', 'created_at', 'updated_at']
        );
    }
}

