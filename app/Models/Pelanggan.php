<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
