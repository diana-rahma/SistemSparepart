<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tahuns = Tahun::when($search, function ($query, $search) {
                return $query->where('tahun', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tahun.index', compact('tahuns', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:50|unique:tahuns,tahun',
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.unique' => 'Tahun tersebut sudah ada dalam data.',
            'tahun.max' => 'Tahun maksimal 50 karakter.',
        ]);

        Tahun::create($validated);

        return redirect()->route('admin.tahun.index')
            ->with('success', 'Data tahun berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.tahun.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tahun = Tahun::findOrFail($id);

        return view('admin.tahun.edit', compact('tahun'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tahun = Tahun::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|string|max:50|unique:tahuns,tahun,' . $id,
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.unique' => 'Tahun tersebut sudah ada dalam data.',
            'tahun.max' => 'Tahun maksimal 50 karakter.',
        ]);

        $tahun->update($validated);

        return redirect()->route('admin.tahun.index')
            ->with('success', 'Data tahun berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tahun = Tahun::findOrFail($id);
        $tahun->delete();

        return redirect()->route('admin.tahun.index')
            ->with('success', 'Data tahun berhasil dihapus!');
    }
}
