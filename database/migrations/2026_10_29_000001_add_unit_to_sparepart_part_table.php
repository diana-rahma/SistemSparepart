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
        if (! Schema::hasColumn('sparepart_part', 'unit')) {
            Schema::table('sparepart_part', function (Blueprint $table) {
                $table->enum('unit', ['pcs', 'set'])->default('pcs')->after('jumlah');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sparepart_part', 'unit')) {
            Schema::table('sparepart_part', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }
    }
};
