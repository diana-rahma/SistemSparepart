@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-tags-fill text-primary me-2"></i>Kelola Spareparts</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Spareparts</li>
        </ol>
      </div>
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <!--begin::Container-->
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan pada pengisian form!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!--begin::Card-->
    <div class="card mb-4 shadow-sm">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
        <div class="d-flex align-items-center gap-2">
          <h3 class="card-title fw-bold m-0">Daftar Sparepart</h3>
        </div>
        
        <div class="d-flex align-items-center gap-2">
          <form action="{{ route('admin.spareparts.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 220px;">
              <input type="text" name="search" class="form-control" placeholder="Cari sparepart..." value="{{ request('search') }}">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
              @if(request('search'))
                <a href="{{ route('admin.spareparts.index') }}" class="btn btn-outline-danger" title="Reset Reset Filter">
                  <i class="bi bi-x-circle"></i>
                </a>
              @endif
            </div>
          </form>

          <a href="{{ route('admin.spareparts.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Sparepart</span>
          </a>
        </div>
      </div>
      <!-- /.card-header -->

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 60px" class="text-center">#</th>
                <th>Nama Sparepart</th>
                <!-- <th>Deskripsi</th> -->
                <th>Gambar</th>
                <th style="width: 140px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($spareparts as $index => $item)
                <tr>
                  <td class="text-center fw-semibold text-secondary">
                    {{ $spareparts->firstItem() + $index }}
                  </td>
                  <td>
                    <span class="fw-bold text-dark">{{ $item->nama_sparepart }}</span>
                  </td>
                  <!-- <td>
                    <span class="fw-bold text-dark">{{ $item->deskripsi }}</span>
                  </td> -->

                  <td>
                    @if($item->gambar)
                      @if(Str::endsWith(strtolower($item->gambar), '.pdf'))
                        <a href="{{ $item->gambar_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                        </a>
                      @else
                        <img src="{{ $item->gambar_url }}"
                            alt="{{ $item->nama_sparepart }}"
                            class="img-thumbnail"
                            style="max-height: 50px; max-width: 80px; object-fit: cover;">
                      @endif
                    @else
                      <span class="badge bg-secondary">Belum ada gambar</span>
                    @endif
                  </td>

                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('admin.spareparts.mapping', $item->id) }}" class="btn btn-primary" title="Mapping Gambar Sparepart">
                        <i class="bi bi-crosshair me-1"></i> Mapping
                      </a>
                      <a href="{{ route('admin.spareparts.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit Sparepart">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus Sparepart">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>

                    <!-- Modal Konfirmasi Hapus -->
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
                            Apakah Anda yakin ingin menghapus sparepart <strong>{{ $item->nama_sparepart }}</strong>?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.spareparts.destroy', $item->id) }}" method="POST" class="d-inline">
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
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                    Belum ada data sparepart. Klik tombol <strong>Tambah Sparepart</strong> untuk menambahkan.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->

      
      @if($spareparts->hasPages())
          <div class="card-footer bg-white">
              <div class="d-flex justify-content-end">
                  {{ $spareparts->links('pagination::bootstrap-5') }}
              </div>
          </div>
      @endif
    </div>
    <!--end::Card-->

  </div>
  <!--end::Container-->
</div>
<!--end::App Content-->
@endsection
