@extends('layout.master')

@section('title', 'Atur Potongan Pesanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header bg-primary rounded-top">
                <h5 class="mb-0 text-white">
                    <i class="bx bx-purchase-tag me-1"></i>
                    Atur Potongan Pesanan #TRX-{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}
                </h5>
            </div>
            <div class="card-body pt-4">
                <div class="mb-4">
                    <p class="mb-1"><strong>Nama pembeli:</strong> {{ $penjualan->nama_pembeli }}</p>
                    <p class="mb-2"><strong>Subtotal:</strong> Rp {{ number_format($penjualan->detailPenjualan->sum('subtotal'), 0, ',', '.') }}</p>
                    <ul class="mb-0">
                        @foreach($penjualan->detailPenjualan as $detail)
                            <li>
                                {{ $detail->sepeda?->merk_sepeda ?? 'Sepeda dihapus' }}
                                ({{ $detail->jumlah_beli }} unit):
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="discount_amount">Potongan (Rupiah)</label>
                            <input
                                type="number"
                                name="discount_amount"
                                id="discount_amount"
                                class="form-control @error('discount_amount') is-invalid @enderror"
                                min="0"
                                step="1"
                                max="{{ $penjualan->detailPenjualan->sum('subtotal') }}"
                                value="{{ old('discount_amount', $penjualan->discount_amount) }}"
                                required
                            >
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Potongan tidak boleh melebihi subtotal pesanan.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="discount_description">Deskripsi Potongan</label>
                            <input
                                type="text"
                                name="discount_description"
                                id="discount_description"
                                class="form-control @error('discount_description') is-invalid @enderror"
                                value="{{ old('discount_description', $penjualan->discount_description) }}"
                                maxlength="255"
                                placeholder="Contoh: Promo akhir bulan"
                            >
                            @error('discount_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <div class="text-end">
                            <div>Perkiraan total bayar:
                                <strong id="discounted-total">
                                    Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                                </strong>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="bx bx-save me-1"></i> Simpan Potongan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const subtotalPesanan = {{ (int) $penjualan->detailPenjualan->sum('subtotal') }};
    const potonganInput = document.getElementById('discount_amount');
    const totalBayar = document.getElementById('discounted-total');

    potonganInput.addEventListener('input', function () {
        const potongan = Math.max(0, parseInt(this.value, 10) || 0);
        totalBayar.textContent = 'Rp ' + Math.max(0, subtotalPesanan - potongan).toLocaleString('id-ID');
    });
</script>
@endpush
