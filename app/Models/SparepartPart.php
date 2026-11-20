<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SparepartPart extends Model
{
    protected $table = 'sparepart_part';

    protected $fillable = [
        'sparepart_id',
        'part_id',
        'nomor_referensi',
        'jumlah',
        'unit' ,
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'unit' => 'string',
    ];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

    public function part()
    {
        return $this->belongsTo(Parts::class, 'part_id');
    }

    public function penanda()
    {
        return $this->hasMany(SparepartPartPenanda::class, 'sparepart_part_id');
    }
}
