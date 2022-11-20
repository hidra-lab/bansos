<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelDM extends Model
{
    use HasFactory;

    protected $table = 'level_d_m';

    public function user()
    {

       return  $this->belongsTo(User::class, 'user_id');

    }
}
