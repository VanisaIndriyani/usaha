<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utang_operasional', function (Blueprint $table) {
            $table->id();
            $table->string('pihak');
            $table->string('sumber')->default('pembelian_stok');
            $table->string('deskripsi');
            $table->unsignedBigInteger('nominal');
            $table->date('tanggal');
            $table->string('status')->default('belum_lunas');
            $table->string('referensi_type')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['pihak', 'status']);
            $table->unique(['referensi_type', 'referensi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utang_operasional');
    }
};
