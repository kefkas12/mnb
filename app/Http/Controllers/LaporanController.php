<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_umum;
use App\Jurnal_umum;
use App\Laporan_bank;
use App\Laporan_kas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kas(Request $request)
    {
        // $laporan_kas = new Laporan_kas;
        // $laporan_kas->generate_laporan_kas();

        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $laporan = Laporan_kas::whereBetween('tanggal_jurnal', [$from, $to])->get();
        return $laporan;
    }
    public function bank(Request $request)
    {
        $laporan_bank = new Laporan_bank;
        $laporan_bank->generate_laporan_bank();

        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $laporan = Laporan_bank::whereBetween('tanggal_jurnal', [$from, $to])->get();
        return $laporan;
    }
}
