@extends('layout.master')

@section('title', 'Laporan Stok Sepeda')

@push('styles')
<!-- 1. DataTables Buttons CSS Integration (Bootstrap 5) sesuai Modul -->
<link rel="stylesheet" href="https://datatables.net">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Histori Mutasi Stok -->
        <div class="card mb-4">
            <div class="card-header bg-primary py-3">
                <h5 class="mb-0 text-white"><i class="bx bx-filter-alt me-1"></i> Filter Histori Mutasi Stok</h5>
            </div>
            <div class="card-body pt-4">
                <form action="{{ route('laporan.stok') }}" method="GET" class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggal_mulai }}">
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggal_selesai }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Kuantitas Aset -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-label-primary border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-primary fw-bold text-uppercase">Total Stok Saat Ini</h6>
                        <h3 class="mb-0 text-primary fw-bold">{{ $total_stok }} Unit</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-label-success border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-success fw-bold text-uppercase">Stok Masuk Periode Ini</h6>
                        <h3 class="mb-0 text-success fw-bold">{{ $total_masuk }} Unit</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-label-danger border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-danger fw-bold text-uppercase">Stok Keluar Periode Ini</h6>
                        <h3 class="mb-0 text-danger fw-bold">{{ $total_keluar }} Unit</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel 1: Stok Terkini Per Sepeda -->
        <div class="card mb-4">
            <div class="card-header border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-package me-1"></i> Stok Terkini Per Sepeda</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="stokTerkiniTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Produk Sepeda</th>
                                <th>Kategori</th>
                                <th>Jumlah Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stoks as $index => $stok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $stok->sepeda->merk_sepeda ?? '-' }}</strong>
                                        <small class="d-block text-muted">{{ $stok->sepeda->tipe_sepeda ?? '-' }}</small>
                                    </td>
                                    <td>{{ $stok->sepeda->kategori->nama_kategori ?? 'Umum' }}</td>
                                    <td class="fw-bold">{{ $stok->jumlah_stok }} Unit</td>
                                    <td>
                                        @if($stok->jumlah_stok <= 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($stok->jumlah_stok <= 5)
                                            <span class="badge bg-warning text-dark">Kritis</span>
                                        @else
                                            <span class="badge bg-success">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel 2: Histori Mutasi Stok Keluar Masuk -->
        <div class="card">
            <div class="card-header border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-transfer me-1"></i> Histori Mutasi Stok</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="mutasiStokTable">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu / Tanggal</th>
                                <th>Produk Sepeda</th>
                                <th>Jenis Pergerakan</th>
                                <th>Jumlah</th>
                                <th>Keterangan Alur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mutasis as $mutasi)
                                <tr>
                                    <td>{{ $mutasi->created_at->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ $mutasi->sepeda->merk_sepeda ?? '-' }}</strong> <small class="text-muted">{{ $mutasi->sepeda->tipe_sepeda ?? '' }}</small></td>
                                    <td>
                                        @if($mutasi->jenis_mutasi === 'masuk')
                                            <span class="badge bg-success">MASUK</span>
                                        @else
                                            <span class="badge bg-danger">KELUAR</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold {{ $mutasi->jenis_mutasi === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $mutasi->jenis_mutasi === 'masuk' ? '+' : '-' }} {{ $mutasi->jumlah }} Unit
                                    </td>
                                    <td><small class="text-muted">{{ $mutasi->keterangan ?? '-' }}</small></td>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function () {
        // Konfigurasi Tabel Kuantitas Terkini
        $('#stokTerkiniTable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"btn-group"B><"search-box"f>>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            buttons: [
                { extend: 'copyHtml5', text: '📋 Copy', className: 'btn btn-secondary btn-sm' },
                { extend: 'excelHtml5', text: '📊 Excel (.xlsx)', className: 'btn btn-success btn-sm', title: 'Laporan_Stok_Terkini_Sepeda' },
                { extend: 'pdfHtml5', text: '📕 PDF', className: 'btn btn-danger btn-sm', title: 'Laporan Kuantitas Stok Terkini', orientation: 'portrait', pageSize: 'A4' },
                { extend: 'print', text: '🖨️ Print', className: 'btn btn-dark btn-sm' }
            ],
            language: {
                search: 'Cari Produk:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' }
            },
            pageLength: 5,
            order: [[3, 'asc']] // Urutkan stok paling sedikit/kritis ke atas
        });

        // Konfigurasi Tabel Histori Mutasi Keluar Masuk Barang
        $('#mutasiStokTable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"btn-group"B><"search-box"f>>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            buttons: [
                { extend: 'copyHtml5', text: '📋 Copy', className: 'btn btn-secondary btn-sm' },
                { extend: 'excelHtml5', text: '📊 Excel (.xlsx)', className: 'btn btn-success btn-sm', title: 'Histori_Mutasi_Stok_Periode' },
                { extend: 'csvHtml5', text: '📄 CSV', className: 'btn btn-info btn-sm text-white', title: 'Histori_Mutasi_Stok_Periode' },
                { extend: 'pdfHtml5', text: '📕 PDF', className: 'btn btn-danger btn-sm', title: 'Laporan Histori Pergerakan Mutasi Stok', orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', text: '🖨️ Print', className: 'btn btn-dark btn-sm' },
                { extend: 'colvis', text: '👁️ Visibilitas Kolom', className: 'btn btn-warning btn-sm text-dark' },
                // Custom Button untuk Ekspor JSON Native berdasarkan Diktat Praktikum
                {
                    text: '{ } JSON',
                    className: 'btn btn-primary btn-sm',
                    action: function (e, dt, node, config) {
                        var data = dt.buttons.exportData();
                        var jsonString = JSON.stringify(data, null, 2);
                        
                        var blob = new Blob([jsonString], { type: "application/json" });
                        var link = document.createElement("a");
                        link.href = URL.createObjectURL(blob);
                        link.download = "Laporan_Mutasi_" + new Date().toISOString().slice(0,10) + ".json";
                        link.click();
                    }
                }
            ],
            language: {
                search: 'Cari Log Mutasi:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ log pergerakan',
                paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' }
            },
            pageLength: 10,
            order: [[0, 'desc']] // Log mutasi paling baru ditampilkan di atas
        });
    });
</script>
@endpush
