<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Toko Sepeda Indah Mulia</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon/bike.svg') }}">

    <meta name="description" content="Landing Page Toko Sepeda Indah Mulia" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com" />
    <link rel="preconnect" href="https://gstatic.com" crossorigin />
    <link
        href="https://googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons Bawaan Template Sneat -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS Bawaan Template Sneat -->
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">

    @stack('styles')

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers & Config -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>

</head>

<body>

    <!-- 1. NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary-custom fs-4" href="#">
                <i class="bx bx-cycling me-2 fs-3 align-middle"></i>Toko Sepeda Indah Mulia
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#keunggulan">Keunggulan</a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#katalog">Katalog Terlaris</a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#kontak">Hubungi Kami</a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-primary btn-sm px-3" href="{{ route('dashboard') }}">
                            <i class="bx bx-lock-alt me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-label-primary px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">Gaya Hidup Sehat
                        & Keren</span>
                    <h1 class="display-5 fw-bold mb-3 text-dark">Temukan Sepeda Terbaik Untuk Petualanganmu!</h1>
                    <p class="lead text-secondary mb-4">Kami menyediakan berbagai macam tipe sepeda berkualitas tinggi
                        mulai dari Sepeda Gunung (MTB), Sepeda Lipat, hingga sepeda anak dengan harga bersahabat dan
                        garansi resmi.</p>
                    <div class="d-flex gap-3">
                        <a href="#katalog" class="btn btn-primary-custom btn-lg px-4 shadow"><i
                                class="bx bx-shopping-bag me-2"></i> Lihat Produk</a>
                        <a href="#kontak" class="btn btn-outline-secondary btn-lg px-4"><i class="bx bx-phone me-2"></i>
                            Hubungi Toko</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bx bx-cycling text-primary-custom" style="font-size: 15rem; opacity: 0.85;"></i>
                </div>
            </div>
        </div>
    </section>
    <!-- 3. KEUNGGULAN TOKO -->
    <section id="keunggulan" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Mengapa Harus Memilih Toko Sepeda Indah Mulia?</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Kami berkomitmen memberikan pelayanan
                    terbaik dan jaminan produk orisinal demi kepuasan bersepeda Anda.</p>
            </div>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm h-100">
                        <div class="feature-icon"><i class="bx bx-shield-quarter"></i></div>
                        <h5 class="fw-bold text-dark">Produk 100% Original</h5>
                        <p class="text-secondary small mb-0">Semua unit sepeda yang kami sediakan datang langsung dari
                            pabrikan resmi dengan garansi rangka asli.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm h-100">
                        <div class="feature-icon"><i class="bx bx-wrench"></i></div>
                        <h5 class="fw-bold text-dark">Gratis Servis Berkala</h5>
                        <p class="text-secondary small mb-0">Setiap pembelian sepeda baru di toko kami mendapatkan
                            kupon servis perawatan gratis selama 1 bulan pertama.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm h-100">
                        <div class="feature-icon"><i class="bx bx-credit-card-front"></i></div>
                        <h5 class="fw-bold text-dark">Metode Pembayaran Luas</h5>
                        <p class="text-secondary small mb-0">Menerima pembayaran tunai, transfer antarbank.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. ETALASE KATALOG TERLARIS -->
    <section id="katalog" class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Katalog Sepeda Unggulan Kami</h2>
                <p class="text-secondary">Produk sepeda pilihan yang paling banyak dicari saat ini.</p>
            </div>
            <div class="row g-4">
                @forelse($sepedas as $sepeda)
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center pt-4 bg-white rounded-top">
                                @if($sepeda->gambar)
                                    <img src="{{ asset('storage/' . $sepeda->gambar) }}" alt="Gambar {{ $sepeda->merk_sepeda }}" class="rounded mb-3" style="width: 100%; height: 180px; object-fit: cover;">
                                @else
                                    <i class="bx bx-cycling text-secondary mb-3" style="font-size: 5rem;"></i>
                                @endif
                                <div class="mb-2">
                                    <span
                                        class="badge bg-label-primary px-3 py-2">{{ $sepeda->kategori->nama_kategori ?? 'Katalog Utama' }}</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $sepeda->merk_sepeda }}</h5>
                                <p class="text-muted small mb-3">{{ $sepeda->tipe_sepeda ?? '-' }}</p>
                                <span class="badge bg-label-success">
                                    Stok: {{ $sepeda->stok->jumlah_stok ?? 0 }} Unit
                                </span>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0 pb-4 text-center">
                                <h5 class="text-primary-custom fw-bold mb-3">Rp
                                    {{ number_format($sepeda->harga_jual, 0, ',', '.') }}</h5>
                                <a href="{{ route('pesanan.create', $sepeda) }}"
                                    class="btn btn-primary d-block w-100 py-2 rounded-3 text-white fw-bold">
                                    <i class="bx bxl-whatsapp me-1 fs-5 align-middle"></i> Pesan / Tanya via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Katalog belum diisi oleh admin atau stok sedang kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. KONTAK TOKO -->
    <section id="kontak" class="py-5">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold text-dark mb-3">Kunjungi Toko Kami</h2>
                    <p class="text-secondary mb-4">Ingin melakukan test-drive sepeda pilihan Anda? Datang langsung ke
                        toko fisik kami untuk berkonsultasi mengenai ukuran rangka sepeda yang cocok untuk postur tubuh
                        Anda.</p>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <i class="bx bx-map text-primary-custom fs-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Alamat Toko:</h6>
                            <p class="text-secondary small mb-0">Gereja GPIB efata, Jln raya Batujajar timur no
                                205,depan, Batujajar Tim., Kec. Batujajar, Kabupaten Bandung Barat, Jawa Barat 40561</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <i class="bx bx-time text-primary-custom fs-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Jam Operasional:</h6>
                            <p class="text-secondary small mb-0">Senin - Minggu: 07.30 - 20.00 WIB</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <i class="bx bx-phone text-primary-custom fs-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Kontak Toko:</h6>
                            <p class="text-secondary small mb-0">WhatsApp: +62 819-1521-5927</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="card p-2 shadow-sm border-0">
                        <h4 class="fw-bold text-dark mb-2">Toko Sepeda Indah Mulia</h4>
                        <iframe
                            src="https://www.google.com/maps?q=Toko+Sepeda+Indah+Mulia,@+-6.9142776,107.49746&z=17&output=embed"
                            width="100%" height="320" style="border: 0; border-radius: 6px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-4 border-top">
        <div class="container text-center">
            <p class="mb-0 small">&copy; {{ date('Y') }} Toko Sepeda Indah Mulia. All rights reserved.</p>
        </div>
    </footer>

    <!-- Core JS Bawaan Sneat -->
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
</body>

</html>
