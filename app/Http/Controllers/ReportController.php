<?php

namespace App\Http\Controllers;

use App\Kwitansi;
use App\Detail_kwitansi;
use App\Perkiraan;
use App\Satuan;
use App\Detail_jurnal_umum;
use App\Detail_jurnal_pengeluaran_kas;
use App\Detail_jurnal_penerimaan_kas;
use App\Jurnal_umum;
use App\Laporan_hutang;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kwitansi(Request $request)
    {

        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $all = $_GET['all'];
        if ($all == '') {
            $customer = $_GET['nama_customer'];
            if ($customer == '') {
                $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.11 as decimal(65,2)) as ppn'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.11 as decimal(65,2)) as sub_total'), 'detail_kwitansi.keterangan')->groupBy('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', 'detail_kwitansi.keterangan')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->orderBy('kwitansi.tanggal_kwitansi', 'DESC')->get();
            } else {
                $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.11 as decimal(65,2)) as ppn'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.11 as decimal(65,2)) as sub_total'), 'detail_kwitansi.keterangan')->groupBy('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', 'detail_kwitansi.keterangan')->where('detail_kwitansi.nama_customer', $customer)->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->orderBy('kwitansi.tanggal_kwitansi', 'DESC')->get();
            }
        } else {
            $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.nama_customer', DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.11 as decimal(65,2)) as ppn '), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.11 as decimal(65,2)) as sub_total'))->groupBy('kwitansi.nama_customer')->groupBy('nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function omset_pks(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $all = $_GET['all'];
        if ($all == '') {
            $pks = $_GET['nama_customer'];
            if ($pks == '') {
                $data['report'] = Detail_kwitansi::select('tanggal', 'nomor', 'nomor_polisi', 'berat_bruto', 'satuan_berat_bruto', 'potongan', 'satuan_potongan', 'berat_bersih', 'satuan_berat_bersih', 'harga_satuan', DB::raw('berat_bersih*harga_satuan  as jumlah'))->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->orderBy('tanggal', 'DESC')->get();
            } else {
                $data['report'] = Detail_kwitansi::select('tanggal', 'nomor', 'nomor_polisi', 'berat_bruto', 'satuan_berat_bruto', 'potongan', 'satuan_potongan', 'berat_bersih', 'satuan_berat_bersih', 'harga_satuan', DB::raw('berat_bersih*harga_satuan  as jumlah'))->where('detail_kwitansi.nama_customer', $pks)->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->orderBy('tanggal', 'DESC')->get();
            }
        } else {
            $data['report'] = Detail_kwitansi::select('nama_customer', DB::raw('cast(sum(berat_bruto) as decimal(65,2)) as berat_bruto'), 'satuan_berat_bruto', DB::raw('cast(sum(potongan) as decimal(65,2)) as potongan'), 'satuan_potongan', DB::raw('cast(sum(berat_bersih) as decimal(65,2)) as berat_bersih'), 'satuan_berat_bersih', DB::raw('cast(sum(berat_bersih*harga_satuan) as decimal(65,2)) as jumlah'))->groupBy('nama_customer', 'satuan_berat_bruto', 'satuan_potongan', 'satuan_berat_bersih')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
        }
        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function omset_ongkos_bongkar(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $all = $_GET['all'];
        if ($all == '') {
            $pks = $_GET['nama_customer'];
            if ($pks == '') {
                $data['report'] = Detail_kwitansi::select('tanggal', 'nomor', 'nomor_polisi', 'berat_bruto', 'satuan_berat_bruto', 'potongan', 'satuan_potongan', 'berat_bersih', 'satuan_berat_bersih', 'ongkos_bongkar')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->orderBy('tanggal', 'DESC')->get();
            } else {
                $data['report'] = Detail_kwitansi::select('tanggal', 'nomor', 'nomor_polisi', 'berat_bruto', 'satuan_berat_bruto', 'potongan', 'satuan_potongan', 'berat_bersih', 'satuan_berat_bersih', 'ongkos_bongkar')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->where('detail_kwitansi.nama_customer', $pks)->orderBy('tanggal', 'DESC')->get();
            }
        } else {
            $data['report'] = Detail_kwitansi::select('nama_customer', DB::raw('cast(sum(berat_bruto) as decimal(65,2)) as berat_bruto'), 'satuan_berat_bruto', DB::raw('cast(sum(potongan) as decimal(65,2)) as potongan'), 'satuan_potongan', DB::raw('cast(sum(berat_bersih) as decimal(65,2)) as berat_bersih'), 'satuan_berat_bersih', DB::raw('cast(sum(ongkos_bongkar) as decimal(65,2)) as ongkos_bongkar'))->groupBy('nama_customer', 'satuan_berat_bruto', 'satuan_potongan', 'satuan_berat_bersih')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function modal_usaha(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $all = $_GET['all'];
        if ($all == '') {
            $kode_akun = $_GET['kode_akun'];
            if ($kode_akun == '') {
                $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debet = "0" ,-saldo_awal_kredit  , saldo_awal_debet)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun', 'nama_perkiraan')->get();
            } else {
                $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->first();
                if ($kode_akun == '310.001') {
                    $data['saldo_awal'] = '(' . $saldo_awal->saldo_awal_kredit . ')';
                } else {
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;
                }
            }
        } else {
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debet = "0" ,-saldo_awal_kredit  , saldo_awal_debet)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun', 'nama_perkiraan')->get();
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function pembagian_kongsi(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $kode_akun = $_GET['kode_akun'];
        if ($kode_akun == '') {
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', 'like', '32%')->Where('tipe_akun', 'Detail')->get();
        } else {
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->get();
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function pendapatan_usaha(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $kode_akun = $_GET['kode_akun'];
        if ($kode_akun == '') {
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit', 'detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', 'like', '41%')->groupBy('kode_akun_kredit', 'detail_kode_akun_kredit')->get();
        } else {

            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode_akun)->orderBy('tanggal_jurnal', 'DESC')->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function pendapatan_lain(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $kode_akun = $_GET['kode_akun'];
        if ($kode_akun == '') {
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit', 'detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '42%')->groupBy('kode_akun_kredit', 'detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        } else {
            $data['jm'] = Detail_jurnal_penerimaan_kas::leftJoin('jurnal_penerimaan_kas', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas', '=', 'jurnal_penerimaan_kas.id')->select('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'detail_jurnal_penerimaan_kas.kode_akun_debit', 'jurnal_penerimaan_kas.nomor_bukti', 'detail_jurnal_penerimaan_kas.keterangan', DB::raw('cast(detail_jurnal_penerimaan_kas.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_penerimaan_kas.tanggal_jurnal', [$from, $to])->where('detail_jurnal_penerimaan_kas.kode_akun_kredit', $kode_akun)->orderBy('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'DESC')->first();
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode_akun)->orderBy('tanggal_jurnal', 'DESC')->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function pendapatan_uang_muka(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $kode_akun = $_GET['kode_akun'];
        if ($kode_akun == '') {
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit', 'detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '43%')->groupBy('kode_akun_kredit', 'detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        } else {
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode_akun)->orderBy('tanggal_jurnal', 'DESC')->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function buku_besar(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $dir = $_GET['dir'];
        if ($dir == 'hutang_supplier') {
            $supplier = $_GET['supplier'];
            $data['report'] = Laporan_hutang::select(DB::raw('sum(debet) as debit'), DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get()->all();

            $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debet) as debit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
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
        } elseif ($dir == 'piutang_customer') {
            $customer = $_GET['customer'];

            $data['debit'] = Kwitansi::select('tanggal_kwitansi as tanggal', 'keterangan_kwitansi as keterangan', DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as debit '))->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $customer)->orderBy('created_at', 'DESC')->get();

            $data['kredit'] = Detail_jurnal_umum::select('tanggal_jurnal as tanggal', 'keterangan', DB::raw('sub_total as kredit'))->where('kode_akun_kredit', '113.101')->whereBetween('tanggal_jurnal', [$from, $to])->where('nama_perusahaan_customer', $customer)->orderBy('created_at', 'DESC')->get();

            $saldo_awal = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->where('nama_customer', $customer)->whereDate('tanggal_kwitansi', '<', $from)->first();

            if ($saldo_awal->debit) {
                $data['saldo_awal'] = $saldo_awal->debit;
            } else {
                $data['saldo_awal'] = 0;
            }
        } elseif ($dir == 'biaya') {
            $kode = $_GET['kode'];

            // $saldo_awal = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debet'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $report = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debet'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $data['saldo_awal'] = $saldo_awal->debet + $report->debet;

            // $data['jk'] = Detail_jurnal_pengeluaran_kas::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debet'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_pengeluaran_kas.created_at', 'DESC')->get();

            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);
            
            // $data['report'] = (object)array_merge_recursive((array)$debit , (array)$kredit);
            // $data['report'] = json_encode(
            //     array_merge(
            //         json_decode($debit, true),
            //         json_decode($kredit, true)
            //     )
            // );
            // 
        } elseif ($dir == 'hutang_dagang') {
            $kode = $_GET['kode'];

            // $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal','keterangan','sub_total')->Where('kode_akun_kredit', $kode)->whereBetween('tanggal_jurnal', [$from, $to])->get();
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('if(kode_akun_debit = "' . $kode . '", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "' . $kode . '", sub_total, 0) as kredit'))->where('kode_akun_kredit', $kode)->orWhere('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '>=', $from)->whereDate('tanggal_jurnal', '<=', $to)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();
            if ($saldo_awal->saldo) {
                $data['saldo_awal'] = $saldo_awal->saldo;
            } else {
                $data['saldo_awal'] = 0;
            }
        }


        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

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

                $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier')->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->get();
                // dd($data);
                $no = 0;
                foreach ($data['report'] as $v) {
                    $data['nama_perusahaan_supplier'] = [];
                    array_push($data['nama_perusahaan_supplier'], $v->nama_perusahaan_supplier);
                    $saldo_awal = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo_awal'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal) {
                        $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                    } else {
                        $data['report'][$no]->offsetSet('saldo_awal', 0);
                    }

                    $debit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as debit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_debit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->first();
                    if ($debit) {
                        $data['report'][$no]->offsetSet('debit', $debit->debit);
                    } else {
                        $data['report'][$no]->offsetSet('debit', 0);
                    }

                    $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->first();
                    if ($kredit) {
                        $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                    } else {
                        $data['report'][$no]->offsetSet('kredit', 0);
                    }
                    $no++;
                }
            } else {
                // dd($supplier);
                // $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier','kode_akun_debit','kode_akun_kredit','keterangan',DB::raw('if(kode_akun_debit = "220.001", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "220.001", sub_total, 0) as kredit'))
                //     ->where('nama_perusahaan_supplier',$supplier)
                //     ->where(function ($query){
                //         $query->where('kode_akun_kredit', '220.001')
                //               ->orWhere('kode_akun_debit', '220.001');
                //     })
                //     ->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])
                //     ->get();
                $data['debit'] = Detail_jurnal_umum::select('tanggal_jurnal as tanggal', 'keterangan', 'sub_total as debit')
                    ->where('nama_perusahaan_supplier', $supplier)
                    ->where('kode_akun_debit', '220.001')
                    ->whereBetween('tanggal_jurnal', [$from, $to])
                    ->orderBy('tanggal_jurnal', 'DESC')
                    ->get();

                $data['kredit'] = Detail_jurnal_umum::select('tanggal_jurnal as tanggal', 'keterangan', 'sub_total as kredit')
                    ->where('nama_perusahaan_supplier', $supplier)
                    ->where('kode_akun_kredit', '220.001')
                    ->whereBetween('tanggal_jurnal', [$from, $to])
                    ->orderBy('tanggal_jurnal', 'DESC')
                    ->get();

                $saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
                if ($saldo_awal->saldo) {
                    $data['saldo_awal'] = $saldo_awal->saldo;
                } else {
                    $data['saldo_awal'] = 0;
                }
            }
        } else {
            $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier')->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->get();
            $no = 0;
            foreach ($data['report'] as $v) {
                $saldo_awal = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo_awal'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();

                if ($saldo_awal) {
                    $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                } else {
                    $data['report'][$no]->offsetSet('saldo_awal', 0);
                }

                $debit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as debit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_debit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->first();
                if ($debit) {
                    $data['report'][$no]->offsetSet('debit', $debit->debit);
                } else {
                    $data['report'][$no]->offsetSet('debit', 0);
                }

                $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->first();
                if ($kredit) {
                    $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                } else {
                    $data['report'][$no]->offsetSet('kredit', 0);
                }
                $no++;
            }
        }
        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function piutang(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        $customer = $_GET['customer'];

        $all = $_GET['all'];
        if ($all == '') {
            if ($customer == '') {
                $data['report'] = Kwitansi::select('nama_customer')->groupBy('nama_customer')->get();
                $no = 0;
                foreach ($data['report'] as $v) {
                    $saldo_awal = Kwitansi::select('nama_customer', DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as saldo_awal'))->where('nama_customer', $data['report'][$no]->nama_customer)->groupBy('nama_customer')->whereDate('tanggal_kwitansi', '<', $from)->first();
                    if ($saldo_awal) {
                        $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                    } else {
                        $data['report'][$no]->offsetSet('saldo_awal', 0);
                    }

                    $debit = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->groupBy('nama_customer')->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $data['report'][$no]->nama_customer)->first();
                    if ($debit) {
                        $data['report'][$no]->offsetSet('debit', $debit->debit);
                    } else {
                        $data['report'][$no]->offsetSet('debit', 0);
                    }

                    $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_customer')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '113.101')->where('nama_perusahaan_customer', $data['report'][$no]->nama_customer)->first();
                    if ($kredit) {
                        $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                    } else {
                        $data['report'][$no]->offsetSet('kredit', 0);
                    }
                    $no++;
                }
            } else {
                $data['debit'] = Kwitansi::select('tanggal_kwitansi as tanggal', 'keterangan_kwitansi as keterangan', DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as debit '))->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $customer)->orderBy('tanggal_kwitansi', 'DESC')->get();

                $data['kredit'] = Detail_jurnal_umum::select('tanggal_jurnal as tanggal', 'keterangan', DB::raw('sub_total as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '113.101')->where('nama_perusahaan_customer', $customer)->orderBy('tanggal_jurnal', 'DESC')->get();

                $saldo_awal = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->where('nama_customer', $customer)->whereDate('tanggal_kwitansi', '<', $from)->first();

                if ($saldo_awal->debit) {
                    $data['saldo_awal'] = $saldo_awal->debit;
                } else {
                    $data['saldo_awal'] = 0;
                }
            }
        } else {
            $data['report'] = Kwitansi::select('nama_customer')->groupBy('nama_customer')->get();
            $no = 0;
            foreach ($data['report'] as $v) {
                $saldo_awal = Kwitansi::select('nama_customer', DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as saldo_awal'))->where('nama_customer', $data['report'][$no]->nama_customer)->groupBy('nama_customer')->whereDate('tanggal_kwitansi', '<', $from)->first();
                if ($saldo_awal) {
                    $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                } else {
                    $data['report'][$no]->offsetSet('saldo_awal', 0);
                }

                $debit = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->groupBy('nama_customer')->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $data['report'][$no]->nama_customer)->first();
                if ($debit) {
                    $data['report'][$no]->offsetSet('debit', $debit->debit);
                } else {
                    $data['report'][$no]->offsetSet('debit', 0);
                }

                $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_customer')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '113.101')->where('nama_perusahaan_customer', $data['report'][$no]->nama_customer)->first();
                if ($kredit) {
                    $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                } else {
                    $data['report'][$no]->offsetSet('kredit', 0);
                }
                $no++;
            }
        }

        $data['tanggal'] = $from . ' ' . $to;

        $datediff = strtotime($to) - strtotime($from);

        $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

        return $data;
    }
    public function pajak_keluaran(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.no_seri_faktur_pajak', 'kwitansi.tanggal_kwitansi', 'kwitansi.keterangan_kwitansi', 'kwitansi.nama_customer', DB::raw('SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as dpp'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.11 as decimal(65,2)) as ppn'), DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.11 as decimal(65,2)) as sub_total'))->groupBy('kwitansi.no_kwitansi', 'kwitansi.no_seri_faktur_pajak', 'kwitansi.tanggal_kwitansi', 'kwitansi.keterangan_kwitansi', 'kwitansi.nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->orderBy('kwitansi.tanggal_kwitansi', 'DESC')->get();
        return $data;
    }
    public function kas(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $all = $_GET['all'];
        if ($all == '') {
            $kode_akun = $_GET['kode_akun'];
            if ($kode_akun == '') {
                //$saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();

                $saldo_awal_kas = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();

                $jk = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_debit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', '111.001')->first();

                $report_kredit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_kredit', '111.001')->first();

                $saldo_awal_1 = 0;
                $jk_1 = 0;
                $report_debit_1 = 0;
                $report_kredit_1 = 0;

                $saldo_awal_1 = $saldo_awal_kas->saldo_awal_debet;

                if ($jk) {
                    $jk_1 = $jk->total;
                }
                if ($report_debit_kas) {
                    $report_debit_1 = $report_debit_kas->total;
                }
                if ($report_kredit_kas) {
                    $report_kredit_1 = $report_kredit_kas->total;
                }


                $data['report_kas'] = number_format($saldo_awal_1 - $jk_1 + $report_debit_1 - $report_kredit_1, 2, ",", ".");

                $saldo_awal_bank = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();

                $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_debit_bank = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_kredit', '112.101')->first();

                $report_kredit_bank = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', '112.101')->first();

                $saldo_awal_2 = 0;
                $jm_2 = 0;
                $report_debit_2 = 0;
                $report_kredit_2 = 0;

                $saldo_awal_2 = $saldo_awal_bank->saldo_awal_kredit;

                if ($jm) {
                    $jm_2 = $jm->total;
                }
                if ($report_debit_bank) {
                    $report_debit_2 = $report_debit_bank->total;
                }
                if ($report_kredit_bank) {
                    $report_kredit_2 = $report_kredit_bank->total;
                }

                $data['report_bank'] = number_format(-$saldo_awal_2 + $jm_2 - $report_debit_2 + $report_kredit_2, 2, ",", ".");
            } else {
                if ($kode_akun == '111.001') {
                    $data['jurnal_pengeluaran'] = Detail_jurnal_pengeluaran_kas::leftJoin('jurnal_pengeluaran_kas', 'detail_jurnal_pengeluaran_kas.id_jurnal_pengeluaran_kas', '=', 'jurnal_pengeluaran_kas.id')->select('detail_jurnal_pengeluaran_kas.tanggal_jurnal', 'jurnal_pengeluaran_kas.nomor_bukti', 'detail_jurnal_pengeluaran_kas.kode_akun_debit', 'detail_jurnal_pengeluaran_kas.keterangan', 'detail_jurnal_pengeluaran_kas.sub_total')->whereBetween('detail_jurnal_pengeluaran_kas.tanggal_jurnal', [$from, $to])->orderBy('detail_jurnal_pengeluaran_kas.tanggal_jurnal', 'DESC')->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal', 'detail_jurnal_umum.kode_akun_debit', 'detail_jurnal_umum.kode_akun_kredit', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->orderBy('detail_jurnal_umum.tanggal_jurnal', 'DESC')->get();

                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;
                } else if ($kode_akun == '112.101') {
                    //tv check again
                    $data['jurnal_penerimaan'] = Detail_jurnal_penerimaan_kas::leftJoin('jurnal_penerimaan_kas', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas', '=', 'jurnal_penerimaan_kas.id')->select('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_bukti', 'detail_jurnal_penerimaan_kas.kode_akun_kredit', 'detail_jurnal_penerimaan_kas.keterangan', 'detail_jurnal_penerimaan_kas.sub_total')->whereBetween('detail_jurnal_penerimaan_kas.tanggal_jurnal', [$from, $to])->orderBy('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'DESC')->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal', 'detail_jurnal_umum.kode_akun_debit', 'detail_jurnal_umum.kode_akun_kredit', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->orderBy('detail_jurnal_umum.tanggal_jurnal', 'DESC')->get();

                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
                }
            }
        } else {
            $data['report'] = Perkiraan::leftjoin('detail_jurnal_umum', 'perkiraan.kode_akun', '=', 'detail_jurnal_umum.kode_akun_debit')->select('perkiraan.kode_akun', 'perkiraan.nama_perkiraan', DB::raw('cast(perkiraan.saldo_awal_debet + sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as saldo'))->Where('perkiraan.kode_akun', 'like', '111%')->Where('perkiraan.tipe_akun', 'Detail')->orWhere('perkiraan.kode_akun', 'like', '112%')->Where('perkiraan.tipe_akun', 'Detail')->groupBy('perkiraan.kode_akun', 'perkiraan.nama_perkiraan', 'perkiraan.saldo_awal_debet')->get();
        }

        return $data;
    }

    public function laba_rugi(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $data['penjualan'] = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_satuan) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->first();

        $data['penjualan'] = $data['penjualan']->penjualan;
        //pendapatan_lainnya

        $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.001')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
        if ($saldo_awal->saldo) {
            $saldo_awal = $saldo_awal->saldo;
        } else {
            $saldo_awal = 0;
        }
        $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '420.001')->first();
        if ($jm->total) {
            $jm = $jm->total;
        } else {
            $jm = 0;
        }
        $pendapatan_lainnya = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '420.001')->first();
        if ($pendapatan_lainnya->saldo) {
            $pendapatan_lainnya = $pendapatan_lainnya->saldo;
        } else {
            $pendapatan_lainnya = 0;
        }

        $data['pendapatan_lainnya'] = $saldo_awal + $jm + $pendapatan_lainnya;

        //pendapatan_bunga_bank

        $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.002')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
        if ($saldo_awal->saldo) {
            $saldo_awal = $saldo_awal->saldo;
        } else {
            $saldo_awal = 0;
        }
        $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '420.002')->first();
        if ($jm->total) {
            $jm = $jm->total;
        } else {
            $jm = 0;
        }
        $pendapatan_bunga_bank = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '420.002')->first();
        if ($pendapatan_bunga_bank->saldo) {
            $pendapatan_bunga_bank = $pendapatan_bunga_bank->saldo;
        } else {
            $pendapatan_bunga_bank = 0;
        }

        $data['pendapatan_bunga_bank'] = $saldo_awal + $jm + $pendapatan_bunga_bank;

        $data['pembelian'] = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_beli) as pembelian'))->whereBetween('tanggal_tagihan', [$from, $to])->first();

        $data['pembelian'] = $data['pembelian']->pembelian;

        $data['biaya_operasional'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '51%')->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();

        $data['biaya_non_operasional'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '52%')->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();

        $data['biaya_administrasi_umum'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '53%')->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();

        $data['biaya_pajak'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '54%')->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();

        $data['biaya_lain'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '55%')->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
        return $data;
    }
    public function neraca(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $hutang_pajak_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(-saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '230.001')->Where('tipe_akun', 'Detail')->first();
        $modal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '310.001')->Where('tipe_akun', 'Detail')->first();
        //
        $laba_ditahan = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(-saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(-saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '310.002')->Where('tipe_akun', 'Detail')->first();

        $piutang_dagang_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(-saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '113.101')->Where('tipe_akun', 'Detail')->first();

        $piutang_dagang = Kwitansi::select(DB::raw('cast(SUM(total_dpp_kwitansi+total_ppn_kwitansi) as decimal(65,2)) as piutang_dagang'))->whereDate('tanggal_kwitansi', '<=', $to)->first();
        if ($piutang_dagang->piutang_dagang) {
            $piutang_dagang = $piutang_dagang->piutang_dagang;
        } else {
            $piutang_dagang = 0;
        }

        $uang_muka_pajak = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as uang_muka_pajak'))->whereDate('tanggal_jurnal', '<=', $to)->where('kode_akun_debit', '540.003')->first();

        if ($uang_muka_pajak->uang_muka_pajak) {
            $uang_muka_pajak = $uang_muka_pajak->uang_muka_pajak;
        } else {
            $uang_muka_pajak = 0;
        }

        $hutang_dagang = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as hutang_dagang'))->whereDate('tanggal_jurnal', '<=', $to)->where('kode_akun_kredit', 'like', '220.00%')->first();

        if ($hutang_dagang->hutang_dagang) {
            $hutang_dagang = $hutang_dagang->hutang_dagang;
        } else {
            $hutang_dagang = 0;
        }

        $hutang_pajak = Kwitansi::select(DB::raw('cast(SUM(total_ppn_kwitansi) as decimal(65,2)) as hutang_pajak'))->whereDate('tanggal_kwitansi', '<=', $to)->first();
        if ($hutang_pajak->hutang_pajak) {
            $hutang_pajak = $hutang_pajak->hutang_pajak;
        } else {
            $hutang_pajak = 0;
        }

        $laba_tahun_berjalan = Detail_kwitansi::select(DB::raw('cast(SUM((harga_satuan-harga_beli)*berat_bersih) as decimal(65,2)) as laba_tahun_berjalan'))->whereDate('tanggal', '<=', $to)->first();
        if ($laba_tahun_berjalan->laba_tahun_berjalan) {
            $laba_tahun_berjalan = $laba_tahun_berjalan->laba_tahun_berjalan;
        } else {
            $laba_tahun_berjalan = 0;
        }

        /////////////////////kas//////////////////////////
        $kas = Perkiraan::select(DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
        $jk = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereDate('tanggal_jurnal', '<=', $to)->first();

        $report_debit_kas = Detail_jurnal_umum::select(DB::raw('cast(sub_total as decimal(65,2)) as total'))->where('kode_akun_debit', '111.001')->whereDate('tanggal_jurnal', '<=', $to)->first();

        $report_kredit_kas = Detail_jurnal_umum::select(DB::raw('cast(sub_total as decimal(65,2)) as total'))->where('kode_akun_kredit', '111.001')->whereDate('tanggal_jurnal', '<=', $to)->first();

        $jk_1 = 0;
        $report_debit_1 = 0;
        $report_kredit_1 = 0;

        $kas_awal = $kas->saldo_awal_debet;

        if ($jk) {
            $jk_1 = $jk->total;
        }
        if ($report_debit_kas) {
            $report_debit_1 = $report_debit_kas->total;
        }
        if ($report_kredit_kas) {
            $report_kredit_1 = $report_kredit_kas->total;
        }


        $kas_akhir = number_format($kas_awal - $jk_1 + $report_debit_1 - $report_kredit_1, 2, ".", "");
        //////////////////kas/////////////////////

        //////////////////bank////////////////////
        $bank = Perkiraan::select(DB::raw('cast(-saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();

        $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereDate('tanggal_jurnal', '<=', $to)->first();

        $report_debit_bank = Detail_jurnal_umum::select(DB::raw('cast(sub_total as decimal(65,2)) as total'))->where('kode_akun_kredit', '112.101')->whereDate('tanggal_jurnal', '<=', $to)->first();

        $report_kredit_bank = Detail_jurnal_umum::select(DB::raw('cast(sub_total as decimal(65,2)) as total'))->where('kode_akun_debit', '112.101')->whereDate('tanggal_jurnal', '<=', $to)->first();

        $jm_2 = 0;
        $report_debit_2 = 0;
        $report_kredit_2 = 0;

        $bank_awal = $bank->saldo_awal_kredit;

        if ($jm) {
            $jm_2 = $jm->total;
        }
        if ($report_debit_bank) {
            $report_debit_2 = $report_debit_bank->total;
        }
        if ($report_kredit_bank) {
            $report_kredit_2 = $report_kredit_bank->total;
        }

        $bank_akhir = number_format($bank_awal + $jm_2 - $report_debit_2 + $report_kredit_2, 2, ".", "");
        //////////////////bank////////////////////
        $piutang_dagang = number_format($piutang_dagang_awal->saldo_awal_debet + $piutang_dagang, 2, ".", "");

        $hutang_pajak_akhir = $hutang_pajak_awal->saldo_awal_kredit + $hutang_pajak;
        $hutang_pajak_akhir = number_format($hutang_pajak_akhir, 2, ".", "");

        $modal_awal = $modal->saldo_awal_kredit;
        $laba_ditahan_awal = $laba_ditahan->saldo_awal_debet;
        $laba_ditahan_akhir = number_format($laba_ditahan_awal + $laba_tahun_berjalan, 2, ".", "");

        $data['kas'] = $kas_akhir; //done
        $data['bank'] = $bank_akhir; //done
        $data['piutang_dagang'] = $piutang_dagang; //done
        $data['uang_muka_pajak'] = $uang_muka_pajak; //done

        $data['hutang_dagang'] = $hutang_dagang; //done
        $data['hutang_pajak'] = $hutang_pajak_akhir; //done
        $data['modal'] = $modal_awal; //done
        $data['laba_ditahan'] = $laba_ditahan_akhir; //done
        $data['laba_tahun_berjalan'] = $laba_tahun_berjalan; //done
        return $data;
    }


    public function pembagian(Request $request)
    {
        $all = $_GET['all'];
        if ($all == '') {
            $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
            $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

            $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as harga_pks'), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99 as decimal(65,0)) as harga_petani'), DB::raw(' sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))-cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99 as decimal(65,0)) as pendapatan_cv '), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total, -sub_total ))*0.99/10000 as decimal(65,0))*10000 as jumlah'), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99-cast(sum( if( kode_akun_debit = "610.001" , sub_total, -sub_total ))*0.99/10000 as decimal(65,0))*10000 as decimal(65,2)) as sisa'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();

            $data['tanggal'] = $from . ' ' . $to;

            $datediff = strtotime($to) - strtotime($from);

            $data['periode_hari'] = round($datediff / (60 * 60 * 24)) + 1;

            return $data;
        } else {
            $tahun = $_GET['tahun'];
            $perbulan = [];
            $bulanan = [];
            $periode = [];
            $json_periode = [];
            $petani = [];
            $pembagian = [];

            // $dataObj = new \stdClass();
            // $dataObj->setAttribute("open",[]);
            // return $dataObj;

            $bulan = Detail_jurnal_umum::select(DB::raw("MONTH(tanggal_jurnal) as bulan"))->whereYear('tanggal_jurnal', $tahun)->Where('kode_akun_kredit', '220.001')->distinct()->get();
            foreach ($bulan as $v) {
                for ($i = 1; $i <= 2; $i++) {
                    $bulan = $v->bulan;
                    if ($v->bulan < 10) {
                        $bulan = '0' . $v->bulan;
                    }
                    if ($i == 1) {
                        $from = $tahun . '-' . $bulan . '-01';
                        $to = $tahun . '-' . $bulan . '-16';
                        $period = '1-16 ' . date_format(date_create($to), "M") . ' ' . $tahun;
                    } else {
                        $bulan_besar = [1, 3, 5, 7, 8, 10, 12];
                        $from = $tahun . '-' . $bulan . '-17';
                        if (in_array($v->bulan, $bulan_besar)) {
                            $tanggal = 31;
                            $to = $tahun . '-' . $bulan . '-' . $tanggal;
                        } elseif ($v->bulan != 2) {
                            $tanggal = 30;
                            $to = $tahun . '-' . $bulan . '-' . $tanggal;
                        } else {
                            if ($tahun % 4 ==  0) {
                                $tanggal = 29;
                            } else {
                                $tanggal = 28;
                            }
                            $to = $tahun . '-' . $bulan . '-' . $tanggal;
                        }

                        $period = '17-' . $tanggal . ' ' . date_format(date_create($to), "M") . ' ' . $tahun;
                    }

                    $rekap = Detail_jurnal_umum::select(DB::raw('sum( banyaknya ) as tonase'), DB::raw('avg( harga ) as harga_rata'), DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as total_pks'), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99 as decimal(65,0)) as total_petani'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->get();
                    $rekap[0]->offsetSet('periode', $period);
                    $petani['tedy'] = $rekap[0]->total_petani * 0.125;
                    $petani['sony'] = $rekap[0]->total_petani * 0.125;
                    $petani['juliano'] = $rekap[0]->total_petani * 0.125;
                    $petani['rudy'] = $rekap[0]->total_petani * 0.125;
                    $petani['ambi'] = $rekap[0]->total_petani * 0.125;
                    $petani['devin'] = $rekap[0]->total_petani * 0.125;
                    $petani['plasma'] = $rekap[0]->total_petani * 0.125;
                    $petani['petani_desa'] = $rekap[0]->total_petani * 0.125;
                    $rekap[0]->offsetSet('pembagian', $petani);

                    array_push($bulanan, $rekap[0]);
                    // $perbulan['bulan: '] = $bulanan;
                    // $perbulan['bulan: '.date_format(date_create($to),"M")] = $bulanan;

                }
                array_push($perbulan,  $bulanan);
                // $perbulan['bulan: '] = [];
                $bulanan = [];
            }
            return $perbulan;
        }
    }

    public function penjualan(Request $request)
    {
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $from = $_GET['tahun'] . '-' . $_GET['bulan'] . '-1';

        $data['to_date'] = Detail_kwitansi::select(DB::raw('sum(berat_bersih * harga_satuan) as to_date_pks'), DB::raw('sum(berat_bersih * harga_beli) as to_date_petani'))->whereDate('tanggal_tagihan', '<', $from)->get();

        $data['report'] = Detail_kwitansi::select('tanggal', 'berat_bersih as qty', 'harga_satuan as harga_pks', 'harga_beli as harga_petani', DB::raw('berat_bersih * (harga_satuan - harga_beli) as keuntungan'))->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->orderBy('tanggal', 'DESC')->get();

        return $data;
    }
}
