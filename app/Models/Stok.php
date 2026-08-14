<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stocks';

    protected $fillable = [
        'jumlah',
        'part_id',
    ];

    public function part()
    {
        return $this->belongsTo(Parts::class, 'part_id');
    }
}