<?php

namespace App\Imports;

use App\Models\Bansos;
use App\Models\Dtks;
use App\Models\DtksBansos;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DtksImport implements ToModel, WithHeadingRow
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

            $dtks = new Dtks([
                'no_kk' => $row['nokk'],
                'nik' => $row['nik'],
                'nama' => $row['nama'],
                'tgl_lahir' => $row['tanggal_lahir'],
                'jk' => $row['jenis_kelamin'],
                'pekerjaan' => $row['pekerjaan'],
                'hub_keluarga' => $row['hub_keluarga'],
                'tanggungan' => 1,
                'rt' => $row['rt'],
                'rw' => $row['rw'],
            ]);

            $dtks->save();
            $dataBansos = [];

            if ($row['bansos_bpnt']) {
                $bansos = Bansos::where('jenis_bansos', 'BPNT')->first();

                $dataBansos[] = [
                    'dtks_id' => $dtks->id,
                    'bansos_id' => $bansos->id,
                ];
            }

            if ($row['bansos_pkh']) {
                $bansos = Bansos::where('jenis_bansos', 'PKH')->first();

                $dataBansos[] = [
                    'dtks_id' => $dtks->id,
                    'bansos_id' => $bansos->id,
                ];
            }

            if ($row['bansos_bpnt_ppkm']) {
                $bansos = Bansos::where('jenis_bansos', 'BPNT-PPKM')->first();

                $dataBansos[] = [
                    'dtks_id' => $dtks->id,
                    'bansos_id' => $bansos->id,
                ];
            }

            if ($row['pbi_jkn']) {
                $bansos = Bansos::where('jenis_bansos', 'JKN')->first();

                $dataBansos[] = [
                    'dtks_id' => $dtks->id,
                    'bansos_id' => $bansos->id,
                ];
            }

            DtksBansos::insert($dataBansos);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
