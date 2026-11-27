<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        // Jika sudah berupa URL lengkap, gunakan langsung.
        if (Str::startsWith($this->gambar, ['http://', 'https://'])) {
            return $this->gambar;
        }

        // Hilangkan prefix storage/ dari path lama.
        $relativePath = Str::startsWith($this->gambar, 'storage/')
            ? substr($this->gambar, strlen('storage/'))
            : $this->gambar;

        // Periksa apakah file tersedia di Supabase.
        if (Storage::disk('supabase')->exists($relativePath)) {
            return Storage::disk('supabase')->url($relativePath);
        }

        // Jika tidak ada di Supabase, gunakan penyimpanan lokal.
        if (Str::startsWith($this->gambar, 'storage/')) {
            return asset($this->gambar);
        }

        return Storage::disk('supabase')->url($relativePath);
    }

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
        return $this->belongsToMany(
            Sparepart::class,
            'tipekendaraan_sparepart',
            'tipekendaraan_id',
            'sparepart_id'
        )->withTimestamps();
    }
}
