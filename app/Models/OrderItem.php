<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sparepart_id',
        'sparepart_nama',
        'vehicle_id',
        'ref',
        'kode_part',
        'nama_part',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
