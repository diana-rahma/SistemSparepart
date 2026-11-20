@extends('layouts.user')

@push('styles')
<style>
  .cart-card {
    border-radius: 12px;
    border: none;
  }
  .qty-input-group {
    max-width: 120px;
    margin: 0 auto;
  }
  .qty-btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50% !important;
  }
  .qty-number {
    text-align: center;
    font-weight: 700;
  }
  .cart-table-header {
    position: relative;
    padding-right: 140px;
  }
  .cart-count-badge {
    position: absolute;
    top: 12px;
    right: 16px;
    z-index: 2;
  }
  .cart-footer {
    position: relative;
    min-height: 70px;
    padding-right: 190px;
  }
  .btn-clear-cart {
    position: absolute;
    right: 16px;
    bottom: 14px;
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
          <i class="bi bi-cart3 text-primary me-2"></i>Keranjang Sparepart
        </h3>
        <p class="text-muted small mb-0">Kelola daftar komponen sparepart yang akan diajukan atau dipesan.</p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    <div id="alertCartContainer">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>

    @php
      $totalItems = array_sum(array_column($cart, 'jumlah'));
      $totalPrice = array_reduce($cart, function ($total, $item) {
          return $total + ($item['jumlah'] * $item['harga_satuan']);
      }, 0);
    @endphp

    @if(count($cart) > 0)
      <div class="row">
        <!-- Left Column: Table of Cart Items -->
        <div class="col-lg-8 mb-4">
          <div class="card shadow-sm cart-card">
            <div class="card-header bg-white py-3 cart-table-header">
              <h5 class="fw-bold m-0 text-dark fs-6">
                <i class="bi bi-list-stars text-primary me-2"></i>Daftar Item Keranjang
              </h5>
              <span class="badge bg-primary rounded-pill px-3 py-2 cart-count-badge" id="cartCountBadge">{{ count($cart) }} Jenis Part</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="cartTable">
                  <thead class="table-light">
                    <tr class="small text-uppercase">
                      <th style="width: 45px;" class="text-center">Ref</th>
                      <th>Detail Part & Sparepart</th>
                      <!-- <th style="width: 120px;" class="text-end">Harga Satuan</th> -->
                      <th style="width: 140px;" class="text-center">Jumlah</th>
                      <!-- <th style="width: 120px;" class="text-end">Subtotal</th> -->
                      <th style="width: 50px;" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cart as $key => $item)
                      @php
                        $subtotal = $item['jumlah'] * $item['harga_satuan'];
                      @endphp
                      <tr id="cartRow-{{ $key }}">
                        <td class="text-center fw-bold text-primary fs-6">{{ $item['ref'] }}</td>
                        <td>
                          <div class="fw-bold text-dark font-monospace">{{ $item['kode_part'] }}</div>
                          <div class="small fw-semibold text-secondary">{{ $item['nama_part'] }}</div>
                          <small class="badge bg-light text-secondary border mt-1">
                            <i class="bi bi-gear-wide-connected me-1"></i>{{ $item['sparepart_nama'] }}
                          </small>
                        </td>
                        <!-- <td class="text-end small font-monospace fw-semibold text-dark">
                          {{ $item['harga_satuan'] > 0 ? 'Rp ' . number_format($item['harga_satuan'], 0, ',', '.') : '-' }}
                        </td> -->
                        <td class="text-center">
                          <div class="input-group input-group-sm qty-input-group">
                            <button class="btn btn-outline-secondary qty-btn btn-dec" type="button" data-key="{{ $key }}">-</button>
                            <input type="number" class="form-control form-control-sm qty-number input-qty" data-key="{{ $key }}" value="{{ $item['jumlah'] }}" min="1">
                            <button class="btn btn-outline-secondary qty-btn btn-inc" type="button" data-key="{{ $key }}">+</button>
                          </div>
                        </td>
                        <!-- <td class="text-end small font-monospace fw-bold text-primary subtotal-val" id="subtotal-{{ $key }}">
                          {{ $subtotal > 0 ? 'Rp ' . number_format($subtotal, 0, ',', '.') : '-' }}
                        </td> -->
                        <td class="text-center">
                          <button type="button" class="btn btn-outline-danger btn-sm p-1 py-0 rounded-circle btn-remove" data-key="{{ $key }}" title="Hapus Part">
                            <i class="bi bi-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer bg-white border-top py-3 cart-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
              <a href="{{ route('spareparts.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i>Tambah Part Lain
              </a>
              <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-clear-cart" id="btnClearCart">
                <i class="bi bi-trash3 me-1"></i>Kosongkan Keranjang
              </button>
            </div>
          </div>
        </div>

        <!-- Right Column: Cart Summary Card -->
        <div class="col-lg-4 mb-4">
          <div class="card shadow-sm cart-card border-0">
            <div class="card-header bg-white py-3">
              <h5 class="fw-bold m-0 text-dark fs-6">
                <i class="bi bi-receipt text-primary me-2"></i>Ringkasan Permintaan
              </h5>
            </div>
            <div class="card-body p-4">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-secondary">Total Jenis Part</span>
                <span class="fw-bold text-dark" id="summaryTotalTypes">{{ count($cart) }} Item</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span class="text-secondary">Total Kuantitas</span>
                <span class="fw-bold text-dark" id="summaryTotalQty">{{ $totalItems }} Pcs</span>
              </div>
              <hr class="my-3">
              <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold text-dark fs-6">Total Estimasi Harga</span>
                <span class="fw-bold text-success fs-5 font-monospace" id="summaryTotalPrice">
                  {{ $totalPrice > 0 ? 'Rp ' . number_format($totalPrice, 0, ',', '.') : 'Rp 0' }}
                </span>
              </div> -->
              <button type="button" class="btn btn-success btn-lg w-100 rounded-pill shadow font-weight-bold" id="btnCheckout">
                <i class="bi bi-check-circle-fill me-2"></i>Ajukan Permintaan Sparepart
              </button>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Empty Cart View -->
      <div class="card shadow-sm cart-card py-5 text-center my-4">
        <div class="card-body">
          <i class="bi bi-cart-x text-muted display-1 d-block mb-3"></i>
          <h4 class="fw-bold text-dark">Keranjang Sparepart Anda Masih Kosong</h4>
          <p class="text-muted">Silakan jelajahi katalog sparepart dan centang komponen yang dibutuhkan.</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const updateUrl = "{{ route('cart.update') }}";
    const clearUrl = "{{ route('cart.clear') }}";
    const alertCartContainer = document.getElementById('alertCartContainer');

    function showAlert(type, message) {
      if (!alertCartContainer) return;
      alertCartContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
          <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
    }

    function updateCartQty(key, newQty) {
      if (newQty < 1) return;

      fetch(updateUrl, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ key: key, jumlah: newQty })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const subtotalEl = document.getElementById(`subtotal-${key}`);
          if (subtotalEl) {
            subtotalEl.textContent = data.item_subtotal_formatted;
          }

          const summaryTotalQty = document.getElementById('summaryTotalQty');
          if (summaryTotalQty) {
            summaryTotalQty.textContent = `${data.total_items} Pcs`;
          }

          const summaryTotalPrice = document.getElementById('summaryTotalPrice');
          if (summaryTotalPrice) {
            summaryTotalPrice.textContent = data.cart_total_formatted;
          }

          const navCartBadge = document.getElementById('navCartBadge');
          if (navCartBadge) {
            navCartBadge.textContent = data.total_items;
          }
        } else {
          showAlert('danger', data.message || 'Gagal memperbarui jumlah.');
        }
      })
      .catch(err => {
        console.error(err);
        showAlert('danger', 'Terjadi kesalahan sistem saat memperbarui jumlah.');
      });
    }

    // Increase Qty
    document.querySelectorAll('.btn-inc').forEach(btn => {
      btn.addEventListener('click', function () {
        const key = this.dataset.key;
        const input = document.querySelector(`.input-qty[data-key="${key}"]`);
        if (input) {
          const currentVal = parseInt(input.value) || 1;
          const newVal = currentVal + 1;
          input.value = newVal;
          updateCartQty(key, newVal);
        }
      });
    });

    // Decrease Qty
    document.querySelectorAll('.btn-dec').forEach(btn => {
      btn.addEventListener('click', function () {
        const key = this.dataset.key;
        const input = document.querySelector(`.input-qty[data-key="${key}"]`);
        if (input) {
          const currentVal = parseInt(input.value) || 1;
          if (currentVal > 1) {
            const newVal = currentVal - 1;
            input.value = newVal;
            updateCartQty(key, newVal);
          }
        }
      });
    });

    // Input Qty Change
    document.querySelectorAll('.input-qty').forEach(input => {
      input.addEventListener('change', function () {
        const key = this.dataset.key;
        const newVal = parseInt(this.value) || 1;
        if (newVal < 1) this.value = 1;
        updateCartQty(key, this.value);
      });
    });

    // Remove single item
    document.querySelectorAll('.btn-remove').forEach(btn => {
      btn.addEventListener('click', function () {
        const key = this.dataset.key;
        if (!confirm('Apakah Anda yakin ingin menghapus part ini dari keranjang?')) return;

        const removeUrl = `{{ url('/cart/remove') }}/${key}`;

        fetch(removeUrl, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            window.location.reload();
          } else {
            showAlert('danger', data.message || 'Gagal menghapus part.');
          }
        })
        .catch(err => {
          console.error(err);
          showAlert('danger', 'Terjadi kesalahan saat menghapus part.');
        });
      });
    });

    // Clear entire cart
    const btnClearCart = document.getElementById('btnClearCart');
    if (btnClearCart) {
      btnClearCart.addEventListener('click', async function () {
        const confirmation = await Swal.fire({
          icon: 'warning',
          title: 'Kosongkan Keranjang?',
          text: 'Seluruh part di dalam keranjang akan dihapus.',
          showCancelButton: true,
          confirmButtonText: 'Ya, Kosongkan',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          reverseButtons: true
        });

        if (!confirmation.isConfirmed) return;

        fetch(clearUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            window.location.reload();
          } else {
            showAlert('danger', data.message || 'Gagal mengosongkan keranjang.');
          }
        })
        .catch(err => {
          console.error(err);
          showAlert('danger', 'Terjadi kesalahan saat mengosongkan keranjang.');
        });
      });
    }

    // Checkout / Submit Order
    const btnCheckout = document.getElementById('btnCheckout');
    const checkoutUrl = "{{ route('orders.store') }}";

    if (btnCheckout) {
      btnCheckout.addEventListener('click', async function () {
        const confirmation = await Swal.fire({
          icon: 'question',
          title: 'Ajukan Permintaan?',
          text: 'Apakah Anda yakin ingin mengajukan permintaan sparepart ini sekarang?',
          showCancelButton: true,
          confirmButtonText: 'Ya, Ajukan',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#198754',
          cancelButtonColor: '#6c757d',
          reverseButtons: true
        });

        if (!confirmation.isConfirmed) return;

        btnCheckout.disabled = true;
        btnCheckout.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...`;

        fetch(checkoutUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            window.location.href = data.redirect_url || "{{ route('orders.index') }}";
          } else {
            btnCheckout.disabled = false;
            btnCheckout.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>Ajukan Permintaan Sparepart`;
            showAlert('danger', data.message || 'Gagal mengajukan permintaan.');
          }
        })
        .catch(err => {
          btnCheckout.disabled = false;
          btnCheckout.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>Ajukan Permintaan Sparepart`;
          console.error(err);
          showAlert('danger', 'Terjadi kesalahan sistem saat membuat pengajuan.');
        });
      });
    }
  });
</script>
@endpush
