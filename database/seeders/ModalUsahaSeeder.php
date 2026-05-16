<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::query()->where('email', 'vanisa@gmail.com')->first();
        $ownerA = Owner::query()->where('name', 'Vanisa')->first();
        $ownerB = Owner::query()->where('name', 'Dimas')->first();

        if (! $creator || ! $ownerA || ! $ownerB) {
            return;
        }

        $rows = [
            ['id' => 1, 'owner_ref' => 'A', 'nominal' => 3000000, 'tanggal' => '2026-05-08', 'catatan' => 'nyewa ruko', 'created_at' => '2026-05-08 16:31:38', 'updated_at' => '2026-05-12 06:36:47'],
            ['id' => 2, 'owner_ref' => 'A', 'nominal' => 241850, 'tanggal' => '2026-05-08', 'catatan' => 'beli printer di shopee', 'created_at' => '2026-05-08 16:32:38', 'updated_at' => '2026-05-08 16:32:38'],
            ['id' => 4, 'owner_ref' => 'A', 'nominal' => 37100, 'tanggal' => '2026-05-09', 'catatan' => 'beli stiker di shopee', 'created_at' => '2026-05-09 15:17:53', 'updated_at' => '2026-05-09 15:17:53'],
            ['id' => 5, 'owner_ref' => 'A', 'nominal' => 685000, 'tanggal' => '2026-05-13', 'catatan' => 'beli hp +kartu + cas an', 'created_at' => '2026-05-12 19:48:05', 'updated_at' => '2026-05-12 19:48:05'],
            ['id' => 6, 'owner_ref' => 'B', 'nominal' => 10500, 'tanggal' => '2026-05-13', 'catatan' => 'beli semprotan di shopee', 'created_at' => '2026-05-13 07:13:02', 'updated_at' => '2026-05-13 07:13:02'],
            ['id' => 7, 'owner_ref' => 'A', 'nominal' => 302725, 'tanggal' => '2026-05-13', 'catatan' => 'beli di progo', 'created_at' => '2026-05-13 07:14:38', 'updated_at' => '2026-05-13 07:14:38'],
            ['id' => 8, 'owner_ref' => 'A', 'nominal' => 123400, 'tanggal' => '2026-05-13', 'catatan' => 'beli plastik di enam puluh', 'created_at' => '2026-05-13 07:18:52', 'updated_at' => '2026-05-13 07:18:52'],
            ['id' => 9, 'owner_ref' => 'A', 'nominal' => 111000, 'tanggal' => '2026-05-13', 'catatan' => 'beli di tanjakannn tinwall', 'created_at' => '2026-05-13 07:22:26', 'updated_at' => '2026-05-13 07:22:26'],
            ['id' => 10, 'owner_ref' => 'B', 'nominal' => 100000, 'tanggal' => '2026-05-13', 'catatan' => 'DP ETLASE', 'created_at' => '2026-05-13 07:52:20', 'updated_at' => '2026-05-14 03:55:24'],
            ['id' => 11, 'owner_ref' => 'B', 'nominal' => 260500, 'tanggal' => '2026-05-14', 'catatan' => 'belii cat tembok', 'created_at' => '2026-05-14 03:53:59', 'updated_at' => '2026-05-14 03:53:59'],
            ['id' => 12, 'owner_ref' => 'A', 'nominal' => 194000, 'tanggal' => '2026-05-14', 'catatan' => 'METRO KAMPUS', 'created_at' => '2026-05-14 03:54:23', 'updated_at' => '2026-05-14 03:54:23'],
            ['id' => 13, 'owner_ref' => 'A', 'nominal' => 650000, 'tanggal' => '2026-05-14', 'catatan' => 'BAYAR ETALASE', 'created_at' => '2026-05-14 03:55:10', 'updated_at' => '2026-05-14 03:55:10'],
            ['id' => 14, 'owner_ref' => 'A', 'nominal' => 15000, 'tanggal' => '2026-05-14', 'catatan' => 'BAYAR COD SEROKAN ES BELI DI TT', 'created_at' => '2026-05-14 03:56:35', 'updated_at' => '2026-05-14 03:56:35'],
            ['id' => 15, 'owner_ref' => 'B', 'nominal' => 1000000, 'tanggal' => '2026-05-14', 'catatan' => 'beli kulkas', 'created_at' => '2026-05-14 15:38:45', 'updated_at' => '2026-05-14 15:38:45'],
            ['id' => 16, 'owner_ref' => 'A', 'nominal' => 175000, 'tanggal' => '2026-05-14', 'catatan' => 'ongkir lalamove', 'created_at' => '2026-05-14 15:39:05', 'updated_at' => '2026-05-14 15:39:05'],
            ['id' => 17, 'owner_ref' => 'B', 'nominal' => 76500, 'tanggal' => '2026-05-15', 'catatan' => 'BELI MIXER DI TIKTOK', 'created_at' => '2026-05-15 04:21:27', 'updated_at' => '2026-05-15 04:21:27'],
            ['id' => 18, 'owner_ref' => 'A', 'nominal' => 37000, 'tanggal' => '2026-05-15', 'catatan' => 'botoll shakkk di tiktok', 'created_at' => '2026-05-15 10:21:13', 'updated_at' => '2026-05-15 10:21:13'],
            ['id' => 19, 'owner_ref' => 'A', 'nominal' => 65145, 'tanggal' => '2026-05-15', 'catatan' => 'standingg kayuu tiktok', 'created_at' => '2026-05-15 10:21:44', 'updated_at' => '2026-05-15 10:21:44'],
            ['id' => 20, 'owner_ref' => 'A', 'nominal' => 44000, 'tanggal' => '2026-05-15', 'catatan' => 'box ager', 'created_at' => '2026-05-15 10:22:55', 'updated_at' => '2026-05-15 10:22:55'],
            ['id' => 21, 'owner_ref' => 'A', 'nominal' => 31500, 'tanggal' => '2026-05-15', 'catatan' => 'BAYARRR QRIS', 'created_at' => '2026-05-15 11:05:14', 'updated_at' => '2026-05-15 11:05:32'],
            ['id' => 22, 'owner_ref' => 'B', 'nominal' => 120000, 'tanggal' => '2026-05-16', 'catatan' => 'BELI MEJA', 'created_at' => '2026-05-16 02:49:11', 'updated_at' => '2026-05-16 02:49:11'],
            ['id' => 23, 'owner_ref' => 'A', 'nominal' => 40500, 'tanggal' => '2026-05-16', 'catatan' => 'isi cupsiler di shopee', 'created_at' => '2026-05-16 02:49:39', 'updated_at' => '2026-05-16 02:49:39'],
            ['id' => 24, 'owner_ref' => 'A', 'nominal' => 38000, 'tanggal' => '2026-05-16', 'catatan' => 'isi printer di shopee', 'created_at' => '2026-05-16 02:50:06', 'updated_at' => '2026-05-16 02:50:06'],
            ['id' => 25, 'owner_ref' => 'A', 'nominal' => 7000, 'tanggal' => '2026-05-16', 'catatan' => 'belii serokann', 'created_at' => '2026-05-16 02:50:32', 'updated_at' => '2026-05-16 02:50:32'],
            ['id' => 26, 'owner_ref' => 'A', 'nominal' => 29000, 'tanggal' => '2026-05-16', 'catatan' => 'belii lacii kasir', 'created_at' => '2026-05-16 02:51:34', 'updated_at' => '2026-05-16 02:51:34'],
        ];

        $payload = collect($rows)->map(function (array $row) use ($creator, $ownerA, $ownerB) {
            $ownerId = $row['owner_ref'] === 'B' ? $ownerB->id : $ownerA->id;

            return [
                'id' => $row['id'],
                'owner_id' => $ownerId,
                'nominal' => $row['nominal'],
                'tanggal' => $row['tanggal'],
                'catatan' => $row['catatan'],
                'created_by' => $creator->id,
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ];
        })->all();

        DB::table('modal_usaha')->upsert(
            $payload,
            ['id'],
            ['owner_id', 'nominal', 'tanggal', 'catatan', 'created_by', 'created_at', 'updated_at']
        );
    }
}

