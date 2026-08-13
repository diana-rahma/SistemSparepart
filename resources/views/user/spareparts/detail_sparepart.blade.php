@extends('layouts.app')

@push('styles')
<style>
  .sparepart-detail-card {
    border-radius: 18px;
    overflow: hidden;
  }
  .sparepart-detail-img {
    border-radius: 18px;
    width: 100%;
    height: 100%;
    object-fit: cover;
    max-height: 400px;
  }
  .sparepart-meta-table th,
  .sparepart-meta-table td {
    vertical-align: middle;
    border-color: #e9ecef;
  }
  .select-part-table th,
  .select-part-table td {
    border-color: #e9ecef;
  }
  .selected-parts-table th,
  .selected-parts-table td {
    border-color: #e9ecef;
  }
  .btn-add-selection {
    min-width: 220px;
  }
</style>
@endpush

@section('content')
@php
  $vehicle = $vehicle ?? ['nama' => 'Kendaraan'];

  $part = $part ?? [
    'nama' => 'Sparepart Utama',
    'gambar' => 'dist/assets/img/prod-1.jpg',
    'kode_part' => 'KP-0001',
    'referensi' => 'REF-0001',
    'jumlah' => 1,
  ];

  $part['referensi'] = $part['referensi'] ?? $part['kode_part'];
  $part['jumlah'] = $part['jumlah'] ?? 1;

  $partOptions = $partOptions ?? [
    [
      'id' => 1,
      'referensi' => 'REF-0001',
      'kode_part' => 'KP-0001',
      'nama' => 'Kampas Rem Depan',
      'jumlah' => 1,
      'harga' => 175000,
      'stok' => 12,
    ],
    [
      'id' => 2,
      'referensi' => 'REF-0002',
      'kode_part' => 'KP-0002',
      'nama' => 'Baut Roda',
      'jumlah' => 4,
      'harga' => 12000,
      'stok' => 64,
    ],
    [
      'id' => 3,
      'referensi' => 'REF-0003',
      'kode_part' => 'KP-0003',
      'nama' => 'Filter Oli',
      'jumlah' => 1,
      'harga' => 95000,
      'stok' => 27,
    ],
  ];
@endphp

<!--begin::Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <a href="{{ route('spareparts.index') }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill">
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Pencarian
        </a>
        <h3 class="mb-0 text-dark font-weight-bold">Detail Sparepart</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Spareparts</a></li>
          <li class="breadcrumb-item"><a href="{{ route('spareparts.show', $vehicle['id'] ?? 0) }}">{{ $vehicle['nama'] ?? 'Kendaraan' }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $part['nama'] }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::Content Header-->

<div class="app-content">
  <div class="container-fluid">
    <div class="card sparepart-detail-card shadow-sm mb-4">
      <div class="card-body p-4">
        <div class="row gy-4">
          <div class="col-lg-5 text-center">
            <img src="{{ asset($part['gambar']) }}" alt="{{ $part['nama'] }}" class="sparepart-detail-img shadow-sm" />
          </div>
          <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <h4 class="fw-bold mb-1">{{ $part['nama'] }}</h4>
                <div class="text-muted fs-7">Referensi utama: {{ $part['referensi'] }}</div>
              </div>
              <span class="badge bg-primary px-3 py-2">Kode Part {{ $part['kode_part'] }}</span>
            </div>

            <div class="table-responsive mb-3">
              <table class="table table-bordered select-part-table mb-0">
                <thead class="table-light text-uppercase fs-7">
                  <tr>
                    <th class="text-center" style="width: 60px;">Pilih</th>
                    <th>Nomer Referensi</th>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th class="text-center">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($partOptions as $option)
                    <tr>
                      <td class="text-center align-middle">
                        <div class="form-check">
                          <input class="form-check-input select-part-checkbox" type="checkbox" value="{{ $option['id'] }}" data-part='@json($option)' id="selectPart{{ $option['id'] }}" />
                        </div>
                      </td>
                      <td class="align-middle">{{ $option['referensi'] ?? $option['kode_part'] }}</td>
                      <td class="align-middle">{{ $option['kode_part'] }}</td>
                      <td class="align-middle">{{ $option['nama'] }}</td>
                      <td class="text-center align-middle">{{ $option['jumlah'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-end">
              <button class="btn btn-primary btn-add-selection rounded-pill" id="addSelectedParts">
                <i class="bi bi-cart-plus me-1"></i>Tambahkan ke pilihan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">Ringkasan Part Terpilih</h5>
        <p class="text-muted fs-7 mb-0">Periksa stok dan harga setiap part yang sudah dipilih sebelum melanjutkan.</p>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover selected-parts-table mb-0">
            <thead class="table-light text-uppercase fs-7">
              <tr>
                <th>Kode Part</th>
                <th>Nama Part</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-center">Stok</th>
              </tr>
            </thead>
            <tbody id="selectedPartsBody">
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Belum ada part yang dipilih.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function() {
    const selectedParts = [];

    function renderSelectedParts() {
      const $body = $('#selectedPartsBody');
      $body.empty();

      if (!selectedParts.length) {
        $body.append(
          '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada part yang dipilih.</td></tr>'
        );
        return;
      }

      selectedParts.forEach(part => {
        $body.append(`
          <tr>
            <td>${part.kode_part}</td>
            <td>${part.nama}</td>
            <td class="text-center">${part.jumlah}</td>
            <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(part.harga)}</td>
            <td class="text-center">${part.stok}</td>
          </tr>
        `);
      });
    }

    $('#addSelectedParts').on('click', function() {
      const checked = $('.select-part-checkbox:checked');

      if (!checked.length) {
        alert('Pilih setidaknya satu part untuk ditambahkan ke pilihan.');
        return;
      }

      checked.each(function() {
        const partData = $(this).data('part');
        const exists = selectedParts.some(item => item.id === partData.id);

        if (!exists) {
          selectedParts.push(partData);
        }
      });

      renderSelectedParts();
    });
  });
</script>
@endpush