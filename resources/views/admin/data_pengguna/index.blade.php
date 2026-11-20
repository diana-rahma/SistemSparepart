@extends('layouts.app')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-user-front text-primary me-2"></i>Kelola Data Pengguna</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Kelola Data Pengguna</li>
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
          <h3 class="card-title fw-bold m-0">Daftar Pengguna Sistem</h3>
        </div>

        <div class="d-flex align-items-center gap-2">
          <form action="{{ route('admin.pengguna.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 260px;">
              <input type="text" name="search" class="form-control" placeholder="Cari nama pengguna" value="{{ request('search') }}">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
              @if(request('search'))
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-outline-danger" title="Reset Filter">
                  <i class="bi bi-x-circle"></i>
                </a>
              @endif
            </div>
          </form>

          <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Penguna</span>
          </a>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 50px" class="text-center">#</th>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Kategori</th>
                <th style="width: 130px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $item)
                <tr>
                  <td class="text-center fw-semibold text-secondary">
                    {{ $users->firstItem() + $index }}
                  </td>
                  <td><span class="fw-bold text-dark">{{ $item->name }}</span></td>
                  <td><span class="fw-bold text-dark">{{ $item->email }}</span></td>
                  <td><span class="fw-bold text-dark">{{ $item->role }}</span></td>
                    <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('admin.pengguna.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit Pengguna">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus Kategori">
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
                            Apakah Anda yakin ingin menghapus Pengguna <strong>{{ $item->name }}</strong>?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST" class="d-inline">
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

      
      @if($users->hasPages())
          <div class="card-footer bg-white">
              <div class="d-flex justify-content-end">
                  {{ $users->links('pagination::bootstrap-5') }}
              </div>
          </div>
      @endif
    </div>
  </div>
</div>
@endsection
