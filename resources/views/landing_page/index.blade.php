<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Katalog Sparepart Kereta Api</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header class="nav">
  <a class="logo" href="{{ url('/') }}"></a>
  <div style="display: flex; gap: 10px; align-items: center;">
    @auth
      @if(auth()->user()->isAdmin())
        <a class="btn btn-dark" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
      @else
        <a class="btn btn-dark" href="{{ route('spareparts.index') }}">Katalog Sparepart</a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-red" style="padding: 10px 16px; font-size: 14px;">Keluar</button>
      </form>
    @else
      <a class="btn btn-dark" href="{{ route('login') }}">Login</a>
      <a class="btn btn-red" href="{{ route('register') }}">Register</a>
    @endauth
  </div>
</header>

<main>
  <section class="hero">
    <div class="hero-text">
      <p class="eyebrow">Katalog sparepart kereta api</p>
      <h1>Temukan part yang tepat, tanpa menebak.</h1>
      <!-- <p class="lead">Cari langsung dengan nomor part, atau telusuri dari kategori sampai ke gambar diagram lengkap dengan nomor referensinya.</p> -->

      <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
        @auth
          <a class="btn btn-red" href="{{ route('spareparts.index') }}">Buka Katalog Sparepart</a>
        @else
          <a class="btn btn-red" href="{{ route('login') }}">Login Sekarang</a>
          <a class="btn btn-dark" href="{{ route('register') }}">Daftar Akun Pembeli</a>
        @endauth
      </div>
    </div>
  </section>

<footer>
  <span>© 2026 . Katalog sparepart kereta api.</span>
  <span>Kategori · Cara mencari · Kontak</span>
</footer>

</body>
</html>
