<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index (Request $request){
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.data_pengguna.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.data_pengguna.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,pembeli,user',
        ]);

        // Create a new user
        User::create($validatedData);

        return redirect()->route('admin.pengguna.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $users)
    {
        return view('admin.data_pengguna.edit', compact('users'));
    }

    public function update(Request $request, User $users)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $users->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:admin,pembeli,user',
        ]);

        // Jika password tidak diisi (null / kosong), jangan update password
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        // Update user data
        $users->update($validatedData);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $users)
    {
        $users->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'User deleted successfully.');
    }
}
