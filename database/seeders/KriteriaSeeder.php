<?php

namespace Database\Seeders;

use App\Models\KriteriaDetail;
use App\Models\KriteriaJoinSub;
use App\Models\LevelDM;
use App\Models\SubKriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $level = [
            'RT' => 'RT',
            'PSM' => 'PS',
            'Kelurahan' => 'KL',
        ];

        $listKriteria = [
            'Kondisi Rumah' => [
                'level' => 'RT',
                'simbol' => 'RT1',
                'sub' => [
                    'Layak' => 'L',
                    'Cukup Layak' => 'CL',
                    'Kurang Layak' => 'KL',
                    'Tidak Layak' => 'TL'
                ]
            ],
            'Perkejaan' => [
                'level' => 'RT',
                'simbol' => 'RT2',
                'sub' => [
                    'Pelajar/Mahasiswa' => 'P',
                    'Wiraswasta' => 'W',
                    'Karyawan Swasta' => 'KW',
                    'Lainnya' => 'L'
                ]
            ],
            'Transportasi' => [
                'level' => 'RT',
                'simbol' => 'RT3',
                'sub' => [
                    'Motor' => 'Mt',
                    'Mobil' => 'Mb',
                    'Tidak Ada' => 'Ta'
                ]
            ],
            'Jumlah Tanggungan' => [
                'level' => 'RT',
                'simbol' => 'RT4',
                'sub' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '>4' => '4'
                ]
            ],
            'Jumlah Kelayakan' => [
                'level' => 'PS',
                'simbol' => 'PS1',
                'sub' => [
                    'Layak' => 'L',
                    'Tidak Layak' => 'TL'
                ]
            ],
            'Jumlah Warga' => [
                'level' => 'PS',
                'simbol' => 'PS2',
                'sub' => [
                    'Laki-laki' => 'L',
                    'Perempuan' => 'P'
                ]
            ],
            'Hubungan Keluarga' => [
                'level' => 'PS',
                'simbol' => 'PS3',
                'sub' => [
                    'Kepala Keluarga' => 'KK',
                    'Istri' => 'I',
                    'Anak' => 'A',
                    'Lainnya' => 'L'
                ]
            ],
            'Tepat Sasaran' => [
                'level' => 'KL',
                'simbol' => 'KL1',
                'sub' => [
                    'Ya' => 'Y',
                    'Tidak' => 'T'
                ]
            ],
            'Tepat Jumlah' => [
                'level' => 'KL',
                'simbol' => 'KL2',
                'sub' => [
                    'Ya' => 'Y',
                    'Tidak' => 'T'
                ]
            ],
            'Tepat Administrasi' => [
                'level' => 'KL',
                'simbol' => 'KL3',
                'sub' => [
                    'Ya' => 'Y',
                    'Tidak' => 'T'
                ]
            ],
        ];

        try {
            DB::beginTransaction();

            // insert level
            foreach ($level as $key => $value) {
                $levelModel = new LevelDM();
                $levelModel->jabatan = $key;
                $levelModel->simbol = $value;

                $levelModel->save();
            }

            // insert kriteria
            foreach ($listKriteria as $name => $detail) {
                $level = LevelDM::where('simbol', $detail['level'])->first();
                $kriteriaModel = new KriteriaDetail();
                $kriteriaModel->kriteria = $name;
                $kriteriaModel->simbol = $detail['simbol'];
                $kriteriaModel->level_d_m_id = $level->id;

                $kriteriaModel->save();

                foreach ($detail['sub'] as $key => $value) {
                    $subKriteria = new SubKriteria();
                    $subKriteria->sub_kriteria = $key;
                    $subKriteria->simbol = $value;

                    $subKriteria->save();

                    // insert join
                    $kriteriaJoin = new KriteriaJoinSub();
                    $kriteriaJoin->kriteria_detail_id = $kriteriaModel->id;
                    $kriteriaJoin->sub_kriteria_id = $subKriteria->id;

                    $kriteriaJoin->save();
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
