<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksiproduk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'transaksi';

    protected $fillable = [
        'no_tagihan',
        'jatuh_tempo',
        'jumlah_tagihan',
        'status_tagihan',
        'pelanggan_id',
        'user_id',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transaksi_produk()
    {
        return $this->hasMany(Transaksiproduk::class);
    }
}
