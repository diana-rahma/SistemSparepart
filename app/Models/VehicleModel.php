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
        'gambar',
        'id_kategori',
        'id_volume',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function volume()
    {
        return $this->belongsTo(VolumeMesin::class, 'id_volume');
    }
}
