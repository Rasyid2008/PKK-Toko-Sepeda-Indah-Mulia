<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('penjualans', function (Blueprint $table) {
           $table->id('id_penjualan');
            $table->string('nama_pembeli', 150)->default('Pembeli Umum');
            $table->string('telepon_pembeli', 20)->nullable();
            $table->text('alamat_pembeli')->nullable();
            $table->dateTime('tanggal_transaksi')->useCurrent();
            $table->decimal('total_bayar', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
