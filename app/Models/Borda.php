<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borda extends Model
{
    use HasFactory;

    protected $table = 't_borda';

    protected $fillable = [
        'dtks_id',
        'bobot_rt',
        'bobot_psm',
        'bobot_kl',
        'score',
        'kelayakan',
        'rank',
    ];

    public function dtks()
    {
        return $this->hasOne(Dtks::class, 'id', 'dtks_id');
    }
}
