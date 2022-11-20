<?php

namespace App\Http\Controllers;

use App\Models\AHPModel;

class AHPController extends Controller
{
    public function rt()
    {
        $listRT = AHPModel::all();

        return view('admin.rt.index', compact('listRT'));
    }

    public function psm()
    {
        $listPSM = AHPModel::all();

        return view('admin.psm.index', compact('listPSM'));
    }

    public function kelurahan()
    {
        $listKL = AHPModel::all();

        return view('admin.kelurahan.index', compact('listKL'));
    }
}
