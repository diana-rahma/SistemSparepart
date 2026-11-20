@extends('layouts.app')

@push('styles')
<style>
  .vehicle-info-card {
    border-radius: 12px;
    background-color: #f8f9fa;
  }
  .vehicle-img-preview {
    max-height: 120px;
    object-fit: cover;
    border-radius: 8px;
  }
  .sparepart-checkbox-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s ease;
    cursor: pointer;
  }
  .sparepart-checkbox-card:hover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
  }
  .sparepart-checkbox-card.attached {
    background-color: #eef9f2;
    border-color: #198754;
  }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0 text-primary font-weight-bold">
          <i class="bi bi-train-front me-2"></i>Kelola Sparepart Sarana Kereta Api
        </h3>
        <p class="text-muted mb-0 fs-7">Tambahkan dan hubungkan diagram sparepart untuk sarana kereta api ini.</p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.tipe_kendaraan.index') }}">Sarana Kereta Api</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah Sparepart</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan saat memilih sparepart.
        <ul class="mb-0 mt-1 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- 1. VEHICLE INFORMATION CARD -->
    <div class="card vehicle-info-card shadow-sm mb-4 border-0">
      <div class="card-body p-4">
        <div class="row align-items-center g-3">
          <div class="col-md-2 text-center">
            @if($tipeKendaraan->gambar)
              <img src="{{ asset($tipeKendaraan->gambar) }}" alt="{{ $tipeKendaraan->nama }}" class="vehicle-img-preview img-fluid shadow-sm" />
            @else
              <div class="bg-secondary bg-opacity-25 p-4 rounded text-center text-muted">
                <i class="bi bi-train-front display-6 text-primary"></i>
              </div>
            @endif
          </div>
          <div class="col-md-7">
            <span class="badge bg-primary px-2 py-1 mb-1">Sarana Kereta Api</span>
            <h4 class="fw-bold text-dark mb-1">{{ $tipeKendaraan->nama }}</h4>
            <div class="d-flex flex-wrap gap-2 text-muted fs-7 mt-2">
              @php
                $nomorModel = $tipeKendaraan->nomor_model ?? $tipeKendaraan->kode_tipe_kendaraan;
              @endphp
              @if($nomorModel)
                <span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-tag me-1"></i>Model: {{ $nomorModel }}</span>
              @endif
              @if($tipeKendaraan->nomor_unit)
                <span class="badge bg-light text-dark border"><i class="bi bi-hash me-1"></i>Unit: {{ $tipeKendaraan->nomor_unit }}</span>
              @endif
              @if($tipeKendaraan->tahun_produksi)
                <span class="badge bg-light text-dark border"><i class="bi bi-calendar-event me-1"></i>Tahun: {{ $tipeKendaraan->tahun_produksi }}</span>
              @endif
            </div>
            @if($tipeKendaraan->deskripsi)
              <p class="text-muted fs-7 mb-0 mt-2"><i class="bi bi-info-circle me-1"></i>{{ $tipeKendaraan->deskripsi }}</p>
            @endif
          </div>
          <div class="col-md-3 text-end">
            <a href="{{ route('admin.tipe_kendaraan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
              <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Sarana
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- 2. FORM PILIH SPAREPART -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold m-0 text-dark">
              <i class="bi bi-plus-circle text-primary me-2"></i>Pilih Sparepart untuk Ditambahkan
            </h5>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.tipe_kendaraan.spareparts.store', $tipeKendaraan->id) }}" method="POST">
              @csrf

              <!-- Filter Search Input Box -->
              <div class="mb-3">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                  <input type="text" id="sparepartFilter" class="form-control bg-light" placeholder="Cari nama sparepart dalam daftar...">
                </div>
              </div>

              <div class="sparepart-select-list" style="max-height: 350px; overflow-y: auto;">
                @forelse($allSpareparts as $sp)
                  @php
                    $isAttached = in_array($sp->id, $attachedIds);
                  @endphp
                  <div class="sparepart-item-option mb-2" data-nama="{{ strtolower($sp->nama_sparepart) }}">
                    <div class="form-check sparepart-checkbox-card p-3 d-flex align-items-center justify-content-between {{ $isAttached ? 'attached' : '' }}">
                      <div class="d-flex align-items-center gap-3">
                        <input 
                          class="form-check-input ms-0 me-2" 
                          type="checkbox" 
                          name="sparepart_ids[]" 
                          value="{{ $sp->id }}" 
                          id="sp_check_{{ $sp->id }}"
                          {{ $isAttached ? 'checked disabled' : '' }}
                        >
                        <div>
                          <label class="form-check-label fw-bold text-dark d-block" for="sp_check_{{ $sp->id }}" style="cursor: pointer;">
                            {{ $sp->nama_sparepart }}
                          </label>
                          <span class="text-muted fs-7">
                            <i class="bi bi-gear me-1"></i>{{ $sp->sparepart_parts_count ?? $sp->sparepartParts->count() }} Part Mapped
                          </span>
                        </div>
                      </div>

                      <div>
                        @if($isAttached)
                          <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terhubung</span>
                        @else
                          <span class="badge bg-light text-secondary border">Belum Terhubung</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Belum ada data sparepart di katalog.<br>
                    <a href="{{ route('admin.spareparts.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Sparepart Baru</a>
                  </div>
                @endforelse
              </div>

              <div class="mt-4 pt-2 border-top d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary fw-bold px-4">
                  <i class="bi bi-save me-1"></i>Simpan & Hubungkan Sparepart
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- 3. DAFTAR SPAREPART YANG SAAT INI TERHUBUNG -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-header bg-white py-3">
            <h5 class="card-title fw-bold m-0 text-dark">
              <i class="bi bi-list-check text-success me-2"></i>Sparepart Terhubung ({{ $tipeKendaraan->spareparts->count() }})
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 40px" class="text-center">#</th>
                    <th style="width: 60px">Gambar</th>
                    <th>Nama Sparepart</th>
                    <th style="width: 100px" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($tipeKendaraan->spareparts as $index => $sp)
                    <tr>
                      <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                      <td>
                        @if($sp->gambar)
                          @if(strtolower(pathinfo($sp->gambar, PATHINFO_EXTENSION)) === 'pdf')
                            <div class="bg-light text-danger rounded text-center p-1" style="width: 40px; height: 40px;">
                              <i class="bi bi-file-earmark-pdf fs-5"></i>
                            </div>
                          @else
                            <img src="{{ asset($sp->gambar) }}" alt="{{ $sp->nama_sparepart }}" class="rounded img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                          @endif
                        @else
                          <div class="bg-light text-muted rounded text-center p-1" style="width: 40px; height: 40px;">
                            <i class="bi bi-image fs-5"></i>
                          </div>
                        @endif
                      </td>
                      <td>
                        <span class="fw-bold text-dark d-block">{{ $sp->nama_sparepart }}</span>
                      </td>
                      <td class="text-center">
                        <div class="btn-group btn-group-sm">
                          <a href="{{ route('admin.spareparts.mapping', $sp->id) }}" class="btn btn-outline-primary" title="Lihat/Edit Mapping Part Diagram">
                            <i class="bi bi-aspect-ratio"></i>
                          </a>
                          <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#detachModal{{ $sp->id }}" title="Lepas Sparepart dari Tipe Kendaraan Ini">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>

                        <!-- Modal Konfirmasi Detach -->
                        <div class="modal fade text-start" id="detachModal{{ $sp->id }}" tabindex="-1" aria-labelledby="detachModalLabel{{ $sp->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="detachModalLabel{{ $sp->id }}">
                                  <i class="bi bi-exclamation-triangle-fill me-2"></i>Lepas Sparepart
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                Apakah Anda yakin ingin melepas sparepart <strong>{{ $sp->nama_sparepart }}</strong> dari tipe kendaraan <strong>{{ $tipeKendaraan->nama }}</strong>?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('admin.tipe_kendaraan.spareparts.destroy', [$tipeKendaraan->id, $sp->id]) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger">Ya, Lepaskan</button>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                        Belum ada sparepart yang terhubung dengan tipe kendaraan ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!--end::App Content-->
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterInput = document.getElementById('sparepartFilter');
    const items = document.querySelectorAll('.sparepart-item-option');

    if (filterInput) {
      filterInput.addEventListener('keyup', function () {
        const query = this.value.toLowerCase().trim();
        items.forEach(function (item) {
          const nama = item.getAttribute('data-nama');
          if (!query || nama.includes(query)) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    }

    const btnSelectAll = document.getElementById('btnSelectAll');
    if (btnSelectAll) {
      btnSelectAll.addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.sparepart-select-list input[type="checkbox"]:not(:disabled)');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
      });
    }
  });
</script>
@endpush
