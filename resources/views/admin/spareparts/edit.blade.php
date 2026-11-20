@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Sparepart</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.spareparts.index') }}">Spareparts</a></li>
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
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold m-0">Edit Sparepart: {{ $sparepart->nama_sparepart }}</h3>
            <a href="{{ route('admin.spareparts.mapping', $sparepart->id) }}" class="btn btn-sm btn-primary">
              <i class="bi bi-crosshair me-1"></i> Buka Mapping Gambar
            </a>
          </div>
          
          <form action="{{ route('admin.spareparts.update', $sparepart->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="mb-3">
                <label for="nama_sparepart" class="form-label fw-bold">Nama Sparepart <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="nama_sparepart" 
                  id="nama_sparepart" 
                  class="form-control @error('nama_sparepart') is-invalid @enderror" 
                  value="{{ old('nama_sparepart', $sparepart->nama_sparepart) }}" 
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
                >{{ old('deskripsi', $sparepart->deskripsi) }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div> -->

              <div class="mb-3">
                <label for="gambar" class="form-label fw-bold">File Gambar / PDF Sparepart</label>
                @if($sparepart->gambar)
                  <div class="mb-2">
                    @if(strtolower(pathinfo($sparepart->gambar, PATHINFO_EXTENSION)) === 'pdf')
                      <span class="badge bg-danger fs-6 p-2"><i class="bi bi-file-earmark-pdf me-1"></i>Dokumen PDF Tersimpan</span>
                    @else
                      <img src="{{ asset($sparepart->gambar) }}" alt="{{ $sparepart->nama_sparepart }}" class="img-thumbnail rounded shadow-sm" style="max-height: 160px;">
                    @endif
                  </div>
                @endif
                <input 
                  type="file" 
                  name="gambar" 
                  id="gambar" 
                  class="form-control @error('gambar') is-invalid @enderror"
                  accept="image/*,.pdf,application/pdf"
                >
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah file gambar/PDF.</small>
                @error('gambar')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.spareparts.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-warning font-weight-bold">
                <i class="bi bi-check-circle me-1"></i> Perbarui Sparepart
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
