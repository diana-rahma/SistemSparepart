@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Pengguna Baru</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.pengguna.index') }}">Data Pengguna</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline mb-4 shadow-sm">
          <div class="card-header">
            <h3 class="card-title fw-bold m-0">Form Tambah Pengguna</h3>
          </div>
          
          <form action="{{ route('admin.pengguna.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="mb-3">
                <label for="name" class="form-label font-weight-bold">Nama Pengguna <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="name" 
                  id="name" 
                  class="form-control @error('name') is-invalid @enderror" 
                  placeholder="John Doe" 
                  value="{{ old('name') }}" 
                  required
                >
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input 
                  type="email" 
                  name="email" 
                  id="email" 
                  class="form-control @error('email') is-invalid @enderror" 
                  placeholder="john.doe@example.com" 
                  value="{{ old('email') }}"
                >
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="role" class="form-label">Role / Peran <span class="text-danger">*</span></label>
                <select
                  name="role"
                  id="role"
                  class="form-select @error('role') is-invalid @enderror"
                  required
                >
                  <option value="" disabled {{ old('role') == '' ? 'selected' : '' }}>-- Pilih Role --</option>
                  <option value="admin"   {{ old('role') == 'admin'   ? 'selected' : '' }}>Admin</option>
                  <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                  <option value="user"    {{ old('role') == 'user'    ? 'selected' : '' }}>User</option>
                </select>
                @error('role')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input 
                  type="password"
                  name="password" 
                  id="password" 
                  class="form-control @error('password') is-invalid @enderror" 
                  placeholder="Masukkan Password minimal 8 karakter"
                  autocomplete="new-password"
                >
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Pengguna Baru
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
