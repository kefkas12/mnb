<?php

namespace App\Http\Controllers;

use App\Provinsi;
use App\Kota;
use App\Kecamatan;
use App\Kelurahan;
use App\Keuntungan;
use App\Variabel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VariabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function keuntungan_get(Request $request)
    {
        $keuntungan = Variabel::where('nama_variabel','keuntungan')->get();

        return $keuntungan;
    }
    public function keuntungan_insert(Request $request)
    {
        $keuntungan = Variabel::where('nama_variabel','keuntungan')->first();
        $keuntungan = Variabel::find($keuntungan->id);
        $keuntungan->nilai = $request->keuntungan;
        $keuntungan->save();

        return $keuntungan;
    }
    
    public function ppn_get(Request $request)
    {
        $ppn = Variabel::where('nama_variabel','ppn')->get();

        return $ppn;
    }
    public function ppn_insert(Request $request)
    {
        $ppn = Variabel::where('nama_variabel','ppn')->first();
        $ppn = Variabel::find($ppn->id);
        $ppn->nilai = $request->ppn;
        $ppn->save();

        return $ppn;
    }

    public function pph_get(Request $request)
    {
        $pph = Variabel::where('nama_variabel','pph')->get();

        return $pph;
    }
    public function pph_insert(Request $request)
    {
        $pph = Variabel::where('nama_variabel','pph')->first();
        $pph = Variabel::find($pph->id);
        $pph->nilai = $request->pph;
        $pph->save();

        return $pph;
    }
}
