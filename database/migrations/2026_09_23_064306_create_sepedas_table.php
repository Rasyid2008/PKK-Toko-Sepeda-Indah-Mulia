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
         Schema::create('sepedas', function (Blueprint $table) {
            $table->id('id_sepeda'); // Primary Key
            $table->foreignId('id_kategori')->nullable()->constrained('kategoris', 'id_kategori')->onDelete('set null');
            $table->string('merk_sepeda', 100);
            $table->string('tipe_sepeda', 100)->nullable();
            $table->decimal('harga_jual', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sepedas');
    }
};
