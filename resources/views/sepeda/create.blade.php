<@extends('layout.master')

@section('title', 'Tambah Sepeda Baru')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary rounded-top">
                <h5 class="mb-0 text-white"><i class="bx bx-plus-circle me-1"></i> Tambah Sepeda Baru</h5>
                <small class="text-white float-end">Katalog Toko</small>
            </div>
            <div class="card-body pt-4">
                <!-- Tampilkan Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('sepeda.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <!-- Merk Sepeda -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="merk_sepeda">Merk Sepeda <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                <input type="text" id="merk_sepeda" name="merk_sepeda" class="form-control" placeholder="Contoh: Polygon, United, Exotic" value="{{ old('merk_sepeda') }}" required />
                            </div>
                        </div>

                        <!-- Tipe Sepeda -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="tipe_sepeda">Tipe / Seri Sepeda</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-wrench"></i></span>
                                <input type="text" id="tipe_sepeda" name="tipe_sepeda" class="form-control" placeholder="Contoh: Xtrada 5, Monarch 4" value="{{ old('tipe_sepeda') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Pilih Kategori -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="id_kategori">Kategori <span class="text-danger">*</span></label>
                            <select name="id_kategori" id="id_kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stok Awal -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="stok_awal">Stok Gudang Awal <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-package"></i></span>
                                <input type="number" id="stok_awal" name="stok_awal" class="form-control" placeholder="0" min="0" value="{{ old('stok_awal', 0) }}" required />
                            </div>
                        </div>
                    </div>

                    <!-- Gambar Sepeda -->
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="gambar">Gambar Sepeda</label>
                        <input type="file" id="gambar" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        <small class="form-text text-muted">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="harga_jual">Harga Jual (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="harga_jual" name="harga_jual" class="form-control" placeholder="0" min="0" value="{{ old('harga_jual') }}" required />
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('sepeda.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i> Simpan Data Sepeda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection