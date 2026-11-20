@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Status Pesanan</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Status</li>
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
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <!-- Kolom Kiri: Form Ubah Status & Ringkasan -->
      <div class="col-lg-5 mb-4">
        <div class="card card-warning card-outline shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="card-title fw-bold m-0 text-dark">
              <i class="bi bi-arrow-repeat text-warning me-2"></i>Ubah Status Pesanan
            </h5>
          </div>

          <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Kode Pesanan</label>
                <div class="fs-5 fw-bold text-primary font-monospace">{{ $order->order_code }}</div>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Status Saat Ini</label>
                <div>
                  @php
                    $statusClass = match ($order->status) {
                      'Menunggu Konfirmasi' => 'bg-warning text-dark',
                      'Diproses' => 'bg-info text-dark',
                      'Siap Diambil' => 'bg-primary text-white',
                      'Selesai' => 'bg-success text-white',
                      'Dibatalkan' => 'bg-danger text-white',
                      default => 'bg-secondary text-white',
                    };
                  @endphp
                  <span class="badge {{ $statusClass }} fs-6 px-3 py-2">
                    {{ $order->status }}
                  </span>
                </div>
              </div>

              <hr>

              <div class="mb-4">
                <label for="status" class="form-label fw-bold">Pilih Status Baru <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select form-select-lg @error('status') is-invalid @enderror" required>
                  @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ old('status', $order->status) === $st ? 'selected' : '' }}>
                      {{ $st }}
                    </option>
                  @endforeach
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text mt-2">
                  <small class="text-muted d-block"><strong>Keterangan Status:</strong></small>
                  <ul class="text-muted small ps-3 mb-0">
                    <li><strong class="text-warning">Menunggu Konfirmasi:</strong> Pesanan baru masuk dan menunggu verifikasi.</li>
                    <li><strong class="text-info">Diproses:</strong> Permintaan diterima, part sedang disiapkan di gudang.</li>
                    <li><strong class="text-primary">Siap Diambil:</strong> Part telah siap dan pemesan dapat mengambil barang.</li>
                    <li><strong class="text-success">Selesai:</strong> Part telah diserahkan / diambil, transaksi selesai.</li>
                    <li><strong class="text-danger">Dibatalkan:</strong> Pesanan dibatalkan / ditolak.</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3">
              <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-warning text-dark fw-bold">
                <i class="bi bi-check-circle me-1"></i> Simpan Status
              </button>
            </div>
          </form>
        </div>

        <!-- Informasi Pemesan -->
        <div class="card shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="card-title fw-bold m-0 text-dark">
              <i class="bi bi-person-badge text-secondary me-2"></i>Informasi Pemesan
            </h5>
          </div>
          <div class="card-body">
            <table class="table table-sm table-borderless mb-0">
              <tr>
                <td class="text-muted" style="width: 140px;">Nama Pemesan</td>
                <td class="fw-semibold">: {{ $order->user?->name ?? 'Pelanggan (Guest)' }}</td>
              </tr>
              <tr>
                <td class="text-muted">Email</td>
                <td>: {{ $order->user?->email ?? '-' }}</td>
              </tr>
              <tr>
                <td class="text-muted">Waktu Pesan</td>
                <td>: {{ $order->created_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB</td>
              </tr>
              <tr>
                <td class="text-muted">Total Jenis Part</td>
                <td>: {{ $order->total_types ?? $order->items->count() }} jenis</td>
              </tr>
              <tr>
                <td class="text-muted">Total Jumlah</td>
                <td>: {{ $order->total_items ?? $order->items->sum('jumlah') }} pcs</td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Rincian Barang yang Dipesan -->
      <div class="col-lg-7 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold m-0 text-dark">
              <i class="bi bi-box-seam text-primary me-2"></i>Daftar Part yang Dipesan
            </h5>
            <span class="badge bg-secondary-subtle text-secondary border">
              {{ $order->items->count() }} Item
            </span>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 40px;" class="text-center">#</th>
                    <th>Sparepart / Sarana</th>
                    <th>Ref</th>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th class="text-center">Jumlah</th>
                    <!-- <th class="text-end">Harga Satuan</th> -->
                    <!-- <th class="text-end">Subtotal</th> -->
                  </tr>
                </thead>
                <tbody>
                  @forelse($order->items as $idx => $item)
                    <tr>
                      <td class="text-center text-muted fw-semibold">{{ $idx + 1 }}</td>
                      <td>
                        <span class="fw-semibold text-dark">{{ $item->sparepart_nama ?? '-' }}</span>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border">{{ $item->ref ?? '-' }}</span>
                      </td>
                      <td>
                        <span class="font-monospace text-primary fw-semibold">{{ $item->kode_part ?? '-' }}</span>
                      </td>
                      <td>{{ $item->nama_part ?? '-' }}</td>
                      <td class="text-center fw-bold">{{ $item->jumlah }}</td>
                      <!-- <td class="text-end text-muted">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                      <td class="text-end fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td> -->
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block text-secondary mb-2"></i>
                        Tidak ada detail part pada pesanan ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                <!-- <tfoot class="table-light">
                  <tr>
                    <td colspan="7" class="text-end fw-bold fs-6">Total Pembayaran:</td>
                    <td class="text-end fw-bold fs-6 text-success">
                      Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                  </tr>
                </tfoot> -->
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
