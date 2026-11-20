@extends('layouts.app')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-train-front text-primary me-2"></i>Kelola Sarana Kereta Api</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Sarana Kereta Api</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card mb-4 shadow-sm">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
        <div class="d-flex align-items-center gap-2">
          <h3 class="card-title fw-bold m-0">Daftar Sarana Kereta Api</h3>
        </div>

        <div class="d-flex align-items-center gap-2">
          <form action="{{ route('admin.tipe_kendaraan.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 260px;">
              <input type="text" name="search" class="form-control" placeholder="Cari nama, model, sarana..." value="{{ request('search') }}">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
              @if(request('search'))
                <a href="{{ route('admin.tipe_kendaraan.index') }}" class="btn btn-outline-danger" title="Reset Filter">
                  <i class="bi bi-x-circle"></i>
                </a>
              @endif
            </div>
          </form>

          <a href="{{ route('admin.tipe_kendaraan.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Sarana</span>
          </a>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 50px" class="text-center">#</th>
                <th style="width: 70px" class="text-center">Gambar</th>
                <th>Nama Kereta</th>
                <th>Nama Model</th>
                <th>Kategori</th>
                <th>Nomor Unit</th>
                <th>Tahun Produksi</th>
                <th style="width: 130px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tipeKendaraans as $index => $item)
                <tr>
                  <td class="text-center fw-semibold text-secondary">
                    {{ $tipeKendaraans->firstItem() + $index }}
                  </td>
                  <td class="text-center">
                    @if($item->gambar)
                      <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="img-thumbnail rounded" style="max-height: 45px; max-width: 60px; object-fit: cover;">
                    @else
                      <span class="badge bg-secondary">No Img</span>
                    @endif
                  </td>
                  <td><span class="fw-bold text-dark">{{ $item->nama }}</span></td>
                  <td>
                    @if($item->nomor_model || $item->kode_tipe_kendaraan)
                      <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">{{ $item->nomor_model ?? $item->kode_tipe_kendaraan }}</span>
                    @else
                      <span class="text-muted fst-italic">-</span>
                    @endif
                  </td>
                  <td>
                    @if($item->kategori)
                      <span class="badge bg-info text-dark">{{ $item->kategori->nama }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td><span class="fw-semibold text-dark">{{ $item->nomor_unit ?? '-' }}</span></td>
                  <td><span class="badge bg-light text-dark border">{{ $item->tahun_produksi ?? '-' }}</span></td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('admin.tipe_kendaraan.spareparts', $item->id) }}" class="btn btn-outline-info" title="Tambah / Kelola Sparepart Sarana">
                        <i class="bi bi-diagram-3"></i> <span class="badge bg-info-subtle text-info border">{{ $item->spareparts_count }}</span>
                      </a>
                      <a href="{{ route('admin.tipe_kendaraan.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit Sarana">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus Sarana">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>

                    <div class="modal fade text-start" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                              <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data sarana kereta api <strong>{{ $item->nama }}</strong>?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.tipe_kendaraan.destroy', $item->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                    Belum ada data sarana kereta api. Klik tombol <strong>Tambah Sarana</strong> untuk menambahkan.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      
      @if($tipeKendaraans->hasPages())
          <div class="card-footer bg-white">
              <div class="d-flex justify-content-end">
                  {{ $tipeKendaraans->links('pagination::bootstrap-5') }}
              </div>
          </div>
      @endif
    </div>
  </div>
</div>
@endsection
