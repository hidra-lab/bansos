<?php

namespace App\Imports;


use App\Models\User;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use function PHPUnit\Framework\isNan;

class WargaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            $tanggalLahirWarga = $row['tgllahir_warga'];
            if ($tanggalLahirWarga === '0000-00-00') {
                $tanggalLahirWarga = null;
            }

            Warga::updateOrCreate([
                'nik' => $row['nik_warga'],
            ], [
                'no_kk' => $row['no_kk'],
                'nama' => $row['nama_warga'],
                'jk' => $row['jkel_warga'],
                'tmp_lahir'  => $row['tlahir_warga'],
                'tgl_lahir' => $tanggalLahirWarga,
                'pendidikan' => $row['pendidikan_warga'],
                'pekerjaan' => $row['pekerjaan_warga'],
                'status_perkawinan' => $row['statusperkawinan_warga'],
                'hub_keluarga' => $row['hubungan_warga'],
                'ket_warga' => $row['ket_warga'],
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
