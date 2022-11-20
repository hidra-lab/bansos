<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';

    protected $fillable = [
        'no_kk',
        'nik',
        'nama',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'hub_keluarga',
        'ket_warga',
    ];

    public function dtks()
    {
        return $this->hasOne(Dtks::class, 'nik', 'nik');
    }

    // cari umur berdasarkan tanggal sekarang
    public function getUmurAttribute()
    {
        $diffDate = Carbon::now()->diff(Carbon::parse($this->tgl_lahir));
        return $diffDate->y . ' Tahun ' . $diffDate->m . ' Bulan';
    }
}
