@extends('layouts.app')

@push('styles')
<!-- Select2 CSS & Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
  .badge-category {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
  }
  .badge-stock {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
  }
  .search-card, .filter-card {
    border-radius: 12px;
    border: none;
  }
  .select2-container--bootstrap-5 .select2-selection {
    border-radius: 8px;
  }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0 text-primary font-weight-bold"></i>Katalog Sparepart
        </h3>
        <p class="text-muted mb-0 fs-7">Cari dan temukan suku cadang kendaraan terbaik sesuai kebutuhan Anda.</p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Spareparts</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    <!-- CARD 1: Kolom Searching Kata Kunci -->
    <div class="card search-card shadow-sm mb-4">
      <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
        <h5 class="card-title text-dark fw-bold mb-1">
          <i class="bi bi-search me-2 text-primary"></i>Pencarian Kata Kunci
        </h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="input-group input-group-lg">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input 
                type="text" 
                id="keywordSearch" 
                class="form-control bg-light border-start-0 ps-0" 
                placeholder="Ketik nama sparepart atau kode produk (opsional, misal: Filter Oli, Busi, Avanza...)"
              />
              <button class="btn btn-outline-secondary" type="button" id="btnClearKeyword" title="Bersihkan pencarian">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CARD 2: Searchable Dropdown Filters -->
    <div class="card filter-card shadow-sm mb-4">
      <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title text-dark fw-bold mb-0">
          <i class="bi bi-funnel-fill me-2 text-primary"></i>Filter Parameter Sparepart
        </h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetFilters">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
        </button>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <!-- 1. Kategori -->
          <div class="col-md-3 col-sm-6">
            <label for="filterKategori" class="form-label fw-semibold text-secondary fs-7">
              <i class="bi bi-grid-3x3-gap me-1"></i>Kategori
            </label>
            <select id="filterKategori" class="form-select select2-searchable" data-placeholder="-- Semua Kategori --">
              <option value=""></option>
              @foreach($kategoriList as $kat)
                <option value="{{ $kat }}">{{ $kat }}</option>
              @endforeach
            </select>
          </div>

          <!-- 2. Volume Mesin -->
          <div class="col-md-3 col-sm-6">
            <label for="filterVolume" class="form-label fw-semibold text-secondary fs-7">
              <i class="bi bi-speedometer2 me-1"></i>Volume Mesin
            </label>
            <select id="filterVolume" class="form-select select2-searchable" data-placeholder="-- Semua Volume Mesin --">
              <option value=""></option>
              @foreach($volumeMesinList as $vol)
                <option value="{{ $vol }}">{{ $vol }}</option>
              @endforeach
            </select>
          </div>

          <!-- 3. Model -->
          <div class="col-md-3 col-sm-6">
            <label for="filterModel" class="form-label fw-semibold text-secondary fs-7">
              <i class="bi bi-car-front me-1"></i>Model Kendaraan
            </label>
            <select id="filterModel" class="form-select select2-searchable" data-placeholder="-- Semua Model --">
              <option value=""></option>
              @foreach($modelList as $mod)
                <option value="{{ $mod }}">{{ $mod }}</option>
              @endforeach
            </select>
          </div>

          <!-- 4. Tahun -->
          <div class="col-md-3 col-sm-6">
            <label for="filterTahun" class="form-label fw-semibold text-secondary fs-7">
              <i class="bi bi-calendar-event me-1"></i>Tahun Pembuatan
            </label>
            <select id="filterTahun" class="form-select select2-searchable" data-placeholder="-- Semua Tahun --">
              <option value=""></option>
              @foreach($tahunList as $thn)
                <option value="{{ $thn }}">{{ $thn }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Tombol Eksekusi Searching -->
        <div class="row mt-4">
          <div class="col-12">
            <button type="button" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" id="btnDoSearch">
              <i class="bi bi-search me-2"></i>Cari Suku Cadang
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- INITIAL STATE: Tampilan Awal Sebelum Pencarian -->
    <div id="initialState" class="card text-center py-5 shadow-sm border-0">
      <div class="card-body">
        <div class="mb-3 text-primary">
          <i class="bi bi-card-search display-1"></i>
        </div>
        <h4 class="fw-bold text-dark">Siap Mencari Sparepart?</h4>
        <p class="text-muted col-md-6 mx-auto">
          Silakan masukkan kata kunci pencarian atau pilih kriteria pada filter di atas (Kategori, Volume Mesin, Model, atau Tahun), lalu tekan tombol <strong>"Cari Suku Cadang"</strong> untuk menampilkan hasil.
        </p>
      </div>
    </div>

    <!-- SECTION 3: Hasil Pencarian & Grid Spareparts (Tampil Setelah Searching) -->
    <div id="resultsSection" class="d-none">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
          Hasil Pencarian Sparepart <span class="badge bg-primary rounded-pill ms-1 fs-7" id="resultCount">0 Suku Cadang</span>
        </h5>
      </div>

      <!-- Empty State Notification jika Tidak Ditemukan -->
      <div id="emptyState" class="card text-center py-5 shadow-sm d-none border-0">
        <div class="card-body">
          <i class="bi bi-exclamation-circle text-warning display-1"></i>
          <h4 class="mt-3 fw-bold">Sparepart Tidak Ditemukan</h4>
          <p class="text-muted">Tidak ada suku cadang yang sesuai dengan kriteria kata kunci/filter Anda.</p>
          <button type="button" class="btn btn-outline-primary mt-2" id="btnResetEmptyState">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter & Cari Lagi
          </button>
        </div>
      </div>

      <!-- Spareparts Cards Grid -->
      <div class="row g-4" id="sparepartsGrid">
        @foreach($vehicles as $item)
        <div class="col-lg-3 col-md-4 col-sm-6 sparepart-item" 
             data-nama="{{ strtolower($item['nama']) }}"
             data-kode="{{ strtolower($item['kode']) }}"
             data-kategori="{{ $item['kategori'] }}"
             data-volume="{{ $item['volume_mesin'] }}"
             data-model="{{ $item['model'] }}"
             data-tahun="{{ $item['tahun'] }}"
             style="display: none;">
          
          <div class="card h-100 sparepart-card shadow-sm border-0">
            <div class="sparepart-img-container">
              <span class="badge bg-dark badge-category">{{ $item['kategori'] }}</span>
              <span class="badge bg-info text-dark badge-stock">
                <i class="bi bi-gear-fill me-1"></i>{{ count($item['komponen']) }} Komponen
              </span>
              <img src="{{ asset($item['gambar']) }}" alt="{{ $item['nama'] }}" />
            </div>

            <div class="card-body d-flex flex-column">
              <div class="text-muted fs-7 mb-1">{{ $item['kode'] }}</div>
              <h6 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="min-height: 40px;">
                {{ $item['nama'] }}
              </h6>

              <!-- Metadata Specs -->
              <div class="bg-light p-2 rounded mb-3 fs-7">
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted"><i class="bi bi-speedometer2 me-1"></i>Mesin:</span>
                  <span class="fw-semibold">{{ $item['volume_mesin'] }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted"><i class="bi bi-car-front me-1"></i>Model:</span>
                  <span class="fw-semibold">{{ $item['model'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="bi bi-calendar-event me-1"></i>Tahun:</span>
                  <span class="fw-semibold">{{ $item['tahun'] }}</span>
                </div>
              </div>

              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <a href="{{ route('spareparts.show', $item['id']) }}" class="btn btn-primary btn-sm rounded-pill fw-semibold">
                    <i class="bi bi-card-checklist me-1"></i> Lihat Komponen Part
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>
<!--end::App Content-->
@endsection

@push('scripts')
<!-- jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    // Inisialisasi Select2 pada dropdown filter
    $('.select2-searchable').select2({
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: function() {
        return $(this).data('placeholder');
      }
    });

    // Eksekusi Pencarian saat Tombol "Cari Suku Cadang" Ditekan
    function executeSearch() {
      const keyword = $('#keywordSearch').val().toLowerCase().trim();
      const selectedKategori = $('#filterKategori').val();
      const selectedVolume = $('#filterVolume').val();
      const selectedModel = $('#filterModel').val();
      const selectedTahun = $('#filterTahun').val();

      // Sembunyikan tampilan awal (initialState) & munculkan section hasil
      $('#initialState').addClass('d-none');
      $('#resultsSection').removeClass('d-none');

      let visibleCount = 0;

      $('.sparepart-item').each(function() {
        const itemNama = $(this).data('nama');
        const itemKode = $(this).data('kode');
        const itemKategori = $(this).data('kategori');
        const itemVolume = $(this).data('volume');
        const itemModel = $(this).data('model');
        const itemTahun = String($(this).data('tahun'));

        // Cek match keyword
        const matchKeyword = !keyword || itemNama.includes(keyword) || itemKode.includes(keyword);

        // Cek match dropdown filters
        const matchKategori = !selectedKategori || itemKategori === selectedKategori;
        const matchVolume = !selectedVolume || itemVolume === selectedVolume;
        const matchModel = !selectedModel || itemModel === selectedModel;
        const matchTahun = !selectedTahun || itemTahun === selectedTahun;

        if (matchKeyword && matchKategori && matchVolume && matchModel && matchTahun) {
          $(this).show();
          visibleCount++;
        } else {
          $(this).hide();
        }
      });

      // Update counter hasil & penanganan empty state
      $('#resultCount').text(visibleCount + ' Suku Cadang');

      if (visibleCount === 0) {
        $('#emptyState').removeClass('d-none');
      } else {
        $('#emptyState').addClass('d-none');
      }
    }

    // Klik tombol Cari Suku Cadang
    $('#btnDoSearch').on('click', function() {
      executeSearch();
    });

    // Tekan Enter pada input keyword search
    $('#keywordSearch').on('keypress', function(e) {
      if (e.which === 13) {
        executeSearch();
      }
    });

    // Clear keyword search button
    $('#btnClearKeyword').on('click', function() {
      $('#keywordSearch').val('');
    });

    // Reset All Filters & Kembalikan ke Tampilan Awal Kosong
    function resetToInitialState() {
      $('#keywordSearch').val('');
      $('.select2-searchable').val(null).trigger('change');
      
      // Sembunyikan hasil & empty state, tampilkan initialState
      $('#resultsSection').addClass('d-none');
      $('#emptyState').addClass('d-none');
      $('.sparepart-item').hide();
      $('#initialState').removeClass('d-none');
    }

    $('#btnResetFilters, #btnResetEmptyState').on('click', function() {
      resetToInitialState();
    });
  });
</script>
@endpush
