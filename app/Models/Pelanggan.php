<?php

namespace App\Models;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pelanggan extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'pelanggan';

    protected $fillable = [
        'name',
        'no_telp',
        'kota',
        'alamat',
        'email',
        'status',
        'jenis',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
