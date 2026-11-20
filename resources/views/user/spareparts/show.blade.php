@extends('layouts.user')

@push('styles')
<style>
  .sparepart-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
    overflow: hidden;
  }
  .sparepart-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
  }
  .sparepart-img-container {
    height: 180px;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
  }
  .sparepart-img-container img {
    max-height: 100%;
    width: 100%;
    object-fit: cover;
  }
  .badge-stock {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
  }
  .vehicle-header-card {
    border-radius: 12px;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
  }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <a href="{{ route('spareparts.index') }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill">
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog Tipe Kendaraan
        </a>
        <h3 class="mb-0 text-dark font-weight-bold">
          Daftar Sparepart Kendaraan
        </h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Tipe Kendaraan</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $tipeKendaraan->nama }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    <!-- VEHICLE HEADER CARD -->
    <div class="card vehicle-header-card shadow-sm border-0 mb-4 text-white">
      <div class="card-body p-4">
        <div class="row align-items-center g-3">
          <div class="col-md-2 text-center">
            @if($tipeKendaraan->gambar)
              <img src="{{ asset($tipeKendaraan->gambar) }}" alt="{{ $tipeKendaraan->nama }}" class="img-fluid rounded bg-white p-1 shadow-sm" style="max-height: 100px; object-fit: cover;">
            @else
              <div class="bg-white bg-opacity-25 rounded p-3 text-center">
                <i class="bi bi-car-front display-4 text-white"></i>
              </div>
            @endif
          </div>
          <div class="col-md-7">
            <span class="badge bg-white text-primary mb-2 fw-semibold">Tipe Kendaraan Terpilih</span>
            <h2 class="fw-bold m-0 text-white">{{ $tipeKendaraan->nama }}</h2>
            <div class="d-flex flex-wrap gap-2 mt-2">
              @if($tipeKendaraan->model)
                <span class="badge bg-white bg-opacity-25 text-white border"><i class="bi bi-car-front me-1"></i>Model: {{ $tipeKendaraan->model->nama }}</span>
              @endif
              @if($tipeKendaraan->kategori)
                <span class="badge bg-white bg-opacity-25 text-white border"><i class="bi bi-grid me-1"></i>Kategori: {{ $tipeKendaraan->kategori->nama }}</span>
              @endif
              @if($tipeKendaraan->nomor_unit)
                <span class="badge bg-white bg-opacity-25 text-white border"><i class="bi bi-barcode me-1"></i>Nomor Unit: {{ $tipeKendaraan->nomor_unit }}</span>
              @endif
              @if($tipeKendaraan->tahun_produksi)
                <span class="badge bg-white bg-opacity-25 text-white border"><i class="bi bi-calendar-event me-1"></i>Tahun: {{ $tipeKendaraan->tahun_produksi }}</span>
              @endif
            </div>
          </div>
          <div class="col-md-3 text-end">
            <span class="fs-3 fw-bold d-block">{{ $tipeKendaraan->spareparts->count() }} Sparepart</span>
            <small class="opacity-75">Tersedia pada kendaraan ini</small>
          </div>
        </div>
      </div>
    </div>

    <!-- SPAREPARTS GRID SECTION -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0 text-dark">
        <i class="bi bi-gear-wide-connected text-primary me-2"></i>Pilih Diagram Sparepart
      </h5>
    </div>

    <div class="row g-4 mb-4">
      @forelse($tipeKendaraan->spareparts as $sp)
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="card h-100 sparepart-card shadow-sm border-0">
            <div class="sparepart-img-container">
              @if($sp->gambar)
                @if(strtolower(pathinfo($sp->gambar, PATHINFO_EXTENSION)) === 'pdf')
                  <div class="d-flex align-items-center justify-content-center h-100 bg-light text-danger w-100">
                    <i class="bi bi-file-earmark-pdf fs-1"></i>
                  </div>
                @else
                  <img src="{{ asset($sp->gambar) }}" alt="{{ $sp->nama_sparepart }}" />
                @endif
              @else
                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted w-100">
                  <i class="bi bi-image fs-1"></i>
                </div>
              @endif
            </div>

            <div class="card-body d-flex flex-column">
              <h6 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="min-height: 40px;">
                {{ $sp->nama_sparepart }}
              </h6>

              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <a href="{{ route('spareparts.detail', [$tipeKendaraan->id, $sp->id]) }}" class="btn btn-primary btn-sm rounded-pill fw-semibold">
                    <i class="bi bi-aspect-ratio me-1"></i> Lihat Detail
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5 card border-0 shadow-sm">
          <div class="card-body">
            <i class="bi bi-inbox text-muted display-4"></i>
            <p class="text-muted mt-2">Belum ada sparepart yang terhubung dengan tipe kendaraan ini.</p>
            <a href="{{ route('spareparts.index') }}" class="btn btn-outline-primary mt-2 rounded-pill">
              <i class="bi bi-arrow-left me-1"></i>Pilih Tipe Kendaraan Lain
            </a>
          </div>
        </div>
      @endforelse
    </div>

  </div>
</div>
<!--end::App Content-->
@endsection
