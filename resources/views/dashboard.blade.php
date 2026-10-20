@extends('layout.master')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Baris Ringkasan Metrik Utama -->
    <div class="col-lg-4 col-md-12 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="badge bg-label-primary p-2"><i class="bx bx-cycling fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Jenis Sepeda</span>
                <h3 class="card-title mb-2">{{ $totalSepeda }} Tipe</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="badge bg-label-success p-2"><i class="bx bx-user fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Pelanggan</span>
                <h3 class="card-title mb-2">{{ $totalPelanggan }} Orang</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="badge bg-label-info p-2"><i class="bx bx-wallet fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Pendapatan Hari Ini</span>
                <h3 class="card-title mb-2">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Peringatan Stok Menipis -->
    <div class="col-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between bg-primary text-white pb-3 rounded-top">
                <h5 class="card-title m-0 me-2 text-white"><i class="bx bx-error-circle me-1"></i> Peringatan Stok Menipis (≤ 5 Unit)</h5>
            </div>
            <div class="card-body pt-3">
                @if($stokMenipis->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-check-circle fs-1 text-success mb-2"></i>
                        <p class="mb-0">Semua pasokan stok sepeda aman.</p>
                    </div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Sepeda</th>
                                    <th>Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokMenipis as $stok)
                                    <tr>
                                        <td><strong>{{ $stok->sepeda->merk_sepeda }}</strong> <small class="text-muted">{{ $stok->sepeda->tipe_sepeda }}</small></td>
                                        <td><span class="badge bg-danger">{{ $stok->jumlah_stok }} unit</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
