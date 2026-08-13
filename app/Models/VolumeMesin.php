<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolumeMesin extends Model
{
    protected $table = 'volume_mesins';

    protected $fillable = [
        'volume',
        'kode',
    ];
}
