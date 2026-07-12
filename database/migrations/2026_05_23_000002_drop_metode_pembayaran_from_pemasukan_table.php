<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pemasukan', 'metode_pembayaran')) {
            Schema::table('pemasukan', function (Blueprint $table) {
                $table->dropColumn('metode_pembayaran');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('pemasukan', 'metode_pembayaran')) {
            Schema::table('pemasukan', function (Blueprint $table) {
                $table->string('metode_pembayaran')->after('nominal');
            });
        }
    }
};
