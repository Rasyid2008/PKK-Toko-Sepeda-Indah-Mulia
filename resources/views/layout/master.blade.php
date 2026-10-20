<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  {{-- data-theme="theme-default"
  data-assets-path="{{ asset('/') }}" --}}
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title')- Admin</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/bike.svg') }}" />

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">

    <!-- Tempat CSS Tambahan Halaman Modular -->
    @stack('styles')

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('js/config.js') }}"></script>
    
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="bx bx-cycling demo menu-text fw-bolder ms-2">Toko Sepeda Indah Mulia</span>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>
 
           <ul class="menu-inner py-1">
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Menu Utama</span>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('penjualan.create', 'penjualan.store') ? 'active' : '' }}">
              <a href="{{ route('penjualan.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Penjualan</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('penjualan.index') ? 'active' : '' }}">
              <a href="{{ route('penjualan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Pesanan Masuk</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('stok.*') ? 'active' : '' }}">
              <a href="{{ route('stok.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Stok Sepeda</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Kelola Toko</span>
            </li>
            <li class="menu-item {{ request()->routeIs('sepeda.*') ? 'active' : '' }}">
              <a href="{{ route('sepeda.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cycling"></i>
                <div>Data Sepeda</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
              <a href="{{ route('kategori.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Kategori</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
              <a href="{{ route('laporan.stok') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Laporan stok</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
              <a href="{{ route('laporan.penjualan') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Laporan Penjualan</div>
              </a>
            </li>
          </ul>
        </aside>
      
        <div class="layout-page">

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

              <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- User -->
  <li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar avatar-online">
      <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('img/avatars/1.png') }}" alt="Foto profil {{ Auth::user()->name }}" class="w-px-40 h-auto rounded-circle" />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="{{ route('profile.edit') }}">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar avatar-online">
              <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('img/avatars/1.png') }}" alt="Foto profil {{ Auth::user()->name }}" class="w-px-40 h-auto rounded-circle" />
            </div>
          </div>
          <div class="flex-grow-1">
            <!-- Menampilkan nama admin yang sedang login secara dinamis -->
            <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'Petugas Kasir' }}</span>
            <small class="text-muted">Admin Toko</small>
          </div>
        </div>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('profile.edit') }}">
        <i class="bx bx-user me-2"></i>
        <span class="align-middle">Profile</span>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <!-- Form Log Out Resmi menggunakan metode POST yang aman -->
      <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
          @csrf
      </form>
      <a class="dropdown-item text-danger" href="javascript:void(0);"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bx bx-power-off me-2"></i>
        <span class="align-middle">Log Out</span>
      </a>
    </li>
  </ul>
</li>
<!--/ User -->
</ul>
</div>
</nav>
<!-- Content wrapper -->
<div class="content-wrapper">
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
 @yield('content')
</div>
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

   

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('js/dashboards-analytics.js') }}"></script>

    <!-- Tempat Script Tambahan Halaman Anak -->
    @stack('scripts')

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js') }}"></script>
  </body>
</html>