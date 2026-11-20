<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TipeKendaraan;
use App\Models\Sparepart;
use App\Models\Kategori;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Step 1: Display catalog search/filter page with Tipe Kendaraan cards.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedKategori = $request->input('kategori');
        $selectedModel = $request->input('model');
        $selectedTahun = $request->input('tahun');

        $tipeKendaraans = TipeKendaraan::with(['model', 'kategori', 'spareparts.sparepartParts'])
            ->withCount('spareparts')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('kode_tipe_kendaraan', 'like', "%{$search}%")
                      ->orWhere('nomor_unit', 'like', "%{$search}%")
                      ->orWhere('tahun_produksi', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('model', function ($qm) use ($search) {
                          $qm->where('nama', 'like', "%{$search}%");
                      })
                      ->orWhereHas('kategori', function ($qk) use ($search) {
                          $qk->where('nama', 'like', "%{$search}%");
                      })
                      ->orWhereHas('spareparts', function ($qs) use ($search) {
                          $qs->where('nama_sparepart', 'like', "%{$search}%");
                      });
                });
            })
            ->when($selectedKategori, function ($query, $kategori) {
                return $query->whereHas('kategori', function ($q) use ($kategori) {
                    $q->where('nama', $kategori)->orWhere('id', $kategori);
                });
            })
            ->when($selectedModel, function ($query, $model) {
                return $query->whereHas('model', function ($q) use ($model) {
                    $q->where('nama', $model)->orWhere('id', $model);
                });
            })
            ->when($selectedTahun, function ($query, $tahun) {
                return $query->where('tahun_produksi', 'like', "%{$tahun}%");
            })
            ->oldest()
            ->get();

        $kategoriList = Kategori::pluck('nama')->toArray();
        $modelList = VehicleModel::pluck('nama')->toArray();
        // Ambil daftar tahun unik langsung dari kolom tahun_produksi
        $tahunList = TipeKendaraan::whereNotNull('tahun_produksi')
            ->pluck('tahun_produksi')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return view('user.spareparts.index', compact(
            'tipeKendaraans',
            'kategoriList',
            'modelList',
            'tahunList',
            'search',
            'selectedKategori',
            'selectedModel',
            'selectedTahun'
        ));
    }

    /**
     * Step 2: Display selected Tipe Kendaraan details & list of its Spareparts.
     */
    public function show($id)
    {
        $tipeKendaraan = TipeKendaraan::with(['model', 'kategori', 'spareparts.sparepartParts'])->findOrFail($id);

        return view('user.spareparts.show', compact('tipeKendaraan'));
    }

    /**
     * Step 3: Display selected Sparepart interactive diagram and mapped parts table.
     */
    public function detail($vehicleId, $sparepartId)
    {
        $tipeKendaraan = TipeKendaraan::with(['model', 'kategori'])->find($vehicleId);
        $sparepart = Sparepart::with(['sparepartParts.part.stok'])->findOrFail($sparepartId);

        return view('user.spareparts.detail_sparepart', compact('tipeKendaraan', 'sparepart'));
    }
}
