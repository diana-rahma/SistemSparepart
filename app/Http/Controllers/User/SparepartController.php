<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    private function getVehiclesData()
    {
        return [
            [
                'id' => 1,
                'nama' => 'Toyota Avanza 1.5L G AT',
                'kode' => 'V-TOY-AVN22',
                'kategori' => 'MPV',
                'volume_mesin' => '1500 cc',
                'model' => 'Avanza',
                'tahun' => '2022',
                'gambar' => 'dist/assets/img/prod-1.jpg',
                'deskripsi' => 'Kendaraan MPV pilihan keluarga Indonesia dengan efisiensi bahan bakar tinggi dan ketahanan mesin teruji.',
                'komponen' => [
                    [
                        'id' => 101,
                        'nama' => 'Filter Oli Engine Original Toyota',
                        'kode_part' => '15601-BZ010',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 65000,
                        'stok' => 45,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Material kertas saring mikro sintetis, perlindungan maksimal dari jelaga & gesekan.'
                    ],
                    [
                        'id' => 102,
                        'nama' => 'Busi Iridium Tough Spark Plug 4 Pcs',
                        'kode_part' => '90919-01253',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-3.jpg',
                        'harga' => 280000,
                        'stok' => 30,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Ujung elektroda iridium 0.4mm, pengapian fokus stabil dan pembakaran sempurna.'
                    ],
                    [
                        'id' => 103,
                        'nama' => 'Filter Udara Dual Element',
                        'kode_part' => '17801-BZ040',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-2.jpg',
                        'harga' => 95000,
                        'stok' => 25,
                        'garansi' => '3 Bulan',
                        'spesifikasi' => 'Menyaring 99.5% partikel debu halus tanpa merestriksi intake angin.'
                    ],
                    [
                        'id' => 104,
                        'nama' => 'Aki Kering Maintenance Free 12V 45Ah',
                        'kode_part' => '28800-45Ah-MF',
                        'kategori' => 'Kelistrikan',
                        'gambar' => 'dist/assets/img/prod-4.jpg',
                        'harga' => 890000,
                        'stok' => 12,
                        'garansi' => '12 Bulan',
                        'spesifikasi' => 'High CCA starter power, anti bocor dan tahan iklim tropis panas.'
                    ],
                    [
                        'id' => 105,
                        'nama' => 'Lampu Utama LED H4 6000K Super Bright',
                        'kode_part' => '81110-BZ-LED',
                        'kategori' => 'Kelistrikan',
                        'gambar' => 'dist/assets/img/prod-3.jpg',
                        'harga' => 320000,
                        'stok' => 20,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Cahaya putih terang 6000K, hemat konsumsi aki & tembus hujan deras.'
                    ],
                    [
                        'id' => 106,
                        'nama' => 'Kampas Rem Depan Ceramic Brake Pad',
                        'kode_part' => '04465-BZ010',
                        'kategori' => 'Pengereman',
                        'gambar' => 'dist/assets/img/prod-2.jpg',
                        'harga' => 290000,
                        'stok' => 18,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Formula keramik anti bunyi nyaring, pengereman pakem tanpa merusak disc rotor.'
                    ],
                    [
                        'id' => 107,
                        'nama' => 'Piringan Cakram Depan Ventid Disc',
                        'kode_part' => '43512-BZ020',
                        'kategori' => 'Pengereman',
                        'gambar' => 'dist/assets/img/prod-5.jpg',
                        'harga' => 520000,
                        'stok' => 10,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Baja karbon tempa dengan ventilasi pelepasan panas cepat.'
                    ],
                    [
                        'id' => 108,
                        'nama' => 'V-Belt & Automatic Tensioner Kit',
                        'kode_part' => '9004A-91040',
                        'kategori' => 'Transmisi',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 450000,
                        'stok' => 15,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Serat EPDM tahan aus tinggi, putaran AC & alternator senyap.'
                    ],
                    [
                        'id' => 109,
                        'nama' => 'Shockbreaker Belakang Gas Type Heavy Duty',
                        'kode_part' => '48530-BZ030',
                        'kategori' => 'Suspensi',
                        'gambar' => 'dist/assets/img/prod-5.jpg',
                        'harga' => 780000,
                        'stok' => 8,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Teknologi twin-tube gas nitrogen, sangat stabil saat muatan penuh.'
                    ]
                ]
            ],
            [
                'id' => 2,
                'nama' => 'Honda Civic 1.8L Turbo Sport',
                'kode' => 'V-HON-CIV21',
                'kategori' => 'Sedan',
                'volume_mesin' => '1800 cc',
                'model' => 'Civic',
                'tahun' => '2021',
                'gambar' => 'dist/assets/img/prod-2.jpg',
                'deskripsi' => 'Sedan sport elegan bermesin responsif dengan teknologi pengereman presisi tinggi.',
                'komponen' => [
                    [
                        'id' => 201,
                        'nama' => 'Filter Oli Full Synthetic Civic',
                        'kode_part' => '15400-PLM-A02',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 85000,
                        'stok' => 30,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Kapasitas penyaringan 20 micron untuk mesin putaran tinggi.'
                    ],
                    [
                        'id' => 202,
                        'nama' => 'Kampas Rem Depan High Performance Sport',
                        'kode_part' => '45022-TGN-G00',
                        'kategori' => 'Pengereman',
                        'gambar' => 'dist/assets/img/prod-2.jpg',
                        'harga' => 480000,
                        'stok' => 15,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Tahan panas hingga 450°C, pengereman stabil saat kecepatan tinggi.'
                    ],
                    [
                        'id' => 203,
                        'nama' => 'Busi Laser Iridium Honda Spec',
                        'kode_part' => '12290-5R0-003',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-3.jpg',
                        'harga' => 340000,
                        'stok' => 20,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Percikan api presisi tinggi khusus mesin VTEC / Turbo.'
                    ],
                    [
                        'id' => 204,
                        'nama' => 'Aki Kering High Power 12V 55Ah',
                        'kode_part' => '31500-TGA-55',
                        'kategori' => 'Kelistrikan',
                        'gambar' => 'dist/assets/img/prod-4.jpg',
                        'harga' => 1150000,
                        'stok' => 10,
                        'garansi' => '12 Bulan',
                        'spesifikasi' => 'Menyuplai modul ECU & fitur kelistrikan pintar secara konsisten.'
                    ],
                    [
                        'id' => 205,
                        'nama' => 'Shockbreaker Depan Adjustable Sport',
                        'kode_part' => '51611-TGG-A01',
                        'kategori' => 'Suspensi',
                        'gambar' => 'dist/assets/img/prod-5.jpg',
                        'harga' => 1250000,
                        'stok' => 6,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Meningkatkan cornering & meredam guncangan di jalan bergelombang.'
                    ]
                ]
            ],
            [
                'id' => 3,
                'nama' => 'Honda Brio 1.2L RS CVT',
                'kode' => 'V-HON-BRI23',
                'kategori' => 'Hatchback',
                'volume_mesin' => '1200 cc',
                'model' => 'Brio',
                'tahun' => '2023',
                'gambar' => 'dist/assets/img/prod-3.jpg',
                'deskripsi' => 'City car lincah, irit bahan bakar, dan sangat cocok untuk penggunaan sehari-hari di perkotaan.',
                'komponen' => [
                    [
                        'id' => 301,
                        'nama' => 'Filter Oli Honda i-VTEC',
                        'kode_part' => '15400-RTA-003',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 55000,
                        'stok' => 40,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Menjaga kebersihan oli mesin hemat energi.'
                    ],
                    [
                        'id' => 302,
                        'nama' => 'Busi Iridium Brio Genuine',
                        'kode_part' => '12290-RB1-003',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-3.jpg',
                        'harga' => 180000,
                        'stok' => 25,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Akselerasi awal halus dan hemat bbm.'
                    ],
                    [
                        'id' => 303,
                        'nama' => 'Kampas Rem Depan Brio RS',
                        'kode_part' => '45022-S6M-000',
                        'kategori' => 'Pengereman',
                        'gambar' => 'dist/assets/img/prod-2.jpg',
                        'harga' => 240000,
                        'stok' => 20,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Cengkraman lembut dan ramah disc rotor.'
                    ]
                ]
            ],
            [
                'id' => 4,
                'nama' => 'Toyota Innova 2.0L Reborn AT',
                'kode' => 'V-TOY-INV20',
                'kategori' => 'MPV',
                'volume_mesin' => '2000 cc',
                'model' => 'Innova',
                'tahun' => '2020',
                'gambar' => 'dist/assets/img/prod-4.jpg',
                'deskripsi' => 'Medium MPV tangguh dengan kabin luas dan suspensi sangat nyaman.',
                'komponen' => [
                    [
                        'id' => 401,
                        'nama' => 'Filter Oli Innova Gasoline 2.0',
                        'kode_part' => '04152-YZZA6',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 75000,
                        'stok' => 35,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Elemen kertas saring tebal untuk siklus oli 10.000 KM.'
                    ],
                    [
                        'id' => 402,
                        'nama' => 'Aki Kering Heavy Duty 12V 65Ah',
                        'kode_part' => '28800-65Ah-MF',
                        'kategori' => 'Kelistrikan',
                        'gambar' => 'dist/assets/img/prod-4.jpg',
                        'harga' => 1250000,
                        'stok' => 14,
                        'garansi' => '12 Bulan',
                        'spesifikasi' => 'Kapasitas daya besar untuk dual AC & audio multimedia.'
                    ],
                    [
                        'id' => 403,
                        'nama' => 'Shockbreaker Belakang Comfort Touring',
                        'kode_part' => '48530-0K260',
                        'kategori' => 'Suspensi',
                        'gambar' => 'dist/assets/img/prod-5.jpg',
                        'harga' => 920000,
                        'stok' => 12,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Meningkatkan kenyamanan penumpang baris ke-2 dan ke-3.'
                    ]
                ]
            ],
            [
                'id' => 5,
                'nama' => 'Mitsubishi Xpander 1.5L Ultimate',
                'kode' => 'V-MIT-XPN22',
                'kategori' => 'MPV',
                'volume_mesin' => '1500 cc',
                'model' => 'Xpander',
                'tahun' => '2022',
                'gambar' => 'dist/assets/img/prod-5.jpg',
                'deskripsi' => 'Crossover MPV modern dengan ground clearance tinggi dan suspensi mantap.',
                'komponen' => [
                    [
                        'id' => 501,
                        'nama' => 'Filter Oli Xpander MIVEC',
                        'kode_part' => 'MD135737',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 60000,
                        'stok' => 30,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Presisi tinggi mencegah aus berlebih pada katup MIVEC.'
                    ],
                    [
                        'id' => 502,
                        'nama' => 'Shockbreaker Belakang Gas Xpander',
                        'kode_part' => '4162A344',
                        'kategori' => 'Suspensi',
                        'gambar' => 'dist/assets/img/prod-5.jpg',
                        'harga' => 820000,
                        'stok' => 10,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Meredam ayunan bodi saat melintasi jalan bergelombang.'
                    ]
                ]
            ],
            [
                'id' => 6,
                'nama' => 'Mitsubishi Pajero Sport 2.5L Dakar',
                'kode' => 'V-MIT-PAJ19',
                'kategori' => 'SUV',
                'volume_mesin' => '2500 cc+',
                'model' => 'Pajero',
                'tahun' => '2019',
                'gambar' => 'dist/assets/img/prod-1.jpg',
                'deskripsi' => 'Ladder frame SUV bertenaga diesel turbo besar untuk segala medan jalanan.',
                'komponen' => [
                    [
                        'id' => 601,
                        'nama' => 'Filter Solar / Fuel Filter Diesel Pajero',
                        'kode_part' => '1770A233',
                        'kategori' => 'Mesin',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 220000,
                        'stok' => 20,
                        'garansi' => '6 Bulan',
                        'spesifikasi' => 'Menyaring air dan kotoran dari bahan bakar bio-diesel.'
                    ],
                    [
                        'id' => 602,
                        'nama' => 'V-Belt Drive Kit Heavy Duty 4x4',
                        'kode_part' => '13568-39015',
                        'kategori' => 'Transmisi',
                        'gambar' => 'dist/assets/img/prod-1.jpg',
                        'harga' => 680000,
                        'stok' => 8,
                        'garansi' => '1 Tahun',
                        'spesifikasi' => 'Serat baja Kevlar anti putus untuk beban ekstrem.'
                    ]
                ]
            ]
        ];
    }

    public function index()
    {
        $vehicles = $this->getVehiclesData();

        // Parameter list filter
        $kategoriList = ['MPV', 'Sedan', 'Hatchback', 'SUV'];
        $volumeMesinList = ['1000 cc', '1200 cc', '1500 cc', '1800 cc', '2000 cc', '2500 cc+'];
        $modelList = ['Avanza', 'Innova', 'Civic', 'Brio', 'Xpander', 'Pajero'];
        $tahunList = ['2024', '2023', '2022', '2021', '2020', '2019', '2018'];

        return view('user.spareparts.index', compact(
            'vehicles',
            'kategoriList',
            'volumeMesinList',
            'modelList',
            'tahunList'
        ));
    }

    public function show($id)
    {
        $vehicles = $this->getVehiclesData();
        
        // Cari kendaraan berdasarkan ID
        $vehicle = collect($vehicles)->firstWhere('id', (int)$id);

        if (!$vehicle) {
            abort(404, 'Kendaraan tidak ditemukan');
        }

        // Kategori unik komponen yang ada di kendaraan ini
        $kategoriKomponen = collect($vehicle['komponen'])->pluck('kategori')->unique()->values()->all();

        return view('user.spareparts.show', compact('vehicle', 'kategoriKomponen'));
    }

    public function detail($vehicleId, $partId)
    {
        $vehicles = $this->getVehiclesData();

        $vehicle = collect($vehicles)->firstWhere('id', (int)$vehicleId);

        if (!$vehicle) {
            abort(404, 'Kendaraan tidak ditemukan');
        }

        $part = collect($vehicle['komponen'])->firstWhere('id', (int)$partId);

        if (!$part) {
            abort(404, 'Part tidak ditemukan');
        }

        $part['referensi'] = $part['referensi'] ?? $part['kode_part'];
        $part['jumlah'] = $part['jumlah'] ?? 1;

        $partOptions = collect($vehicle['komponen'])->map(function ($option) {
            $option['jumlah'] = $option['jumlah'] ?? 1;
            $option['referensi'] = $option['referensi'] ?? $option['kode_part'];
            return $option;
        })->all();

        return view('user.spareparts.detail_sparepart', compact('vehicle', 'part', 'partOptions'));
    }
}
