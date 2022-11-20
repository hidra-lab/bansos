<?php

namespace App\Http\Controllers;

use App\Imports\WargaImport;
use App\Models\Warga;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warga = Warga::latest()->get();

        return view('admin.warga.index', compact('warga'));
    }

    public function uploadDocument(Request $r)
    {
        try {
            Excel::import(new WargaImport, $r->file('uploaded_file'));
            return redirect()->back()->withSuccess('Berhasil data di upload');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors("Upload Data Gagal");
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Tambah';
        $route = route('warga.store');

        return view('admin.warga.editor', compact('title', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|min:16|max:16',
            'nik' => 'required|min:16|max:16',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'hubungan_keluarga' => 'required',
            'keterangan_warga' => 'required',
        ]);

        Warga::create([
            'no_kk' => $request->no_kk,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jk' => $request->jenis_kelamin,
            'tmp_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tanggal_lahir,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->pekerjaan,
            'status_perkawinan' => $request->status_perkawinan,
            'hub_keluarga' => $request->hubungan_keluarga,
            'ket_warga' => $request->keterangan_warga,
        ]);

        return redirect()->route('warga.index')->withSuccess('Berhasil tambah warga');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit';
        $warga = Warga::where('id', $id)->first();
        $route = route('warga.update', $id);

        return view('admin.warga.editor', compact('title', 'route', 'warga'));
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
        $request->validate([
            'no_kk' => 'required|min:16|max:16',
            'nik' => 'required|min:16|max:16',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'status_perkawinan' => 'required',
            'hubungan_keluarga' => 'required',
            'keterangan_warga' => 'required',
        ]);

        Warga::where('id', $id)
            ->update([
                'no_kk' => $request->no_kk,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'jk' => $request->jenis_kelamin,
                'tmp_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tanggal_lahir,
                'pendidikan' => $request->pendidikan,
                'pekerjaan' => $request->pekerjaan,
                'status_perkawinan' => $request->status_perkawinan,
                'hub_keluarga' => $request->hubungan_keluarga,
                'ket_warga' => $request->keterangan_warga,
            ]);

        return redirect()->route('warga.index')->withSuccess('Berhasil edit warga');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Warga::destroy($id);

        return redirect()->route('warga.index')->withSuccess('Berhasil hapus warga');
    }
}
