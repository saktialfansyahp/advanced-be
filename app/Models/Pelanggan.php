<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pelanggan extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'pelanggan';

    protected $fillable = [
        'no_telp',
        'kota',
        'status',
        'jenis',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
