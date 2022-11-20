<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtksBansos extends Model
{
    use HasFactory;

    protected $table = 'dtks_bansos';

    protected $fillable = [
        'dtks_id',
        'bansos_id',
    ];
}
