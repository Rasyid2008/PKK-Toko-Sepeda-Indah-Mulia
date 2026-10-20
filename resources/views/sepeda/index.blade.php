@extends('layout.master')

@section('title', 'Daftar Master Data Sepeda')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-cycling me-1"></i> Data Master Katalog Sepeda</h5>
                <a href="{{ route('sepeda.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> Tambah Sepeda Baru
                </a>
            </div>
            
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="table-sepeda">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Merk Sepeda</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Status Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sepedas as $index => $sepeda)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($sepeda->gambar)
                                            <img src="{{ asset('storage/' . $sepeda->gambar) }}" alt="Gambar {{ $sepeda->merk_sepeda }}" class="rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><strong>{{ $sepeda->merk_sepeda }}</strong></td>
                                    <td>{{ $sepeda->tipe_sepeda ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-label-secondary">
                                            {{ $sepeda->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($sepeda->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        @if(($sepeda->stok->jumlah_stok ?? 0) <= 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif(($sepeda->stok->jumlah_stok ?? 0) <= 5)
                                            <span class="badge bg-warning text-dark">{{ $sepeda->stok->jumlah_stok }} Unit</span>
                                        @else
                                            <span class="badge bg-success">{{ $sepeda->stok->jumlah_stok }} Unit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('sepeda.edit', $sepeda->id_sepeda) }}" class="btn btn-icon btn-outline-warning btn-sm">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <form action="{{ route('sepeda.destroy', $sepeda->id_sepeda) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sepeda ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-outline-danger btn-sm">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
        $('#table-sepeda').DataTable({
            "language": {
                "url": "https://datatables.net"
            },
            "pageLength": 10,
            "order": [[0, "asc"]]
        });
    });
</script>
@endpush