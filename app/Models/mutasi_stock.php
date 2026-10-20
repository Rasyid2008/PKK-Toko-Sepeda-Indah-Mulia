<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutasi_stock extends Model
{
    /** @use HasFactory<\Database\Factories\MutasiStockFactory> */
    use HasFactory;

    protected $table = 'mutasi_stocks';
    protected $primaryKey = 'id_mutasi';
    protected $fillable = ['id_sepeda', 'jenis_mutasi', 'jumlah', 'keterangan'];

    public function sepeda()
    {
        return $this->belongsTo(Sepeda::class, 'id_sepeda', 'id_sepeda');
    }
}
