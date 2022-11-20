<?php

namespace App\Services;

use App\Models\Konsistensi;
use App\Models\KriteriaDetail;
use App\Models\SubKriteria;

class AHP
{
    // batas minimal kelayakan
    static public function threshold()
    {
        // rt1 = Layak
        // rt2 = Wiraswasta
        // rt3 = Mobil
        // rt4 = 1

        // cari berdasarkan simbol RT1
        $rt1 = KriteriaDetail::where('simbol', 'RT1')
            ->first();

        // cari subkriteria yang simbol L
        $rt1Sub = $rt1->subKriteria->where('simbol', 'L')
            ->first();
        $bobotRT1 = $rt1->prioritas * $rt1Sub->sub_prioritas;

        // cari berdasarkan simbol RT2
        $rt2 = KriteriaDetail::where('simbol', 'RT2')
            ->first();

        // cari subkriteria yang simbol W
        $rt2Sub = $rt2->subKriteria->where('simbol', 'W')
            ->first();
        $bobotRT2 = $rt2->prioritas * $rt2Sub->sub_prioritas;

        // cari berdasarkan simbol RT3
        $rt3 = KriteriaDetail::where('simbol', 'RT3')
            ->first();

        // cari subkriteria yang simbol W
        $rt3Sub = $rt3->subKriteria->where('simbol', 'Mb')
            ->first();
        $bobotRT3 = $rt3->prioritas * $rt3Sub->sub_prioritas;

        // cari berdasarkan simbol RT4
        $rt4 = KriteriaDetail::where('simbol', 'RT4')
            ->first();

        // cari subkriteria yang simbol W
        $rt4Sub = $rt4->subKriteria->where('simbol', '1')
            ->first();
        $bobotRT4 = $rt4->prioritas * $rt4Sub->sub_prioritas;

        $threshold = $bobotRT1 + $bobotRT2 + $bobotRT3 + $bobotRT4;

        return $threshold;
    }

    protected function getMatrixPerbanding(int $matrix, array $initValue, string $nama)
    {
        $tableRi = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.9,
        ];

        $result = [];
        $totalResult = [];
        $normalisasi = [];
        $totalNormalisasi = [];
        $prioritas = [];
        $eigen = [];

        // matrix perbandingan
        for ($i = 1; $i <= $matrix; $i++) {
            for ($j = 1; $j <= $matrix; $j++) {
                if ($i === $j) {
                    $result[$i][$j] = 1;
                } else {
                    $value = $initValue[$i][$j] ?? 0;
                    $result[$i][$j] = $value;
                }
            }
        }

        for ($i = 1; $i <= $matrix; $i++) {
            for ($j = 1; $j <= $matrix; $j++) {
                if ($result[$i][$j] === 0) {
                    $result[$i][$j] = 1 / $result[$j][$i];
                }
            }
        }

        // mencari total matrix dan normalisasi matrix
        for ($i = 1; $i <= $matrix; $i++) {
            for ($j = 1; $j <= $matrix; $j++) {
                $tot = array_reduce($result, function ($res, $item) use ($j) {
                    return $res + $item[$j];
                }, 0);

                $totalResult[] = $tot;
                $normalisasi[$i][$j] = $result[$i][$j] / $tot;
            }
        }

        // total normalisasi dan prioritas
        for ($j = 1; $j <= $matrix; $j++) {
            $tot = array_sum($normalisasi[$j]);

            $totalNormalisasi[] = $tot;
            $prioritas[] = $tot / $matrix;
            $eigen[] = ($tot / $matrix) * $totalResult[$j - 1];
        }

        $lambdaMax = array_sum($eigen);
        $ci = ($lambdaMax - $matrix) / ($matrix - 1);
        $cr = $tableRi[$matrix] === 0 ? 0 : $ci / $tableRi[$matrix];

        $result = [
            'matrix' => $result,
            'total_matrix' => array_unique($totalResult),
            'normalisasi' => $normalisasi,
            'total_normalisasi' => $totalNormalisasi,
            'prioritas' => $prioritas,
            'total_prioritas' => array_sum($prioritas),
            'eigen' => $eigen,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'cr' => $cr,
            'konsistensi' => $cr <= 0.1 ? 'KONSISTEN' : 'TIDAK KONSISTEN',
        ];

        // insert konsistensi data
        $this->insertKonsistensi($nama, $result['cr'], $result['konsistensi']);

        return $result;
    }

    public function hitungPrioritas()
    {
        $this->hitungKriteriaPSM();

        $this->hitungKriteriaRT();

        $this->hitungKriteriaKL();
    }

    protected function insertKonsistensi($nama, $nilaiCr, $konsistensi)
    {
        Konsistensi::updateOrCreate([
            'nama' => $nama,
        ], [
            'nilai_cr' => $nilaiCr,
            'konsistensi' => $konsistensi,
        ]);
    }

    protected function hitungKriteriaPSM()
    {
        // PS = PSM
        $PS[1][2] = 3;
        $PS[1][3] = 3;
        $PS[3][2] = 2;

        $PS = AHP::getMatrixPerbanding(3, $PS, 'PS');

        // insert data PS
        $listPS = ['PS1', 'PS2', 'PS3'];
        $this->insertPrioritas($listPS, $PS);

        // PS1, PS2, PS3
        $PS1[1][2] = 2;
        $PS1 = AHP::getMatrixPerbanding(2, $PS1, 'PS1');

        $PS2[2][1] = 2;
        $PS2 = AHP::getMatrixPerbanding(2, $PS2, 'PS2');

        $PS3[1][2] = 2;
        $PS3[1][3] = 2;
        $PS3[1][4] = 2;
        $PS3[3][2] = 2;
        $PS3[2][4] = 2;
        $PS3[3][4] = 2;
        $PS3 = AHP::getMatrixPerbanding(4, $PS3, 'PS3');

        // insert data PS
        $basePS = [
            ['kriteria' => 'PS1', 'sub' => ['L', 'TL'], 'result' => $PS1],
            ['kriteria' => 'PS2', 'sub' => ['L', 'P'], 'result' => $PS2],
            ['kriteria' => 'PS3', 'sub' => ['KK', 'I', 'A', 'L'], 'result' => $PS3],
        ];
        $this->insertSubPrioritas($basePS);
    }

    protected function hitungKriteriaRT()
    {
        // RT
        $RT[1][2] = 2;
        $RT[1][3] = 2;
        $RT[1][4] = 2;
        $RT[2][3] = 2;
        $RT[4][2] = 2;
        $RT[4][3] = 2;
        $RT = AHP::getMatrixPerbanding(4, $RT, 'RT');

        // insert data RT
        $listRT = ['RT1', 'RT2', 'RT3', 'RT4'];
        $this->insertPrioritas($listRT, $RT);

        // RT1, RT2, RT3, RT4
        $RT1[2][1] = 2;
        $RT1[3][1] = 2;
        $RT1[4][1] = 3;
        $RT1[3][2] = 2;
        $RT1[4][2] = 2;
        $RT1[4][3] = 2;
        $RT1 = AHP::getMatrixPerbanding(4, $RT1, 'RT1');

        $RT2[1][2] = 2;
        $RT2[1][3] = 2;
        $RT2[4][1] = 3;
        $RT2[3][2] = 2;
        $RT2[4][2] = 3;
        $RT2[4][3] = 3;
        $RT2 = AHP::getMatrixPerbanding(4, $RT2, 'RT2');

        $RT3[1][2] = 2;
        $RT3[3][1] = 3;
        $RT3[3][2] = 3;
        $RT3 = AHP::getMatrixPerbanding(3, $RT3, 'RT3');

        $RT4[1][2] = 2;
        $RT4[3][1] = 3;
        $RT4[4][1] = 3;
        $RT4[3][2] = 3;
        $RT4[4][2] = 3;
        $RT4[3][4] = 2;
        $RT4 = AHP::getMatrixPerbanding(4, $RT4, 'RT4');

        // insert data RT
        $baseRT = [
            ['kriteria' => 'RT1', 'sub' => ['L', 'CL', 'KL', 'TL'], 'result' => $RT1],
            ['kriteria' => 'RT2', 'sub' => ['P', 'W', 'KW', 'L'], 'result' => $RT2],
            ['kriteria' => 'RT3', 'sub' => ['Mt', 'Mb', 'Ta'], 'result' => $RT3],
            ['kriteria' => 'RT4', 'sub' => ['1', '2', '3', '4'], 'result' => $RT4],
        ];
        $this->insertSubPrioritas($baseRT);
    }

    protected function hitungKriteriaKL()
    {

        // KL
        $KL[1][2] = 2;
        $KL[3][1] = 2;
        $KL[3][2] = 2;
        $KL = AHP::getMatrixPerbanding(3, $KL, 'KL');

        // insert data KL
        $listKL = ['KL1', 'KL2', 'KL3'];
        $this->insertPrioritas($listKL, $KL);

        // KL1
        $KL1[1][2] = 3;
        $KL1 = AHP::getMatrixPerbanding(2, $KL1, 'KL1');

        // KL2
        $KL2[1][2] = 3;
        $KL2 = AHP::getMatrixPerbanding(2, $KL2, 'KL2');

        // KL3
        $KL3[1][2] = 2;
        $KL3 = AHP::getMatrixPerbanding(2, $KL3, 'KL3');

        // insert data KL
        $baseKL = [
            ['kriteria' => 'KL1', 'sub' => ['Y', 'T'], 'result' => $KL1],
            ['kriteria' => 'KL2', 'sub' => ['Y', 'T'], 'result' => $KL2],
            ['kriteria' => 'KL3', 'sub' => ['Y', 'T'], 'result' => $KL3],
        ];
        $this->insertSubPrioritas($baseKL);
    }

    protected function insertPrioritas($list, $data)
    {
        foreach ($list as $index => $value) {
            KriteriaDetail::where('simbol', $value)->update(['prioritas' => $data['prioritas'][$index]]);
        }
    }

    protected function insertSubPrioritas($base)
    {
        foreach ($base as $value) {
            foreach ($value['sub'] as $index => $sub) {
                $max = max($value['result']['prioritas']);

                SubKriteria::whereHas('kriteria', function ($query) use ($value) {
                    $query->where('simbol', $value['kriteria']);
                })
                    ->where('simbol', $sub)
                    ->update([
                        'prioritas' => $value['result']['prioritas'][$index],
                        'sub_prioritas' => $value['result']['prioritas'][$index] / $max,
                    ]);
            }
        }
    }
}
