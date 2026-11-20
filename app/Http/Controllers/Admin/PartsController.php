<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parts;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $parts = Parts::when($search, function ($query, $search) {
                return $query->where('nama_parts', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.parts.index', compact('parts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_parts' => 'required|string|max:255',
            'kode_parts' => 'required|string|max:25|unique:parts,kode_parts',
            'deskripsi'  => 'nullable|string|max:255',
        ], [
            'nama_parts.required' => 'Nama part wajib diisi.',
            'kode_parts.required' => 'Kode part wajib diisi.',
            'kode_parts.unique'   => 'Kode part tersebut sudah ada dalam data.',
        ]);

        Parts::create($validated);

        return redirect()->route('admin.parts.index')
            ->with('success', 'Data Part berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return redirect()->route('admin.parts.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $parts = Parts::findOrFail($id);

        return view('admin.parts.edit', compact('parts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $parts = Parts::findOrFail($id);

        $validated = $request->validate([
            'nama_parts' => 'required|string|max:255',
            'kode_parts' => [
                'required',
                'string',
                'max:25',
                Rule::unique('parts', 'kode_parts')->ignore($id),
            ],
            'deskripsi'  => 'nullable|string|max:255',
        ], [
            'nama_parts.required' => 'Nama part wajib diisi.',
            'kode_parts.required' => 'Kode part wajib diisi.',
            'kode_parts.unique'   => 'Kode part tersebut sudah ada dalam data.',
        ]);

        $parts->update($validated);

        return redirect()->route('admin.parts.index')
            ->with('success', 'Data Part berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $parts = Parts::findOrFail($id);
        $parts->delete();

        return redirect()->route('admin.parts.index')
            ->with('success', 'Data part berhasil dihapus!');
    }
}
