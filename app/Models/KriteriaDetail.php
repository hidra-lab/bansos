<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaDetail extends Model
{
    use HasFactory;

    public function level()
    {
        return $this->hasOne(LevelDM::class, 'id', 'level_d_m_id');
    }

    public function subKriteria()
    {
        return $this->hasManyThrough(SubKriteria::class, KriteriaJoinSub::class, 'kriteria_detail_id', 'id', 'id', 'sub_kriteria_id');
    }
}
