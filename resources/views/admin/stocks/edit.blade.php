@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Stok</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.stok.index') }}">Stok</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
        <div class="card card-warning card-outline mb-4 shadow-sm">
          <div class="card-header">
            <h3 class="card-title fw-bold m-0">Edit Stok: {{ $stok->part?->nama ?? 'Stok' }}</h3>
          </div>
          
          <form action="{{ route('admin.stok.update', $stok->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="mb-3">
                <label for="nama" class="form-label font-weight-bold">Nama Part <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="nama" 
                  id="nama" 
                  class="form-control @error('nama') is-invalid @enderror" 
                  placeholder="Contoh: Mesin, Kelistrikan, MPV" 
                  value="{{ old('nama', $stok->part?->nama ?? '') }}" 
                  required
                >
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input 
                  type="text" 
                  name="stok" 
                  id="stok" 
                  class="form-control @error('stok') is-invalid @enderror" 
                  placeholder="Contoh: 100" 
                  value="{{ old('stok', $stok->jumlah) }}"
                >
                <div class="form-text">Jumlah stok yang tersedia.</div>
                @error('stok')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                <i class="bi bi-check-circle me-1"></i> Perbarui Stok
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
