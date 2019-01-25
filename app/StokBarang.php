<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';
    protected $fillable = [
        'namabarang', 'stok'
    ];
}
