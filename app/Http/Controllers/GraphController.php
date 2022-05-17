<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Detail_jurnal_umum;
use App\Detail_kwitansi;
use App\Kwitansi;
use App\Keuntungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class GraphController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function graph_kwitansi(Request $request)
    {
        dd(1);
        $year = $_GET['year'];
        $data['graph'] = Detail_kwitansi::select(DB::raw('MONTH(tanggal_tagihan) AS bulan'), DB::raw('cast(sum(dpp) as decimal(10,2)) AS dpp'), DB::raw('cast(sum(ppn) as decimal(10,2)) AS ppn'), DB::raw('cast(sum(total) as decimal(10,2)) AS subtotal'))->where(DB::raw('YEAR(tanggal_tagihan)'), '=', $year)->groupBy('bulan')->get();

        return $data;
    }
    public function graph_omset(Request $request)
    {
        $year = $_GET['year'];
        $data['graph'] = Detail_kwitansi::select(DB::raw('MONTH(tanggal_tagihan) AS bulan'), DB::raw('cast(sum(berat_bruto) as decimal(10,2)) AS berat_bruto'), DB::raw('cast(sum(potongan) as decimal(10,2)) AS potongan'), DB::raw('cast(sum(berat_bersih) as decimal(10,2)) AS berat_bersih'), DB::raw('cast(sum(harga_beli) as decimal(10,2)) AS nilai_beli'), DB::raw('cast(sum(harga_satuan) as decimal(10,2)) AS nilai_jual'))->where(DB::raw('YEAR(tanggal_tagihan)'), '=', $year)->groupBy('bulan')->get();

        return $data;
    }

}
