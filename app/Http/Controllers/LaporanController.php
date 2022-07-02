<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_penerimaan_kas;
use App\Detail_jurnal_pengeluaran_kas;
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

    public function kas_bank(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $all = $_GET['all'];
        if ($all == '') {
            $kode_akun = $_GET['kode_akun'];
            if ($kode_akun != '') {
                if ($kode_akun == '111.001') {
                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;

                    $data['report'] = Laporan_kas::whereBetween('tanggal_jurnal', [$from, $to])->get();
                }
                if ($kode_akun == '112.101') {
                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
                    $data['report'] = Laporan_bank::whereBetween('tanggal_jurnal', [$from, $to])->get();
                }
            } else {
                $saldo_awal_kas = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();

                $jk = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_kas = Laporan_kas::select(DB::raw('cast(sum(debet) as decimal(65,2)) as debet'),DB::raw('cast(sum(kredit) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $saldo_awal_kas_total = 0;
                $jk_kas_total = 0;
                $report_debit_kas_total = 0;
                $report_kredit_kas_total = 0;

                $saldo_awal_kas_total = $saldo_awal_kas->saldo_awal_debet;

                if ($jk) {
                    $jk_kas_total = $jk->total;
                }
                if ($report_kas) {
                    $report_debit_kas_total = $report_kas->debet;
                    $report_kredit_kas_total = $report_kas->kredit;
                }
                
                $data['report_kas'] = number_format($saldo_awal_kas_total - $jk_kas_total + $report_debit_kas_total - $report_kredit_kas_total, 2, ",", ".");

                /////

                $saldo_awal_bank = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();

                $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_bank = Laporan_bank::select(DB::raw('cast(sum(debet) as decimal(65,2)) as debet'),DB::raw('cast(sum(kredit) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $saldo_awal_bank_total = 0;
                $jk_bank_total = 0;
                $report_debit_bank_total = 0;
                $report_kredit_bank_total = 0;

                $saldo_awal_bank_total = $saldo_awal_bank->saldo_awal_kredit;

                if ($jm) {
                    $jm_bank_total = $jm->total;
                }
                if ($report_bank) {
                    $report_debit_bank_total = $report_bank->debet;
                    $report_kredit_bank_total = $report_bank->kredit;
                }
                
                $data['report_bank'] = number_format(-$saldo_awal_bank_total + $jm_bank_total - $report_debit_bank_total + $report_kredit_bank_total, 2, ",", ".");
            }
        } else {
        }
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
                $report = Laporan_hutang::select('supplier', DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get();

                $data_report=[];

                foreach($report as $v){
                    $data_report = $v;
                }
                
                

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
                $data['report'] = Laporan_hutang::select(DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get();

                $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debet) as debit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_debit->debit) {
                    $data['report']->offsetSet('saldo_awal_debit', $saldo_awal_debit->debit);
                } else {
                    $data['report']->offsetSet('saldo_awal_debit', 0);
                }
                $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
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
