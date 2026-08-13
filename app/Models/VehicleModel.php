<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'models';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
    ];
}
