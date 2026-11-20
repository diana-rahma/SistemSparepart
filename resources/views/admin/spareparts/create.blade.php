@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Sparepart</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.spareparts.index') }}">Spareparts</a></li>
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
            <h3 class="card-title fw-bold m-0">Form Tambah Sparepart</h3>
          </div>
          
          <form action="{{ route('admin.spareparts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="mb-3">
                <label for="nama_sparepart" class="form-label fw-bold">Nama Sparepart <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="nama_sparepart" 
                  id="nama_sparepart" 
                  class="form-control @error('nama_sparepart') is-invalid @enderror"  
                  required
                >
                @error('nama_sparepart')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea 
                  name="deskripsi" 
                  id="deskripsi" 
                  rows="4" 
                  class="form-control @error('deskripsi') is-invalid @enderror" 
                  placeholder="Penjelasan singkat mengenai sparepart ini..."
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div> -->

              <div class="mb-3">
                <label for="gambar" class="form-label fw-bold">Gambar Sparepart <span class="text-danger">*</span></label>
                <input 
                  type="file" 
                  name="gambar" 
                  id="gambar" 
                  class="form-control @error('gambar') is-invalid @enderror" 
                  accept="image/*,.pdf,application/pdf"
                  required
                >
                <small class="form-text text-muted">
                  <i class="bi bi-info-circle me-1"></i>Unggah gambar (JPG, PNG, WebP) atau file PDF sparepart. Setelah disimpan, Anda akan langsung diarahkan ke halaman mapping.
                </small>
                @error('gambar')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-right-circle me-1"></i> Simpan & Lanjut Mapping
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection