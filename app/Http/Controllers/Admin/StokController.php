<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\Parts;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $stoks = Stok::with('part')
            ->when($search, function ($query, $search) {
                return $query->whereHas('part', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.stocks.index', compact('stoks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'nullable|integer|max:100000',
        ], [
            'nama.required' => 'Nama part wajib diisi.',
            'nama.max' => 'Nama part maksimal 255 karakter.',
            'stok.max' => 'Stok maksimal 100000.',
        ]);

        // find or create part, then create stock record
        $part = Parts::firstOrCreate([
            'nama' => $validated['nama'],
        ], [
            'kode' => null,
            'deskripsi' => null,
        ]);

        Stok::create([
            'jumlah' => $validated['stok'] ?? null,
            'part_id' => $part->id,
        ]);

        return redirect()->route('admin.stok.index')
            ->with('success', 'Stok baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.stok.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stok = Stok::findOrFail($id);

        return view('admin.stocks.edit', compact('stok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stok = Stok::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'nullable|integer|max:100000',
        ], [
            'nama.required' => 'Nama part wajib diisi.',
            'nama.max' => 'Nama part maksimal 255 karakter.',
            'stok.max' => 'Stok maksimal 100000.',
        ]);

        // update part name and stock jumlah
        if ($stok->part) {
            $stok->part->update(['nama' => $validated['nama']]);
        } else {
            $part = Parts::firstOrCreate(['nama' => $validated['nama']]);
            $stok->part_id = $part->id;
        }

        $stok->update(['jumlah' => $validated['stok'] ?? $stok->jumlah]);

        return redirect()->route('admin.stok.index')
            ->with('success', 'Data stok berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stok = Stok::findOrFail($id);
        $stok->delete();

        return redirect()->route('admin.stok.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
