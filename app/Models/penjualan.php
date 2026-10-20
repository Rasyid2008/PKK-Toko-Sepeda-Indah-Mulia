<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penjualan extends Model
{
    /** @use HasFactory<\Database\Factories\PenjualanFactory> */
    use HasFactory;

    protected $table = 'penjualans';
    protected $primaryKey = 'id_penjualan';
    protected $fillable = [
        'nama_pembeli',
        'telepon_pembeli',
        'alamat_pembeli',
        'tanggal_transaksi',
        'total_bayar',
        'discount_amount',
        'discount_description',
        'status',
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
