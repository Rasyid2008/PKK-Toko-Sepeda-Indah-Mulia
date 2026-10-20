@extends('layout.master')

@section('title', 'Kontrol Stok Sepeda')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-package me-1"></i> Penyesuaian Kuantiti Stok Sepeda</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="table-stok">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Produk Sepeda</th>
                                <th>Kategori</th>
                                <th>Sisa Stok Fisik</th>
                                <th>Perbarui Kuantiti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stoks as $index => $stok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $stok->sepeda->merk_sepeda }}</strong> <small class="text-muted">{{ $stok->sepeda->tipe_sepeda }}</small></td>
                                    <td>{{ $stok->sepeda->kategori->nama_kategori ?? 'Umum' }}</td>
                                    <td>
                                        @if($stok->jumlah_stok <= 0)
                                            <span class="badge bg-label-danger">Kosong / Habis</span>
                                        @elseif($stok->jumlah_stok <= 5)
                                            <span class="badge bg-label-warning text-dark">Sisa Kritis ({{ $stok->jumlah_stok }})</span>
                                        @else
                                            <span class="badge bg-label-success">Tersedia ({{ $stok->jumlah_stok }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Form Update Cepat -->
                                        <form action="{{ route('stok.update', $stok->id_stok) }}" method="POST" class="d-flex align-items-center gap-2" style="max-width: 200px;">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="jumlah_stok" class="form-control form-control-sm text-center" value="{{ $stok->jumlah_stok }}" min="0" required>
                                            <button type="submit" class="btn btn-sm btn-icon btn-primary" title="Update Stok">
                                                <i class="bx bx-refresh"></i>
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
        $('#table-stok').DataTable({
            "language": { "url": "https://datatables.net" },
            "pageLength": 10
        });
    });
</script>
@endpush
