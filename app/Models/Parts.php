<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parts extends Model
{
    protected $table = 'parts';

    protected $fillable = [
        'nama_parts',
        'kode_parts',
        'nama_part',
        'kode_part',
        'deskripsi',
    ];

    public function getNamaPartAttribute()
    {
        return $this->attributes['nama_parts'] ?? $this->attributes['nama_part'] ?? null;
    }

    public function getKodePartAttribute()
    {
        return $this->attributes['kode_parts'] ?? $this->attributes['kode_part'] ?? null;
    }

    public function parts() {
        return $this->hasMany(Parts::class, 'id_part');
    }

    public function stok()
    {
        return $this->hasOne(Stok::class, 'part_id');
    }
}
