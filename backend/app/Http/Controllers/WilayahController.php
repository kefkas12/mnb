<?php

namespace App\Http\Controllers;

use App\Provinsi;
use App\Kota;
use App\Kecamatan;
use App\Kelurahan;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WilayahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function provinsi()
    {
        $provinsi = Provinsi::get();

        return $provinsi;
    }
    public function Kota($id_provinsi)
    {
        $kota = Kota::where('id_provinsi',$id_provinsi)->get();

        return $kota;
    }
    public function Kecamatan($id_provinsi,$id_kota)
    {
        $kecamatan = Kecamatan::where('id_provinsi',$id_provinsi)->where('id_kota',$id_kota)->get();

        return $kecamatan;
    }
    public function Kelurahan($id_provinsi,$id_kota,$id_kecamatan)
    {
        $kelurahan = Kelurahan::where('id_provinsi',$id_provinsi)->where('id_kota',$id_kota)->where('id_kecamatan',$id_kecamatan)->get();

        return $kelurahan;
    }
    
}
