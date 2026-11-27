<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use App\Models\Parts;
use App\Models\SparepartPart;
use App\Models\SparepartPartPenanda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $spareparts = Sparepart::when($search, function ($query, $search) {
                return $query->where('nama_sparepart', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.spareparts.index', compact('spareparts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.spareparts.create');
    }

    /**
     * Store a newly created resource in storage and redirect directly to mapping page.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_sparepart' => 'required|string|max:255',
        'deskripsi' => 'nullable|string|max:1000',
        'gambar' => 'required|file|mimes:jpeg,png,jpg,webp,svg,pdf|max:10240',
    ], [
        'nama_sparepart.required' => 'Nama sparepart wajib diisi.',
        'nama_sparepart.max' => 'Nama sparepart maksimal 255 karakter.',
        'gambar.required' => 'File gambar/PDF sparepart wajib diunggah.',
        'gambar.file' => 'File tidak valid.',
        'gambar.mimes' => 'Format file harus jpeg, png, jpg, webp, svg, atau pdf.',
        'gambar.max' => 'Ukuran file maksimal 10MB.',
    ]);

    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('spareparts', 'supabase');

        $validated['gambar'] = $path;
    }

    $sparepart = Sparepart::create($validated);

    return redirect()->route('admin.spareparts.mapping', $sparepart->id)
        ->with('success', 'Sparepart berhasil ditambahkan! Silakan lakukan mapping gambar berikut.');
}

    /**
     * Display the mapping interface for the sparepart image.
     */
   public function mapping($id)
    {
        $sparepart = Sparepart::with(['sparepartParts.part', 'sparepartParts.penanda'])->findOrFail($id);
        $parts = Parts::orderBy('nama_parts')->get();

        return view('admin.spareparts.mapping', compact('sparepart', 'parts'));
    }

    /**
     * Route: POST /admin/spareparts/{id}/parts
     * Satu part = satu baris sparepart_part, tiap panah = satu baris sparepart_part_penanda.
     */
    public function storePart(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'part_id'         => 'required|exists:parts,id',
            'nomor_referensi' => 'required|string|max:50',
            'jumlah'          => 'required|integer|min:1',
            'unit'            => 'required|in:pcs,set',
            'tip_x'  => 'required|numeric|between:0,100',
            'tip_y'  => 'required|numeric|between:0,100',
            'tail_x' => 'required|numeric|between:0,100',
            'tail_y' => 'required|numeric|between:0,100',
            'box_x'  => 'required|numeric|between:0,100',
            'box_y'  => 'required|numeric|between:0,100',
        ], [
            'part_id.required'         => 'Pilih part terlebih dahulu.',
            'part_id.exists'           => 'Part yang dipilih tidak valid.',
            'nomor_referensi.required' => 'Nomor referensi wajib diisi.',
            'jumlah.required'          => 'Jumlah wajib diisi.',
            'unit.required'            => 'Unit wajib dipilih.',
            'unit.in'                  => 'Unit yang dipilih tidak valid.',
            'tip_x.required'           => 'Panah dan kotak nomor belum lengkap.',
        ]);

        $mapping = DB::transaction(function () use ($sparepart, $validated) {
            $mapping = SparepartPart::firstOrCreate(
                ['sparepart_id' => $sparepart->id, 'part_id' => $validated['part_id']],
                [
                    'nomor_referensi' => trim($validated['nomor_referensi']),
                    'jumlah'          => (int) $validated['jumlah'],
                    'unit'            => $validated['unit'],
                ]
            );

            // Part sudah ada: nomor & unit dipertahankan, jumlah mengikuti input terbaru
            if (!$mapping->wasRecentlyCreated) {
                $mapping->update(['jumlah' => (int) $validated['jumlah']]);
            }

            $mapping->penanda()->create(
                Arr::only($validated, ['tip_x', 'tip_y', 'tail_x', 'tail_y', 'box_x', 'box_y'])
            );

            return $mapping;
        });

        $mapping->load('part', 'penanda');

        return response()->json([
            'success' => true,
            'message' => $mapping->wasRecentlyCreated
                ? "Mapping nomor referensi {$mapping->nomor_referensi} berhasil ditambahkan!"
                : "Panah baru ditambahkan ke nomor referensi {$mapping->nomor_referensi}, jumlah menjadi {$mapping->jumlah}.",
            'data' => $this->mappingPayload($mapping),
        ]);
    }

    /**
     * Route: DELETE /admin/spareparts/{id}/parts/{pivotId}
     * Menghapus mapping beserta semua panahnya (penanda ikut terhapus lewat cascade di database).
     */
    public function destroyPart($id, $pivotId)
    {
        $mapping = SparepartPart::where('sparepart_id', $id)->find($pivotId);

        if (!$mapping) {
            return response()->json(['success' => false, 'message' => 'Mapping tidak ditemukan.'], 404);
        }

        $mapping->delete();

        return response()->json(['success' => true, 'message' => 'Mapping part berhasil dihapus.']);
    }

    /**
     * Route: DELETE /admin/spareparts/{id}/penanda/{penandaId}
     * Menghapus satu panah + kotak nomor saja, mapping part tetap ada.
     */
    public function destroyPenanda($id, $penandaId)
    {
        $penanda = SparepartPartPenanda::where('id', $penandaId)
            ->whereHas('mapping', fn ($q) => $q->where('sparepart_id', $id))
            ->first();

        if (!$penanda) {
            return response()->json(['success' => false, 'message' => 'Penanda tidak ditemukan.'], 404);
        }

        $penanda->delete();

        return response()->json(['success' => true, 'message' => 'Panah berhasil dihapus.']);
    }

    /** Bentuk data yang dibaca JavaScript, sama dengan $mappingsData di view. */
    private function mappingPayload(SparepartPart $m): array
    {
        return [
            'id'              => $m->id,
            'part_id'         => $m->part_id,
            'nomor_referensi' => (string) $m->nomor_referensi,
            'jumlah'          => (int) $m->jumlah,
            'unit'            => $m->unit ?? 'pcs',
            'part_kode'       => $m->part?->kode_parts ?? $m->part?->kode_part ?? '-',
            'part_nama'       => $m->part?->nama_parts ?? $m->part?->nama_part ?? '-',
            'penanda'         => $m->penanda->map(fn ($p) => [
                'id'     => $p->id,
                'tip_x'  => (float) $p->tip_x,  'tip_y'  => (float) $p->tip_y,
                'tail_x' => (float) $p->tail_x, 'tail_y' => (float) $p->tail_y,
                'box_x'  => (float) $p->box_x,  'box_y'  => (float) $p->box_y,
            ])->values(),
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.spareparts.mapping', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    /**
     * Update the specified resource in storage.
     */
    

    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,pdf|max:10240',
        ], [
            'nama_sparepart.required' => 'Nama sparepart wajib diisi.',
            'nama_sparepart.max' => 'Nama sparepart maksimal 255 karakter.',
            'gambar.file' => 'File tidak valid.',
            'gambar.mimes' => 'Format file harus jpeg, png, jpg, webp, svg, atau pdf.',
            'gambar.max' => 'Ukuran file maksimal 10MB.',
        ]);

        // Simpan path gambar lama sebelum data diperbarui.
        $oldPath = $sparepart->gambar;
        $newPath = null;

        // Jika ada file baru, unggah ke Supabase.
        if ($request->hasFile('gambar')) {
            $newPath = $request->file('gambar')->store('spareparts', 'supabase');
            $validated['gambar'] = $newPath;
        }

        // Perbarui data sparepart.
        $sparepart->update($validated);

        // Hapus file lama hanya jika file baru berhasil diunggah
        // dan file lama memang tersimpan di Supabase.
        if ($newPath && $oldPath &&
            !str_starts_with($oldPath, 'storage/') &&
            !str_starts_with($oldPath, 'http://') &&
            !str_starts_with($oldPath, 'https://')) {
            Storage::disk('supabase')->delete($oldPath);
        }

        return redirect()->route('admin.spareparts.mapping', $sparepart->id)
            ->with('success', 'Data sparepart berhasil diperbarui!');
    }



    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $oldPath = $sparepart->gambar;

        // Hapus file dari Supabase jika menggunakan path Supabase.
        if (
            $oldPath &&
            !str_starts_with($oldPath, 'storage/') &&
            !str_starts_with($oldPath, 'http://') &&
            !str_starts_with($oldPath, 'https://')
        ) {
            Storage::disk('supabase')->delete($oldPath);
        }

        // Hapus data sparepart dari database.
        $sparepart->delete();

        return redirect()->route('admin.spareparts.index')
            ->with('success', 'Data sparepart berhasil dihapus!');
    }

}
