      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">

          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="{{ route('home') }}" class="nav-link fw-bold text-dark d-flex align-items-center gap-2">
                <!-- <span class="badge bg-danger text-white rounded px-2 py-1">R</span> -->
                <!-- <span>Relparts</span> -->
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

            <!--begin::Katalog Sparepart Link-->
            <li class="nav-item">
              <a href="{{ route('spareparts.index') }}" class="nav-link" title="Katalog Sparepart">
                <i class="bi bi-grid fs-5"></i>
                <span class="d-none d-md-inline">Katalog Sparepart</span>
              </a>
            </li>
            <!--end::Katalog Sparepart Link-->
            
            <!--begin::Cart Link-->
            <li class="nav-item">
              <a href="{{ route('cart.index') }}" class="nav-link position-relative me-2" title="Keranjang Sparepart">
                <i class="bi bi-cart3 fs-5"></i>
                <span class="d-none d-md-inline">Keranjang</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartItemCount > 0 ? '' : 'd-none' }}" id="navCartBadge">
                  {{ $cartItemCount }}
                </span>
              </a>
            </li>
            <!--end::Cart Link-->

            <!--begin::Orders Link-->
            <li class="nav-item">
              <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" title="Status Pesanan">
                <i class="bi bi-bag-check fs-5"></i>
                <span class="d-none d-md-inline">Pesanan</span>
              </a>
            </li>
            <!--end::Orders Link-->
            

            @auth
              @if(auth()->user()->isAdmin())
                <!--begin::Admin Shortcut Link-->
                <li class="nav-item me-2">
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 mt-1" title="Masuk ke Panel Administrator">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Panel Admin</span>
                  </a>
                </li>
                <!--end::Admin Shortcut Link-->
              @endif

              <!--begin::User Menu Dropdown-->
              <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; font-weight: 600; font-size: 14px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
                  <span class="d-none d-md-inline fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0">
                  <!--begin::User Header-->
                  <li class="user-header text-bg-primary p-3 text-center">
                    <div class="rounded-circle bg-white text-primary mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; font-weight: 700; font-size: 24px;">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <p class="mb-1 fw-bold fs-6">
                      {{ Auth::user()->name }}
                    </p>
                    <small class="d-block text-white-50">{{ Auth::user()->email }}</small>
                    <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-success' }} mt-1 px-2 py-1">
                      <i class="bi {{ Auth::user()->isAdmin() ? 'bi-shield-check' : 'bi-bag' }} me-1"></i>
                      {{ Auth::user()->isAdmin() ? 'Administrator' : 'Pembeli' }}
                    </span>
                  </li>
                  <!--end::User Header-->

                  <!--begin::Menu Body-->
                  <li class="user-body p-3">
                    @if(Auth::user()->isAdmin())
                      <a href="{{ route('admin.dashboard') }}" class="dropdown-item py-2 rounded text-danger fw-semibold">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                      </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="dropdown-item py-2 rounded">
                      <i class="bi bi-person-gear me-2 text-secondary"></i> Pengaturan Profil
                    </a>
                  </li>
                  <!--end::Menu Body-->

                  <!--begin::Menu Footer-->
                  <li class="user-footer d-flex justify-content-between p-3 bg-light">
                    <!-- <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-person me-1"></i> Profil
                    </a> -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                      </button>
                    </form>
                  </li>
                  <!--end::Menu Footer-->
                </ul>
              </li>
              <!--end::User Menu Dropdown-->
            @else
              <!--begin::Guest Links-->
              <li class="nav-item d-flex align-items-center gap-2 ms-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                  <i class="bi bi-person-plus me-1"></i> Daftar
                </a>
              </li>
              <!--end::Guest Links-->
            @endauth
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->