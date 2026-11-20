@extends('layouts.app')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-receipt text-primary me-2"></i>Pesanan Masuk</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
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

    <div class="card mb-4 shadow-sm">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
        <div class="d-flex align-items-center gap-2">
          <h3 class="card-title fw-bold m-0">Daftar Pesanan</h3>
        </div>

        <div class="d-flex align-items-center gap-2">
          <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="status" class="form-select form-select-sm" style="width: 175px;" onchange="this.form.submit()">
              <option value="">Semua Status</option>
              @foreach($statuses as $st)
                <option value="{{ $st }}" {{ (request('status') === $st || ($status ?? '') === $st) ? 'selected' : '' }}>{{ $st }}</option>
              @endforeach
            </select>

            <div class="input-group input-group-sm" style="width: 250px;">
              <input type="text" name="search" class="form-control" placeholder="Cari kode, nama..." value="{{ $search }}">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
              @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger" title="Reset Filter">
                  <i class="bi bi-x-circle"></i>
                </a>
              @endif
            </div>
          </form>

        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 50px" class="text-center">#</th>
                <th>Kode Pesanan</th>
                <th>Nama Pemesan</th>
                <th>Tanggal Pemesanan</th>
                <th>Status</th>
                <th style="width: 160px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $index => $order)
                <tr>
                  <td class="text-center fw-semibold text-secondary">
                    {{ $orders->firstItem() + $index }}
                  </td>
                  <td>
                    <span class="fw-bold text-primary font-monospace">{{ $order->order_code }}</span>
                  </td>
                  <td>
                    <span class="fw-semibold text-dark">{{ $order->user?->name ?? 'Pelanggan' }}</span>
                  </td>
                  <td>
                    {{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}
                  </td>
                  <td>
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
                    <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <!-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetail{{ $order->id }}" title="Lihat Detail Pesanan">
                        <i class="bi bi-eye me-1"></i>Detail
                      </button> -->
                      <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-warning" title="Ubah Status Pesanan">
                        <i class="bi bi-pencil-square me-1"></i>Edit
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                    {{ $search || request('status') ? 'Tidak ada pesanan yang cocok dengan pencarian / filter.' : 'Belum ada pesanan masuk.' }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @foreach($orders as $order)
          <div class="modal fade text-start" id="orderDetail{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title fw-bold" id="orderDetailLabel{{ $order->id }}">
                    <i class="bi bi-receipt text-primary me-2"></i>Detail Pesanan <span class="font-monospace text-primary">{{ $order->order_code }}</span>
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                  <div class="row mb-3">
                    <div class="col-sm-6 mb-2"><strong>Nama Pemesan:</strong> {{ $order->user?->name ?? 'Pelanggan' }}</div>
                    <div class="col-sm-6 mb-2"><strong>Tanggal:</strong> {{ $order->created_at?->format('d/m/Y H:i') ?? '-' }} WIB</div>
                    <div class="col-sm-6">
                      <strong>Status Saat Ini:</strong> 
                      @php
                        $modalStatusClass = match ($order->status) {
                          'Menunggu Konfirmasi' => 'bg-warning text-dark',
                          'Diproses' => 'bg-info text-dark',
                          'Siap Diambil' => 'bg-primary text-white',
                          'Selesai' => 'bg-success text-white',
                          'Dibatalkan' => 'bg-danger text-white',
                          default => 'bg-secondary text-white',
                        };
                      @endphp
                      <span class="badge {{ $modalStatusClass }}">{{ $order->status }}</span>
                    </div>
                    <div class="col-sm-6"><strong>Total Part:</strong> {{ $order->total_items ?? $order->items->sum('jumlah') }} pcs</div>
                  </div>
                  <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered align-middle">
                      <thead class="table-light">
                        <tr>
                          <th>Sparepart</th>
                          <th>Ref</th>
                          <th>Kode Part</th>
                          <th>Nama Part</th>
                          <th class="text-center">Jumlah</th>
                          <th class="text-end">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($order->items as $orderItem)
                          <tr>
                            <td>{{ $orderItem->sparepart_nama ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $orderItem->ref ?? '-' }}</span></td>
                            <td><span class="font-monospace text-primary">{{ $orderItem->kode_part ?? '-' }}</span></td>
                            <td>{{ $orderItem->nama_part ?? '-' }}</td>
                            <td class="text-center">{{ $orderItem->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}</td>
                          </tr>
                        @empty
                          <tr><td colspan="6" class="text-center text-muted">Tidak ada detail item pesanan.</td></tr>
                        @endforelse
                      </tbody>
                      <tfoot class="table-light">
                        <tr>
                          <td colspan="5" class="text-end fw-bold">Total Pembayaran:</td>
                          <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <!-- Quick Status Update Card in Modal -->
                  <div class="card bg-light border p-3">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                      @csrf
                      @method('PUT')
                      <div class="d-flex align-items-center gap-2">
                        <label for="modal_status_{{ $order->id }}" class="form-label mb-0 fw-semibold text-secondary small text-uppercase">Ubah Status:</label>
                        <select name="status" id="modal_status_{{ $order->id }}" class="form-select form-select-sm" style="width: 200px;">
                          @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">
                          <i class="bi bi-check-circle me-1"></i> Simpan Status
                        </button>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                          <i class="bi bi-pencil-square me-1"></i> Form Edit Lengkap
                        </a>
                      </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      
      @if($orders->hasPages())
          <div class="card-footer bg-white">
              <div class="d-flex justify-content-end">
                  {{ $orders->links('pagination::bootstrap-5') }}
              </div>
          </div>
      @endif

    </div>
  </div>
</div>
@endsection
