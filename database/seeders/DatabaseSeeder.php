<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $ownerUserA = User::query()->firstOrCreate(
            ['email' => 'vanisa@gmail.com'],
            ['name' => 'Vanisa', 'password' => Hash::make('password'), 'role' => 'owner']
        );
        $ownerUserB = User::query()->firstOrCreate(
            ['email' => 'dimas@gmail.com'],
            ['name' => 'Dimas', 'password' => Hash::make('password'), 'role' => 'owner']
        );

        $ownerA = Owner::query()->firstOrCreate(
            ['user_id' => $ownerUserA->id],
            ['name' => 'Vanisa']
        );
        $ownerB = Owner::query()->firstOrCreate(
            ['user_id' => $ownerUserB->id],
            ['name' => 'Dimas']
        );

        $this->call([
            ModalUsahaSeeder::class,
            BarangUsahaSeeder::class,
            PengeluaranSeeder::class,
        ]);
    }
}
