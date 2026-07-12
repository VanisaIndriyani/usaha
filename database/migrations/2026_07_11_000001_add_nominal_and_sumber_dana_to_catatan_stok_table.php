<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatan_stok', function (Blueprint $table) {
            if (! Schema::hasColumn('catatan_stok', 'nominal')) {
                $table->unsignedBigInteger('nominal')->default(0)->after('satuan');
            }

            if (! Schema::hasColumn('catatan_stok', 'sumber_dana')) {
                $table->string('sumber_dana')->nullable()->after('nominal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('catatan_stok', function (Blueprint $table) {
            if (Schema::hasColumn('catatan_stok', 'sumber_dana')) {
                $table->dropColumn('sumber_dana');
            }

            if (Schema::hasColumn('catatan_stok', 'nominal')) {
                $table->dropColumn('nominal');
            }
        });
    }
};
