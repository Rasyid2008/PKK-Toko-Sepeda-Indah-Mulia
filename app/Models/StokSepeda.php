<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokSepeda extends Model
{
    /** @use HasFactory<\Database\Factories\StokSepedaFactory> */
    use HasFactory;

    protected $table = 'stok_sepedas';
    protected $primaryKey = 'id_stok';
    protected $fillable = ['id_sepeda', 'jumlah_stok'];

    public function sepeda()
    {
        return $this->belongsTo(Sepeda::class, 'id_sepeda', 'id_sepeda');
    }
}