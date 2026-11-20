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
        Schema::table('tipe_kendaraan', function (Blueprint $table) {
            if (!Schema::hasColumn('tipe_kendaraan', 'nomor_model')) {
                $table->string('nomor_model')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('tipe_kendaraan', 'jenis_sarana')) {
                $table->string('jenis_sarana')->nullable()->after('nomor_model');
            }
            if (!Schema::hasColumn('tipe_kendaraan', 'nomor_unit')) {
                $table->string('nomor_unit')->nullable()->after('jenis_sarana');
            }
            if (!Schema::hasColumn('tipe_kendaraan', 'tahun_produksi')) {
                $table->string('tahun_produksi')->nullable()->after('nomor_unit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipe_kendaraan', function (Blueprint $table) {
            $table->dropColumn(['nomor_model', 'jenis_sarana', 'nomor_unit', 'tahun_produksi']);
        });
    }
};
