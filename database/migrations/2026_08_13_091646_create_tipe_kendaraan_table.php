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
        Schema::create('tipe_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_tipe_kendaraan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->foreignId('id_model')->nullable()->constrained('models')->onDelete('cascade');
            $table->foreignId('id_tahun')->nullable()->constrained('tahuns')->onDelete('cascade');
            $table->foreignId('id_kategori')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('id_volume')->nullable()->constrained('volume_mesins')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_kendaraan');
    }
};
