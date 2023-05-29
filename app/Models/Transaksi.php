<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'transaksi';

    protected $fillable = [
        'no_tagihan',
        'produk',
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
}
