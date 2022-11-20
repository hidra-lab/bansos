<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsistensi extends Model
{
    use HasFactory;

    protected $table = 't_konsistensi';

    protected $fillable = [
        'nama',
        'nilai_cr',
        'konsistensi',
    ];
}
