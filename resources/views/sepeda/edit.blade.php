@extends('layout.master')

@section('title', 'Edit Data Sepeda')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-warning rounded-top">
                <h5 class="mb-0 text-white"><i class="bx bx-edit me-1"></i> Edit Informasi Data Sepeda</h5>
                <small class="text-white float-end">ID Sepeda: #{{ $sepeda->id_sepeda }}</small>
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

                <form action="{{ route('sepeda.update', $sepeda->id_sepeda) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Merk Sepeda -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="merk_sepeda">Merk Sepeda <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                <input type="text" id="merk_sepeda" name="merk_sepeda" class="form-control" value="{{ old('merk_sepeda', $sepeda->merk_sepeda) }}" required />
                            </div>
                        </div>

                        <!-- Tipe Sepeda -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold" for="tipe_sepeda">Tipe / Seri Sepeda</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-wrench"></i></span>
                                <input type="text" id="tipe_sepeda" name="tipe_sepeda" class="form-control" value="{{ old('tipe_sepeda', $sepeda->tipe_sepeda) }}" />
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
                                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $sepeda->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keterangan Informasi Stok Aktif Saat Ini -->
                        <div class="mb-3 col-md-6">
                            <label class="form-label fw-bold d-block">Stok Saat Ini (Gudang)</label>
                            <div class="form-control-plaintext ps-1">
                                <span class="badge bg-label-info fs-6 px-3 py-2">
                                    <i class="bx bx-box me-1"></i> {{ $sepeda->stok->jumlah_stok ?? 0 }} Unit Tersedia
                                </span>
                                <small class="text-muted d-block mt-1">* Catatan: Untuk merubah jumlah stok fisik, silakan akses melalui menu khusus Kelola Stok.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Gambar Sepeda -->
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="gambar">Gambar Sepeda</label>
                        @if($sepeda->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $sepeda->gambar) }}" alt="Gambar {{ $sepeda->merk_sepeda }}" class="rounded border" style="width: 120px; height: 90px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" id="gambar" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        <small class="form-text text-muted">Format JPG, PNG, atau WEBP. Maksimal 2 MB. Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="harga_jual">Harga Jual (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="harga_jual" name="harga_jual" class="form-control" min="0" value="{{ old('harga_jual', intval($sepeda->harga_jual)) }}" required />
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('sepeda.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning text-white px-4">
                            <i class="bx bx-refresh me-1"></i> Perbarui Data Sepeda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
