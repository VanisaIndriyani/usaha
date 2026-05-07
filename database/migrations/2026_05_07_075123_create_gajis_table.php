<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedBigInteger('gaji_harian');
            $table->unsignedInteger('hari_kerja');
            $table->unsignedBigInteger('gaji_pokok');
            $table->unsignedBigInteger('bonus')->default(0);
            $table->unsignedBigInteger('nominal'); // total gaji
            $table->string('status')->default('belum_dibayar');
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->unique(['karyawan_id', 'bulan', 'tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};
