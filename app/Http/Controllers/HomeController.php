<?php

namespace App\Http\Controllers;

use App\Detail_kwitansi;
use App\Kwitansi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function refresh(){
        $kwitansi = Kwitansi::get();
        foreach ($kwitansi as $v) {
            $detail_kwitansi = Detail_kwitansi::where('id_kwitansi', $v->id)->get();
            $kwitansi = Kwitansi::find($v->id);
            $total_dpp_kwitansi = 0;
            $total_pph_kwitansi = 0;
            $total_ppn_kwitansi = 0;
            $total_nilai_kwitansi = 0;
            foreach ($detail_kwitansi as $w) {
                $total_dpp_kwitansi += $w->dpp;
                $total_pph_kwitansi += $w->pph;
                $total_ppn_kwitansi += $w->ppn;
                $total_nilai_kwitansi += $w->total;
            }
            $kwitansi->total_dpp_kwitansi = $total_dpp_kwitansi;
            $kwitansi->total_pph_kwitansi = $total_pph_kwitansi;
            $kwitansi->total_ppn_kwitansi = $total_ppn_kwitansi;
            $kwitansi->total_nilai_kwitansi = $total_nilai_kwitansi;
            $kwitansi->save();
        }
        return 'Refresh Done'
    }
}
