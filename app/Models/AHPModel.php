<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AHPModel extends Model
{
    use HasFactory;

    protected $table = 't_ahp';

    protected $fillable = [
        'dtks_id',
        'score_rt',
        'score_psm',
        'score_kl',
    ];

    public function dtks()
    {
        return $this->hasOne(Dtks::class, 'id', 'dtks_id');
    }
}
