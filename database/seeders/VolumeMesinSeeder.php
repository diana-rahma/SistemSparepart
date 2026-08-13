<?php

namespace Database\Seeders;

use App\Models\VolumeMesin;
use Illuminate\Database\Seeder;

class VolumeMesinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['volume' => '1000 cc', 'kode' => 'VOL-1000'],
            ['volume' => '1200 cc', 'kode' => 'VOL-1200'],
            ['volume' => '1300 cc', 'kode' => 'VOL-1300'],
            ['volume' => '1500 cc', 'kode' => 'VOL-1500'],
            ['volume' => '1800 cc', 'kode' => 'VOL-1800'],
            ['volume' => '2000 cc', 'kode' => 'VOL-2000'],
            ['volume' => '2500 cc', 'kode' => 'VOL-2500'],
        ];

        foreach ($data as $item) {
            VolumeMesin::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
