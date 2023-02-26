<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $connection = 'mysql';
    protected $table = 'todo';

    protected $fillable = [
        'title'
    ];
}