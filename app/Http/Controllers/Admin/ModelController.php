<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel as AppModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $models = AppModel::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('kode', 'like', "%{$search}%")
                             ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.model.index', compact('models', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.model.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50|unique:models,kode',
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama model wajib diisi.',
            'nama.max' => 'Nama model maksimal 255 karakter.',
            'kode.unique' => 'Kode model sudah digunakan.',
            'kode.max' => 'Kode model maksimal 50 karakter.',
        ]);

        AppModel::create($validated);

        return redirect()->route('admin.model.index')
            ->with('success', 'model baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('admin.model.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $model = AppModel::findOrFail($id);

        return view('admin.model.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $model = AppModel::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50|unique:models,kode,' . $id,
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama model wajib diisi.',
            'nama.max' => 'Nama model maksimal 255 karakter.',
            'kode.unique' => 'Kode model sudah digunakan.',
            'kode.max' => 'Kode model maksimal 50 karakter.',
        ]);

        $model->update($validated);

        return redirect()->route('admin.model.index')
            ->with('success', 'Data model berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $model = AppModel::findOrFail($id);
        $model->delete();

        return redirect()->route('admin.model.index')
            ->with('success', 'model berhasil dihapus!');
    }
}
