<?php

namespace App\Http\Controllers;

use App\Imports\DtksImport;
use App\Models\AHPModel;
use App\Models\Alternatif;
use App\Models\Bansos;
use App\Models\Dtks;
use App\Models\DtksBansos;
use App\Models\KriteriaDetail;
use App\Models\SubKriteria;
use App\Models\Warga;
use App\Services\AHP;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DtksController extends Controller
{
    // kondisi rumah
    protected $kondisiRumah = [
        'Jenis Lantai' => ['Tanah', 'Bambu', 'Kayu Murahan'],
        'Jenis Dinding' => ['Bambu', 'Rumbia', 'Kayu Berkualitas Rendah', 'Tembok Tanpa Diplester'],
        'Sumber Air Minum' => ['Sumur', 'Mata Air', 'Sungai', 'Air Hujan'],
        'Bahan Bakar' => ['Kayu Bakar', 'Arang', 'Minyak Tanah'],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dtks = Dtks::latest()->get();

        return view('admin.dtks.index', compact('dtks'));
    }

    public function uploadDocument(Request $r)
    {
        $r->validate([
            'uploaded_file' => 'required',
        ]);

        try {
            // hapus semua data yang bukan berasal dari tambah manual
            $dtksId = Dtks::whereNotNull('id')
                ->where('is_added', false)
                ->pluck('id')
                ->all();

            Dtks::whereIn('id', $dtksId)->delete();
            DtksBansos::whereIn('dtks_id', $dtksId)->delete();

            Excel::import(new DtksImport, $r->file('uploaded_file'));

            // hitung tanggungan
            $listDtks = Dtks::all();

            foreach ($listDtks as $dtks) {
                $tanggungan = 0;

                $hubungan = ['anak', 'cucu'];
                if (!in_array(Str::lower($dtks->hub_keluarga), $hubungan)) {
                    foreach ($dtks->warga as $warga) {
                        // jika nik pada dtks dan nik warga berbeda (beda orang)
                        if ($dtks->nik != $warga->nik) {
                            if (empty($warga->dtks)) {
                                $tanggungan += 1;
                            } else {
                                if ($warga->dtks->bansos->isEmpty()) {
                                    $tanggungan += 1;
                                }
                            }
                        }
                    }
                }

                $tanggungan = $tanggungan === 0 ? 1 : $tanggungan;

                $dtks->tanggungan = $tanggungan;
                $dtks->save();
            }

            return redirect()->back()->withSuccess('Berhasil data di upload');
        } catch (\Throwable $e) {
            throw $e;
            return redirect()->back()->withErrors("Upload Data Gagal");
        }
    }

    public function edit($id)
    {
        $title = 'Edit';
        $dtks = Dtks::where('id', $id)->first();
        $bansos = Bansos::all();
        $route = route('dtks.update', $dtks);
        // $kondisiRumah = ['Layak', 'Cukup Layak', 'Kurang Layak', 'Tidak Layak'];
        $jenisTransportasi = ['Motor', 'Mobil', 'Tidak Ada'];

        $kondisiRumah = $this->kondisiRumah;

        return view('admin.dtks.editor', compact('title', 'route', 'dtks', 'bansos', 'kondisiRumah', 'jenisTransportasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dtks = Dtks::find($id);
        $request->validate([
            'rt' => 'required',
            'rw' => 'required',
            'transportasi' => 'required',
            'tanggungan' => 'required',
        ]);

        $kondisi = $this->getKondisiRumah($request);

        // update bansos
        // hapus yang lama berdasarkan dtks id
        DtksBansos::where('dtks_id', $dtks->id)->delete();
        foreach ($request->jenis_bansos ?? [] as $item) {
            DtksBansos::create([
                'dtks_id' => $dtks->id,
                'bansos_id' => $item,
            ]);
        }

        $dtks->update([
            'kondisi_rumah' => $kondisi,
            'pekerjaan' => $request->pekerjaan,
            'jenis_transportasi' => $request->transportasi,
            'hub_keluarga' => $request->hub_keluarga,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'tanggungan' => $request->tanggungan,
            'kondisi_rumah_json' => $request->kondisi ?? [],
        ]);
        return redirect()->route('dtks.index')->withSuccess('Berhasil Di Simpan');
    }

    public function proses()
    {
        $listDtks = Dtks::all();

        foreach ($listDtks as $dtks) {
            $rt = [
                'RT1' => $dtks->kondisi_rumah,
                'RT2' => $dtks->pekerjaan,
                'RT3' => $dtks->jenis_transportasi,
                'RT4' => $dtks->tanggungan,
            ];
            $dataRT = [];

            // cari berdasarkan kriteria RT1, RT2, RT3, RT4
            foreach ($rt as $simbol => $data) {
                $simbolRT = SubKriteria::whereHas('kriteria', function ($query) use ($simbol) {
                    $query->where('simbol', $simbol);
                })
                    ->where('sub_kriteria', $data)
                    ->first();

                if ($simbolRT) {
                    $simbolRT = $simbolRT->simbol;
                } else {
                    $simbolRT = 'L'; // lainnya jika sub kriteria tidak ada, contoh nya Tukang Kayu
                }

                $dataRT[Str::lower($simbol)] = $simbolRT;
            }

            // cari berdasarkan PS2 dan PS3
            $ps = [
                'PS2' => $dtks->jk,
                'PS3' => $dtks->hub_keluarga,
            ];
            $dataPS = [];

            // cari berdasarkan kriteria PS2, PS3
            foreach ($ps as $simbol => $data) {
                $simbolPS = SubKriteria::whereHas('kriteria', function ($query) use ($simbol) {
                    $query->where('simbol', $simbol);
                })
                    ->where('sub_kriteria', $data)
                    ->first();

                if ($simbolPS) {
                    $simbolPS = $simbolPS->simbol;
                } else {
                    $simbolPS = 'L'; // lainnya jika sub kriteria tidak ada, contoh nya Tukang Kayu
                }

                $dataPS[Str::lower($simbol)] = $simbolPS;
            }

            // update atau tambah berdasarkan dtks_id
            Alternatif::updateOrCreate([
                'dtks_id' => $dtks->id,
            ], [
                'rt1' => $dataRT['rt1'],
                'rt2' => $dataRT['rt2'],
                'rt3' => $dataRT['rt3'],
                'rt4' => $dataRT['rt4'],
                'ps2' => $dataPS['ps2'],
                'ps3' => $dataPS['ps3'],
            ]);
        }

        // hitung AHP RT
        $listAlternatif = Alternatif::all();

        foreach ($listAlternatif as $alternatif) {
            // hitung score RT
            $prioritasRT1 = KriteriaDetail::where('simbol', 'RT1')->first()->prioritas;
            $prioritasRT2 = KriteriaDetail::where('simbol', 'RT2')->first()->prioritas;
            $prioritasRT3 = KriteriaDetail::where('simbol', 'RT3')->first()->prioritas;
            $prioritasRT4 = KriteriaDetail::where('simbol', 'RT4')->first()->prioritas;

            $subPrioritasRT1 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'RT1');
            })->where('simbol', $alternatif->rt1)->first()->sub_prioritas;

            $subPrioritasRT2 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'RT2');
            })->where('simbol', $alternatif->rt2)->first()->sub_prioritas;

            $subPrioritasRT3 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'RT3');
            })->where('simbol', $alternatif->rt3)->first()->sub_prioritas;

            $subPrioritasRT4 = SubKriteria::whereHas('kriteria', function ($query) {
                $query->where('simbol', 'RT4');
            })->where('simbol', $alternatif->rt4)->first()->sub_prioritas;

            $scoreRT = ($prioritasRT1 * $subPrioritasRT1) + ($prioritasRT2 * $subPrioritasRT2) + ($prioritasRT3 * $subPrioritasRT3) + ($prioritasRT4 * $subPrioritasRT4);

            AHPModel::updateOrCreate([
                'dtks_id' => $alternatif->dtks_id,
            ], [
                'score_rt' => $scoreRT,
                'score_psm' => 0,
                'score_kl' => 0,
            ]);
        }

        // rank rt
        $uniqueScoreRT = AHPModel::orderBy('score_rt', 'desc')->pluck('score_rt')->unique()->all();
        $listAhpRT = AHPModel::orderBy('score_rt', 'desc')->get();

        $counter = 1;
        foreach ($uniqueScoreRT as $unique) {
            foreach ($listAhpRT as $value) {
                if ($unique === $value->score_rt) {
                    AHPModel::where('id', $value->id)->update([
                        'rank_rt' => $counter,
                    ]);
                }
            }

            $counter += 1;
        }

        $dataAHP = AHPModel::all();
        foreach ($dataAHP as $item) {
            $keterangan = 'Layak';
            if ($item->score_rt <= AHP::threshold()) {
                $keterangan = 'Tidak Layak';
            }

            AHPModel::where([
                'dtks_id' => $item->dtks_id,
            ])->update([
                'ket_rt' => $keterangan,
            ]);
        }

        return redirect()->back()->withSuccess('Berhasil data diproses');
    }

    public function create()
    {
        $title = 'Tambah';
        $warga = Warga::all();
        $bansos = Bansos::all();
        $route = route('dtks.store');
        $kondisiRumah = $this->kondisiRumah;
        $jenisTransportasi = ['Motor', 'Mobil', 'Tidak Ada'];

        return view('admin.dtks.editor', compact('title', 'warga', 'bansos', 'route', 'kondisiRumah', 'jenisTransportasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|min:16|max:16',
            // 'jenis_bansos' => 'required',
        ]);

        $warga = Warga::where('nik', $request->nik)->first();

        $kondisi = $this->getKondisiRumah($request);

        $dtks = Dtks::create([
            // warga
            'no_kk' => $warga->no_kk,
            'nik' => $warga->nik,
            'nama' => $warga->nama,
            'jk' => $warga->jk === 'LAKI_LAKI' ? 'laki-laki' : 'perempuan',
            'tgl_lahir' => $warga->tgl_lahir,
            'pekerjaan' => $warga->pekerjaan,
            'hub_keluarga' => $warga->hub_keluarga,
            'rt' => $request->rt,
            'rw' => $request->rw,

            // dtks
            'tanggungan' => $request->tanggungan,
            'kondisi_rumah' => $kondisi,
            'jenis_transportasi' => $request->transportasi,
            'kondisi_rumah_json' => $request->kondisi ?? [],

            // penanda data tersebut ditambahkan manual
            'is_added' => true,
        ]);

        foreach ($request->jenis_bansos ?? [] as $item) {
            DtksBansos::create([
                'dtks_id' => $dtks->id,
                'bansos_id' => $item,
            ]);
        }

        return redirect()->route('dtks.index')->withSuccess('Berhasil tambah data');
    }

    public function destroy($id)
    {
        Dtks::destroy($id);

        return redirect()->back()->withSuccess('Berhasil hapus data');
    }

    protected function getKondisiRumah(Request $request): string
    {
        $totalKondisiRumah = 0;
        foreach ($this->kondisiRumah as $jenis => $items) {
            foreach ($items as $item) {
                $totalKondisiRumah = $totalKondisiRumah + 1;
            }
        }

        // Layak = 0
        // Cukup Layak = 1 - 4 = 4
        // Kurang Layak = 5 - 8 = 4
        // Tidak Layak = 9 - 14 = 6
        // perbandingan nya adalah
        // 4 : 4 : 6
        // dengan total
        // 14
        $rentangCL = (4 * $totalKondisiRumah) / 14; // 4
        $rentangKL = (4 * $totalKondisiRumah) / 14; // 4
        $rentangTL = (6 * $totalKondisiRumah) / 14; // 6
        $totalPilihan = count($request->kondisi ?? []);

        $kondisi = null;
        if ($totalPilihan == 0) {
            $kondisi = 'Layak';
            // total pilihan besar sama dari 1 dan kecil sama dari rentang Cukup Layak, yaitu 4
        } else if ($totalPilihan >= 1 && $totalPilihan <= $rentangCL) {
            $kondisi = 'Cukup Layak';
            // total pilihan besar dari (rentang cukup layak + rentang kurang layak) yaitu 4 dan kecil sama dari rentang Kurang Layak, yaitu 4
        } else if ($totalPilihan >= ($rentangCL + 1) && $totalPilihan <= ($rentangCL + $rentangKL)) {
            $kondisi = 'Kurang Layak';
        } else if ($totalPilihan >= ($rentangCL + $rentangKL + 1) && $totalPilihan <= $totalKondisiRumah) {
            $kondisi = 'Tidak Layak';
        }

        return $kondisi;
    }
}
