
@extends(auth()->user()->isAdmin() ? 'layouts.app' : 'layouts.user')

@section('content')

<!-- Header -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    Profil Saya
                </h3>
                <p class="text-muted small mb-0">
                    Kelola informasi akun dan keamanan password Anda.
                </p>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('spareparts.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="app-content">
    <div class="container-fluid">

        <!-- Alert Success -->
        @if(session('success') && auth()->user()->isAdmin())
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success_password'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success_password') }}
                <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Validation Error -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Terdapat kesalahan pada pengisian form.

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">

            <!-- INFORMASI PROFIL -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">
                        <h5 class="card-title fw-bold mb-0">
                            <i class="bi bi-person-vcard text-primary me-2"></i>
                            Informasi Profil
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <!-- Profile Identity -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle bg-primary-subtle
                                        text-primary d-flex align-items-center
                                        justify-content-center"
                                 style="width: 65px; height: 65px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    {{ $user->name }}
                                </h5>

                                <span class="badge bg-primary-subtle text-primary">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <hr>

                        <form action="{{ route('profile.update') }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Nama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-person"></i>
                                    </span>

                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Email
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-envelope"></i>
                                    </span>

                                    <input type="email"
                                           name="email"
                                           class="form-control"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Role
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-shield-check"></i>
                                    </span>

                                    <input type="text"
                                           class="form-control bg-light"
                                           value="{{ ucfirst($user->role) }}"
                                           readonly>
                                </div>

                                <small class="text-muted">
                                    Role akun tidak dapat diubah melalui halaman profil.
                                </small>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Password
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input type="password"
                                           class="form-control bg-light"
                                           value="password-placeholder"
                                           readonly>
                                </div>

                                <small class="text-muted">
                                    Password disembunyikan demi keamanan akun.
                                </small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit"
                                        class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- UBAH PASSWORD -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">
                        <h5 class="card-title fw-bold mb-0">
                            <i class="bi bi-shield-lock text-primary me-2"></i>
                            Ubah Password
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Gunakan password baru yang kuat dan mudah Anda ingat.
                        </div>

                        <form action="{{ route('profile.password.update') }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Password Lama -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Lama
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                           name="current_password"
                                           id="current_password"
                                           class="form-control"
                                           placeholder="Masukkan password lama"
                                           required>

                                    <button class="btn btn-outline-secondary toggle-password"
                                            type="button"
                                            data-target="current_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Password Baru
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control"
                                           placeholder="Masukkan password baru"
                                           required>

                                    <button class="btn btn-outline-secondary toggle-password"
                                            type="button"
                                            data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>

                                <small class="text-muted">
                                    Minimal 8 karakter.
                                </small>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Konfirmasi Password Baru
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control"
                                           placeholder="Ulangi password baru"
                                           required>

                                    <button class="btn btn-outline-secondary toggle-password"
                                            type="button"
                                            data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-success w-100">
                                <i class="bi bi-shield-check me-2"></i>
                                Perbarui Password
                            </button>

                        </form>

                    </div>
                </div>

                <!-- SECURITY INFORMATION -->
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <i class="bi bi-shield-check text-success fs-3"></i>

                            <div>
                                <h6 class="fw-bold mb-1">
                                    Keamanan Akun
                                </h6>

                                <p class="text-muted small mb-0">
                                    Jaga kerahasiaan password dan jangan
                                    membagikan informasi login kepada orang lain.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Toggle visibility password
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');

            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>
@endpush
