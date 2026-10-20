@extends('layout.master')

@section('title', 'Laporan Penjualan')

@push('styles')
<!-- 1. DataTables Buttons CSS Integration (Bootstrap 5) sesuai Modul -->
<link rel="stylesheet" href="https://datatables.net">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Form Filter Periode Transaksi -->
        <div class="card mb-4">
            <div class="card-header bg-primary py-3">
                <h5 class="mb-0 text-white"><i class="bx bx-file me-1"></i> Laporan Penjualan</h5>
            </div>
            <div class="card-body pt-4">
                <form action="{{ route('laporan.penjualan') }}" method="GET" class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggal_mulai }}">
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggal_selesai }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search me-1"></i> Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Statistik Laporan -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card bg-label-primary border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-1 text-primary fw-bold text-uppercase">Total Transaksi</h6>
                        <h3 class="mb-0 text-primary fw-bold">{{ $total_transaksi }} Transaksi</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-label-success border-0 h-100">
                    <div class="card-body">
                        <h6 class="mb-1 text-success fw-bold text-uppercase">Total Penjualan</h6>
                        <h3 class="mb-0 text-success fw-bold">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Transaksi Penjualan -->
        <div class="card">
            <div class="card-header border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-receipt me-1"></i> Daftar Transaksi Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="penjualanTable">
                        <thead class="table-light">
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal & Waktu</th>
                                <th>Nama Pembeli</th>
                                <th>Status</th>
                                <th>Jumlah Item</th>
                                <th>Potongan</th>
                                <th>Total Bayar</th>
                                <th class="no-export">Aksi</th> <!-- Class no-export disematkan agar tidak ikut terunduh -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualans as $penjualan)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">#TRX-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}</td>
                                    <td>
                                        <i class="bx bx-user me-1 text-muted"></i>
                                        {{ $penjualan->nama_pembeli }}
                                    </td>
                                    <td><span class="badge bg-label-success">Lunas</span></td>
                                    <td>{{ $penjualan->detailPenjualan->sum('jumlah_beli') }} Unit</td>
                                    <td>
                                        @if($penjualan->discount_amount > 0)
                                            <span class="text-danger">Rp {{ number_format($penjualan->discount_amount, 0, ',', '.') }}</span>
                                            <br><small class="text-muted">{{ $penjualan->discount_description }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="fw-bold text-success">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bx bx-show-alt me-1"></i> Rincian Nota
                                        </a>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        $('#penjualanTable').DataTable({
            // Pengaturan Layout (dom) untuk Menampilkan Tombol di atas Tabel selaras Modul
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"btn-group"B><"search-box"f>>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '📋 Copy',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: { columns: ':not(.no-export)' } // Mencegah kolom aksi/tombol nota ikut tersalin
                },
                {
                    extend: 'excelHtml5',
                    text: '📊 Excel (.xlsx)',
                    className: 'btn btn-success btn-sm',
                    title: 'Laporan_Transaksi_Penjualan_Toko_Sepeda',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'csvHtml5',
                    text: '📄 CSV',
                    className: 'btn btn-info btn-sm text-white',
                    title: 'Laporan_Transaksi_Penjualan_Toko_Sepeda',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'pdfHtml5',
                    text: '📕 PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Laporan Transaksi Penjualan - Toko Sepeda Indah Mulia',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'print',
                    text: '🖨️ Print',
                    className: 'btn btn-dark btn-sm',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'colvis',
                    text: '👁️ Visibilitas Kolom',
                    className: 'btn btn-warning btn-sm text-dark'
                },
                // Custom Button untuk Ekspor JSON Format Native sesuai Diktat
                {
                    text: '{ } JSON',
                    className: 'btn btn-primary btn-sm',
                    action: function (e, dt, node, config) {
                        var data = dt.buttons.exportData({ columns: ':not(.no-export)' });
                        var jsonString = JSON.stringify(data, null, 2);
                        
                        // Buat file blob JSON & trigger download otomatis
                        var blob = new Blob([jsonString], { type: "application/json" });
                        var link = document.createElement("a");
                        link.href = URL.createObjectURL(blob);
                        link.download = "Laporan_Penjualan_" + new Date().toISOString().slice(0,10) + ".json";
                        link.click();
                    }
                }
            ],
            language: {
                search: "Cari Data Transaksi:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                emptyTable: "Belum ada transaksi pada periode ini.",
                paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
            },
            pageLength: 10,
            order: [[1, 'desc']] // Mengurutkan otomatis dari transaksi terbaru
        });
    });
</script>
@endpush
