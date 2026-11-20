@extends('layouts.app')

@push('styles')
<style>
  .image-container-wrapper{position:relative;width:100%;display:inline-block;user-select:none;overflow:hidden;background:#f8f9fa;border-radius:.375rem;touch-action:none;min-height:200px}
  .sparepart-image,.sparepart-pdf-canvas{width:100%;height:auto;display:block;pointer-events:none;-webkit-user-drag:none}

  /* Lapisan: gambar < svg panah < kotak nomor < kanvas drag (aktif hanya saat mode tambah) */
  #arrowLayer{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:5}
  #markersContainer{position:absolute;inset:0;z-index:10;pointer-events:none}
  .drag-canvas{position:absolute;inset:0;z-index:20;display:none;cursor:crosshair;touch-action:none}
  .drag-canvas.active{display:block}

  .arrow-line{stroke:#0d6efd;stroke-width:2;fill:none;transition:stroke .15s}
  .arrow-line.active{stroke:#dc3545;stroke-width:3.5}
  .arrow-line.draft{stroke-dasharray:6 4;stroke:#198754}

  .marker-box{position:absolute;transform:translate(-50%,-50%);aspect-ratio:1/1;border:2px solid #0d6efd;background:#fff;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0b5ed7;cursor:pointer;pointer-events:auto;box-shadow:0 1px 3px rgba(0,0,0,.25)}
  .marker-box.active{background:#dc3545;border-color:#dc3545;color:#fff;box-shadow:0 0 0 4px rgba(220,53,69,.3)}
  .marker-box.preview{pointer-events:none;border-style:dashed;border-color:#198754;color:#198754;background:#fff}
  .marker-del{display:none;position:absolute;top:-9px;right:-9px;width:16px;height:16px;border-radius:50%;background:#212529;color:#fff;font-size:12px;line-height:16px;text-align:center}
  .marker-box.active .marker-del{display:block}

  tr.table-active-custom>td{background:#fde2e4!important;color:#b02a37!important;font-weight:600}
  tr.table-active-custom>td:first-child{border-left:4px solid #dc3545!important}
</style>
@endpush

@section('content')
@php
  $isPdf = !empty($sparepart->gambar) && strtolower(pathinfo($sparepart->gambar, PATHINFO_EXTENSION)) === 'pdf';
@endphp
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0"><i class="bi bi-crosshair text-primary me-2"></i>Mapping Gambar Sparepart</h3>
        <p class="text-muted small mb-0">Tambah mapping dengan menggambar panah ke part, lalu tempatkan kotak nomor referensinya.</p>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.spareparts.index') }}">Spareparts</a></li>
          <li class="breadcrumb-item active">Mapping</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    <div id="alertContainer"></div>

    <div class="card card-outline card-primary mb-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-bold m-0"><i class="bi bi-info-circle me-1"></i> Sparepart: {{ $sparepart->nama_sparepart }}</h5>
        <div>
          <a href="{{ route('admin.spareparts.edit', $sparepart->id) }}" class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-pencil-square me-1"></i> Edit Detail</a>
          <a href="{{ route('admin.spareparts.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar</a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <span class="fw-bold"><i class="bi bi-image me-1"></i> Canvas Dokumen / Gambar</span>
            @if($sparepart->gambar)
              <button type="button" class="btn btn-sm btn-primary" id="btnTambah"><i class="bi bi-plus-circle me-1"></i>Tambah Mapping</button>
            @endif
          </div>
          <div class="card-body p-3 text-center">
            @if($sparepart->gambar)
              <div class="alert alert-info py-2 small text-start d-none" id="modeHint"></div>
              <div class="image-container-wrapper" id="imageWrapper">
                @if($isPdf)
                  <canvas id="pdfCanvas" class="sparepart-pdf-canvas"></canvas>
                  <div id="pdfLoadingSpinner" class="py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat dokumen PDF...</div>
                @else
                  <img src="{{ asset($sparepart->gambar) }}" alt="{{ $sparepart->nama_sparepart }}" class="sparepart-image" draggable="false">
                @endif

                <svg id="arrowLayer">
                  <defs>
                    <marker id="ah" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto">
                      <path d="M0 0L10 5L0 10z" fill="context-stroke"/>
                    </marker>
                  </defs>
                  <g id="gSaved"></g>
                  <g id="gDraft"></g>
                </svg>
                <div id="markersContainer"></div>
                <div class="drag-canvas" id="dragCanvas"></div>
              </div>
            @else
              <div class="alert alert-warning my-4">
                <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                Sparepart ini belum memiliki file gambar/PDF. Silakan edit sparepart dan unggah file terlebih dahulu.
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card card-outline card-success shadow-sm mb-4 d-none" id="formCard">
          <div class="card-header bg-success text-white">
            <h5 class="card-title fw-bold fs-6 m-0"><i class="bi bi-plus-square me-1"></i> Lengkapi Data Mapping</h5>
          </div>
          <div class="card-body p-3">
            <form id="partMappingForm">
              <div class="mb-2">
                <label for="fPartId" class="form-label small fw-bold">Pilih Part <span class="text-danger">*</span></label>
                <select id="fPartId" class="form-select form-select-sm" required>
                  <option value="">-- Pilih Part --</option>
                  @foreach($parts as $part)
                    <option value="{{ $part->id }}">{{ $part->kode_parts ?? $part->kode_part ?? '-' }} - {{ $part->nama_parts ?? $part->nama_part ?? '-' }}</option>
                  @endforeach
                </select>
                <div class="form-text d-none" id="partExistsNote">Part ini sudah ter-mapping. Nomor referensi dan unit mengikuti mapping yang ada, jumlah boleh diubah.</div>
              </div>
              <div class="row g-2 mb-3">
                <div class="col-4">
                  <label for="fRef" class="form-label small fw-bold">Nomor Referensi <span class="text-danger">*</span></label>
                  <input type="text" id="fRef" class="form-control form-control-sm" placeholder="1, 2, 3A" required autocomplete="off">
                </div>
                <div class="col-4">
                  <label for="fJumlah" class="form-label small fw-bold">Jumlah <span class="text-danger">*</span></label>
                  <input type="number" id="fJumlah" class="form-control form-control-sm" value="1" min="1" required>
                </div>
                <div class="col-4">
                  <label for="fUnit" class="form-label small fw-bold">Unit <span class="text-danger">*</span></label>
                  <select id="fUnit" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>
                    <option value="pcs">Pcs</option>
                    <option value="set">Set</option>
                  </select>
                </div>
              </div>
              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-secondary" id="btnBatalArea"><i class="bi bi-x-circle me-1"></i>Batal</button>
                <button type="submit" class="btn btn-sm btn-success" id="btnSimpanArea"><i class="bi bi-check-circle me-1"></i>Simpan Mapping</button>
              </div>
            </form>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
            <h6 class="card-title fw-bold m-0 fs-6"><i class="bi bi-list-check me-1"></i> Daftar Part Ter-mapping</h6>
            <span class="badge bg-primary" id="partCountBadge">0 Part</span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" style="max-height:520px;overflow-y:auto">
              <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light sticky-top">
                  <tr class="small">
                    <th style="width:45px" class="text-center">Ref</th>
                    <th style="width:140px">Kode Part</th>
                    <th>Nama Part</th>
                    <th style="width:60px" class="text-center">Jumlah</th>
                    <th style="width:60px" class="text-center">Unit</th>
                    <th style="width:60px" class="text-center">Panah</th>
                    <th style="width:45px" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody id="partsTbody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@php
  $mappingsData = $sparepart->sparepartParts->map(fn($sp) => [
    'id' => $sp->id,
    'part_id' => $sp->part_id,
    'nomor_referensi' => (string) $sp->nomor_referensi,
    'jumlah' => (int) ($sp->jumlah ?? 1),
    'unit' => $sp->unit ?? 'pcs',
    'part_kode' => $sp->part?->kode_parts ?? $sp->part?->kode_part ?? '-',
    'part_nama' => $sp->part?->nama_parts ?? $sp->part?->nama_part ?? '-',
    'penanda' => $sp->penanda->map(fn($p) => [
      'id' => $p->id,
      'tip_x' => (float) $p->tip_x, 'tip_y' => (float) $p->tip_y,
      'tail_x' => (float) $p->tail_x, 'tail_y' => (float) $p->tail_y,
      'box_x' => (float) $p->box_x, 'box_y' => (float) $p->box_y,
    ])->values(),
  ])->values();
@endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = id => document.getElementById(id);
  const wrapper = $('imageWrapper');
  if (!wrapper) return;

  const URLS = {
    store: "{{ route('admin.spareparts.parts.store', $sparepart->id) }}",
    parts: "{{ url('/admin/spareparts/' . $sparepart->id . '/parts') }}",
    penanda: "{{ url('/admin/spareparts/' . $sparepart->id . '/penanda') }}",
  };
  const CSRF = '{{ csrf_token() }}';
  const IS_PDF = @json($isPdf);
  const FILE_URL = "{{ asset($sparepart->gambar) }}";
  const BOX_PCT = 4.8;        // lebar kotak nomor (% lebar gambar), tinggi mengikuti (persegi)
  const MIN_ARROW_PX = 20;    // panah lebih pendek dari ini dianggap klik biasa

  let mappings = @json($mappingsData);
  let state = 'idle';         // idle -> drawing-arrow -> placing-number -> form
  let draft = null, dragging = false, previewBox = null, activeRef = null;

  const dragCanvas = $('dragCanvas'), gSaved = $('gSaved'), gDraft = $('gDraft');
  const markersBox = $('markersContainer'), tbody = $('partsTbody');
  const formCard = $('formCard'), form = $('partMappingForm'), hint = $('modeHint');
  const btnTambah = $('btnTambah');

  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const HINTS = {
    'drawing-arrow': '<b>Langkah 1 dari 2.</b> Tekan dan seret dari lokasi part menuju area kosong. Ujung panah mengarah ke part.',
    'placing-number': '<b>Langkah 2 dari 2.</b> Klik lokasi kotak nomor, sebaiknya dekat pangkal panah.',
    'form': 'Lengkapi data di samping. Klik gambar lagi untuk memindahkan kotak nomor.',
  };

  function setState(s) {
    state = s;
    dragCanvas.classList.toggle('active', s !== 'idle');
    hint.classList.toggle('d-none', s === 'idle');
    hint.innerHTML = HINTS[s] || '';
    formCard.classList.toggle('d-none', s !== 'form');
    btnTambah.innerHTML = s === 'idle'
      ? '<i class="bi bi-plus-circle me-1"></i>Tambah Mapping'
      : '<i class="bi bi-x-circle me-1"></i>Batal';
    if (s === 'form') $('fPartId').focus();
  }

  const arrowLine = (p, W, H, attrs = '', cls = '') =>
    `<line class="arrow-line ${cls}" ${attrs} x1="${p.tail_x / 100 * W}" y1="${p.tail_y / 100 * H}" x2="${p.tip_x / 100 * W}" y2="${p.tip_y / 100 * H}" marker-end="url(#ah)"/>`;

  function render() {
    const W = wrapper.clientWidth, H = wrapper.clientHeight;
    let arrows = '';
    markersBox.innerHTML = '';
    tbody.innerHTML = '';

    mappings.forEach(m => {
      const ref = esc(m.nomor_referensi);
      m.penanda.forEach(p => {
        arrows += arrowLine(p, W, H, `data-ref="${ref}"`);
        const b = document.createElement('div');
        b.className = 'marker-box';
        b.dataset.ref = m.nomor_referensi;
        b.style.cssText = `left:${p.box_x}%;top:${p.box_y}%;width:${BOX_PCT}%`;
        b.title = `Ref ${m.nomor_referensi} | ${m.part_kode} | ${m.part_nama}`;
        b.innerHTML = `${ref}<span class="marker-del" title="Hapus panah ini">&times;</span>`;
        b.addEventListener('click', e => {
          if (e.target.classList.contains('marker-del')) hapusPenanda(m.id, p.id);
          else highlightByRef(m.nomor_referensi);
        });
        markersBox.appendChild(b);
      });

      const tr = document.createElement('tr');
      tr.dataset.ref = m.nomor_referensi;
      tr.style.cursor = 'pointer';
      tr.innerHTML = `
        <td class="text-center fw-bold text-primary">${ref}</td>
        <td class="font-monospace small fw-bold text-dark">${esc(m.part_kode)}</td>
        <td class="small text-secondary">${esc(m.part_nama)}</td>
        <td class="text-center fw-semibold">${m.jumlah}</td>
        <td class="text-center small text-uppercase">${esc(m.unit)}</td>
        <td class="text-center"><span class="badge bg-secondary">${m.penanda.length}</span></td>
        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm p-1 py-0" title="Hapus mapping"><i class="bi bi-trash"></i></button></td>`;
      tr.addEventListener('click', () => highlightByRef(m.nomor_referensi, true));
      tr.querySelector('button').addEventListener('click', e => { e.stopPropagation(); hapusMapping(m.id); });
      tbody.appendChild(tr);
    });

    gSaved.innerHTML = arrows;
    $('partCountBadge').textContent = mappings.length + ' Part';
    if (!mappings.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted small">Belum ada part yang ter-mapping.<br>Tekan "Tambah Mapping" lalu gambar panah ke part.</td></tr>';
    }
    applyHighlight(false);
    drawDraft();
  }

  function highlightByRef(ref, scroll = true) {
    activeRef = String(ref);
    applyHighlight(scroll);
  }
  function applyHighlight(scroll) {
    document.querySelectorAll('[data-ref]').forEach(el => {
      const on = activeRef !== null && el.dataset.ref === activeRef;
      if (el.tagName === 'TR') {
        el.classList.toggle('table-active-custom', on);
        if (on && scroll) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else el.classList.toggle('active', on);
    });
  }

  // ---- Draft (panah + kotak pratinjau yang sedang dibuat) ----
  function drawDraft() {
    const W = wrapper.clientWidth, H = wrapper.clientHeight;
    gDraft.innerHTML = draft ? arrowLine(draft, W, H, '', 'draft') : '';
    if (draft && draft.box_x !== null) {
      if (!previewBox) {
        previewBox = document.createElement('div');
        previewBox.className = 'marker-box preview';
        markersBox.appendChild(previewBox);
      }
      previewBox.style.cssText = `left:${draft.box_x}%;top:${draft.box_y}%;width:${BOX_PCT}%`;
      previewBox.textContent = $('fRef').value.trim() || '?';
    } else if (previewBox) {
      previewBox.remove();
      previewBox = null;
    }
  }

  function cancelDraft() {
    draft = null; dragging = false;
    form.reset();
    lockExisting(null);
    drawDraft();
    setState('idle');
  }

  const pct = e => {
    const r = dragCanvas.getBoundingClientRect();
    return {
      x: Math.min(100, Math.max(0, (e.clientX - r.left) / r.width * 100)),
      y: Math.min(100, Math.max(0, (e.clientY - r.top) / r.height * 100)),
    };
  };

  dragCanvas.addEventListener('pointerdown', e => {
    if (e.button !== 0) return;
    const p = pct(e);
    if (state === 'drawing-arrow') {
      draft = { tip_x: p.x, tip_y: p.y, tail_x: p.x, tail_y: p.y, box_x: null, box_y: null };
      dragging = true;
      dragCanvas.setPointerCapture(e.pointerId);
    } else if (state === 'placing-number' || state === 'form') {
      draft.box_x = p.x; draft.box_y = p.y;
      drawDraft();
      setState('form');
    }
  });

  dragCanvas.addEventListener('pointermove', e => {
    if (!dragging) return;
    const p = pct(e);
    draft.tail_x = p.x; draft.tail_y = p.y;
    drawDraft();
  });

  dragCanvas.addEventListener('pointerup', e => {
    if (!dragging) return;
    dragging = false;
    const p = pct(e);
    draft.tail_x = p.x; draft.tail_y = p.y;
    const dx = (draft.tail_x - draft.tip_x) / 100 * wrapper.clientWidth;
    const dy = (draft.tail_y - draft.tip_y) / 100 * wrapper.clientHeight;
    if (Math.hypot(dx, dy) < MIN_ARROW_PX) {   // terlalu pendek: ulangi langkah 1
      draft = null; drawDraft(); return;
    }
    drawDraft();
    setState('placing-number');
  });

  btnTambah.addEventListener('click', () => state === 'idle' ? setState('drawing-arrow') : cancelDraft());
  $('btnBatalArea').addEventListener('click', cancelDraft);
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && state !== 'idle') cancelDraft(); });
  $('fRef').addEventListener('input', () => { if (previewBox) previewBox.textContent = $('fRef').value.trim() || '?'; });

  // Part yang sudah ter-mapping: nomor referensi & jumlah dikunci agar konsisten
  function lockExisting(m) {
    $('fRef').readOnly = $('fUnit').disabled = !!m;   // jumlah tetap bisa diubah
    $('partExistsNote').classList.toggle('d-none', !m);
    if (m) { $('fRef').value = m.nomor_referensi; $('fJumlah').value = m.jumlah; $('fUnit').value = m.unit; }
    if (previewBox) previewBox.textContent = $('fRef').value.trim() || '?';
  }
  $('fPartId').addEventListener('change', e => {
    const m = mappings.find(x => String(x.part_id) === e.target.value);
    if (!m) { $('fRef').value = ''; $('fJumlah').value = 1; $('fUnit').value = ''; }
    lockExisting(m || null);
  });

  // ---- Simpan / hapus ----
  const call = (url, method, body) => fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    body: body ? JSON.stringify(body) : undefined,
  }).then(r => r.json());

  form.addEventListener('submit', e => {
    e.preventDefault();
    if (!draft || draft.box_x === null) return showAlert('danger', 'Gambar panah dan tempatkan kotak nomor terlebih dahulu.');
    const ref = $('fRef').value.trim();
    if (!ref) return showAlert('danger', 'Nomor referensi wajib diisi.');

    const btn = $('btnSimpanArea');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    call(URLS.store, 'POST', {
      part_id: $('fPartId').value, nomor_referensi: ref, jumlah: $('fJumlah').value, unit: $('fUnit').value,
      tip_x: draft.tip_x, tip_y: draft.tip_y, tail_x: draft.tail_x, tail_y: draft.tail_y,
      box_x: draft.box_x, box_y: draft.box_y,
    }).then(res => {
      if (!res.success) return showAlert('danger', res.message || 'Gagal menyimpan mapping.');
      const i = mappings.findIndex(m => m.id === res.data.id);   // data = mapping lengkap + semua penandanya
      if (i >= 0) mappings[i] = res.data; else mappings.push(res.data);
      cancelDraft();
      render(); 
      highlightByRef(res.data.nomor_referensi);
      showAlert('success', res.message);
    }).catch(err => { console.error(err); showAlert('danger', 'Terjadi kesalahan sistem saat menyimpan mapping.'); })
      .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Simpan Mapping'; });
  });

  function hapusMapping(id) {
    if (!confirm('Hapus mapping part ini beserta semua panahnya?')) return;
    call(`${URLS.parts}/${id}`, 'DELETE').then(res => {
      if (res.success) { mappings = mappings.filter(m => m.id !== id); render(); }
      showAlert(res.success ? 'success' : 'danger', res.message || 'Gagal menghapus mapping.');
    }).catch(() => showAlert('danger', 'Terjadi kesalahan sistem saat menghapus mapping.'));
  }

  function hapusPenanda(mappingId, penandaId) {
    if (!confirm('Hapus panah dan kotak nomor ini?')) return;
    call(`${URLS.penanda}/${penandaId}`, 'DELETE').then(res => {
      if (res.success) {
        const m = mappings.find(x => x.id === mappingId);
        m.penanda = m.penanda.filter(p => p.id !== penandaId);
        render();
      }
      showAlert(res.success ? 'success' : 'danger', res.message || 'Gagal menghapus penanda.');
    }).catch(() => showAlert('danger', 'Terjadi kesalahan sistem saat menghapus penanda.'));
  }

  function showAlert(type, message) {
    $('alertContainer').innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show shadow-sm">
        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>${esc(message)}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>`;
  }

  // ---- PDF.js (halaman 1) ----
  if (IS_PDF) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    const canvas = $('pdfCanvas'), spinner = $('pdfLoadingSpinner');
    pdfjsLib.getDocument(FILE_URL).promise
      .then(pdf => pdf.getPage(1))
      .then(page => {
        const vp = page.getViewport({ scale: 1.5 });
        canvas.width = vp.width; canvas.height = vp.height;
        return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
      })
      .then(() => { spinner.style.display = 'none'; })
      .catch(err => {
        console.error(err);
        spinner.className = 'alert alert-danger my-3';
        spinner.textContent = 'Gagal memuat dokumen PDF.';
      });
  }

  // Panah dihitung ke pixel, jadi gambar ulang setiap ukuran kontainer berubah (gambar selesai dimuat, resize layar)
  new ResizeObserver(render).observe(wrapper);
  render();
});
</script>
@endpush