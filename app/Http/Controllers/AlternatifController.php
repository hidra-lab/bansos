<?php

namespace App\Http\Controllers;

use App\Models\AHPModel;
use App\Models\Alternatif;
use App\Models\Borda;
use App\Models\Dtks;
use App\Models\KriteriaDetail;
use App\Models\SubKriteria;
use App\Services\AHP;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index(Request $request)
    {
        $dtks = Dtks::all()->toArray();
        $rt = array_unique(array_column($dtks, 'rt'));
        $rw = array_unique(array_column($dtks, 'rw'));
        sort($rt);
        sort($rw);

        $alternatif = Alternatif::query()
        // filter berdasarkan RT
            ->when($request->rt, function ($query) use ($request) {
                // where relasi ke dtks
                $query->whereHas('dtks', function ($query) use ($request) {
                    $query->where('rt', $request->rt);
                });
            })
        // filter berdasarkan RW
            ->when($request->rw, function ($query) use ($request) {
                // where relasi ke dtks
                $query->whereHas('dtks', function ($query) use ($request) {
                    $query->where('rw', $request->rw);
                });
            })
            ->get();

        return view('admin.alternatif.index', compact('alternatif', 'rt', 'rw'));
    }

    public function edit($id)
    {
        $title = 'Edit';
        $method = 'PUT';
        $alternatif = Alternatif::where('id', $id)->first();
        $route = route('alternatif.update', $alternatif);

        $kelayakan = SubKriteria::whereHas('kriteria', function ($query) {
            $query->where('simbol', 'PS1');
        })->pluck('sub_kriteria', 'simbol')->toArray();

        $warga = SubKriteria::whereHas('kriteria', function ($query) {
            $query->where('simbol', 'PS2');
        })->pluck('sub_kriteria', 'simbol')->toArray();

        $sasaran = SubKriteria::whereHas('kriteria', function ($query) {
            $query->where('simbol', 'KL1');
        })->pluck('sub_kriteria', 'simbol')->toArray();

        $jumlah = SubKriteria::whereHas('kriteria', function ($query) {
            $query->where('simbol', 'KL2');
        })->pluck('sub_kriteria', 'simbol')->toArray();

        $administrasi = SubKriteria::whereHas('kriteria', function ($query) {
            $query->where('simbol', 'KL3');
        })->pluck('sub_kriteria', 'simbol')->toArray();

        return view('admin.alternatif.editor', compact('title', 'method', 'route', 'alternatif', 'kelayakan', 'warga', 'sasaran', 'jumlah', 'administrasi'));
    }

    public function update(Request $request, $id)
    {
        $alternatif = Alternatif::find($id);

        if ($request->filled('kelayakan')) {
            $alternatif->ps1 = $request->get('kelayakan');
        }

        if ($request->filled('warga')) {
            $alternatif->ps2 = $request->get('warga');
        }

        if ($request->filled('sasaran')) {
            $alternatif->kl1 = $request->get('sasaran');
        }

        if ($request->filled('jumlah')) {
            $alternatif->kl2 = $request->get('jumlah');
        }

        if ($request->filled('administrasi')) {
            $alternatif->kl3 = $request->get('administrasi');
        }

        $alternatif->save();

        return redirect()->route('alternatif.index')->withSuccess('Berhasil Di Simpan');
    }

    public function proses(Request $request)
    {
        // hitung prioritas dan sub prioritas AHP
        $ahp = new AHP();
        $ahp->hitungPrioritas();

        $listAlternatif = Alternatif::query()
        // filter berdasarkan RT
            ->when($request->rt, function ($query) use ($request) {
                // where relasi ke dtks
                $query->whereHas('dtks', function ($query) use ($request) {
                    $query->where('rt', $request->rt);
                });
            })
        // filter berdasarkan RW
            ->when($request->rw, function ($query) use ($request) {
                // where relasi ke dtks
                $query->whereHas('dtks', function ($query) use ($request) {
                    $query->where('rw', $request->rw);
                });
            });

        // clone query dari variabel listAlternatif
        $checkAlternatif = clone $listAlternatif;

        // cek apakah tidak ada nilai null pada t_alternatif
        $columns = ['ps1', 'ps2', 'ps3', 'kl1', 'kl2', 'kl3'];
        $checkAlternatif->where(function ($query) use ($columns) {
            foreach ($columns as $column) {
                $query->orWhereNull($column);
            }
        });

        $checkAlternatif = $checkAlternatif->first();
        $listAlternatif = $listAlternatif->get();
        if ($checkAlternatif) {
            return redirect()->back()->withErrors('Data alternatif masih ada yang kosong');
        }

        // hitung AHP
        foreach ($listAlternatif as $alternatif) {
            // hitung score PSM
            $prioritasPSM1 = KriteriaDetail::where('simbol', 'PS1')->first()->prioritas;
            $prioritasPSM2 = KriteriaDetail::where('simbol', 'PS2')->first()->prioritas;
            $prioritasPSM3 = KriteriaDetail::where('simbol', 'PS3')->first()->prioritas;

            $subPrioritasPSM1 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'PS1');
            })->where('simbol', $alternatif->ps1)->first()->sub_prioritas;

            $subPrioritasPSM2 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'PS2');
            })->where('simbol', $alternatif->ps2)->first()->sub_prioritas;

            $subPrioritasPSM3 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'PS3');
            })->where('simbol', $alternatif->ps3)->first()->sub_prioritas;

            $scorePSM = ($prioritasPSM1 * $subPrioritasPSM1) + ($prioritasPSM2 * $subPrioritasPSM2) + ($prioritasPSM3 * $subPrioritasPSM3);

            // hitung score KL
            $prioritasKL1 = KriteriaDetail::where('simbol', 'KL1')->first()->prioritas;
            $prioritasKL2 = KriteriaDetail::where('simbol', 'KL2')->first()->prioritas;
            $prioritasKL3 = KriteriaDetail::where('simbol', 'KL3')->first()->prioritas;

            $subPrioritasKL1 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'KL1');
            })->where('simbol', $alternatif->kl1)->first()->sub_prioritas;

            $subPrioritasKL2 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'KL2');
            })->where('simbol', $alternatif->kl2)->first()->sub_prioritas;

            $subPrioritasKL3 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'KL3');
            })->where('simbol', $alternatif->kl3)->first()->sub_prioritas;

            $scoreKL = ($prioritasKL1 * $subPrioritasKL1) + ($prioritasKL2 * $subPrioritasKL2) + ($prioritasKL3 * $subPrioritasKL3);

            AHPModel::updateOrCreate([
                'dtks_id' => $alternatif->dtks_id,
            ], [
                'score_psm' => $scorePSM,
                'score_kl' => $scoreKL,
            ]);
        }

        // rank psm
        $uniqueScorePSM = AHPModel::orderBy('score_psm', 'desc')->pluck('score_psm')->unique()->all();
        $listAhpPSM = AHPModel::orderBy('score_psm', 'desc')->get();

        $counter = 1;
        foreach ($uniqueScorePSM as $unique) {
            foreach ($listAhpPSM as $value) {
                if ($unique === $value->score_psm) {
                    AHPModel::where('id', $value->id)->update([
                        'rank_psm' => $counter,
                    ]);
                }
            }

            $counter += 1;
        }

        // rank kl
        $uniqueScoreKL = AHPModel::orderBy('score_kl', 'desc')->pluck('score_kl')->unique()->all();
        $listAhpKL = AHPModel::orderBy('score_kl', 'desc')->get();

        // berikan nilai default counter 1, yang artinya ranking 1
        $counter = 1;
        foreach ($uniqueScoreKL as $unique) {
            foreach ($listAhpKL as $value) {
                if ($unique === $value->score_kl) {
                    AHPModel::where('id', $value->id)->update([
                        'rank_kl' => $counter,
                    ]);
                }
            }

            $counter += 1;
        }

        $allAHP = AHPModel::all();
        // hitung BORDA
        // ambil max ranking RT
        $maxRT = AHPModel::orderBy('rank_rt', 'desc')->first()->rank_rt;
        // ambil max ranking PSM
        $maxPSM = AHPModel::orderBy('rank_psm', 'desc')->first()->rank_psm;
        // ambil max ranking KL
        $maxKL = AHPModel::orderBy('rank_kl', 'desc')->first()->rank_kl;

        // ambil ranking maksimal global
        $maxRank = max($maxRT, $maxPSM, $maxKL);

        // menentukan prioritas alternatif
        $result = [];
        for ($i = $maxRank; $i > 0; $i--) {
            // berdasarkan RT, PSM, KL
            $result[$i] = [
                'rt' => [],
                'psm' => [],
                'kl' => [],
            ];

            foreach ($allAHP as $item) {
                if ($item->rank_rt === ($maxRank - $i + 1)) {
                    $result[$i]['rt'][] = $item->dtks_id;
                }

                if ($item->rank_psm === ($maxRank - $i + 1)) {
                    $result[$i]['psm'][] = $item->dtks_id;
                }

                if ($item->rank_kl === ($maxRank - $i + 1)) {
                    $result[$i]['kl'][] = $item->dtks_id;
                }
            }
        }

        $voting = [];
        // hasil voting
        foreach ($allAHP as $item) {
            $temp['dtks_id'] = $item->dtks_id;
            $temp['score_rt'] = 0;
            $temp['score_psm'] = 0;
            $temp['score_kl'] = 0;
            $temp['total_score'] = 0;

            foreach ($result as $rank => $value) {
                // check RT
                foreach ($value['rt'] as $dtksId) {
                    if ($item->dtks_id === $dtksId) {
                        $temp['score_rt'] += $rank;
                    }
                }

                // check PSM
                foreach ($value['psm'] as $dtksId) {
                    if ($item->dtks_id === $dtksId) {
                        $temp['score_psm'] += $rank;
                    }
                }

                // check KL
                foreach ($value['kl'] as $dtksId) {
                    if ($item->dtks_id === $dtksId) {
                        $temp['score_kl'] += $rank;
                    }
                }
            }

            $temp['total_score'] = $temp['score_rt'] + $temp['score_psm'] + $temp['score_kl'];

            $voting[] = $temp;
        }

        // ambil nilai score per decision maker
        $arrScoreRT = array_column($voting, 'score_rt');
        $arrScorePSM = array_column($voting, 'score_psm');
        $arrScoreKL = array_column($voting, 'score_kl');

        // urutkan dari kecil ke besar
        sort($arrScoreRT);
        sort($arrScorePSM);
        sort($arrScoreKL);

        // batas minimal score
        $batasMinScore = $arrScoreRT[0] + $arrScorePSM[0] + $arrScoreKL[0];

        // insert data borda
        foreach ($voting as $item) {
            $keterangan = 'Layak';
            if ($item['total_score'] <= $batasMinScore) {
                $keterangan = 'Tidak Layak';
            }

            Borda::updateOrCreate([
                'dtks_id' => $item['dtks_id'],
            ], [
                'bobot_rt' => $item['score_rt'],
                'bobot_psm' => $item['score_psm'],
                'bobot_kl' => $item['score_kl'],
                'score' => $item['total_score'],
                'kelayakan' => $keterangan,
                'rank' => 0,
            ]);
        }

        $uniqueScoreBorda = array_unique(array_column($voting, 'total_score'));
        rsort($uniqueScoreBorda);
        $borda = Borda::all();

        // ranking borda
        // berikan nilai default counter 1, yang artinya ranking 1
        $counter = 1;
        foreach ($uniqueScoreBorda as $unique) {
            foreach ($borda as $value) {
                if ($unique === $value->score) {
                    Borda::where('id', $value->id)->update([
                        'rank' => $counter,
                    ]);
                }
            }

            $counter += 1;
        }

        return redirect()->back()->withSuccess('Berhasil proses data');
    }
}
