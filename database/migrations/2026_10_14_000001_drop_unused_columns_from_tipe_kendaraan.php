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
            // Hapus foreign key constraints terlebih dahulu
            if (Schema::hasColumn('tipe_kendaraan', 'id_tahun')) {
                try {
                    $table->dropForeign(['id_tahun']);
                } catch (\Exception $e) {
                    // abaikan jika constraint tidak ada
                }
                $table->dropColumn('id_tahun');
            }

            if (Schema::hasColumn('tipe_kendaraan', 'id_volume')) {
                try {
                    $table->dropForeign(['id_volume']);
                } catch (\Exception $e) {
                    // abaikan jika constraint tidak ada
                }
                $table->dropColumn('id_volume');
            }

            if (Schema::hasColumn('tipe_kendaraan', 'jenis_sarana')) {
                $table->dropColumn('jenis_sarana');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipe_kendaraan', function (Blueprint $table) {
            $table->foreignId('id_tahun')->nullable()->constrained('tahuns')->onDelete('cascade');
            $table->foreignId('id_volume')->nullable()->constrained('volume_mesins')->onDelete('set null');
            $table->string('jenis_sarana')->nullable();
        });
    }
};
