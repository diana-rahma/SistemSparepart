<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipeKendaraan;
use App\Models\VehicleModel;
use App\Models\Kategori;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TipeKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tipeKendaraans = TipeKendaraan::withCount('spareparts')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_model', 'like', "%{$search}%")
                    ->orWhere('jenis_sarana', 'like', "%{$search}%")
                    ->orWhere('nomor_unit', 'like', "%{$search}%")
                    ->orWhere('tahun_produksi', 'like', "%{$search}%")
                    ->orWhere('kode_tipe_kendaraan', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tipe_kendaraan.index', compact('tipeKendaraans', 'search'));
    }

    public function create()
    {
        $models = VehicleModel::orderBy('kode')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.tipe_kendaraan.create', compact('models', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'id_model' => 'nullable|exists:models,id',
            'id_kategori' => 'nullable|exists:kategoris,id',
            'nomor_unit' => 'nullable|string|max:100',
            'tahun_produksi' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ], [
            'nama.required' => 'Nama kereta wajib diisi.',
            'nama.max' => 'Nama kereta maksimal 255 karakter.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau svg.',
            'gambar.max' => 'Ukuran gambar maksimal 4MB.',
        ]);

        $model = isset($validated['id_model'])
            ? VehicleModel::findOrFail($validated['id_model'])
            : null;

        $validated['nomor_model'] = $model?->nama;
        $validated['kode_tipe_kendaraan'] = $validated['nomor_model'] ?? null;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('tipe_kendaraan', 'supabase');
            $validated['gambar'] = $path;
        }

        $tipeKendaraan = TipeKendaraan::create($validated);

        return redirect()->route('admin.tipe_kendaraan.spareparts', $tipeKendaraan->id)
            ->with('success', 'Data sarana kereta api berhasil ditambahkan! Silakan pilih sparepart untuk tipe ini.');
    }

    public function show($id)
    {
        return redirect()->route('admin.tipe_kendaraan.edit', $id);
    }

    public function edit($id)
    {
        $tipeKendaraan = TipeKendaraan::findOrFail($id);
        $models = VehicleModel::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.tipe_kendaraan.edit', compact('tipeKendaraan', 'models', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $tipeKendaraan = TipeKendaraan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'id_model' => 'nullable|exists:models,id',
            'id_kategori' => 'nullable|exists:kategoris,id',
            'nomor_unit' => 'nullable|string|max:100',
            'tahun_produksi' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ], [
            'nama.required' => 'Nama kereta wajib diisi.',
            'nama.max' => 'Nama kereta maksimal 255 karakter.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau svg.',
            'gambar.max' => 'Ukuran gambar maksimal 4MB.',
        ]);

        $model = isset($validated['id_model'])
            ? VehicleModel::findOrFail($validated['id_model'])
            : null;

        $validated['nomor_model'] = $model?->nama;
        $validated['kode_tipe_kendaraan'] = $validated['nomor_model'] ?? null;

        // Simpan path gambar lama sebelum update.
        $oldPath = $tipeKendaraan->gambar;
        $newPath = null;

        if ($request->hasFile('gambar')) {
            $newPath = $request->file('gambar')->store('tipe_kendaraan', 'supabase');
            $validated['gambar'] = $newPath;
        }

        // Perbarui data.
        $tipeKendaraan->update($validated);

        // Hapus gambar lama setelah gambar baru berhasil diunggah dan data diperbarui.
        if ($newPath && $oldPath) {
            $this->deleteStoredImage($oldPath);
        }

        return redirect()->route('admin.tipe_kendaraan.index')
            ->with('success', 'Data sarana kereta api berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tipeKendaraan = TipeKendaraan::findOrFail($id);

        $oldPath = $tipeKendaraan->gambar;

        $tipeKendaraan->delete();

        if ($oldPath) {
            $this->deleteStoredImage($oldPath);
        }

        return redirect()->route('admin.tipe_kendaraan.index')
            ->with('success', 'Data sarana kereta api berhasil dihapus!');
    }

    /**
     * Show form to manage spareparts for a specific Tipe Kendaraan / Sarana.
     */
    public function spareparts($id)
    {
        $tipeKendaraan = TipeKendaraan::with(['spareparts.sparepartParts'])->findOrFail($id);
        $allSpareparts = Sparepart::withCount('sparepartParts')->orderBy('nama_sparepart')->get();
        $attachedIds = $tipeKendaraan->spareparts->pluck('id')->toArray();

        return view('admin.tipe_kendaraan.spareparts', compact('tipeKendaraan', 'allSpareparts', 'attachedIds'));
    }

    /**
     * Store selected spareparts into tipekendaraan_sparepart pivot table.
     */
    public function storeSpareparts(Request $request, $id)
    {
        $tipeKendaraan = TipeKendaraan::findOrFail($id);

        $validated = $request->validate([
            'sparepart_ids' => 'required|array',
            'sparepart_ids.*' => 'exists:sparepart,id',
        ], [
            'sparepart_ids.required' => 'Pilih minimal satu sparepart yang akan ditambahkan.',
            'sparepart_ids.array' => 'Data pilihan sparepart tidak valid.',
            'sparepart_ids.*.exists' => 'Sparepart yang dipilih tidak ditemukan dalam database.',
        ]);

        $tipeKendaraan->spareparts()->syncWithoutDetaching($validated['sparepart_ids']);

        return redirect()->route('admin.tipe_kendaraan.spareparts', $tipeKendaraan->id)
            ->with('success', 'Sparepart berhasil ditambahkan ke ' . $tipeKendaraan->nama . '!');
    }

    /**
     * Remove a sparepart relationship from tipekendaraan_sparepart pivot table.
     */
    public function destroySparepart($id, $sparepartId)
    {
        $tipeKendaraan = TipeKendaraan::findOrFail($id);
        $tipeKendaraan->spareparts()->detach($sparepartId);

        return redirect()->route('admin.tipe_kendaraan.spareparts', $tipeKendaraan->id)
            ->with('success', 'Sparepart berhasil dilepas dari tipe sarana!');
    }

    private function deleteStoredImage($path)
    {
        if (!$path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        // Mendukung path lama yang menggunakan awalan storage/.
        $relativePath = str_starts_with($path, 'storage/')
            ? substr($path, strlen('storage/'))
            : $path;

        // Periksa Supabase terlebih dahulu.
        if (Storage::disk('supabase')->exists($relativePath)) {
            Storage::disk('supabase')->delete($relativePath);
            return;
        }

        // Jika tidak ada di Supabase, periksa penyimpanan lokal.
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
