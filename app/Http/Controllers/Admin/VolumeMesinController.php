<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolumeMesin;
use Illuminate\Http\Request;

class VolumeMesinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $volumes = VolumeMesin::when($search, function ($query, $search) {
                return $query->where('volume', 'like', "%{$search}%")
                             ->orWhere('kode', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.volume_mesin.index', compact('volumes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.volume_mesin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'volume' => 'required|string|max:50|unique:volume_mesins,volume',
            'kode' => 'nullable|string|max:20|unique:volume_mesins,kode',
        ], [
            'volume.required' => 'Volume mesin wajib diisi.',
            'volume.unique' => 'Volume mesin tersebut sudah ada dalam data.',
            'volume.max' => 'Volume mesin maksimal 50 karakter.',
            'kode.unique' => 'Kode tersebut sudah digunakan.',
            'kode.max' => 'Kode maksimal 20 karakter.',
        ]);

        VolumeMesin::create($validated);

        return redirect()->route('admin.volume_mesin.index')
            ->with('success', 'Data volume mesin berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.volume_mesin.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $volume = VolumeMesin::findOrFail($id);

        return view('admin.volume_mesin.edit', compact('volume'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $volume = VolumeMesin::findOrFail($id);

        $validated = $request->validate([
            'volume' => 'required|string|max:50|unique:volume_mesins,volume,' . $id,
            'kode' => 'nullable|string|max:20|unique:volume_mesins,kode,' . $id,
        ], [
            'volume.required' => 'Volume mesin wajib diisi.',
            'volume.unique' => 'Volume mesin tersebut sudah ada dalam data.',
            'volume.max' => 'Volume mesin maksimal 50 karakter.',
            'kode.unique' => 'Kode tersebut sudah digunakan.',
            'kode.max' => 'Kode maksimal 20 karakter.',
        ]);

        $volume->update($validated);

        return redirect()->route('admin.volume_mesin.index')
            ->with('success', 'Data volume mesin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $volume = VolumeMesin::findOrFail($id);
        $volume->delete();

        return redirect()->route('admin.volume_mesin.index')
            ->with('success', 'Data volume mesin berhasil dihapus!');
    }
}
