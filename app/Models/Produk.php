<?php

namespace App\Models;

use App\Models\Transaksiproduk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
    ];
    public function transaksi_produk()
    {
        return $this->hasMany(Transaksiproduk::class);
    }
}
