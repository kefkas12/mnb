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
    
    public function pajak_get(Request $request)
    {
        $pajak = Variabel::where('nama_variabel','pajak')->get();

        return $pajak;
    }
    public function pajak_insert(Request $request)
    {
        $pajak = Variabel::where('nama_variabel','pajak')->first();
        $pajak = Variabel::find($pajak->id);
        $pajak->nilai = $request->pajak;
        $pajak->save();

        return $pajak;
    }
}
