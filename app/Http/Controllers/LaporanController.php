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
                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debit;

		    $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->where('jurnal_umum.tanggal_jurnal','<', $from)->first();

                    if($saldo_awal_debit){
                        $data['saldo_awal'] = $data['saldo_awal'] + $saldo_awal_debit->debit;
                    }

                    $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->where('jurnal_umum.tanggal_jurnal','<', $from)->first();

                    if($saldo_awal_kredit){
                        $data['saldo_awal'] = $data['saldo_awal'] - $saldo_awal_kredit->kredit;
                    }

                    $debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total as debit', DB::raw('0 as kredit'), 'jurnal_umum.created_at as created_at', 'jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', DB::raw('0 as debit'), 'detail_jurnal_umum.sub_total as kredit', 'jurnal_umum.created_at as created_at', 'jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $debit = $debit->toArray();
                    $kredit = $kredit->toArray();

                    $data['report'] = array_merge($debit, $kredit);
                }
                if ($kode_akun == '112.101') {
                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
                    
		    $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<',$from)->first();

                    if($saldo_awal_debit){
                        $data['saldo_awal'] = $data['saldo_awal'] + $saldo_awal_debit->debit;
                    }

                    $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select( DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<',$from)->first();

                    if($saldo_awal_kredit){
                        $data['saldo_awal'] = $data['saldo_awal'] - $saldo_awal_kredit->kredit;
                    }

                    $debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total as debit', DB::raw('0 as kredit'), 'detail_jurnal_umum.created_at as created_at','detail_jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', DB::raw('0 as debit'), 'detail_jurnal_umum.sub_total as kredit', 'detail_jurnal_umum.created_at as created_at','detail_jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $debit = $debit->toArray();
                    $kredit = $kredit->toArray();

                    $data['report'] = array_merge($debit, $kredit);
                }
            } else {
                $saldo_awal_kas = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();

                $jk = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_kas = Laporan_kas::select(DB::raw('cast(sum(debit) as decimal(65,2)) as debit'), DB::raw('cast(sum(kredit) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $saldo_awal_kas_total = 0;
                $jk_kas_total = 0;
                $report_debit_kas_total = 0;
                $report_kredit_kas_total = 0;

                $saldo_awal_kas_total = $saldo_awal_kas->saldo_awal_debit;

                if ($jk) {
                    $jk_kas_total = $jk->total;
                }
                if ($report_kas) {
                    $report_debit_kas_total = $report_kas->debit;
                    $report_kredit_kas_total = $report_kas->kredit;
                }

                $data['report_kas'] = number_format($saldo_awal_kas_total - $jk_kas_total + $report_debit_kas_total - $report_kredit_kas_total, 2, ",", ".");

                /////

                $saldo_awal_bank = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();

                $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_bank = Laporan_bank::select(DB::raw('cast(sum(debit) as decimal(65,2)) as debit'), DB::raw('cast(sum(kredit) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $saldo_awal_bank_total = 0;
                $jk_bank_total = 0;
                $report_debit_bank_total = 0;
                $report_kredit_bank_total = 0;

                $saldo_awal_bank_total = $saldo_awal_bank->saldo_awal_kredit;

                if ($jm) {
                    $jm_bank_total = $jm->total;
                }
                if ($report_bank) {
                    $report_debit_bank_total = $report_bank->debit;
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
                $data['report'] = Laporan_hutang::select('supplier', DB::raw('sum(debit) as debit'), DB::raw('sum(kredit) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get()->all();

                $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debit) as debit'))->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_debit) {
                    $data['debit'] = $saldo_awal_debit->debit;
                } else {
                    $data['debit'] = 0;
                }
                $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_kredit) {
                    $data['kredit'] = $saldo_awal_kredit->kredit;
                } else {
                    $data['kredit'] = 0;
                }
            } else {
                $data['report'] = Laporan_hutang::select(DB::raw('sum(debit) as debit'), DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get()->all();

                $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debit) as debit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_debit->debit) {
                    $data['debit'] = $saldo_awal_debit->debit;
                } else {
                    $data['debit'] = 0;
                }
                $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal_kredit->kredit) {
                    $data['kredit'] = $saldo_awal_kredit->kredit;
                } else {
                    $data['kredit'] = 0;
                }
            }
        } else {
            $data['report'] = Laporan_hutang::select('supplier', DB::raw('sum(debit) as debit'), DB::raw('sum(kredit) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get()->all();

            $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debit) as debit'))->whereDate('tanggal_jurnal', '<', $from)->first();
            if ($saldo_awal_debit) {
                $data['debit'] = $saldo_awal_debit->debit;
            } else {
                $data['debit'] = 0;
            }
            $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->first();
            if ($saldo_awal_kredit) {
                $data['kredit'] = $saldo_awal_kredit->kredit;
            } else {
                $data['kredit'] = 0;
            }
        }
        $laporan = Laporan_hutang::whereBetween('tanggal_jurnal', [$from, $to])->get();

        return $data;
    }
}
