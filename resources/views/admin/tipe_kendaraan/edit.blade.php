@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Tipe Kendaraan / Sarana Kereta Api</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.tipe_kendaraan.index') }}">Tipe Kendaraan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="card card-primary card-outline mb-4 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold m-0">Form Edit Sarana Kereta Api</h3>
            <a href="{{ route('admin.tipe_kendaraan.spareparts', $tipeKendaraan->id) }}" class="btn btn-outline-info btn-sm rounded-pill">
              <i class="bi bi-diagram-3 me-1"></i> Kelola Sparepart
            </a>
          </div>

          <form action="{{ route('admin.tipe_kendaraan.update', $tipeKendaraan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
              {{-- 1. Nama Kereta --}}
              <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama Kereta <span class="text-danger">*</span></label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $tipeKendaraan->nama) }}" required>
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="row">
                {{-- 2. Nama Model (FK) --}}
                <div class="col-md-6 mb-3">
                  <label for="id_model" class="form-label fw-bold">Nama Model</label>
                  <select name="id_model" id="id_model" class="form-select @error('id_model') is-invalid @enderror" data-placeholder="-- Pilih Nomor Model --">
                    <option value=""></option>
                    @foreach($models as $model)
                      <option value="{{ $model->id }}" {{ old('id_model', $tipeKendaraan->id_model) == $model->id ? 'selected' : '' }}>
                        {{ $model->nama }}{{ $model->kode ? ' (' . $model->kode . ')' : '' }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_model')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- 3. Kategori (FK) --}}
                <div class="col-md-6 mb-3">
                  <label for="id_kategori" class="form-label fw-bold">Kategori</label>
                  <select name="id_kategori" id="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" data-placeholder="-- Pilih Kategori --">
                    <option value=""></option>
                    @foreach($kategoris as $kategori)
                      <option value="{{ $kategori->id }}" {{ old('id_kategori', $tipeKendaraan->id_kategori) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_kategori')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                {{-- 4. Nomor Unit --}}
                <div class="col-md-6 mb-3">
                  <label for="nomor_unit" class="form-label fw-bold">Nomor Unit</label>
                  <input type="text" name="nomor_unit" id="nomor_unit" class="form-control @error('nomor_unit') is-invalid @enderror" value="{{ old('nomor_unit', $tipeKendaraan->nomor_unit) }}">
                  @error('nomor_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- 5. Tahun Pembuatan --}}
                <div class="col-md-6 mb-3">
                  <label for="tahun_produksi" class="form-label fw-bold">Tahun Pembuatan</label>
                  <input type="text" name="tahun_produksi" id="tahun_produksi" class="form-control @error('tahun_produksi') is-invalid @enderror" placeholder="Contoh: 2018, 2021" value="{{ old('tahun_produksi', $tipeKendaraan->tahun_produksi) }}">
                  @error('tahun_produksi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              {{-- 6. Deskripsi --}}
              <div class="mb-3">
                <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $tipeKendaraan->deskripsi) }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- 7. Gambar --}}
              <div class="mb-3">
                <label for="gambar" class="form-label fw-bold">Gambar Sarana / Kereta</label>
                @if($tipeKendaraan->gambar)
                  <div class="mb-2">
                    <img src="{{ $tipeKendaraan->gambar_url }}" alt="{{ $tipeKendaraan->nama }}" class="img-thumbnail rounded shadow-sm" style="max-height: 150px;">
                  </div>
                @endif
                <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                @error('gambar')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('admin.tipe_kendaraan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(function() {
    $('#id_model').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: $('#id_model').data('placeholder')
    });

    $('#id_kategori').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: $('#id_kategori').data('placeholder')
    });
  });
</script>
@endpush
