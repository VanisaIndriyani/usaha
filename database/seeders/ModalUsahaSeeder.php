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

        $badCatatan = [
            'nyewa ruko',
            'beli printer di shopee',
            'beli stiker di shopee',
            'beli hp +kartu + cas an',
            'beli semprotan di shopee',
            'beli di progo',
            'beli plastik di enam puluh',
            'beli di tanjakannn tinwall',
            'DP ETLASE',
            'belii cat tembok',
            'METRO KAMPUS',
            'BAYAR ETALASE',
            'BAYAR COD SEROKAN ES BELI DI TT',
            'beli kulkas',
            'ongkir lalamove',
            'BELI MIXER DI TIKTOK',
            'botoll shakkk di tiktok',
            'standingg kayuu tiktok',
            'box ager',
            'BAYARRR QRIS',
            'BELI MEJA',
            'isi cupsiler di shopee',
            'isi printer di shopee',
            'belii serokann',
            'belii lacii kasir',
            'BAYAR HIASANGAN DINGDING TIKTOK',
            'KOTAK SAMPAH SHOPEE',
            'meja 120+ kipas 150 + belanja pamela 193500',
            'bayar cod bubuk cremer di tiktok',
            'cap stampell gelasdi shopee',
            'beli paku sama doubeltip',
            'cetak banner',
            'beli keranjang buah',
            'pamela gula teh susu',
        ];

        DB::table('modal_usaha')
            ->whereBetween('tanggal', ['2026-05-01', '2026-05-31'])
            ->whereIn('catatan', $badCatatan)
            ->delete();

        $rows = [
            ['id' => 1000, 'owner_ref' => 'A', 'nominal' => 4523860, 'tanggal' => '2026-05-01', 'catatan' => 'Modal awal (dibagi 2 dari 8.447.720 + 600.000)', 'created_at' => '2026-05-01 00:00:00', 'updated_at' => '2026-05-01 00:00:00'],
            ['id' => 1001, 'owner_ref' => 'B', 'nominal' => 4523860, 'tanggal' => '2026-05-01', 'catatan' => 'Modal awal (dibagi 2 dari 8.447.720 + 600.000)', 'created_at' => '2026-05-01 00:00:00', 'updated_at' => '2026-05-01 00:00:00'],
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
