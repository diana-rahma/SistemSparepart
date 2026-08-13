@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Volume Mesin</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.volume_mesin.index') }}">Volume Mesin</a></li>
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
            <h3 class="card-title fw-bold m-0">Form Tambah Volume Mesin</h3>
          </div>
          
          <form action="{{ route('admin.volume_mesin.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="mb-3">
                <label for="volume" class="form-label font-weight-bold">Volume Mesin <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="volume" 
                  id="volume" 
                  class="form-control @error('volume') is-invalid @enderror" 
                  placeholder="Contoh: 1500 cc, 2000 cc" 
                  value="{{ old('volume') }}" 
                  required
                >
                @error('volume')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="kode" class="form-label font-weight-bold">Kode Volume Mesin <span class="text-muted font-weight-normal">(Opsional)</span></label>
                <input 
                  type="text" 
                  name="kode" 
                  id="kode" 
                  class="form-control @error('kode') is-invalid @enderror" 
                  placeholder="Contoh: VOL-1500" 
                  value="{{ old('kode') }}"
                >
                <div class="form-text">Kode unik untuk mengelompokkan volume mesin.</div>
                @error('kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.volume_mesin.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
