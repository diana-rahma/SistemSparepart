@extends('layouts.user')

@push('styles')
<style>
  .image-container-wrapper {
    position: relative;
    width: 100%;
    display: inline-block;
    user-select: none;
    -webkit-user-select: none;
    overflow: hidden;
    background-color: #f8fafc;
    border-radius: 0.75rem;
    min-height: 220px;
  }

  .sparepart-image, .sparepart-pdf-canvas {
    width: 100%;
    height: auto;
    display: block;
    pointer-events: none;
  }

  .marker-box {
    position: absolute;
    border: 2px solid #0284c7;
    background: rgba(2, 132, 199, 0.22);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    color: #0369a1;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  }

  .marker-box:hover, .marker-box.hover-active {
    background: rgba(2, 132, 199, 0.45);
    border-color: #0369a1;
    transform: scale(1.1);
    z-index: 15;
    box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
  }

  .marker-box.active {
    background: #0284c7 !important;
    border-color: #0284c7 !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.4), 0 4px 12px rgba(0,0,0,0.25) !important;
    transform: scale(1.15);
    z-index: 20;
    animation: markerPulse 1.6s infinite;
  }

  @keyframes markerPulse {
    0% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7); }
    70% { box-shadow: 0 0 0 8px rgba(2, 132, 199, 0); }
    100% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0); }
  }

  tr.table-active-custom > td {
    background-color: #e0f2fe !important;
    color: #0369a1 !important;
    font-weight: 600;
    transition: background-color 0.2s ease;
  }

  tr.table-active-custom > td:first-child {
    border-left: 4px solid #0284c7 !important;
  }

  tr.table-hover-custom > td {
    background-color: #f0f9ff !important;
  }
</style>
@endpush

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        @if(isset($tipeKendaraan))
          <a href="{{ route('spareparts.show', $tipeKendaraan->id) }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Sparepart {{ $tipeKendaraan->nama }}
          </a>
        @else
          <a href="{{ route('spareparts.index') }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
          </a>
        @endif
        <h3 class="mb-0 text-dark font-weight-bold">
          Diagram & Komponen Sparepart
        </h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('spareparts.index') }}">Home</a></li>
          @if(isset($tipeKendaraan))
            <li class="breadcrumb-item"><a href="{{ route('spareparts.show', $tipeKendaraan->id) }}">{{ $tipeKendaraan->nama }}</a></li>
          @endif
          <li class="breadcrumb-item active" aria-current="page">{{ $sparepart->nama_sparepart }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--end::App Content Header-->

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">

    <!-- SPAREPART TITLE CARD -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="border-radius: 12px;">
      <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <span class="badge bg-white text-primary mb-2 fw-semibold">Diagram Sparepart</span>
          <h2 class="fw-bold m-0 text-white">{{ $sparepart->nama_sparepart }}</h2>
          @if(isset($tipeKendaraan))
            <small class="opacity-75 d-block mt-1"><i class="bi bi-car-front me-1"></i>Kendaraan: {{ $tipeKendaraan->nama }}</small>
          @endif
        </div>
        <!-- <div class="text-end">
          <span class="fs-4 fw-bold text-white d-block">{{ $sparepart->sparepartParts->count() }} Part</span>
          <small class="opacity-75">Ter-mapping</small>
        </div> -->
      </div>
    </div>

    <!-- MAIN DIAGRAM & PARTS SECTION -->
    <div class="row">
      <!-- Left Column: Interactive Diagram Image/PDF Canvas -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100 border-0" style="border-radius: 12px;">
          <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="fw-bold m-0 text-dark fs-6">
              <i class="bi bi-crosshair text-primary me-2"></i>Diagram Interaktif Sparepart
            </h5>
          </div>
          <div class="card-body p-3 text-center">

            @if($sparepart->gambar)
              @php
                $isPdf = strtolower(pathinfo($sparepart->gambar, PATHINFO_EXTENSION)) === 'pdf';
              @endphp

              <div class="image-container-wrapper" id="imageWrapper">
                @if($isPdf)
                  <canvas id="pdfCanvas" class="sparepart-pdf-canvas"></canvas>
                  <div id="pdfLoadingSpinner" class="py-5 text-muted">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Memuat dokumen PDF...
                  </div>
                @else
                  <img src="{{ $sparepart->gambar_url }}" alt="{{ $sparepart->nama_sparepart }}" class="sparepart-image" id="sparepartImg">
                @endif

                <!-- Container for Interactive Markers -->
                <div id="markersContainer"></div>
              </div>
            @else
              <div class="alert alert-secondary my-4">
                <i class="bi bi-image text-muted display-4 d-block mb-2"></i>
                Diagram gambar belum tersedia untuk sparepart ini.
              </div>
            @endif

          </div>
        </div>
      </div>

      <!-- Right Column: Mapped Parts List Table -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
          <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0 text-dark fs-6">
              <i class="bi bi-list-check text-primary me-2"></i>Daftar Komponen Part Terkait
            </h5>
            <!-- @php
              $groupedParts = $sparepart->sparepartParts->groupBy(function($item) {
                return trim($item->nomor_referensi);
              });
            @endphp
            <span class="badge bg-light text-dark border">{{ $groupedParts->count() }} Komponen</span> -->
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 540px; overflow-y: auto;">
              <table class="table table-hover align-middle mb-0" id="userPartsTable">
                <thead class="table-light sticky-top">
                  <tr class="small text-uppercase">
                    <th style="width: 40px;" class="text-center">
                      <input type="checkbox" class="form-check-input" id="selectAllParts" title="Pilih Semua">
                    </th>
                    <th style="width: 50px;" class="text-center">Ref</th>
                    <th style="width: 130px;">Kode Part</th>
                    <th>Nama Part</th>
                    <th style="width: 55px;" class="text-center">Jumlah</th>
                    <th style="width: 55px;" class="text-center">Unit</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($groupedParts as $ref => $items)
                    @php
                      $firstItem = $items->first();
                      $totalJumlah = $items->sum('jumlah');
                      $unit = $firstItem->unit ?? 'pcs';
                      $partKode = $firstItem->part ? ($firstItem->part->kode_parts ?? $firstItem->part->kode_part) : '-';
                      $partNama = $firstItem->part ? ($firstItem->part->nama_parts ?? $firstItem->part->nama_part) : '-';
                      $stokTersedia = $firstItem->part && $firstItem->part->stok ? (int) $firstItem->part->stok->jumlah : 0;
                      $hargaSatuan = $firstItem->part && $firstItem->part->stok ? (float) $firstItem->part->stok->harga_satuan : 0;
                    @endphp
                    <tr data-ref="{{ trim($ref) }}" style="cursor: pointer;">
                      <td class="text-center" onclick="event.stopPropagation()">
                        <input type="checkbox" class="form-check-input part-checkbox" 
                               data-ref="{{ trim($ref) }}" 
                               data-kode="{{ $partKode }}" 
                               data-nama="{{ $partNama }}" 
                               data-harga="{{ $hargaSatuan }}" 
                               data-jml="{{ $totalJumlah }}" 
                               data-stok="{{ $stokTersedia }}">
                      </td>
                      <td class="text-center fw-bold text-primary fs-6">{{ $ref }}</td>
                      <td class="font-monospace small fw-bold text-dark">{{ $partKode }}</td>
                      <td class="small fw-semibold text-dark">{{ $partNama }}</td>
                      <td class="text-center fw-bold">{{ $totalJumlah }}</td>
                      <td class="text-center text-uppercase small">{{ $unit }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-4">
                        Belum ada part yang ter-mapping pada sparepart ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          

          <div class="card-footer bg-white border-top py-3 d-flex align-items-center flex-wrap gap-2"
              style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
              <span class="text-muted small fw-semibold" id="selectedCountText">
                  0 komponen dipilih
              </span>
              <button type="button"
                      class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold ms-auto"
                      id="btnCekStok">
                  <i class="bi bi-box-seam me-2"></i>Cek Stok Sparepart
              </button>
          </div>
        </div>

        <!-- TABEL CEK STOK -->
        <div class="card shadow-sm border-0 mt-4 d-none" id="stockResultCard" style="border-radius: 12px;">

          <div class="card-header bg-white border-bottom py-3 d-flex align-items-center flex-wrap gap-2">

              <h5 class="fw-bold m-0 text-dark fs-6">
                  <i class="bi bi-boxes text-primary me-2"></i>Hasil Cek Stok Sparepart
              </h5>
<!-- 
              <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold ms-auto"
                    id="stockStatusOverallBadge">
                  <i class="bi bi-check-circle-fill me-1"></i>Stok Tersedia
              </span> -->

          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" id="stockResultTable">
                <thead class="table-light">
                  <tr class="small text-uppercase">
                    <th style="width: 45px;" class="text-center">Ref</th>
                    <th style="width: 120px;">Kode Part</th>
                    <th>Nama Part</th>
                    <!-- <th style="width: 120px;" class="text-end">Harga Satuan</th> -->
                    <th style="width: 85px;" class="text-center">Dibutuhkan</th>
                    <th style="width: 75px;" class="text-center">Stok</th>
                    <th style="width: 100px;" class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody id="stockResultTbody">
                  <!-- Populated dynamically via JavaScript -->
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-footer bg-white border-top py-3 d-flex align-items-center flex-wrap gap-2"
              style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
              <span class="text-muted small fw-semibold" id="cartResultItemCountText">
                  Komponen siap dimasukkan ke keranjang
              </span>
              <button type="button"
                      class="btn btn-success rounded-pill px-4 shadow-sm fw-bold ms-auto"
                      id="btnAddToCart">
                  <i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang
              </button>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>
<!--end::App Content-->

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- PDF.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<!-- PDF.js CDN for PDF rendering support -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
  if (window['pdfjs-dist/build/pdf']) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
  }

  document.addEventListener('DOMContentLoaded', function () {
    const fileUrl = @json($sparepart->gambar_url);
    const isPdf = {{ !empty($sparepart->gambar) && strtolower(pathinfo($sparepart->gambar, PATHINFO_EXTENSION)) === 'pdf' ? 'true' : 'false' }};

    const mappedParts = [
      @foreach($sparepart->sparepartParts as $sp)
        {
          id: {{ $sp->id }},
          nomor_referensi: "{{ $sp->nomor_referensi }}",
          jumlah: {{ $sp->jumlah ?? 1 }},
          pos_x: {{ $sp->pos_x ?? 0 }},
          pos_y: {{ $sp->pos_y ?? 0 }},
          lebar: {{ $sp->lebar ?? 4.8 }},
          tinggi: {{ $sp->tinggi ?? 4.8 }},
          part_nama: "{{ $sp->part ? ($sp->part->nama_parts ?? $sp->part->nama_part) : '-' }}",
          part_kode: "{{ $sp->part ? ($sp->part->kode_parts ?? $sp->part->kode_part) : '-' }}"
        },
      @endforeach
    ];

    const markersContainer = document.getElementById('markersContainer');

    if (isPdf && fileUrl) {
      const pdfCanvas = document.getElementById('pdfCanvas');
      const pdfSpinner = document.getElementById('pdfLoadingSpinner');

      pdfjsLib.getDocument(fileUrl).promise.then(function (pdf) {
        return pdf.getPage(1);
      }).then(function (page) {
        const viewport = page.getViewport({ scale: 1.5 });
        const context = pdfCanvas.getContext('2d');
        pdfCanvas.height = viewport.height;
        pdfCanvas.width = viewport.width;

        return page.render({ canvasContext: context, viewport: viewport }).promise;
      }).then(function () {
        if (pdfSpinner) pdfSpinner.style.display = 'none';
        renderMarkers();
      }).catch(function (err) {
        console.error('Error loading PDF:', err);
      });
    } else {
      renderMarkers();
    }

    function renderMarkers() {
      if (!markersContainer) return;
      markersContainer.innerHTML = '';

      mappedParts.forEach(p => {
        const marker = document.createElement('div');
        marker.className = 'marker-box';
        marker.dataset.id = p.id;
        marker.dataset.ref = String(p.nomor_referensi).trim();
        marker.textContent = p.nomor_referensi;
        marker.style.left = p.pos_x + '%';
        marker.style.top = p.pos_y + '%';
        marker.style.width = p.lebar + '%';
        marker.style.height = p.tinggi + '%';
        marker.title = `Ref: ${p.nomor_referensi} | Kode: ${p.part_kode} | Nama: ${p.part_nama}`;

        marker.addEventListener('click', function (e) {
          e.stopPropagation();
          highlightByRef(p.nomor_referensi);
        });

        marker.addEventListener('mouseenter', function () {
          highlightHoverByRef(p.nomor_referensi, true);
        });

        marker.addEventListener('mouseleave', function () {
          highlightHoverByRef(p.nomor_referensi, false);
        });

        markersContainer.appendChild(marker);
      });
    }

    // Sorotan 2-Arah berdasarkan Nomor Referensi (Sorot SEMUA marker dengan ref yang sama & baris tabel)
    function highlightByRef(ref) {
      const cleanRef = String(ref).trim();

      document.querySelectorAll('.marker-box, tr[data-ref]').forEach(el => {
        el.classList.remove('active', 'table-active-custom');
      });

      const activeMarkers = document.querySelectorAll(`.marker-box[data-ref="${cleanRef}"]`);
      activeMarkers.forEach(m => m.classList.add('active'));

      const activeRow = document.querySelector(`tr[data-ref="${cleanRef}"]`);
      if (activeRow) {
        activeRow.classList.add('table-active-custom');
        activeRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

    function highlightHoverByRef(ref, isHover) {
      const cleanRef = String(ref).trim();

      document.querySelectorAll(`.marker-box[data-ref="${cleanRef}"]`).forEach(m => {
        if (!m.classList.contains('active')) {
          if (isHover) m.classList.add('hover-active');
          else m.classList.remove('hover-active');
        }
      });

      const row = document.querySelector(`tr[data-ref="${cleanRef}"]`);
      if (row && !row.classList.contains('table-active-custom')) {
        if (isHover) row.classList.add('table-hover-custom');
        else row.classList.remove('table-hover-custom');
      }
    }

    // Event listener hover & click pada baris tabel
    document.querySelectorAll('tr[data-ref]').forEach(row => {
      const ref = row.dataset.ref;

      row.addEventListener('click', function () {
        highlightByRef(ref);
      });

      row.addEventListener('mouseenter', function () {
        highlightHoverByRef(ref, true);
      });

      row.addEventListener('mouseleave', function () {
        highlightHoverByRef(ref, false);
      });
    });

    // --- FITUR CHECKBOX & CEK STOK SPAREPART ---
    const selectAllParts = document.getElementById('selectAllParts');
    const partCheckboxes = document.querySelectorAll('.part-checkbox');
    const selectedCountText = document.getElementById('selectedCountText');
    const btnCekStok = document.getElementById('btnCekStok');
    const stockResultCard = document.getElementById('stockResultCard');
    const stockResultTbody = document.getElementById('stockResultTbody');
    const stockStatusOverallBadge = document.getElementById('stockStatusOverallBadge');
    const cartResultItemCountText = document.getElementById('cartResultItemCountText');

    function updateSelectedCount() {
      const checkedBoxes = document.querySelectorAll('.part-checkbox:checked');
      const count = checkedBoxes.length;
      if (selectedCountText) {
        selectedCountText.textContent = `${count} komponen dipilih`;
      }
      if (selectAllParts) {
        selectAllParts.checked = partCheckboxes.length > 0 && count === partCheckboxes.length;
        selectAllParts.indeterminate = count > 0 && count < partCheckboxes.length;
      }
    }

    if (selectAllParts) {
      selectAllParts.addEventListener('change', function () {
        const isChecked = this.checked;
        partCheckboxes.forEach(cb => {
          cb.checked = isChecked;
        });
        updateSelectedCount();
      });
    }

    partCheckboxes.forEach(cb => {
      cb.addEventListener('change', updateSelectedCount);
    });

    if (btnCekStok) {
      btnCekStok.addEventListener('click', function () {
        const checkedBoxes = document.querySelectorAll('.part-checkbox:checked');

        if (checkedBoxes.length === 0) {
          alert('Silakan centang minimal 1 komponen part terlebih dahulu untuk mengecek stok.');
          return;
        }

        stockResultTbody.innerHTML = '';
        let allAvailable = true;

        checkedBoxes.forEach(cb => {
          const ref = cb.dataset.ref;
          const kode = cb.dataset.kode;
          const nama = cb.dataset.nama;
          const harga = parseFloat(cb.dataset.harga) || 0;
          const jmlDibutuhkan = parseInt(cb.dataset.jml) || 0;
          const stokTersedia = parseInt(cb.dataset.stok) || 0;

          const hargaFormatted = harga > 0 
            ? 'Rp ' + Math.round(harga).toLocaleString('id-ID')
            : '-';

          let statusBadge = '';
          if (stokTersedia >= jmlDibutuhkan) {
            statusBadge = `<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle me-1"></i>Tersedia</span>`;
          } else if (stokTersedia > 0) {
            statusBadge = `<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i>Stok Kurang</span>`;
            allAvailable = false;
          } else {
            statusBadge = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-x-circle me-1"></i>Stok Habis</span>`;
            allAvailable = false;
          }

          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td class="text-center fw-bold text-primary">${ref}</td>
            <td class="font-monospace small fw-bold text-dark">${kode}</td>
            <td class="small fw-semibold text-dark">${nama}</td>
            <td class="text-center fw-bold text-dark">${jmlDibutuhkan}</td>
            <td class="text-center fw-bold ${stokTersedia >= jmlDibutuhkan ? 'text-success' : (stokTersedia > 0 ? 'text-warning' : 'text-danger')}">${stokTersedia}</td>
            <td class="text-center">${statusBadge}</td>
          `;
          stockResultTbody.appendChild(tr);
        });

        if (stockStatusOverallBadge) {
          if (allAvailable) {
            stockStatusOverallBadge.className = 'badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold';
            stockStatusOverallBadge.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>Semua Stok Cukup`;
          } else {
            stockStatusOverallBadge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fw-semibold';
            stockStatusOverallBadge.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i>Ada Stok Tidak Cukup`;
          }
        }

        if (cartResultItemCountText) {
          cartResultItemCountText.textContent = `${checkedBoxes.length} komponen siap ditambahkan ke keranjang`;
        }

        stockResultCard.classList.remove('d-none');
        stockResultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    }
    
    // --- FITUR TAMBAH KE KERANJANG ---
    const btnAddToCart = document.getElementById('btnAddToCart');
    const addToCartUrl = "{{ route('cart.add') }}";
    const sparepartId = {{ $sparepart->id }};
    const vehicleId = {{ isset($tipeKendaraan) ? $tipeKendaraan->id : 'null' }};

    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', async function () {
            const checkedBoxes = document.querySelectorAll('.part-checkbox:checked');

            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada Komponen',
                    text: 'Silakan pilih minimal satu komponen part terlebih dahulu.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }

            const items = [];

            checkedBoxes.forEach(cb => {
                items.push({
                    ref: cb.dataset.ref,
                    kode: cb.dataset.kode,
                    nama: cb.dataset.nama,
                    harga: parseFloat(cb.dataset.harga) || 0,
                    jml: parseInt(cb.dataset.jml) || 1,
                    stok: parseInt(cb.dataset.stok) || 0
                });
            });

            btnAddToCart.disabled = true;
            btnAddToCart.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"
                      role="status" aria-hidden="true"></span>
                Menambahkan...
            `;

            try {
                const response = await fetch(addToCartUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        sparepart_id: sparepartId,
                        vehicle_id: vehicleId,
                        items: items
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Gagal menambahkan komponen ke keranjang.'
                    );
                }

                // Perbarui jumlah item pada ikon keranjang di navbar
                const navCartBadge = document.getElementById('navCartBadge');

                if (navCartBadge) {
                    navCartBadge.textContent =
                        data.cart_count ?? data.total_items ?? 0;

                    navCartBadge.classList.remove('d-none');
                }

                // Popup berhasil dengan pilihan tindakan
                const result = await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Ditambahkan!',
                    text: data.message ||
                        `${items.length} komponen berhasil ditambahkan ke keranjang.`,
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-cart3 me-1"></i> Lihat Keranjang',
                    cancelButtonText: 'Tetap di Halaman',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    allowOutsideClick: false
                });

                // Arahkan pengguna jika memilih Lihat Keranjang
                if (result.isConfirmed) {
                    window.location.href =
                        data.cart_url || "{{ route('cart.index') }}";
                }

            } catch (error) {
                console.error('Error menambahkan ke keranjang:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menambahkan',
                    text: error.message ||
                        'Terjadi kesalahan sistem saat menambahkan ke keranjang.',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#dc3545'
                });

            } finally {
                btnAddToCart.disabled = false;
                btnAddToCart.innerHTML = `
                    <i class="bi bi-cart-plus-fill me-2"></i>
                    Tambah ke Keranjang
                `;
            }
        });
    }

  });
</script>
@endpush