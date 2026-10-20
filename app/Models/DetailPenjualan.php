<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    /** @use HasFactory<\Database\Factories\DetailPenjualanFactory> */
    use HasFactory;

    protected $table = 'detail_penjualans';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['id_penjualan', 'id_sepeda', 'jumlah_beli', 'subtotal'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function sepeda()
    {
        return $this->belongsTo(Sepeda::class, 'id_sepeda', 'id_sepeda');
    }
}