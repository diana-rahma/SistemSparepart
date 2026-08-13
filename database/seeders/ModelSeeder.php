<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            ['nama' => 'Avanza', 'kode' => 'MDL-AVN', 'deskripsi' => 'Model kendaraan MPV dari Toyota'],
            ['nama' => 'Innova', 'kode' => 'MDL-INV', 'deskripsi' => 'Model kendaraan MPV medium dari Toyota'],
            ['nama' => 'Civic', 'kode' => 'MDL-CVC', 'deskripsi' => 'Model kendaraan Sedan sport dari Honda'],
            ['nama' => 'Brio', 'kode' => 'MDL-BRI', 'deskripsi' => 'Model kendaraan City Car / Hatchback dari Honda'],
            ['nama' => 'Xpander', 'kode' => 'MDL-XPN', 'deskripsi' => 'Model kendaraan Small MPV dari Mitsubishi'],
            ['nama' => 'Pajero', 'kode' => 'MDL-PAJ', 'deskripsi' => 'Model kendaraan SUV ladder frame dari Mitsubishi'],
        ];

        foreach ($models as $mod) {
            \App\Models\VehicleModel::updateOrCreate(
                ['kode' => $mod['kode']],
                $mod
            );
        }
    }
}
