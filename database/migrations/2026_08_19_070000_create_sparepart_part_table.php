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
        Schema::create('sparepart_part', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_referensi')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('unit', 20)->nullable();
            $table->foreignId('sparepart_id')->constrained('sparepart')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->decimal('pos_x', 8, 4)->nullable();
            $table->decimal('pos_y', 8, 4)->nullable();
            $table->decimal('lebar', 8, 4)->nullable();
            $table->decimal('tinggi', 8, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparepart_part');
    }
};
