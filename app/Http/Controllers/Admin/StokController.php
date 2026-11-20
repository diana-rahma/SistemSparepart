<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\Parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    $q->where('nama_parts', 'like', "%{$search}%")
                      ->orWhere('kode_parts', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.stocks.index', compact('stoks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parts = Parts::orderBy('nama_parts', 'asc')->get();

        return view('admin.stocks.create', compact('parts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'jumlah' => 'required|integer|min:0|max:100000',
            'harga_satuan' => 'nullable|numeric|min:0',
        ], [
            'part_id.required' => 'Part wajib dipilih.',
            'part_id.exists' => 'Part yang dipilih tidak valid.',
            'jumlah.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah.required' => 'Jumlah stok wajib diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated) {
            $stok = Stok::where('part_id', $validated['part_id'])
                ->lockForUpdate()
                ->first();

            if ($stok) {
                $stok->jumlah = ($stok->jumlah ?? 0) + $validated['jumlah'];
                $stok->save();

                return;
            }

            Stok::create([
                'part_id' => $validated['part_id'],
                'jumlah' => $validated['jumlah'],
                'harga_satuan' => $validated['harga_satuan'] ?? 0,
            ]);
        });

        return redirect()->route('admin.stok.index')
            ->with('success', 'Jumlah stok berhasil ditambahkan!');
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
        $parts = Parts::orderBy('nama_parts', 'asc')->get();

        return view('admin.stocks.edit', compact('stok', 'parts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stok = Stok::findOrFail($id);

        $validated = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'jumlah' => 'required|integer|min:0|max:100000',
            'harga_satuan' => 'nullable|numeric|min:0',
        ], [
            'part_id.required' => 'Part wajib dipilih.',
            'part_id.exists' => 'Part yang dipilih tidak valid.',
            'jumlah.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah.required' => 'Jumlah stok wajib diisi',
            'harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
        ]);

        $stok->update([
            'part_id' => $validated['part_id'],
            'jumlah' => $validated['jumlah'] ?? 0,
            'harga_satuan' => $validated['harga_satuan'] ?? 0,
        ]);

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
            ->with('success', 'Stok berhasil dihapus!');
    }
}
