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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id')->nullable()->index();
            $table->string('key')->index();
            $table->unsignedBigInteger('sparepart_id')->nullable();
            $table->string('sparepart_nama')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('ref')->nullable();
            $table->string('kode_part')->nullable();
            $table->string('nama_part')->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
