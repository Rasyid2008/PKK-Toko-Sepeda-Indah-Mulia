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
         Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id('id_detail'); // Primary Key
            $table->foreignId('id_penjualan')->constrained('penjualans', 'id_penjualan')->onDelete('cascade');
            $table->foreignId('id_sepeda')->nullable()->constrained('sepedas', 'id_sepeda')->onDelete('set null');
            $table->integer('jumlah_beli');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
