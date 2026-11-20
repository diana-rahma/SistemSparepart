<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartPartPenanda extends Model
{
    protected $table = 'sparepart_part_penanda';

    protected $fillable = [
        'sparepart_part_id', 'tip_x', 'tip_y', 'tail_x', 'tail_y', 'box_x', 'box_y',
    ];

    protected $casts = [
        'tip_x' => 'float', 'tip_y' => 'float',
        'tail_x' => 'float', 'tail_y' => 'float',
        'box_x' => 'float', 'box_y' => 'float',
    ];

    public function mapping()
    {
        return $this->belongsTo(SparepartPart::class, 'sparepart_part_id');
    }
}
