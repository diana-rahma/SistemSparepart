<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeKendaraan extends Model
{
    protected $table = 'tipe_kendaraan';

    protected $fillable = [
        'nama',
        'kode_tipe_kendaraan',
        'nomor_model',
        'nomor_unit',
        'tahun_produksi',
        'deskripsi',
        'gambar',
        'id_model',
        'id_kategori',
    ];

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'id_model');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'tipekendaraan_sparepart', 'tipekendaraan_id', 'sparepart_id')
            ->withTimestamps();
    }
}
