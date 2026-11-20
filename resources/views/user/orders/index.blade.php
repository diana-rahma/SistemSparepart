@extends('layouts.user')

@push('styles')
<style>
  .order-card {
    border-radius: 12px;
    border: none;
    transition: all 0.2s ease;
  }
  .order-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
  }
  
  /* Timeline Progress Bar */
  .order-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 1.5rem 0 0.5rem 0;
  }
  .order-timeline::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 10%;
    right: 10%;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
  }
  .timeline-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
  }
  .timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    margin: 0 auto 6px auto;
    transition: all 0.3s ease;
  }
  .timeline-step.completed .timeline-icon {
    background: #0d6efd;
    color: #ffffff;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
  }
  .timeline-step.active .timeline-icon {
    background: #ffc107;
    color: #212529;
    box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.3);
    animation: pulseStep 1.8s infinite;
  }
  @keyframes pulseStep {
    0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.6); }
    70% { box-shadow: 0 0 0 8px rgba(255, 193, 7, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
  }
  .timeline-label {
    font-size: 12px;
    font-weight: 600;
    color: #495057;
  }
  .order-card-header {
    position: relative;
    padding-right: 180px;
  }
  .order-status-badge {
    position: absolute;
    top: 12px;
    right: 16px;
    z-index: 2;
  }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="mb-0 text-dark font-weight-bold">
          <i class="bi bi-bag-check text-primary me-2"></i>Status & Riwayat Pesanan
        </h3>
        <p class="text-muted small mb-0">Pantau perkembangan status pengajuan dan riwayat permintaan sparepart Anda.</p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
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
      <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(isset($orders) && count($orders) > 0)
      <div class="row">
        <div class="col-12">
          
          @foreach($orders as $order)
            @php
              $status = $order['status'] ?? 'Menunggu Konfirmasi';
              
              // Status Badge styling
              $badgeClass = match($status) {
                'Menunggu Konfirmasi' => 'bg-warning-subtle text-warning-emphasis border-warning',
                'Diproses' => 'bg-primary-subtle text-primary border-primary',
                'Siap Diambil' => 'bg-info-subtle text-info border-info',
                'Selesai' => 'bg-success-subtle text-success border-success',
                'Dibatalkan' => 'bg-danger-subtle text-danger border-danger',
                default => 'bg-secondary-subtle text-secondary border-secondary'
              };

              $statusIcon = match($status) {
                'Menunggu Konfirmasi' => 'bi-clock-history',
                'Diproses' => 'bi-gear-fill',
                'Siap Diambil' => 'bi-box-seam-fill',
                'Selesai' => 'bi-check-circle-fill',
                'Dibatalkan' => 'bi-x-circle-fill',
                default => 'bi-info-circle-fill'
              };

              // Step tracking indices
              $stepIndex = match($status) {
                'Menunggu Konfirmasi' => 1,
                'Diproses' => 2,
                'Siap Diambil' => 3,
                'Selesai' => 4,
                default => 1
              };
            @endphp

            <div class="card shadow-sm order-card mb-4">
              <div class="card-header bg-white py-3 order-card-header">
                <div>
                  <span class="font-monospace fw-bold text-dark fs-6 me-2">
                    <i class="bi bi-hash text-primary"></i>{{ $order['order_code'] }}
                  </span>
                  <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $order['created_at_formatted'] ?? $order['created_at'] }}</small>
                </div>
                <div class="order-status-badge">
                  <span class="badge border px-3 py-2 fw-semibold {{ $badgeClass }}">
                    <i class="bi {{ $statusIcon }} me-1"></i>{{ $status }}
                  </span>
                </div>
              </div>

              <div class="card-body p-4">
                <!-- Timeline Status Progress -->
                @if($status !== 'Dibatalkan')
                  <div class="order-timeline px-2 mb-4">
                    <div class="timeline-step {{ $stepIndex > 1 ? 'completed' : ($stepIndex == 1 ? 'active' : '') }}">
                      <div class="timeline-icon"><i class="bi bi-send-check"></i></div>
                      <div class="timeline-label">Diajukan</div>
                    </div>
                    <div class="timeline-step {{ $stepIndex > 2 ? 'completed' : ($stepIndex == 2 ? 'active' : '') }}">
                      <div class="timeline-icon"><i class="bi bi-file-earmark-check"></i></div>
                      <div class="timeline-label">Konfirmasi Admin</div>
                    </div>
                    <div class="timeline-step {{ $stepIndex > 3 ? 'completed' : ($stepIndex == 3 ? 'active' : '') }}">
                      <div class="timeline-icon"><i class="bi bi-boxes"></i></div>
                      <div class="timeline-label">Penyiapan Part</div>
                    </div>
                    <div class="timeline-step {{ $stepIndex == 4 ? 'completed' : '' }}">
                      <div class="timeline-icon"><i class="bi bi-check-lg"></i></div>
                      <div class="timeline-label">Selesai</div>
                    </div>
                  </div>
                @endif

                <!-- Items Table -->
                <div class="table-responsive mt-3">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                      <tr class="small text-uppercase">
                        <th style="width: 45px;" class="text-center">Ref</th>
                        <th style="width: 140px;">Kode Part</th>
                        <th>Nama Komponen Part</th>
                        <th>Sparepart</th>
                        <!-- <th style="width: 120px;" class="text-end">Harga Satuan</th> -->
                        <th style="width: 80px;" class="text-center">Jumlah</th>
                        <!-- <th style="width: 120px;" class="text-end">Subtotal</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order['items'] as $item)
                        @php
                          $subtotal = ($item['jumlah'] ?? 1) * ($item['harga_satuan'] ?? 0);
                        @endphp
                        <tr>
                          <td class="text-center fw-bold text-primary">{{ $item['ref'] ?? '-' }}</td>
                          <td class="font-monospace small fw-bold text-dark">{{ $item['kode_part'] }}</td>
                          <td class="small fw-semibold text-dark">{{ $item['nama_part'] }}</td>
                          <td class="small text-secondary">
                            <span class="badge bg-light text-secondary border">
                              <i class="bi bi-gear-wide-connected me-1"></i>{{ $item['sparepart_nama'] ?? 'Sparepart' }}
                            </span>
                          </td>
                          <!-- <td class="text-end small font-monospace fw-semibold text-dark">
                            {{ ($item['harga_satuan'] ?? 0) > 0 ? 'Rp ' . number_format($item['harga_satuan'], 0, ',', '.') : '-' }}
                          </td> -->
                          <td class="text-center fw-bold text-dark">{{ $item['jumlah'] }} Pcs</td>
                          <!-- <td class="text-end small font-monospace fw-bold text-primary">
                            {{ $subtotal > 0 ? 'Rp ' . number_format($subtotal, 0, ',', '.') : '-' }}
                          </td> -->
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-secondary small fw-semibold">
                  <i class="bi bi-box-seam me-1"></i>Total {{ $order['total_types'] ?? count($order['items']) }} Part ({{ $order['total_items'] }} Pcs)
                </span>
                <!-- <div class="text-end">
                  <span class="small text-muted me-2">Total Estimasi Harga:</span>
                  <span class="fw-bold text-success fs-5 font-monospace">
                    {{ $order['total_price_formatted'] ?? ('Rp ' . number_format($order['total_price'] ?? 0, 0, ',', '.')) }}
                  </span>
                </div> -->
              </div>
            </div>
          @endforeach

        </div>
      </div>
    @else
      <!-- Empty Orders View -->
      <div class="card shadow-sm order-card py-5 text-center my-4">
        <div class="card-body">
          <i class="bi bi-bag-x text-muted display-1 d-block mb-3"></i>
          <h4 class="fw-bold text-dark">Belum Ada Pesanan Sparepart</h4>
          <p class="text-muted">Anda belum memiliki riwayat atau pengajuan permintaan sparepart.</p>
          <a href="{{ route('spareparts.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm mt-2 font-weight-bold">
            <i class="bi bi-box-seam me-2"></i>Buka Katalog Sparepart
          </a>
        </div>
      </div>
    @endif

  </div>
</div>
<!--end::App Content-->
@endsection
