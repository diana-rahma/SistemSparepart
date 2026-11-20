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
        Schema::create('sparepart_part_penanda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_part_id')
                  ->constrained('sparepart_part')   
                  ->cascadeOnDelete();
            $table->decimal('tip_x', 5, 2);
            $table->decimal('tip_y', 5, 2);
            $table->decimal('tail_x', 5, 2);
            $table->decimal('tail_y', 5, 2);
            $table->decimal('box_x', 5, 2);
            $table->decimal('box_y', 5, 2);
            $table->timestamps();
        });

        foreach (['tip_x', 'tip_y', 'tail_x', 'tail_y', 'box_x', 'box_y'] as $c) {
            DB::statement("ALTER TABLE sparepart_part_penanda
                ADD CONSTRAINT chk_penanda_{$c} CHECK ({$c} BETWEEN 0 AND 100)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sparepart_part_penanda');
    }
};
