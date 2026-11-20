<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'key',
        'sparepart_id',
        'sparepart_nama',
        'vehicle_id',
        'ref',
        'kode_part',
        'nama_part',
        'jumlah',
        'harga_satuan',
        'stok_tersedia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
