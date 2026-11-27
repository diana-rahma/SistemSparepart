<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Sparepart extends Model
{
    protected $table = 'sparepart';

    protected $fillable = [
        'nama_sparepart',
        'deskripsi',
        'gambar',
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

        // File lama yang masih tersimpan di Laravel public storage.
        if (Str::startsWith($this->gambar, 'storage/')) {
            return asset($this->gambar);
        }

        // File baru yang tersimpan di Supabase Storage.
        return Storage::disk('supabase')->url($this->gambar);
    }

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
