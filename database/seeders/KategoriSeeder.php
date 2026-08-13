<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama' => 'Mesin', 'kode' => 'KAT-MSN', 'deskripsi' => 'Komponen suku cadang mesin kendaraan'],
            ['nama' => 'Kelistrikan', 'kode' => 'KAT-ELK', 'deskripsi' => 'Sistem kelistrikan, aki, lampu dan kabel'],
            ['nama' => 'Pengereman', 'kode' => 'KAT-PRM', 'deskripsi' => 'Kampas rem, piringan cakram dan minyak rem'],
            ['nama' => 'Suspensi', 'kode' => 'KAT-SPS', 'deskripsi' => 'Shockbreaker, per dan kaki-kaki kendaraan'],
            ['nama' => 'Transmisi', 'kode' => 'KAT-TRM', 'deskripsi' => 'Komponen kopling, v-belt dan sistem transmisi'],
            ['nama' => 'MPV', 'kode' => 'KAT-MPV', 'deskripsi' => 'Kategori tipe kendaraan Multi Purpose Vehicle'],
            ['nama' => 'SUV', 'kode' => 'KAT-SUV', 'deskripsi' => 'Kategori tipe kendaraan Sport Utility Vehicle'],
            ['nama' => 'Sedan', 'kode' => 'KAT-SDN', 'deskripsi' => 'Kategori tipe kendaraan Sedan'],
            ['nama' => 'Hatchback', 'kode' => 'KAT-HBK', 'deskripsi' => 'Kategori tipe kendaraan Hatchback'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Kategori::updateOrCreate(
                ['kode' => $cat['kode']],
                $cat
            );
        }
    }
}
