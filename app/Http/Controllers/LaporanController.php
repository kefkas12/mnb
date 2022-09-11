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
use App\Supplier;
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

                    $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal_debit) {
                        $data['saldo_awal'] = $data['saldo_awal'] + $saldo_awal_debit->debit;
                    }

                    $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal_kredit) {
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

                    $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal_debit) {
                        $data['saldo_awal'] = $data['saldo_awal'] + $saldo_awal_debit->debit;
                    }

                    $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal_kredit) {
                        $data['saldo_awal'] = $data['saldo_awal'] - $saldo_awal_kredit->kredit;
                    }
                    
                    $debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total as debit', DB::raw('0 as kredit'), 'detail_jurnal_umum.created_at as created_at', 'detail_jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', DB::raw('0 as debit'), 'detail_jurnal_umum.sub_total as kredit', 'detail_jurnal_umum.created_at as created_at', 'detail_jurnal_umum.updated_at as updated_at')->where('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->get();

                    $debit = $debit->toArray();
                    $kredit = $kredit->toArray();

                    $data['report'] = array_merge($debit, $kredit);
                }
            } else {
                $kode_akun = ['111.001', '112.101'];
    
                $no = 0;
                foreach ($kode_akun as $v) {
                    $data['report'][$no]['kode_akun'] = $v;
    
                    $pendapatan_usaha_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $v)->Where('tipe_akun', 'Detail')->first();
    
                    $data['report'][$no]['nama_akun'] = $pendapatan_usaha_awal->nama_perkiraan;
                    
                    if($v == '111.001'){
                        $pendapatan_usaha_awal = $pendapatan_usaha_awal->saldo_awal_debit ? $pendapatan_usaha_awal->saldo_awal_debit : 0;
                    }
                    if($v == '112.101'){
                        $pendapatan_usaha_awal = $pendapatan_usaha_awal->saldo_awal_kredit ? $pendapatan_usaha_awal->saldo_awal_kredit : 0;
                    }
    
                    $pendapatan_usaha = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as pendapatan_usaha'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_kredit', $v)->first();
    
                    $pendapatan_usaha = $pendapatan_usaha->pendapatan_usaha ? $pendapatan_usaha->pendapatan_usaha : 0;
    
                    $debit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as debit'))->whereDate('tanggal_jurnal', '<', $from)->Where('kode_akun_debit', $v)->first();
    
                    $debit = $debit->debit ? $debit->debit : 0;
    
                    $kredit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->Where('kode_akun_kredit', $v)->first();
    
                    $kredit = $kredit->kredit ? $kredit->kredit : 0;
    
                    $saldo_awal = $pendapatan_usaha_awal + $pendapatan_usaha + $debit - $kredit;
    
                    $data['report'][$no]['saldo_awal'] = $saldo_awal;

                    $debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(SUM(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $v)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->first();
    
                    $debit = $debit->debit ? $debit->debit : 0;

                    $kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(SUM(detail_jurnal_umum.sub_total) as decimal(65,2)) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $v)->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->first();
    
                    $kredit = $kredit->kredit ? $kredit->kredit : 0;
    
                    $data['report'][$no]['debit'] = $debit;
                    $data['report'][$no]['kredit'] = $kredit;
                    
                    $data['report'][$no]['saldo'] = $saldo_awal + $debit - $kredit;
    
                    $no++;
                }
            }
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
                $data['report'] = Supplier::select('nama_perusahaan')->get();
                // dd($data);
                $no = 0;
                foreach ($data['report'] as $v) {
                    $hutang_supplier_awal = Supplier::where('nama_perusahaan', $data['report'][$no]->nama_perusahaan)->first();
                    $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->where('detail_jurnal_umum.kode_akun_debit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal_debit) {
                        $hutang_supplier_debit = $saldo_awal_debit->debit;
                    } else {
                        $hutang_supplier_debit = 0;
                    }

                    $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->where('detail_jurnal_umum.kode_akun_kredit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();
                    if ($saldo_awal_kredit) {
                        $hutang_supplier_kredit = $saldo_awal_kredit->kredit;
                    } else {
                        $hutang_supplier_kredit = 0;
                    }
                    $saldo_awal_temp = 0;
                    if ($hutang_supplier_awal) {
                        $saldo_awal_temp = $hutang_supplier_awal->saldo_awal - $hutang_supplier_debit + $hutang_supplier_kredit;
                        $data['report'][$no]->offsetSet('saldo_awal', number_format($saldo_awal_temp, 2, ".", ""));
                    }else{
                        $data['report'][$no]->offsetSet('saldo_awal', 0);
                    }

                    $debit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as debit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_debit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->first();
                    $debit_temp = 0;
                    if ($debit) {
                        $debit_temp = $debit->debit;
                        $data['report'][$no]->offsetSet('debit', $debit->debit);
                    } else {
                        $data['report'][$no]->offsetSet('debit', 0);
                    }

                    $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->first();
                    $kredit_temp = 0;
                    if ($kredit) {
                        $kredit_temp = $kredit->kredit;
                        $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                    } else {
                        $data['report'][$no]->offsetSet('kredit', 0);
                    }
                    $data['report'][$no]['saldo'] = $saldo_awal_temp - $debit_temp + $kredit_temp;
                    $no++;
                }
            } else {

                $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'kode_akun_debit', 'kode_akun_kredit', 'keterangan', DB::raw('if(kode_akun_debit = "220.001", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "220.001", sub_total, 0) as kredit'))->where('nama_perusahaan_supplier', $supplier)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where(function ($query) {
                    $query->where('kode_akun_kredit', '220.001')
                        ->orWhere('kode_akun_debit', '220.001');
                })->orderBy('tanggal_jurnal', 'ASC')->get();

                $hutang_supplier_awal = Supplier::where('nama_perusahaan', $supplier)->first();

                $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('nama_perusahaan_supplier', $supplier)->where('detail_jurnal_umum.kode_akun_debit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                if ($saldo_awal_debit) {
                    $hutang_supplier_debit = $saldo_awal_debit->debit;
                }

                $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('nama_perusahaan_supplier', $supplier)->where('detail_jurnal_umum.kode_akun_kredit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                if ($saldo_awal_kredit) {
                    $hutang_supplier_kredit = $saldo_awal_kredit->kredit;
                }

                $data['saldo_awal'] = number_format($hutang_supplier_awal->saldo_awal - $hutang_supplier_debit + $hutang_supplier_kredit, 2, ".", "");
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
