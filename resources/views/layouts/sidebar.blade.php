      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('dist/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">

            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard Admin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags-fill"></i>
                  <p>Data Kategori</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.model.index') }}" class="nav-link {{ request()->routeIs('admin.model.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags-fill"></i>
                  <p>Data Model</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.tahun.index') }}" class="nav-link {{ request()->routeIs('admin.tahun.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags-fill"></i>
                  <p>Data Tahun</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.volume_mesin.index') }}" class="nav-link {{ request()->routeIs('admin.volume_mesin.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-cpu-fill"></i>
                  <p>Data Volume Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.tipe_kendaraan.index') }}" class="nav-link {{ request()->routeIs('admin.tipe_kendaraan.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-cpu-fill"></i>
                  <p>Data Tipe Kendaraan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.volume_mesin.index') }}" class="nav-link {{ request()->routeIs('admin.volume_mesin.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-cpu-fill"></i>
                  <p>Data Volume Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('spareparts.index') }}" class="nav-link {{ request()->routeIs('spareparts.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Catalog Sparepart</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->