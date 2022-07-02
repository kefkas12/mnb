<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_umum;
use App\Jurnal_umum;
use App\Laporan_bank;
use App\Laporan_hutang;
use App\Laporan_kas;
use App\Perkiraan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $laporan_kas = new Laporan_kas;
        // $laporan_kas->generate_laporan_kas();

        // $laporan_bank = new Laporan_bank;
        // $laporan_bank->generate_laporan_bank();

        // $laporan_hutang = new Laporan_hutang;
        // $laporan_hutang->generate_laporan_hutang();
    }
    public function kas(Request $request)
    {
        $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
        $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $data['report'] = Laporan_kas::whereBetween('tanggal_jurnal', [$from, $to])->get();
        return $data;
    }
    public function bank(Request $request)
    {
        $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
        $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $data['report'] = Laporan_bank::whereBetween('tanggal_jurnal', [$from, $to])->get();
        return $data;
    }
    public function hutang(Request $request)
    {

        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $supplier = $_GET['supplier'];

        $all = $_GET['all'];
        if ($all == '') {
            if ($supplier == '') {
                $data['report'] = Laporan_hutang::select('supplier', DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get();

                $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debet) as debit'))->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_debit) {
                    $data['report']->offsetSet('saldo_awal_debit', $saldo_awal_debit->debit);
                } else {
                    $data['report']->offsetSet('saldo_awal_debit', 0);
                }
                $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_kredit) {
                    $data['report']->offsetSet('saldo_awal_kredit', $saldo_awal_kredit->kredit);
                } else {
                    $data['report']->offsetSet('saldo_awal_kredit', 0);
                }
            } else {
                $data['report'] = Laporan_hutang::select(DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->where('supplier',$supplier)->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get();

                $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debet) as debit'))->where('supplier',$supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_debit->debit) {
                    $data['report']->offsetSet('saldo_awal_debit', $saldo_awal_debit->debit);
                } else {
                    $data['report']->offsetSet('saldo_awal_debit', 0);
                }
                $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->where('supplier',$supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_kredit->kredit) {
                    $data['report']->offsetSet('saldo_awal_kredit', $saldo_awal_kredit->kredit);
                } else {
                    $data['report']->offsetSet('saldo_awal_kredit', 0);
                }
            }
        } else {
            $data['report'] = Laporan_hutang::select('supplier', DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get();

            $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debet) as debit'))->whereDate('tanggal_jurnal', '<', $from)->first();
            if ($saldo_awal_debit) {
                $data['report']->offsetSet('saldo_awal_debit', $saldo_awal_debit->debit);
            } else {
                $data['report']->offsetSet('saldo_awal_debit', 0);
            }
            $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->first();
            if ($saldo_awal_kredit) {
                $data['report']->offsetSet('saldo_awal_kredit', $saldo_awal_kredit->kredit);
            } else {
                $data['report']->offsetSet('saldo_awal_kredit', 0);
            }
        }
        $laporan = Laporan_hutang::whereBetween('tanggal_jurnal', [$from, $to])->get();

        return $data;
    }
}
