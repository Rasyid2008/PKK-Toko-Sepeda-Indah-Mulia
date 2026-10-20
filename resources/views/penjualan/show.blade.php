@extends('layout.master')

@section('title', 'Nota Transaksi #TRX-' . str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Area Nota yang Akan Dicetak -->
        <div class="card p-4" id="print-area">
            <!-- Header Nota -->
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-1 text-primary">
                        <i class="bx bx-cycling me-2 fs-3 align-middle"></i>Toko Sepeda Indah Mulia
                    </h4>
                    <p class="text-muted mb-0 small">Gereja GPIB efata, Jln raya Batujajar timur no
                                205,depan, Batujajar Tim., Kec. Batujajar, Kabupaten Bandung Barat, Jawa Barat 40561</p>
                    <p class="text-muted mb-0 small">WhatsApp: +62 819-1521-5927</p>
                </div>
                <div class="text-sm-end">
                    <h5 class="fw-bold text-uppercase mb-1">Nota Transaksi</h5>
                    <h6 class="text-danger mb-1">#TRX-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</h6>
                    <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}</small>
                </div>
            </div>

            <!-- Informasi Pelanggan & Petugas -->
            <div class="row mb-4">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <h6 class="text-secondary fw-bold mb-2">Ditujukan Kepada:</h6>
                    <p class="fw-bold mb-1">{{ $penjualan->nama_pembeli ?: 'Pembeli Umum' }}</p>
                    @if($penjualan->telepon_pembeli || $penjualan->alamat_pembeli)
                        <p class="text-muted mb-1 small"><i class="bx bx-phone me-1"></i> {{ $penjualan->telepon_pembeli ?: '-' }}</p>
                        <p class="text-muted mb-0 small"><i class="bx bx-map me-1"></i> {{ $penjualan->alamat_pembeli ?: '-' }}</p>
                    @else
                        <p class="text-muted mb-0 small">Pembeli umum</p>
                    @endif
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h6 class="text-secondary fw-bold mb-2">Petugas Kasir:</h6>
                    <p class="fw-bold mb-1">Admin Toko</p>
                    <p class="text-muted mb-0 small">Status Pembayaran:
                        @if($penjualan->status === 'menunggu_pembayaran')
                            <span class="badge bg-label-warning">MENUNGGU PEMBAYARAN</span>
                        @elseif($penjualan->status === 'dibatalkan')
                            <span class="badge bg-label-danger">DIBATALKAN</span>
                        @else
                            <span class="badge bg-label-success">LUNAS</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Tabel Daftar Item Sepeda -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 50%;">Item Sepeda & Spesifikasi</th>
                            <th class="text-end" style="width: 15%;">Harga Satuan</th>
                            <th class="text-center" style="width: 10%;">Jumlah</th>
                            <th class="text-end" style="width: 20%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualan as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($detail->sepeda)
                                        <h6 class="mb-0 fw-bold">{{ $detail->sepeda->merk_sepeda }}</h6>
                                        <small class="text-muted">{{ $detail->sepeda->tipe_sepeda ?? '-' }} ({{ $detail->sepeda->kategori?->nama_kategori ?? 'Umum' }})</small>
                                    @else
                                        <span class="text-muted italic">Produk telah dihapus dari sistem</span>
                                    @endif
                                </td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal / $detail->jumlah_beli, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->jumlah_beli }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">Subtotal:</td>
                            <td class="text-end">Rp {{ number_format($penjualan->detailPenjualan->sum('subtotal'), 0, ',', '.') }}</td>
                        </tr>
                        @if($penjualan->discount_amount > 0)
                            <tr>
                                <td colspan="4" class="text-end text-danger">
                                    Potongan{{ $penjualan->discount_description ? ' - ' . $penjualan->discount_description : '' }}:
                                </td>
                                <td class="text-end text-danger">- Rp {{ number_format($penjualan->discount_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold py-3 fs-5 text-secondary">Total Bayar:</td>
                            <td class="text-end fw-bold py-3 fs-5 text-primary">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Keterangan Tambahan Struk -->
            <div class="text-center mt-3 pt-3 border-top">
                <p class="mb-1 fw-semibold text-secondary">Terima Kasih Atas Kunjungan & Pembelian Anda!</p>
                <p class="text-muted small mb-0">Sepeda yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian garansi pabrik.</p>
            </div>
        </div>

        <!-- Tombol Kontrol Cetak/Kembali (Tidak akan ikut tercetak) -->
        <div class="d-flex justify-content-between align-items-center mt-4 no-print">
            <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Riwayat
            </a>
            <button type="button" class="btn btn-primary px-4" onclick="window.print()">
                <i class="bx bx-printer me-1"></i> Cetak Struk / Nota
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mengamankan tampilan print agar tombol navigasi dan menu sidebar bawaan template menghilang saat cetak */
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .no-print, .layout-menu, .layout-navbar, .content-footer {
            display: none !important;
        }
        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endpush
