<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 't_alternatif';

    protected $fillable = [
        'dtks_id',
        'rt1',
        'rt2',
        'rt3',
        'rt4',
        'ps1',
        'ps2',
        'ps3',
        'kl1',
        'kl2',
        'kl3',
    ];

    public function dtks()
    {
        return $this->hasOne(Dtks::class, 'id', 'dtks_id');
    }

    public function getPs1LabelAttribute()
    {
        if (empty($this->ps1)) {
            return '-';
        }

        $subKriteria = $this->getSubKriteria('PS1', $this->ps1);
        return $subKriteria . ' (' . $this->ps1 . ')';
    }

    public function getPs2LabelAttribute()
    {
        if (empty($this->ps2)) {
            return '-';
        }

        $subKriteria = $this->getSubKriteria('PS2', $this->ps2);
        return $subKriteria . ' (' . $this->ps2 . ')';
    }

    public function getKl1LabelAttribute()
    {
        if (empty($this->kl1)) {
            return '-';
        }

        $subKriteria = $this->getSubKriteria('KL1', $this->kl1);
        return $subKriteria . ' (' . $this->kl1 . ')';
    }

    public function getKl2LabelAttribute()
    {
        if (empty($this->kl2)) {
            return '-';
        }

        $subKriteria = $this->getSubKriteria('KL2', $this->kl2);
        return $subKriteria . ' (' . $this->kl2 . ')';
    }

    public function getKl3LabelAttribute()
    {
        if (empty($this->kl3)) {
            return '-';
        }

        $subKriteria = $this->getSubKriteria('KL3', $this->kl3);
        return $subKriteria . ' (' . $this->kl3 . ')';
    }

    protected function getSubKriteria($kriteriaSimbol, $subKriteriaSimbol)
    {
        return SubKriteria::whereHas('kriteria', function ($query) use ($kriteriaSimbol) {
            return $query->where('simbol', $kriteriaSimbol);
        })
            ->where('simbol', $subKriteriaSimbol)
            ->first()
            ->sub_kriteria;
    }
}
