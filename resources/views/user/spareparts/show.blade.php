@extends('layouts.app')

@push('styles')
<style>
  .vehicle-banner-card {
    border-radius: 16px;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #fff;
    overflow: hidden;
  }
  .vehicle-banner-img {
    height: 220px;
    width: 100%;
    object-fit: cover;
    border-radius: 12px;
  }
  .component-card {
    border-radius: 14px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 1px solid #e2e8f0;
  }
  .component-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12) !important;
  }
  .component-img-box {
    height: 190px;
    background-color: #f8fafc;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .component-img-box img {
    max-height: 100%;
    width: 100%;
    object-fit: cover;
  }
  .badge-cat {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 20px;
  }
  .badge-stk {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 2;
    font-size: 0.75rem;
    padding: 6px 12px;
    border-radius: 20px;
  }
  .nav-pills-custom .nav-link {
    border-radius: 30px;
    color: #475569;
    font-weight: 600;
    padding: 8px 20px;
    background-color: #f1f5f9;
    transition: all 0.2s ease;
  }
  .nav-pills-custom .nav-link.active,
  .nav-pills-custom .nav-link:hover {
    background-color: #0284c7;
    color: #fff;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
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
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Pencarian
        </a>
        <h3 class="mb-0 text-dark font-weight-bold">
          Detail Komponen Sparepart Kendaraan
        </h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Spareparts</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $vehicle['model'] }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    <!-- VEHICLE BANNER HEADER CARD -->
    <div class="card vehicle-banner-card shadow-lg mb-4">
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-4 mb-3 mb-md-0 text-center">
            <img src="{{ asset($vehicle['gambar']) }}" alt="{{ $vehicle['nama'] }}" class="vehicle-banner-img shadow-sm" />
          </div>
          <div class="col-md-8">
            <h2 class="fw-bold mb-2 text-white">{{ $vehicle['nama'] }}</h2>
            <p class="text-light opacity-75 mb-3 fs-6">{{ $vehicle['deskripsi'] }}</p>

            <div class="row g-2 text-dark">
              <div class="col-sm-3 col-6">
                <div class="bg-white bg-opacity-10 text-white p-2 rounded text-center">
                  <div class="fs-7 opacity-75"><i class="bi bi-speedometer2 me-1"></i>Mesin</div>
                  <strong class="fs-6">{{ $vehicle['volume_mesin'] }}</strong>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="bg-white bg-opacity-10 text-white p-2 rounded text-center">
                  <div class="fs-7 opacity-75"><i class="bi bi-car-front me-1"></i>Model</div>
                  <strong class="fs-6">{{ $vehicle['model'] }}</strong>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="bg-white bg-opacity-10 text-white p-2 rounded text-center">
                  <div class="fs-7 opacity-75"><i class="bi bi-calendar-event me-1"></i>Tahun</div>
                  <strong class="fs-6">{{ $vehicle['tahun'] }}</strong>
                </div>
              </div>
              <div class="col-sm-3 col-6">
                <div class="bg-white bg-opacity-10 text-white p-2 rounded text-center">
                  <div class="fs-7 opacity-75"><i class="bi bi-gear-fill me-1"></i>Total Part</div>
                  <strong class="fs-6 text-warning">{{ count($vehicle['komponen']) }} Komponen</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FILTER TABS KATEGORI KOMPONEN -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-bold mb-0 text-dark">
        <i class="bi bi-cpu-fill text-primary me-2"></i>Daftar Komponen Suku Cadang
      </h4>
    </div>

    <!-- CARDS GRID DAFTAR KOMPONEN SPAREPART -->
    <div class="row g-4" id="componentCardsGrid">
      @foreach($vehicle['komponen'] as $part)
      <div class="col-lg-4 col-md-6 component-card-item" data-category="{{ strtolower($part['kategori']) }}">
        <div class="card h-100 component-card shadow-sm bg-white">
          
          <!-- Gambar Komponen Detail -->
          <div class="component-img-box">
            <img src="{{ asset($part['gambar']) }}" alt="{{ $part['nama'] }}" />
          </div>

          <div class="card-body d-flex flex-column">

            <h5 class="card-title fw-bold text-dark mb-2 mt-1">
              {{ $part['nama'] }}
            </h5>

            <div class="mt-auto">

              <div class="row g-2">
                <div class="col-4">
                  <a href="{{ route('spareparts.detail', ['vehicleId' => $vehicle['id'], 'partId' => $part['id']]) }}" class="btn btn-outline-secondary w-100 btn-sm rounded-pill fw-semibold">
                    <i class="bi bi-info-circle me-1"></i>Detail
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</div>
<!--end::App Content-->

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function() {
    // Filter Kategori Pills
    $('#componentCategoryTabs .nav-link').on('click', function() {
      $('#componentCategoryTabs .nav-link').removeClass('active');
      $(this).addClass('active');

      const selectedCat = $(this).data('category');

      $('.component-card-item').each(function() {
        const itemCat = $(this).data('category');

        if (selectedCat === 'all' || itemCat === selectedCat) {
          $(this).fadeIn(200);
        } else {
          $(this).fadeOut(200);
        }
      });
    });

  });
</script>
@endpush
