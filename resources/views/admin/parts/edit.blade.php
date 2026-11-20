@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Parts</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.parts.index') }}">Parts</a></li>
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
            <h3 class="card-title fw-bold m-0">Edit Parts: {{ $parts->nama_parts }}</h3>
          </div>
          
          <form action="{{ route('admin.parts.update', $parts->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="mb-3">
                <label for="nama_parts" class="form-label font-weight-bold">Nama Parts <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="nama_parts" 
                  id="nama_parts" 
                  class="form-control @error('nama_parts') is-invalid @enderror" 
                  placeholder="Contoh: Mesin, Kelistrikan, MPV" 
                  value="{{ old('nama_parts', $parts->nama_parts) }}" 
                  required
                >
                @error('nama_parts')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="kode_parts" class="form-label">Kode Parts <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="kode_parts" 
                  id="kode_parts" 
                  class="form-control @error('kode_parts') is-invalid @enderror" 
                  placeholder="Contoh: KAT-MSN" 
                  value="{{ old('kode_parts', $parts->kode_parts) }}"
                >
                @error('kode_parts')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi <span class="text-muted">(Opsional)</span></label>
                <textarea 
                  name="deskripsi" 
                  id="deskripsi" 
                  rows="4" 
                  class="form-control @error('deskripsi') is-invalid @enderror" 
                  placeholder="Penjelasan singkat mengenai kategori ini..."
                >{{ old('deskripsi', $parts->deskripsi) }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div> -->
            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.parts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                <i class="bi bi-check-circle me-1"></i> Perbarui Parts
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
