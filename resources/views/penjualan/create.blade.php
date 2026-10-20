@extends('layout.master')

@section('title', 'Transaksi Penjualan Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary rounded-top">
                <h5 class="mb-0 text-white"><i class="bx bx-cart-add me-1"></i> Form Penjualan Kasir</h5>
                <small class="text-white float-end">Aplikasi Toko Sepeda</small>
            </div>
            <div class="card-body pt-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
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

                <form action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

          
                    <h5 class="text-secondary mb-3"><i class="bx bx-user me-1"></i> Informasi Pembeli / Pelanggan</h5>
                    <div class="row">
                        <!-- Nama Pembeli -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="nama_pembeli">Nama Lengkap Pembeli</label>
                            <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control" placeholder="Masukkan nama (Kosongkan jika Pembeli Umum)" value="{{ old('nama_pembeli') }}">
                        </div>
                        <!-- Nomor Telepon -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="telepon_pembeli">Nomor WhatsApp / Telepon</label>
                            <input type="text" name="telepon_pembeli" id="telepon_pembeli" class="form-control" placeholder="Contoh: 08123456xxx" value="{{ old('telepon_pembeli') }}">
                        </div>
                        <!-- Alamat Pembeli -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold" for="alamat_pembeli">Alamat Lengkap Pembeli</label>
                            <textarea name="alamat_pembeli" id="alamat_pembeli" class="form-control" rows="2" placeholder="Masukkan alamat pengiriman atau domisili pembeli...">{{ old('alamat_pembeli') }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Keranjang Dinamis -->
                    <h5 class="text-secondary mb-3"><i class="bx bx-shopping-bag me-1"></i> Keranjang Item Belanja</h5>
                    
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered align-middle" id="cart-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">Pilih Sepeda</th>
                                    <th style="width: 15%;">Harga</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 20%;">Subtotal</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Item dimasukkan via Javascript -->
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="add-item-btn">
                        <i class="bx bx-plus me-1"></i> Tambah Item Sepeda
                    </button>

                    <hr class="my-4">
                    <h5 class="text-secondary mb-3"><i class="bx bx-purchase-tag me-1"></i> Potongan Harga</h5>
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
                                value="{{ old('discount_amount', 0) }}"
                                placeholder="0"
                            >
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="discount_description">Deskripsi Potongan</label>
                            <input
                                type="text"
                                name="discount_description"
                                id="discount_description"
                                class="form-control @error('discount_description') is-invalid @enderror"
                                value="{{ old('discount_description') }}"
                                maxlength="255"
                                placeholder="Contoh: Promo akhir bulan"
                            >
                            @error('discount_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Ringkasan Total -->
                    <div class="row justify-content-end mb-4">
                        <div class="col-md-4 text-end">
                            <p class="mb-1">Subtotal: <span id="subtotal-total">Rp 0</span></p>
                            <p class="mb-1 text-danger">Potongan: <span id="discount-total">Rp 0</span></p>
                            <h4 class="fw-bold">Total Bayar: <span class="text-primary" id="grand-total">Rp 0</span></h4>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="submit" class="btn btn-primary px-4 ">
                            <i class="bx bx-check-shield me-1"></i> Proses & Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Logika Pemrosesan Keranjang Dinamis Kasir -->
<script>
    let rowIndex = 0;
    const sepedasData = @json($sepedas);
    const discountInput = document.getElementById('discount_amount');
    const discountDescriptionInput = document.getElementById('discount_description');

    discountInput.addEventListener('input', calculateGrandTotal);
    discountDescriptionInput.addEventListener('input', calculateGrandTotal);

    document.getElementById('add-item-btn').addEventListener('click', function() {
        const tbody = document.getElementById('cart-items');
        
        let options = '<option value="">-- Pilih Sepeda --</option>';
        sepedasData.forEach(sepeda => {
            const stokTersedia = sepeda.stok ? sepeda.stok.jumlah_stok : 0;
            options += `<option value="${sepeda.id_sepeda}" data-price="${sepeda.harga_jual}" data-stock="${stokTersedia}">
                            ${sepeda.merk_sepeda} ${sepeda.tipe_sepeda ?? ''} (Stok: ${stokTersedia})
                        </option>`;
        });

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][id_sepeda]" class="form-select select-sepeda" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="text" class="form-control price-display bg-light" value="Rp 0" readonly>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][jumlah_beli]" class="form-control input-qty" min="1" value="1" disabled required>
            </td>
            <td>
                <input type="text" class="form-control subtotal-display bg-light" value="Rp 0" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger remove-row-btn"><i class="bx bx-trash"></i></button>
            </td>
        `;

        tbody.appendChild(newRow);
        rowIndex++;

        attachRowEvents(newRow);
    });

    function attachRowEvents(row) {
        const selectSepeda = row.querySelector('.select-sepeda');
        const inputQty = row.querySelector('.input-qty');
        const priceDisplay = row.querySelector('.price-display');
        const subtotalDisplay = row.querySelector('.subtotal-display');
        const removeBtn = row.querySelector('.remove-row-btn');

        selectSepeda.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const maxStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

            if (this.value) {
                if (maxStock <= 0) {
                    alert('Stok sepeda ini habis! Silakan pilih tipe sepeda lain.');
                    this.value = '';
                    inputQty.disabled = true;
                    inputQty.value = '';
                    priceDisplay.value = 'Rp 0';
                } else {
                    inputQty.disabled = false;
                    inputQty.max = maxStock;
                    inputQty.value = 1;
                    priceDisplay.value = formatRupiah(price);
                }
            } else {
                inputQty.disabled = true;
                inputQty.value = '';
                priceDisplay.value = 'Rp 0';
            }
            calculateRowSubtotal(row);
        });

        inputQty.addEventListener('input', function() {
            const selectedOption = selectSepeda.options[selectSepeda.selectedIndex];
            const maxStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            
            if (parseInt(this.value) > maxStock) {
                alert(`Stok terbatas! Maksimal kuantiti yang tersedia adalah ${maxStock} unit.`);
                this.value = maxStock;
            }
            calculateRowSubtotal(row);
        });

        removeBtn.addEventListener('click', function() {
            row.remove();
            calculateGrandTotal();
        });
    }

    function calculateRowSubtotal(row) {
        const selectSepeda = row.querySelector('.select-sepeda');
        const inputQty = row.querySelector('.input-qty');
        const subtotalDisplay = row.querySelector('.subtotal-display');

        const selectedOption = selectSepeda.options[selectSepeda.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const qty = parseInt(inputQty.value) || 0;

        const subtotal = price * qty;
        subtotalDisplay.setAttribute('data-subtotal', subtotal);
        subtotalDisplay.value = formatRupiah(subtotal);

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.subtotal-display').forEach(element => {
            const sub = parseFloat(element.getAttribute('data-subtotal')) || 0;
            grandTotal += sub;
        });

        const discount = parseInt(discountInput.value, 10) || 0;
        discountDescriptionInput.required = discount > 0;
        document.getElementById('subtotal-total').innerText = formatRupiah(grandTotal);
        document.getElementById('discount-total').innerText = formatRupiah(discount);
        document.getElementById('grand-total').innerText = formatRupiah(Math.max(0, grandTotal - discount));
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }
</script>
@endsection
