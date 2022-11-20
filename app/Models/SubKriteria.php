<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria';

    public function kriteria()
    {
        return $this->hasManyThrough(KriteriaDetail::class, KriteriaJoinSub::class, 'sub_kriteria_id', 'id', 'id', 'kriteria_detail_id');
    }
}
