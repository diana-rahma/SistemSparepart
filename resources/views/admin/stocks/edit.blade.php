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
            <h3 class="card-title fw-bold m-0">Edit Stok: {{ $stok->part?->nama_parts ?? $stok->part?->nama_part ?? 'Stok' }}</h3>
          </div>
          
          <form action="{{ route('admin.stok.update', $stok->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="mb-3">
                <label for="part_id" class="form-label font-weight-bold">Pilih Part <span class="text-danger">*</span></label>
                <select 
                  name="part_id" 
                  id="part_id" 
                  class="form-select @error('part_id') is-invalid @enderror" 
                  required
                >
                  <option value="">-- Pilih Part --</option>
                  @foreach($parts as $part)
                    <option value="{{ $part->id }}" {{ old('part_id', $stok->part_id) == $part->id ? 'selected' : '' }}>
                      {{ $part->nama_parts ?? $part->nama_part }} {{ ($part->kode_parts ?? $part->kode_part) ? '('.($part->kode_parts ?? $part->kode_part).')' : '' }}
                    </option>
                  @endforeach
                </select>
                @error('part_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="jumlah" class="form-label font-weight-bold">Jumlah Stok</label>
                <input 
                  type="number" 
                  name="jumlah" 
                  id="jumlah" 
                  min="0"
                  class="form-control @error('jumlah') is-invalid @enderror" 
                  placeholder="Contoh: 100" 
                  value="{{ old('jumlah', $stok->jumlah) }}"
                >
                <div class="form-text">Jumlah stok part yang tersedia.</div>
                @error('jumlah')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- <div class="mb-3">
                <label for="harga_satuan" class="form-label font-weight-bold">Harga Satuan (Rp)</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input 
                    type="number" 
                    name="harga_satuan" 
                    id="harga_satuan" 
                    min="0"
                    step="0.01"
                    class="form-control @error('harga_satuan') is-invalid @enderror" 
                    placeholder="Contoh: 150000" 
                    value="{{ old('harga_satuan', $stok->harga_satuan) }}"
                  >
                </div>
                <div class="form-text">Harga satuan untuk part ini dalam Rupiah.</div>
                @error('harga_satuan')
                  <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
              </div> -->

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
