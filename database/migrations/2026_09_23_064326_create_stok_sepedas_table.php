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
        Schema::create('stok_sepedas', function (Blueprint $table) {
            $table->id('id_stok'); // Primary Key
            $table->foreignId('id_sepeda')->unique()->constrained('sepedas', 'id_sepeda')->onDelete('cascade');
            $table->integer('jumlah_stok')->default(0);
            $table->timestamps(); // Menggantikan fungsi terakhir_diperbarui secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_sepedas');
    }
};
