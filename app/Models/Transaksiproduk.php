<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksiproduk extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'transaksi_produk';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'quantity',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
