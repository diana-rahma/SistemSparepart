      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Cart Link-->
            <!-- @php
              $cartSession = session('cart', []);
              $cartItemCount = is_array($cartSession) ? count($cartSession) : 0;
            @endphp
            <li class="nav-item">
              <a href="{{ route('cart.index') }}" class="nav-link position-relative me-2" title="Keranjang Sparepart">
                <i class="bi bi-cart3 fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartItemCount > 0 ? '' : 'd-none' }}" id="navCartBadge">
                  {{ $cartItemCount }}
                </span>
              </a>
            </li> -->
            <!--end::Cart Link-->
            <!--begin::Notifications Dropdown Menu-->
            <!-- <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li> -->
            <!--end::Notifications Dropdown Menu-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; font-weight: 600; font-size: 14px;">
                  {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                </div>
                <span class="d-none d-md-inline fw-semibold">{{ Auth::user()?->name ?? 'Admin' }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0">
                <!--begin::User Image-->
                <li class="user-header text-bg-danger p-3 text-center">
                  <div class="rounded-circle bg-white text-danger mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; font-weight: 700; font-size: 24px;">
                    {{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}
                  </div>
                  <p class="mb-1 fw-bold fs-6">
                    {{ Auth::user()?->name ?? 'Admin' }}
                  </p>
                  <small class="d-block text-white-50">{{ Auth::user()?->email ?? 'admin@inka.co.id' }}</small>
                  <span class="badge bg-light text-danger mt-1 px-2 py-1">
                    <i class="bi bi-shield-lock-fill me-1"></i>Administrator
                  </span>
                </li>
                <!--end::User Image-->

                <!--begin::Menu Body-->
                <li class="user-body p-3">
                  <a href="{{ route('spareparts.index') }}" class="dropdown-item py-2 rounded text-primary">
                    <i class="bi bi-shop me-2"></i> Buka Katalog Pembeli
                  </a>
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
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->