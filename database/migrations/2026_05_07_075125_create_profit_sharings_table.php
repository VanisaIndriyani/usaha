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
        Schema::create('profit_sharing', function (Blueprint $table) {
            $table->id();
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->unsignedBigInteger('total_modal');
            $table->unsignedBigInteger('laba_bersih');
            $table->unsignedBigInteger('owner_a_nominal');
            $table->unsignedBigInteger('owner_b_nominal');
            $table->decimal('owner_a_persen', 5, 2)->unsigned();
            $table->decimal('owner_b_persen', 5, 2)->unsigned();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_sharing');
    }
};
