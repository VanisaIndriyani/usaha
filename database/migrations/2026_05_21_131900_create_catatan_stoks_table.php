<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_stok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item');
            $table->string('jenis');
            $table->decimal('jumlah', 12, 2)->unsigned();
            $table->string('satuan')->default('pcs');
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_stok');
    }
};

