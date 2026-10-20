@extends('layout.master')

@section('title', 'Riwayat Transaksi Penjualan')

@push('styles')
<link rel="stylesheet" href="https://datatables.net">
<style>
    .dataTables_filter { text-align: right; }
    .dataTables_filter label { display: inline-flex; align-items: center; gap: 0.5rem; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-3">
                <h5 class="mb-0 fw-bold"><i class="bx bx-file me-1"></i> Riwayat Transaksi Penjualan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->has('status'))
                    <div class="alert alert-danger">{{ $errors->first('status') }}</div>
                @endif
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-striped align-middle w-100" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th>No Transaksi</th>
                                <th>Tanggal & Waktu</th>
                                <th>Nama Pembeli</th>
                                <th>WhatsApp</th>
                                <th>Potongan</th>
                                <th>Total Pembayaran</th>
                                <th>Status</th>
                                <th class="no-export">Aksi</th>
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
                                    <td>{{ $penjualan->telepon_pembeli ?: '-' }}</td>
                                    <td>
                                        @if($penjualan->discount_amount > 0)
                                            <span class="text-danger">Rp {{ number_format($penjualan->discount_amount, 0, ',', '.') }}</span>
                                            <br><small class="text-muted">{{ $penjualan->discount_description }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if($penjualan->status === 'menunggu_pembayaran')
                                            <span class="badge bg-label-warning">Menunggu pembayaran</span>
                                        @elseif($penjualan->status === 'dibatalkan')
                                            <span class="badge bg-label-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-label-success">Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bx bx-show-alt me-1"></i> Rincian
                                            </a>
                                            @if($penjualan->status === 'menunggu_pembayaran')
                                                <a href="{{ route('penjualan.edit', $penjualan->id_penjualan) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bx bx-purchase-tag me-1"></i> Atur Potongan
                                                </a>
                                                <form action="{{ route('penjualan.konfirmasi', $penjualan->id_penjualan) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran dan kurangi stok?')">
                                                        <i class="bx bx-check me-1"></i> Konfirmasi
                                                    </button>
                                                </form>
                                                <form action="{{ route('penjualan.batalkan', $penjualan->id_penjualan) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan pesanan ini?')">
                                                        <i class="bx bx-x me-1"></i> Batal
                                                    </button>
                                                </form>
                                            @endif
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script>
    // Menggunakan fungsi inisialisasi aman untuk menghindari conflict library bawaan tema
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#historyTable')) {
            $('#historyTable').DataTable().destroy();
        }

        $('#historyTable').DataTable({
            dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '📋 Copy',
                    className: 'btn btn-secondary btn-sm me-1',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'excelHtml5',
                    text: '📊 Excel (.xlsx)',
                    className: 'btn btn-success btn-sm me-1',
                    title: 'Laporan_Transaksi_Penjualan',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'csvHtml5',
                    text: '📄 CSV',
                    className: 'btn btn-info btn-sm text-white me-1',
                    title: 'Laporan_Transaksi_Penjualan',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'pdfHtml5',
                    text: '📕 PDF',
                    className: 'btn btn-danger btn-sm me-1',
                    title: 'Laporan Transaksi Penjualan',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'print',
                    text: '🖨️ Print',
                    className: 'btn btn-dark btn-sm me-1',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'colvis',
                    text: '👁️ Kolom',
                    className: 'btn btn-warning btn-sm text-dark me-1'
                },
                {
                    text: '{ } JSON',
                    className: 'btn btn-primary btn-sm me-1',
                    action: function (e, dt, node, config) {
                        var data = dt.buttons.exportData({ columns: ':not(.no-export)' });
                        var jsonString = JSON.stringify(data, null, 2);
                        var blob = new Blob([jsonString], { type: "application/json" });
                        var link = document.createElement("a");
                        link.href = URL.createObjectURL(blob);
                        link.download = "Laporan_Transaksi_" + new Date().toISOString().slice(0,10) + ".json";
                        link.click();
                    }
                }
            ],
            language: {
                search: "Cari Data Transaksi:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            },
            pageLength: 10,
            order: [[0, "desc"]]
        });
    }

    // Memastikan skrip dieksekusi setelah seluruh DOM dan script template siap
    if (window.jQuery) {
        $(document).ready(function() {
            initDataTable();
        });
    } else {
        window.addEventListener('DOMContentLoaded', function() {
            initDataTable();
        });
    }
</script>
@endpush
