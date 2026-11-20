<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Kategori::query()->delete();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'nama' => 'Kereta Rel Listrik (KRL)',
                'kode' => 'KAT-KRL',
                'deskripsi' => 'Sarana Kereta Rel Listrik (Electric Multiple Unit / EMU) untuk transportasi komuter dan perkotaan'
            ],
            [
                'nama' => 'Kereta Rel Diesel (KRD)',
                'kode' => 'KAT-KRD',
                'deskripsi' => 'Sarana Kereta Rel Diesel (Diesel Multiple Unit / DMU, KRDE, KRDI) antar-kota dan bandara'
            ],
            [
                'nama' => 'Lokomotif',
                'kode' => 'KAT-LOK',
                'deskripsi' => 'Sarana lokomotif penarik rangkaian kereta api (Diesel Hidrolik & Diesel Elektrik)'
            ],
            [
                'nama' => 'Kereta Penumpang',
                'kode' => 'KAT-KNP',
                'deskripsi' => 'Kereta penumpang berbagai kelas (Eksekutif Stainless Steel, Luxury Sleeper, Panoramic, Ekonomi)'
            ],
            [
                'nama' => 'Gerbong Barang',
                'kode' => 'KAT-GBG',
                'deskripsi' => 'Sarana gerbong angkutan barang (PPCW Peti Kemas, KKBW Batu Bara, TTW Tangki)'
            ],
            [
                'nama' => 'Light Rail Transit (LRT)',
                'kode' => 'KAT-LRT',
                'deskripsi' => 'Sarana kereta api ringan modern perkotaan berteknologi otomasi GoA3'
            ],
            [
                'nama' => 'Bogie & Running Gear',
                'kode' => 'KAT-BOG',
                'deskripsi' => 'Sistem bogie kereta (TB-398, TB-1014, Bolsterless), wheelset, dan perangkat mekanikal penunjang'
            ],
        ];

        foreach ($categories as $cat) {
            Kategori::create($cat);
        }
    }
}
