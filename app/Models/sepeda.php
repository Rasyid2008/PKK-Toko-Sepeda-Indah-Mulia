<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sepeda extends Model
{
    /** @use HasFactory<\Database\Factories\SepedaFactory> */
    use HasFactory;

    protected $table = 'sepedas';
    protected $primaryKey = 'id_sepeda';
    protected $fillable = ['id_kategori', 'merk_sepeda', 'tipe_sepeda', 'harga_jual', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function stok()
    {
        return $this->hasOne(StokSepeda::class, 'id_sepeda', 'id_sepeda');
    }
}
