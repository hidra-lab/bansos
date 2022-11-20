<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtks extends Model
{
    use HasFactory;

    protected $table = 'dtks';

    protected $casts = [
        'kondisi_rumah_json' => 'collection',
    ];

    protected $fillable = [
        'no_kk',
        'nik',
        'nama',
        'tgl_lahir',
        'jk',
        'pekerjaan',
        'hub_keluarga',
        'kondisi_rumah',
        'jenis_transportasi',
        'tanggungan',
        'is_added',
        'rt',
        'rw',
        'kondisi_rumah_json',
    ];

    public function bansos()
    {
        return $this->hasManyThrough(Bansos::class, DtksBansos::class, 'dtks_id', 'id', 'id', 'bansos_id');
    }

    public function warga()
    {
        // no kk 1 dari tabel warga, kedua dtks
        return $this->hasMany(Warga::class, 'no_kk', 'no_kk');
    }

    public function getJenisBansosAttribute()
    {
        $bansos = [];

        foreach ($this->bansos as $item) {
            $bansos[] = $item->jenis_bansos;
        }

        return implode(', ', $bansos);
    }

    // cari umur berdasarkan tanggal sekarang
    public function getUmurAttribute()
    {
        $diffDate = Carbon::now()->diff(Carbon::parse($this->tgl_lahir));
        return $diffDate->y . ' Tahun ' . $diffDate->m . ' Bulan';
    }
}
