<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Pesanan - Toko Sepeda Indah Mulia</title>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">
</head>
<body>
    <main class="container py-5" style="max-width: 760px;">
        <a href="{{ url('/#katalog') }}" class="text-muted d-inline-block mb-4">
            <i class="bx bx-arrow-back me-1"></i> Kembali ke katalog
        </a>

        <div class="card shadow-sm">
            <div class="card-header bg-primary py-3">
                <h4 class="mb-0 text-white"><i class="bx bx-shopping-bag me-2"></i>Form Pesanan</h4>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-3 mb-4">
                    <div>
                        @if($sepeda->gambar)
                            <img src="{{ asset('storage/' . $sepeda->gambar) }}" alt="Gambar {{ $sepeda->merk_sepeda }}" class="rounded mb-3" style="max-width: 160px; max-height: 120px; object-fit: cover;">
                        @endif
                        <h5 class="mb-1">{{ $sepeda->merk_sepeda }} {{ $sepeda->tipe_sepeda }}</h5>
                        <span class="text-muted">Stok tersedia: {{ $sepeda->stok->jumlah_stok }} unit</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Harga satuan</small>
                        <strong class="text-primary text-nowrap">Rp {{ number_format($sepeda->harga_jual, 0, ',', '.') }}</strong>
                        <small class="text-muted d-block mt-2">Total pesanan</small>
                        <strong id="total-harga" class="text-success text-nowrap" data-harga="{{ $sepeda->harga_jual }}">Rp {{ number_format($sepeda->harga_jual * max(1, (int) old('jumlah_beli', 1)), 0, ',', '.') }}</strong>
                    </div>
                </div>

                <form action="{{ route('pesanan.store', $sepeda) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_pembeli" class="form-label">Nama</label>
                        <input type="text" id="nama_pembeli" name="nama_pembeli" value="{{ old('nama_pembeli') }}" class="form-control @error('nama_pembeli') is-invalid @enderror" required maxlength="150" autocomplete="name">
                        @error('nama_pembeli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="telepon_pembeli" class="form-label">Nomor WhatsApp</label>
                        <input type="tel" id="telepon_pembeli" name="telepon_pembeli" value="{{ old('telepon_pembeli') }}" class="form-control @error('telepon_pembeli') is-invalid @enderror" required maxlength="20" autocomplete="tel" placeholder="Contoh: 081234567890">
                        @error('telepon_pembeli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="alamat_pembeli" class="form-label">Alamat (Lengkap)</label>
                        <textarea id="alamat_pembeli" name="alamat_pembeli" rows="2" maxlength="1000" class="form-control @error('alamat_pembeli') is-invalid @enderror" autocomplete="street-address">{{ old('alamat_pembeli') }}</textarea>
                        @error('alamat_pembeli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="jumlah_beli" class="form-label">Jumlah</label>
                        <input type="number" id="jumlah_beli" name="jumlah_beli" value="{{ old('jumlah_beli', 1) }}" min="1" max="{{ $sepeda->stok->jumlah_stok }}" class="form-control @error('jumlah_beli') is-invalid @enderror" required>
                        @error('jumlah_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if($errors->has('status'))
                        <div class="alert alert-danger">{{ $errors->first('status') }}</div>
                    @endif
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bx bxl-whatsapp me-1"></i> Simpan Pesanan & Lanjutkan ke WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </main>
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script>
        const inputJumlah = document.getElementById('jumlah_beli');
        const totalHarga = document.getElementById('total-harga');
        const hargaSatuan = Number(totalHarga.dataset.harga);

        function perbaruiTotal() {
            const jumlah = Math.max(0, Number.parseInt(inputJumlah.value, 10) || 0);
            totalHarga.textContent = 'Rp ' + (hargaSatuan * jumlah).toLocaleString('id-ID');
        }

        inputJumlah.addEventListener('input', perbaruiTotal);
        inputJumlah.addEventListener('change', perbaruiTotal);
        perbaruiTotal();
    </script>
</body>
</html>