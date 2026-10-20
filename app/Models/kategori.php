<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
     use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori'];

    public function sepeda()
    {
        return $this->hasMany(Sepeda::class, 'id_kategori', 'id_kategori');
    }
}
