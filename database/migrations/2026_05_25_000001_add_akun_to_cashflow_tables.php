<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->string('akun')->default('BRI')->after('tanggal');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->string('akun')->default('BRI')->after('tanggal');
        });

        Schema::table('modal_usaha', function (Blueprint $table) {
            $table->string('akun')->default('BRI')->after('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropColumn('akun');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('akun');
        });

        Schema::table('modal_usaha', function (Blueprint $table) {
            $table->dropColumn('akun');
        });
    }
};

