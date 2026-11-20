<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'sparepart';

    protected $fillable = [
        'nama_sparepart',
        'deskripsi',
        'gambar',
    ];

    public function sparepartParts()
    {
        return $this->hasMany(SparepartPart::class, 'sparepart_id');
    }

    public function parts()
    {
        return $this->belongsToMany(Parts::class, 'sparepart_part', 'sparepart_id', 'part_id')
            ->withPivot(['id', 'nomor_referensi', 'jumlah', 'unit', 'pos_x', 'pos_y', 'lebar', 'tinggi'])
            ->withTimestamps();
    }

    public function tipeKendaraans()
    {
        return $this->belongsToMany(TipeKendaraan::class, 'tipekendaraan_sparepart', 'sparepart_id', 'tipekendaraan_id')
            ->withTimestamps();
    }
}
