<?php

namespace Tests\Feature;

use App\Models\DetailPenjualan;
use App\Models\mutasi_stock;
use App\Models\Penjualan;
use App\Models\Sepeda;
use App\Models\StokSepeda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananWhatsAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_order_is_saved_and_stock_is_reserved_before_redirecting_to_whatsapp(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();

        $this->get(route('pesanan.create', $sepeda))
            ->assertOk()
            ->assertSee('Form Pesanan')
            ->assertSee('Nomor WhatsApp');

        $response = $this->post(route('pesanan.store', $sepeda), [
            'nama_pembeli' => 'Nadia',
            'telepon_pembeli' => '081234567890',
            'alamat_pembeli' => 'Batujajar',
            'jumlah_beli' => 2,
        ]);

        $response->assertRedirect();
        $this->assertStringStartsWith('https://wa.me/', $response->headers->get('Location'));
        $this->assertDatabaseHas('penjualans', [
            'nama_pembeli' => 'Nadia',
            'telepon_pembeli' => '081234567890',
            'total_bayar' => 5000000,
            'status' => 'menunggu_pembayaran',
        ]);
        $this->assertSame(3, $stok->fresh()->jumlah_stok);
    }

    public function test_admin_confirmation_marks_reserved_order_paid_without_reducing_stock_twice(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();
        $penjualan = $this->buatPesanan($sepeda);

        $this->actingAs(User::factory()->create())
            ->patch(route('penjualan.konfirmasi', $penjualan))
            ->assertRedirect(route('penjualan.index'));

        $this->assertSame('lunas', $penjualan->fresh()->status);
        $this->assertSame(3, $stok->fresh()->jumlah_stok);
    }

    public function test_admin_can_cancel_order_without_reducing_stock(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();
        $penjualan = $this->buatPesanan($sepeda);

        $this->actingAs(User::factory()->create())
            ->patch(route('penjualan.batalkan', $penjualan))
            ->assertRedirect(route('penjualan.index'));

        $this->assertSame('dibatalkan', $penjualan->fresh()->status);
        $this->assertSame(5, $stok->fresh()->jumlah_stok);
    }

    public function test_admin_can_open_incoming_orders_from_the_sidebar(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('penjualan.index'))
            ->assertOk()
            ->assertSee('Pesanan Masuk')
            ->assertSee(route('penjualan.index'));
    }

    public function test_cashier_sale_applies_and_displays_manual_discount(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();

        $this->actingAs(User::factory()->create())
            ->post(route('penjualan.store'), [
                'items' => [
                    ['id_sepeda' => $sepeda->id_sepeda, 'jumlah_beli' => 2],
                ],
                'discount_amount' => 500000,
                'discount_description' => 'Promo akhir bulan',
            ])
            ->assertRedirect(route('laporan.penjualan'));

        $penjualan = Penjualan::latest('id_penjualan')->firstOrFail();
        $this->assertSame(4500000.0, (float) $penjualan->total_bayar);
        $this->assertSame(500000.0, (float) $penjualan->discount_amount);
        $this->assertSame('Promo akhir bulan', $penjualan->discount_description);
        $this->assertSame(3, $stok->fresh()->jumlah_stok);

        $this->actingAs(User::factory()->create())
            ->get(route('penjualan.show', $penjualan))
            ->assertOk()
            ->assertSee('Promo akhir bulan')
            ->assertSee('4.500.000');
    }

    public function test_cashier_cannot_apply_discount_above_subtotal_or_without_description(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();
        $kasir = User::factory()->create();
        $dataTransaksi = [
            'items' => [
                ['id_sepeda' => $sepeda->id_sepeda, 'jumlah_beli' => 2],
            ],
        ];

        $this->actingAs($kasir)
            ->from(route('penjualan.create'))
            ->post(route('penjualan.store'), $dataTransaksi + ['discount_amount' => 100])
            ->assertRedirect(route('penjualan.create'))
            ->assertSessionHasErrors('discount_description');

        $this->from(route('penjualan.create'))
            ->post(route('penjualan.store'), $dataTransaksi + [
                'discount_amount' => 5000001,
                'discount_description' => 'Diskon berlebih',
            ])
            ->assertRedirect(route('penjualan.create'))
            ->assertSessionHasErrors('discount_amount');

        $this->assertDatabaseCount('penjualans', 0);
        $this->assertSame(5, $stok->fresh()->jumlah_stok);
    }

    public function test_admin_can_apply_discount_to_pending_online_order(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();
        $penjualan = $this->buatPesanan($sepeda);

        $this->actingAs(User::factory()->create())
            ->get(route('penjualan.edit', $penjualan))
            ->assertOk()
            ->assertSee('Atur Potongan');

        $this->put(route('penjualan.update', $penjualan), [
            'discount_amount' => 250000,
            'discount_description' => 'Promo pesanan online',
        ])->assertRedirect(route('penjualan.index'));

        $this->assertSame(4750000.0, (float) $penjualan->fresh()->total_bayar);
        $this->assertSame(250000.0, (float) $penjualan->fresh()->discount_amount);
        $this->assertSame('Promo pesanan online', $penjualan->fresh()->discount_description);
        $this->assertSame(3, $stok->fresh()->jumlah_stok);
    }

    public function test_admin_cannot_set_invalid_discount_on_pending_order(): void
    {
        [$sepeda, $stok] = $this->buatSepedaDenganStok();
        $penjualan = $this->buatPesanan($sepeda);
        $kasir = User::factory()->create();

        $this->actingAs($kasir)
            ->from(route('penjualan.edit', $penjualan))
            ->put(route('penjualan.update', $penjualan), [
                'discount_amount' => 5000001,
                'discount_description' => 'Diskon berlebih',
            ])
            ->assertRedirect(route('penjualan.edit', $penjualan))
            ->assertSessionHasErrors('discount_amount');

        $this->assertSame(5000000.0, (float) $penjualan->fresh()->total_bayar);
        $this->assertSame(3, $stok->fresh()->jumlah_stok);
    }

    private function buatSepedaDenganStok(): array
    {
        $sepeda = Sepeda::create([
            'merk_sepeda' => 'Sepeda Uji',
            'tipe_sepeda' => 'Kota',
            'harga_jual' => 2500000,
        ]);
        $stok = StokSepeda::create(['id_sepeda' => $sepeda->id_sepeda, 'jumlah_stok' => 5]);

        return [$sepeda, $stok];
    }

    private function buatPesanan(Sepeda $sepeda): Penjualan
    {
        $penjualan = Penjualan::create([
            'nama_pembeli' => 'Nadia',
            'telepon_pembeli' => '081234567890',
            'total_bayar' => 5000000,
            'status' => 'menunggu_pembayaran',
        ]);

        DetailPenjualan::create([
            'id_penjualan' => $penjualan->id_penjualan,
            'id_sepeda' => $sepeda->id_sepeda,
            'jumlah_beli' => 2,
            'subtotal' => 5000000,
        ]);

        StokSepeda::where('id_sepeda', $sepeda->id_sepeda)->decrement('jumlah_stok', 2);
        mutasi_stock::create([
            'id_sepeda' => $sepeda->id_sepeda,
            'jenis_mutasi' => 'keluar',
            'jumlah' => 2,
            'keterangan' => 'Reservasi pesanan uji',
        ]);

        return $penjualan;
    }
}