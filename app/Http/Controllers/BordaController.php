<?php

namespace App\Http\Controllers;

use App\Models\Borda;
use App\Models\Dtks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class BordaController extends Controller
{
    public function index()
    {
        // ambil data tahun di request 
        if (request('tahun')) {
            $listBorda = Borda::whereRaw('year(created_at) = ?', [request('tahun')])
                ->get();
        } else {
            $listBorda = Borda::all();
        }

        $years = Borda::selectRaw('year(created_at) as year')
            ->groupBy(DB::raw('year(created_at)'))
            ->pluck('year')
            ->toArray();

        return view('admin.borda.index', compact('listBorda', 'years'));
    }

    public function cetak()
    {
        $path = Storage::path('template/surat2.docx');
        $savedPath = Storage::path('template/hasil-surat.docx');

        $template = new TemplateProcessor($path);

        $data = [];
        $rtrw = Dtks::selectRaw("DISTINCT CONCAT(rt, '/', rw) AS rt_rw")
            ->orderBy('rt')
            ->pluck('rt_rw')
            ->all();

        // clone row berdasarkan banyaknya rt rw
        $countRow = count($rtrw);
        $template->cloneRow('namaKelurahan', $countRow);

        foreach ($rtrw as $item) {
            $data[] = [
                'rt_rw' => $item,
                'rt' => explode('/', $item)[0],
                'rw' => explode('/', $item)[1],
                'layak' => 0,
                'tidak_layak' => 0,
            ];
        }

        $borda = Borda::all();
        foreach ($borda as $item) {
            $rtrw = $item->dtks->rt . '/' . $item->dtks->rw;

            foreach ($data as &$dataItem) {
                if ($rtrw == $dataItem['rt_rw'] && $item->kelayakan == 'Layak') {
                    $dataItem['layak'] += 1;
                }

                if ($rtrw == $dataItem['rt_rw'] && $item->kelayakan == 'Tidak Layak') {
                    $dataItem['tidak_layak'] += 1;
                }
            }
            unset($dataItem);
        }

        // set value berdasarkan template
        $template->setValue('kelurahan', 'GANTING PARAK GADANG');
        $template->setValue('kelurahan2', 'Ganting Parak Gadang');

        for ($i = 1; $i <= $countRow; $i++) {
            $item = $data[$i - 1];
            $rt = str_pad($item['rt'], 2, '0', STR_PAD_LEFT);
            $rw = str_pad($item['rw'], 2, '0', STR_PAD_LEFT);

            // merge vertical
            $template->setValue(
                "namaKelurahan#$i",
                '<w:tcPr>
                    <w:vMerge w:val="continue"/>
                </w:tcPr>
                <w:p>
                    <w:t>Ganting Parak Gadang</w:t>
                </w:p>'
            );

            $template->setValue("rt/rw#$i", "RT.$rt / RW.$rw");
            $template->setValue("totalLayak#$i", $item['layak']);
            $template->setValue("totalTidakLayak#$i", $item['tidak_layak']);
            $template->setValue("jumlah#$i", $item['layak'] + $item['tidak_layak']);
        }

        // simpan hasil yang sudah diubah
        $template->saveAs($savedPath);

        // download document
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        return response()->download($savedPath, 'Hasil Surat.docx', $headers);
    }
}
