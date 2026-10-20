@extends('layout.master')

@section('title', 'Manajemen Kategori Sepeda')

@section('content')
<div class="row">
    <!-- Form Tambah Kategori -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white"><i class="bx bx-plus me-1"></i> Tambah Kategori</h5>
            </div>
            <div class="card-body pt-3">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="nama_kategori">Nama Kategori</label>
                        <input type="text" id="nama_kategori" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Contoh: Sepeda Gunung (MTB)" required />
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bx bx-save me-1"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-md-8 mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="table-kategori">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Koleksi Sepeda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoris as $index => $kategori)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                                    <td><span class="badge bg-label-info">{{ $kategori->sepeda_count }} Model</span></td>
                                    <td>
                                        <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" onsubmit="return confirm('Menghapus kategori akan mengosongkan kategori terkait pada data sepeda. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-outline-danger btn-sm">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#table-kategori').DataTable({
            "language": { "url": "https://datatables.net" },
            "pageLength": 5
        });
    });
</script>
@endpush
