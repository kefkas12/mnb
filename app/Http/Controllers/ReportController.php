<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Kwitansi;
use App\Detail_kwitansi;
use App\Perkiraan;
use App\Satuan;
use App\Detail_jurnal_umum;
use App\Detail_jurnal_pengeluaran_kas;
use App\Detail_jurnal_penerimaan_kas;
use App\Jurnal_umum;
use App\Laporan_hutang;
use App\Supplier;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->saldo_awal = new Perkiraan;
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
                $data['report'] = Detail_kwitansi::select('tanggal', 'nomor', 'nomor_polisi', 'berat_bruto', 'satuan_berat_bruto', 'potongan', 'satuan_potongan', 'berat_bersih', 'satuan_berat_bersih', 'harga_satuan', DB::raw('berat_bersih*harga_satuan  as jumlah'))->where('nama_customer', $pks)->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->orderBy('tanggal', 'DESC')->get();
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
                $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debit = "0" ,-saldo_awal_kredit  , saldo_awal_debit)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun', 'nama_perkiraan')->get();
            } else {
                $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->first();
                if ($kode_akun == '310.001') {
                    $data['saldo_awal'] = '(' . $saldo_awal->saldo_awal_kredit . ')';
                } else {
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debit;
                }
            }
        } else {
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debit = "0" ,-saldo_awal_kredit  , saldo_awal_debit)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun', 'nama_perkiraan')->get();
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
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', 'like', '32%')->Where('tipe_akun', 'Detail')->get();
        } else {
            $data['report'] = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->get();
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

        $kode = $_GET['kode'];
        if ($kode == '') {
            if ($_GET['pendapatan'] == 'lain-lain') {
                $kode_akun = ['444.400', '444.500'];
            }
            if ($_GET['pendapatan'] == 'uang-muka') {
                $kode_akun = ['115.002', '115.003', '115.004', '115.005', '115.006', '115.007'];
            }

            $no = 0;
            foreach ($kode_akun as $v) {
                $data['report'][$no]['kode_akun'] = $v;

                $pendapatan_usaha_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $v)->Where('tipe_akun', 'Detail')->first();

                $data['report'][$no]['nama_akun'] = $pendapatan_usaha_awal->nama_perkiraan;

                $pendapatan_usaha_awal = $pendapatan_usaha_awal->saldo_awal_debit ? $pendapatan_usaha_awal->saldo_awal_debit : 0;

                $pendapatan_usaha = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as pendapatan_usaha'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_kredit', $v)->first();

                $pendapatan_usaha = $pendapatan_usaha->pendapatan_usaha ? $pendapatan_usaha->pendapatan_usaha : 0;

                $debit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as debit'))->whereDate('tanggal_jurnal', '<', $from)->Where('kode_akun_debit', $v)->first();

                $debit = $debit->debit ? $debit->debit : 0;

                $kredit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as kredit'))->whereDate('tanggal_jurnal', '<', $from)->Where('kode_akun_kredit', $v)->first();

                $kredit = $kredit->kredit ? $kredit->kredit : 0;

                if ($_GET['pendapatan'] == 'lain-lain') {
                    $saldo_awal = $pendapatan_usaha_awal + $pendapatan_usaha - $debit + $kredit;
                }
                if ($_GET['pendapatan'] == 'uang-muka') {
                    $saldo_awal = $pendapatan_usaha_awal + $pendapatan_usaha + $debit - $kredit;
                }

                $data['report'][$no]['saldo_awal'] = $saldo_awal;

                $debit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $v)->first();

                $debit = $debit->debit ? $debit->debit : 0;

                $kredit = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $v)->first();

                $kredit = $kredit->kredit ? $kredit->kredit : 0;

                $data['report'][$no]['debit'] = $debit;
                $data['report'][$no]['kredit'] = $kredit;

                if ($_GET['pendapatan'] == 'lain-lain') {
                    $data['report'][$no]['saldo'] = $saldo_awal - $debit + $kredit;
                }
                if ($_GET['pendapatan'] == 'uang-muka') {
                    $data['report'][$no]['saldo'] = $saldo_awal + $debit - $kredit;
                }

                $no++;
            }
        } else {
            $pendapatan_usaha_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode)->Where('tipe_akun', 'Detail')->first();

            $pendapatan_usaha = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as pendapatan_usaha'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_debit', $kode)->first();

            if ($pendapatan_usaha->pendapatan_usaha) {
                $pendapatan_usaha = $pendapatan_usaha->pendapatan_usaha;
            } else {
                $pendapatan_usaha = 0;
            }

            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);

            $pendapatan_usaha = number_format($pendapatan_usaha_awal->saldo_awal_debit + $pendapatan_usaha, 2, ".", "");

            $data['saldo_awal'] = $pendapatan_usaha;
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

        $kode = $_GET['kode_akun'];
        if ($kode == '') {
            //     $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit', 'detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '42%')->groupBy('kode_akun_kredit', 'detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        } else {
            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();
            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);

            $pendapatan_lain_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode)->Where('tipe_akun', 'Detail')->first();
            $pendapatan_lain = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as pendapatan_lain'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_debit', $kode)->first();

            if ($pendapatan_lain->pendapatan_lain) {
                $pendapatan_lain = $pendapatan_lain->pendapatan_lain;
            } else {
                $pendapatan_lain = 0;
            }

            $pendapatan_lain = number_format($pendapatan_lain_awal->saldo_awal_debit + $pendapatan_lain, 2, ".", "");

            $data['saldo_awal'] = $pendapatan_lain;
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

        $kode = $_GET['kode_akun'];
        if ($kode == '') {
            $saldo_awal_biaya = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->whereIn('kode_akun', ['115.002', '115.003', '115.004', '115.005', '115.006', '115.007'])->orderBy('kode_akun', 'ASC')->get();
            $data['biaya'] = [];
            $data_biaya = [];
            foreach ($saldo_awal_biaya as $v) {
                $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', $v->kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', $v->kode_akun)->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                $debit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $v->kode_akun)->first();

                if ($debit) {
                    $saldo_debit = $debit->debit;
                } else {
                    $saldo_debit = 0;
                }

                $kredit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $v->kode_akun)->first();

                if ($kredit) {
                    $saldo_kredit = $kredit->kredit;
                } else {
                    $saldo_kredit = 0;
                }

                $data_biaya['kode_akun'] = $v->kode_akun;
                $data_biaya['nama_perkiraan'] = $v->nama_perkiraan;
                $data_biaya['saldo'] = $v->saldo_awal_debit + $saldo_debit - $saldo_kredit;
                // $data_biaya['saldo'] = $v->saldo_awal_debit + $saldo_awal_debit->debit - $saldo_awal_kredit->kredit;

                array_push($data['biaya'], $data_biaya);
                $data_biaya = [];
            }
        } else {
            //     $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('tanggal_jurnal', 'DESC')->get();
            //     $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode)->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();
            //     $data['saldo_awal'] = $saldo_awal->saldo;

            $pendapatan_uang_muka_awal = $this->saldo_awal->saldo_awal($kode)->saldo_awal_debit;

            $pendapatan_uang_muka = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as pendapatan_uang_muka'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_debit', $kode)->first();

            if ($pendapatan_uang_muka->pendapatan_uang_muka) {
                $pendapatan_uang_muka = $pendapatan_uang_muka->pendapatan_uang_muka;
            } else {
                $pendapatan_uang_muka = 0;
            }

            $pendapatan_uang_muka = number_format($pendapatan_uang_muka_awal + $pendapatan_uang_muka, 2, ".", "");

            $data['saldo_awal'] = $pendapatan_uang_muka;

            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);
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
            //$data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'kode_akun_debit', 'kode_akun_kredit', 'keterangan', DB::raw('if(kode_akun_debit = "220.001", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "220.001", sub_total, 0) as kredit'))->where('nama_perusahaan_supplier',$supplier)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '220.001')->orWhere('kode_akun_debit', '220.001')->orderBy('tanggal_jurnal', 'ASC')->get();

            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal', 'kode_akun_debit', 'kode_akun_kredit', 'keterangan', DB::raw('if(kode_akun_debit = "220.001", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "220.001", sub_total, 0) as kredit'))->where('nama_perusahaan_supplier', $supplier)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where(function ($query) {
                $query->where('kode_akun_kredit', '220.001')
                    ->orWhere('kode_akun_debit', '220.001');
            })->orderBy('tanggal_jurnal', 'ASC')->get();

            $hutang_supplier_awal = Supplier::where('nama_perusahaan', $supplier)->first();

            // $hutang_supplier = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->where('nama_perusahaan_supplier',$supplier)->Where('kode_akun_kredit', '220.001')->whereDate('tanggal_jurnal', '<', $from)->first();

            $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('nama_perusahaan_supplier', $supplier)->where('detail_jurnal_umum.kode_akun_debit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

            if ($saldo_awal_debit) {
                $hutang_supplier_debit = $saldo_awal_debit->debit;
            }

            $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('nama_perusahaan_supplier', $supplier)->where('detail_jurnal_umum.kode_akun_kredit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

            if ($saldo_awal_kredit) {
                $hutang_supplier_kredit = $saldo_awal_kredit->kredit;
            }

            $data['saldo_awal'] = number_format($hutang_supplier_awal->saldo_awal - $hutang_supplier_debit + $hutang_supplier_kredit, 2, ".", "");

            // $data['report'] = Laporan_hutang::select(DB::raw('sum(debit) as debit'), DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereBetween('tanggal_jurnal', [$from, $to])->groupBy('supplier')->get()->all();

            // $saldo_awal_debit = Laporan_hutang::select(DB::raw('sum(debit) as debit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
            // if ($saldo_awal_debit->debit) {
            //     $data['debit'] = $saldo_awal_debit->debit;
            // } else {
            //     $data['debit'] = 0;
            // }
            // $saldo_awal_kredit = Laporan_hutang::select(DB::raw('sum(kredit) as kredit'))->where('supplier', $supplier)->whereDate('tanggal_jurnal', '<', $from)->first();
            // if ($saldo_awal_kredit->kredit) {
            //     $data['kredit'] = $saldo_awal_kredit->kredit;
            // } else {
            //     $data['kredit'] = 0;
            // }
        } elseif ($dir == 'piutang_customer') {
            $customer = $_GET['customer'];

            // $data['debit'] = Kwitansi::select('tanggal_kwitansi as tanggal', 'keterangan_kwitansi as keterangan', DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as debit '))->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $customer)->orderBy('created_at', 'DESC')->get();

            // $data['kredit'] = Detail_jurnal_umum::select('tanggal_jurnal as tanggal', 'keterangan', DB::raw('sub_total as kredit'))->where('kode_akun_kredit', '113.101')->whereBetween('tanggal_jurnal', [$from, $to])->where('nama_perusahaan_customer', $customer)->orderBy('created_at', 'DESC')->get();

            $debit = Kwitansi::select('tanggal_kwitansi as tanggal_jurnal', 'keterangan_kwitansi as keterangan', DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as debit '), DB::raw('0 as kredit'))->where('nama_customer', $customer)->whereBetween('tanggal_kwitansi', [$from, $to])->orderBy('tanggal_jurnal', 'ASC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'kode_akun_debit', 'kode_akun_kredit', 'keterangan', DB::raw('if(kode_akun_debit = "113.101", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "113.101", sub_total, 0) as kredit'))->where('nama_perusahaan_customer', $customer)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '113.101')->orWhere('kode_akun_debit', '113.101')->orderBy('tanggal_jurnal', 'ASC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            $data['report'] = array_merge($debit, $kredit);

            $kwitansi_awal = Customer::where('nama_perusahaan', $customer)->first();

            $kwitansi_debit = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->where('nama_customer', $customer)->whereDate('tanggal_kwitansi', '<', $from)->first();

            if ($kwitansi_debit->debit) {
                $kwitansi_debit = $kwitansi_debit->debit;
            } else {
                $kwitansi_debit = 0;
            }

            $kwitansi_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('nama_perusahaan_customer', $customer)->where('detail_jurnal_umum.kode_akun_kredit', '113.101')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

            if ($kwitansi_kredit) {
                $kwitansi_kredit = $kwitansi_kredit->kredit;
            } else {
                $kwitansi_kredit = 0;
            }

            $data['saldo_awal'] = number_format($kwitansi_awal->saldo_awal + $kwitansi_debit - $kwitansi_kredit, 2, ".", "");
        } elseif ($dir == 'biaya') {
            $kode = $_GET['kode'];

            // $saldo_awal = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $report = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $data['saldo_awal'] = $saldo_awal->debit + $report->debit;

            // $data['jk'] = Detail_jurnal_pengeluaran_kas::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_pengeluaran_kas.created_at', 'DESC')->get();

            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);

            $biaya_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode)->Where('tipe_akun', 'Detail')->first();

            $biaya = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as biaya'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_debit', $kode)->first();

            if ($biaya->biaya) {
                $biaya = $biaya->biaya;
            } else {
                $biaya = 0;
            }

            $biaya = number_format($biaya_awal->saldo_awal_debit + $biaya, 2, ".", "");

            $data['saldo_awal'] = $biaya;

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

            // $saldo_awal = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $report = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->Where('kode_akun_debit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();

            // $data['saldo_awal'] = $saldo_awal->debit + $report->debit;

            // $data['jk'] = Detail_jurnal_pengeluaran_kas::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_pengeluaran_kas.created_at', 'DESC')->get();

            $debit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('cast(sub_total as decimal(65,2)) as debit'), DB::raw('0 as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $kredit = Detail_jurnal_umum::select('tanggal_jurnal', 'keterangan', DB::raw('0 as debit'), DB::raw('cast(sub_total as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orderBy('detail_jurnal_umum.created_at', 'DESC')->get();

            $debit = $debit->toArray();
            $kredit = $kredit->toArray();

            // $data['report'] = $kredit->merge($debit);
            $data['report'] = array_merge($debit, $kredit);

            $hutang_dagang_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode)->Where('tipe_akun', 'Detail')->first();

            $hutang_dagang = Detail_jurnal_umum::select(DB::raw('cast(SUM(sub_total) as decimal(65,2)) as hutang_dagang'))->whereDate('tanggal_jurnal', '<', $from)->where('kode_akun_debit', $kode)->first();

            if ($hutang_dagang->hutang_dagang) {
                $hutang_dagang = $hutang_dagang->hutang_dagang;
            } else {
                $hutang_dagang = 0;
            }

            $hutang_dagang = number_format($hutang_dagang_awal->saldo_awal_debit + $hutang_dagang, 2, ".", "");

            $data['saldo_awal'] = $hutang_dagang;

            // $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal','kode_akun_debit','kode_akun_kredit','keterangan',DB::raw('if(kode_akun_debit = "' . $kode . '", sub_total, 0)as debit'), DB::raw('if(kode_akun_kredit = "' . $kode . '", sub_total, 0) as kredit'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $kode)->orWhere('kode_akun_debit', $kode)->orderBy('tanggal_jurnal','ASC')->get();

            // $hutang_dagang_awal = Supplier::where('nama_perusahaan',$supplier)->first();

            // $hutang_supplier = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('tanggal_jurnal','<',$from)->first();
            // if($hutang_supplier->saldo != null){
            //     $hutang_supplier = $hutang_supplier->saldo;
            // }else{
            //     $hutang_supplier = 0;
            // }

            // $data['saldo_awal'] = number_format($hutang_dagang_awal->saldo_awal + $hutang_supplier, 2, ".", "");

            // /////////////

            // $saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', $kode)->whereDate('tanggal_jurnal', '<', $from)->first();
            // if ($saldo_awal->saldo) {
            //     $data['saldo_awal'] = $saldo_awal->saldo;
            // } else {
            //     $data['saldo_awal'] = 0;
            // }
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

                $data['report'] = Supplier::select('nama_perusahaan')->get();
                // dd($data);
                $no = 0;
                foreach ($data['report'] as $v) {
                    $saldo_awal = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo_awal'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($saldo_awal) {
                        $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                    } else {
                        $data['report'][$no]->offsetSet('saldo_awal', 0);
                    }

                    $debit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as debit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_debit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->first();
                    if ($debit) {
                        $data['report'][$no]->offsetSet('debit', $debit->debit);
                    } else {
                        $data['report'][$no]->offsetSet('debit', 0);
                    }

                    $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_supplier')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan)->first();
                    if ($kredit) {
                        $data['report'][$no]->offsetSet('kredit', $kredit->kredit);
                    } else {
                        $data['report'][$no]->offsetSet('kredit', 0);
                    }
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
            $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier')->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->get();
            $no = 0;
            foreach ($data['report'] as $v) {
                $saldo_awal = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo_awal'))->where('nama_perusahaan_supplier', $data['report'][$no]->nama_perusahaan_supplier)->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereDate('detail_jurnal_umum.tanggal_jurnal', '<', $from)->first();

                if ($saldo_awal) {
                    $data['report'][$no]->offsetSet('saldo_awal', $saldo_awal->saldo_awal);
                } else {
                    $data['report'][$no]->offsetSet('saldo_awal', 0);
                }

                dd($saldo_awal->saldo_awal);

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
                    $kwitansi_awal = Customer::where('nama_perusahaan', $data['report'][$no]->nama_customer)->first();

                    $kwitansi_debit = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->where('nama_customer', $data['report'][$no]->nama_customer)->whereDate('tanggal_kwitansi', '<', $from)->first();

                    if ($kwitansi_debit->debit) {
                        $kwitansi_debit = $kwitansi_debit->debit;
                    } else {
                        $kwitansi_debit = 0;
                    }

                    $kwitansi_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('nama_perusahaan_customer', $data['report'][$no]->nama_customer)->where('detail_jurnal_umum.kode_akun_kredit', '113.101')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first();

                    if ($kwitansi_kredit) {
                        $kwitansi_kredit = $kwitansi_kredit->kredit;
                    } else {
                        $kwitansi_kredit = 0;
                    }
                    $saldo_awal_temp = 0;
                    if ($kwitansi_awal) {
                        $saldo_awal_temp = $kwitansi_awal->saldo_awal + $kwitansi_debit - $kwitansi_kredit;
                        $data['report'][$no]->offsetSet('saldo_awal', number_format($saldo_awal_temp, 2, ".", ""));
                    } else {
                        $data['report'][$no]->offsetSet('saldo_awal', 0);
                    }

                    $debit = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as debit'))->groupBy('nama_customer')->whereBetween('tanggal_kwitansi', [$from, $to])->where('nama_customer', $data['report'][$no]->nama_customer)->first();
                    $debit_temp = 0;
                    if ($debit) {
                        $debit_temp = $debit->debit;
                        $data['report'][$no]->offsetSet('debit', $debit_temp);
                    } else {
                        $data['report'][$no]->offsetSet('debit', 0);
                    }

                    $kredit = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->groupBy('nama_perusahaan_customer')->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '113.101')->where('nama_perusahaan_customer', $data['report'][$no]->nama_customer)->first();
                    $kredit_temp = 0;
                    if ($kredit) {
                        $kredit_temp = $kredit->kredit;
                        $data['report'][$no]->offsetSet('kredit', $kredit_temp);
                    } else {
                        $data['report'][$no]->offsetSet('kredit', 0);
                    }
                    $data['report'][$no]['saldo'] = $saldo_awal_temp + $debit_temp - $kredit_temp;
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

                $saldo_awal_kas = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();

                $jk = Detail_jurnal_pengeluaran_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();

                $report_debit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', '111.001')->first();

                $report_kredit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_kredit', '111.001')->first();

                $saldo_awal_1 = 0;
                $jk_1 = 0;
                $report_debit_1 = 0;
                $report_kredit_1 = 0;

                $saldo_awal_1 = $saldo_awal_kas->saldo_awal_debit;

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

                $saldo_awal_bank = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();

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

                $data['report_bank'] = number_format($saldo_awal_2 - $jm_2 + $report_debit_2 - $report_kredit_2, 2, ",", ".");
            } else {
                if ($kode_akun == '111.001') {
                    $data['jurnal_pengeluaran'] = Detail_jurnal_pengeluaran_kas::leftJoin('jurnal_pengeluaran_kas', 'detail_jurnal_pengeluaran_kas.id_jurnal_pengeluaran_kas', '=', 'jurnal_pengeluaran_kas.id')->select('detail_jurnal_pengeluaran_kas.tanggal_jurnal', 'jurnal_pengeluaran_kas.nomor_bukti', 'detail_jurnal_pengeluaran_kas.kode_akun_debit', 'detail_jurnal_pengeluaran_kas.keterangan', 'detail_jurnal_pengeluaran_kas.sub_total')->whereBetween('detail_jurnal_pengeluaran_kas.tanggal_jurnal', [$from, $to])->orderBy('detail_jurnal_pengeluaran_kas.tanggal_jurnal', 'DESC')->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal', 'detail_jurnal_umum.kode_akun_debit', 'detail_jurnal_umum.kode_akun_kredit', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->orderBy('detail_jurnal_umum.tanggal_jurnal', 'DESC')->get();

                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debit;
                } else if ($kode_akun == '112.101') {
                    //tv check again
                    $data['jurnal_penerimaan'] = Detail_jurnal_penerimaan_kas::leftJoin('jurnal_penerimaan_kas', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas', '=', 'jurnal_penerimaan_kas.id')->select('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_bukti', 'detail_jurnal_penerimaan_kas.kode_akun_kredit', 'detail_jurnal_penerimaan_kas.keterangan', 'detail_jurnal_penerimaan_kas.sub_total')->whereBetween('detail_jurnal_penerimaan_kas.tanggal_jurnal', [$from, $to])->orderBy('detail_jurnal_penerimaan_kas.tanggal_jurnal', 'DESC')->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal', 'detail_jurnal_umum.kode_akun_debit', 'detail_jurnal_umum.kode_akun_kredit', 'jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->where('detail_jurnal_umum.kode_akun_debit', $kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit', $kode_akun)->orderBy('detail_jurnal_umum.tanggal_jurnal', 'DESC')->get();

                    $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
                }
            }
        } else {
            $data['report'] = Perkiraan::leftjoin('detail_jurnal_umum', 'perkiraan.kode_akun', '=', 'detail_jurnal_umum.kode_akun_debit')->select('perkiraan.kode_akun', 'perkiraan.nama_perkiraan', DB::raw('cast(perkiraan.saldo_awal_debit + sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as saldo'))->Where('perkiraan.kode_akun', 'like', '111%')->Where('perkiraan.tipe_akun', 'Detail')->orWhere('perkiraan.kode_akun', 'like', '112%')->Where('perkiraan.tipe_akun', 'Detail')->groupBy('perkiraan.kode_akun', 'perkiraan.nama_perkiraan', 'perkiraan.saldo_awal_debit')->get();
        }

        return $data;
    }
    public function laba_rugi()
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $saldo_awal_perkiraan = $this->saldo_awal->saldo_awal('444.100')->saldo_awal_debit;

        $data['penjualan'] = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_satuan) as decimal(65,2)) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->penjualan;
        $data['penjualan'] = $from < '2022-04-01' ? $saldo_awal_perkiraan + $data['penjualan'] : $data['penjualan'];

        $saldo_awal_perkiraan = $this->saldo_awal->saldo_awal('444.500')->saldo_awal_debit;

        $pendapatan_lainnya = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.500')->first()->saldo;

        $pendapatan_lainnya = $pendapatan_lainnya ? $pendapatan_lainnya : 0;

        $data['pendapatan_lainnya'] = $from < '2022-04-01' ? $saldo_awal_perkiraan + $pendapatan_lainnya : $pendapatan_lainnya;

        $saldo_awal_perkiraan = $this->saldo_awal->saldo_awal('444.400')->saldo_awal_debit;

        $pendapatan_bunga_bank = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.400')->first()->saldo;

        $pendapatan_bunga_bank = $pendapatan_bunga_bank ? $pendapatan_bunga_bank : 0;

        $data['pendapatan_bunga_bank'] = $from < '2022-04-01' ? $saldo_awal_perkiraan + $pendapatan_bunga_bank : $pendapatan_bunga_bank;

        //pembelian
        $saldo_awal_perkiraan = $this->saldo_awal->saldo_awal('610.001')->saldo_awal_debit;

        $data['pembelian'] = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_beli) as decimal(65,2)) as pembelian'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->pembelian;

        $data['pembelian'] = $from < '2022-04-01' ? $saldo_awal_perkiraan + $data['pembelian'] : $data['pembelian'];

        $saldo_awal_biaya = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', 'like', '555%')->orderBy('kode_akun', 'ASC')->get();
        $data['biaya'] = [];
        foreach ($saldo_awal_biaya as $v) {
            $saldo_debit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', $v->kode_akun)->first()->debit;

            $saldo_kredit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', $v->kode_akun)->first()->kredit;

            $data_biaya['kode_akun'] = $v->kode_akun;
            $data_biaya['nama_perkiraan'] = $v->nama_perkiraan;

            $data_biaya['saldo'] = $from < '2022-04-01' ? $v->saldo_awal_debit + $saldo_debit - $saldo_kredit : $saldo_debit - $saldo_kredit;

            array_push($data['biaya'], $data_biaya);
            $data_biaya = [];
        }
        return $data;
    }
    public function neraca(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));

        $kas_awal = $this->saldo_awal->saldo_awal('111.001')->saldo_awal_debit;
        $bank_awal = $this->saldo_awal->saldo_awal('112.101')->saldo_awal_kredit;
        $modal_awal = $this->saldo_awal->saldo_awal('310.001')->saldo_awal_kredit;
        $piutang_dagang_awal = $this->saldo_awal->saldo_awal('113.101')->saldo_awal_debit;
        $hutang_dagang_awal = $this->saldo_awal->saldo_awal('220.001')->saldo_awal_debit;
        $hutang_pajak_awal = $this->saldo_awal->saldo_awal('230.001')->saldo_awal_kredit;
        $laba_ditahan_awal = $this->saldo_awal->saldo_awal('310.002')->saldo_awal_debit;
        $penjualan_awal = $this->saldo_awal->saldo_awal('444.100')->saldo_awal_debit;
        $pendapatan_bunga_bank_awal = $this->saldo_awal->saldo_awal('444.400')->saldo_awal_debit;
        $pendapatan_lainnya_awal = $this->saldo_awal->saldo_awal('444.500')->saldo_awal_debit;
        $pembelian_awal = $this->saldo_awal->saldo_awal('610.001')->saldo_awal_debit;

        $saldo_awal_biaya = Perkiraan::select(DB::raw('cast(sum(saldo_awal_debit) as decimal(65,2)) as saldo_awal_debit'))->whereIn('kode_akun', ['115.002', '115.003', '115.004', '115.005', '115.006', '115.007'])->first()->saldo_awal_debit;

        $saldo_awal_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->whereIn('detail_jurnal_umum.kode_akun_debit', ['115.002', '115.003', '115.004', '115.005', '115.006', '115.007'])->where('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->debit;

        $saldo_awal_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as kredit'))->whereIn('detail_jurnal_umum.kode_akun_kredit', ['115.002', '115.003', '115.004', '115.005', '115.006', '115.007'])->where('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->kredit;

        $uang_muka_pajak = $saldo_awal_biaya + $saldo_awal_debit - $saldo_awal_kredit;

        $supplier_awal = Supplier::select(DB::raw('sum(saldo_awal) as saldo_awal'))->first()->saldo_awal;
        $supplier_awal = $supplier_awal ? $supplier_awal : 0;

        $hutang_supplier_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as debit'))->where('detail_jurnal_umum.kode_akun_debit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first()->debit;
        $hutang_supplier_debit = $hutang_supplier_debit ? $hutang_supplier_debit : 0;

        $hutang_supplier_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('sum(detail_jurnal_umum.sub_total) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', '220.001')->where('jurnal_umum.tanggal_jurnal', '<', $from)->first()->kredit;

        $hutang_supplier_kredit = $hutang_supplier_kredit ? $hutang_supplier_kredit : 0;

        $saldo_awal_temp = $supplier_awal - $hutang_supplier_debit + $hutang_supplier_kredit;

        $debit_temp = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_debit', '220.001')->first()->debit;
        $debit_temp = $debit_temp ? $debit_temp : 0;

        $kredit_temp = Detail_jurnal_umum::select(DB::raw('sum(sub_total) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit', '220.001')->first()->kredit;
        $kredit_temp = $kredit_temp ? $kredit_temp : 0;

        $hutang_dagang = $saldo_awal_temp - $debit_temp + $kredit_temp;

        // $hutang_pajak = Kwitansi::select(DB::raw('cast(SUM(total_ppn_kwitansi) as decimal(65,2)) as hutang_pajak'))->whereDate('tanggal_kwitansi', '<=', $to)->first()->hutang_pajak;

        $hutang_pajak = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select(DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.11 as decimal(65,2)) as ppn'))->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->first()->ppn;
        $hutang_pajak = $hutang_pajak ? $hutang_pajak : $hutang_pajak_awal;
        
        $penjualan = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_satuan) as decimal(65,2)) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->penjualan;
        $penjualan = $from < '2022-04-01' ? $penjualan_awal + $penjualan : $penjualan;
        
        $pendapatan_bunga_bank = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.400')->first()->saldo;
        $pendapatan_bunga_bank = $pendapatan_bunga_bank ? $pendapatan_bunga_bank : 0;
        $pendapatan_bunga_bank = $from < '2022-04-01' ? $pendapatan_bunga_bank_awal + $pendapatan_bunga_bank : $pendapatan_bunga_bank;
        
        $pendapatan_lainnya = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.500')->first()->saldo;
        $pendapatan_lainnya = $pendapatan_lainnya ? $pendapatan_lainnya : 0;
        $pendapatan_lainnya = $from < '2022-04-01' ? $pendapatan_lainnya_awal + $pendapatan_lainnya : $pendapatan_lainnya;

        $pembelian = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_beli) as decimal(65,2)) as pembelian'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->pembelian;
        $pembelian = $from < '2022-04-01' ? $pembelian_awal + $pembelian : $pembelian;

        $supplier_awal = Supplier::select(DB::raw('sum(saldo_awal) as saldo_awal'))->first()->saldo_awal;
        $saldo_awal_biaya = Perkiraan::select(DB::raw('cast(sum(saldo_awal_debit) as decimal(65,2)) as saldo_awal_debit'))->Where('kode_akun', 'like', '555%')->first()->saldo_awal_debit;
        $biaya_debit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '555%')->first()->debit;
        $biaya_kredit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', 'like', '555%')->first()->kredit;

        $biaya = $from < '2022-04-01' ? $saldo_awal_biaya + $biaya_debit - $biaya_kredit : $biaya_debit - $biaya_kredit;

        dd($biaya);

        $laba_tahun_berjalan = $penjualan + $pendapatan_bunga_bank + $pendapatan_lainnya - $pembelian - $biaya;

        $kas_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->where('detail_jurnal_umum.kode_akun_debit', '111.001')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->debit;
        $bank_debit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->where('detail_jurnal_umum.kode_akun_debit', '112.101')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->debit;

        $kas_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', '111.001')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->kredit;
        $bank_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', '112.101')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->kredit;

        $piutang_dagang_debit = Kwitansi::select(DB::raw('cast(sum( total_dpp_kwitansi + total_ppn_kwitansi) as decimal(65,2)) as debit'))->whereDate('tanggal_kwitansi', '<=', $to)->first()->debit;
        $piutang_dagang_kredit = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as kredit'))->where('detail_jurnal_umum.kode_akun_kredit', '113.101')->where('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->kredit;

        $ppn_keluaran = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->where('detail_jurnal_umum.kode_akun_debit', '230.001')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->debit;

        $ppn_masukan = Detail_jurnal_umum::leftJoin('jurnal_umum', 'detail_jurnal_umum.id_jurnal_umum', '=', 'jurnal_umum.id')->select(DB::raw('cast(sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as debit'))->where('detail_jurnal_umum.kode_akun_debit', '115.001')->whereDate('jurnal_umum.tanggal_jurnal', '<=', $to)->first()->debit;

        $data['kas'] = number_format($kas_awal + $kas_debit - $kas_kredit, 2, ".", "");
        $data['bank'] = number_format($bank_awal + $bank_debit - $bank_kredit, 2, ".", "");
        $data['piutang_dagang'] = number_format($piutang_dagang_awal + $piutang_dagang_debit - $piutang_dagang_kredit, 2, ".", "");
        $data['uang_muka_pajak'] = number_format($uang_muka_pajak, 2, ".", "");
        $data['hutang_dagang'] = number_format($hutang_dagang, 2, ".", "");
        // $data['hutang_pajak'] = number_format($hutang_pajak_awal + $hutang_pajak - $ppn_keluaran - $ppn_masukan, 2, ".", "");
        $data['hutang_pajak'] = number_format($hutang_pajak, 2, ".", "");
        $data['modal'] = $modal_awal;
        $data['laba_tahun_berjalan'] = number_format($laba_tahun_berjalan, 2, ".", "");

        $from_awal = $from;

        $from = date('Y',strtotime($from_awal . " -1 days")).'-'.date('m',strtotime($from_awal . " -1 days")).'-01';
        $to = date('Y-m-d',strtotime($from_awal . " -1 days"));

        $penjualan = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_satuan) as decimal(65,2)) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->penjualan;
        $penjualan = $penjualan_awal + $penjualan;
        
        $pendapatan_bunga_bank = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.400')->first()->saldo;
        $pendapatan_bunga_bank = $pendapatan_bunga_bank ? $pendapatan_bunga_bank : 0;
        $pendapatan_bunga_bank = $pendapatan_bunga_bank_awal + $pendapatan_bunga_bank;
        
        $pendapatan_lainnya = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', '444.500')->first()->saldo;
        $pendapatan_lainnya = $pendapatan_lainnya ? $pendapatan_lainnya : 0;
        $pendapatan_lainnya = $pendapatan_lainnya_awal + $pendapatan_lainnya;

        $pembelian = Detail_kwitansi::select(DB::raw('cast(SUM(berat_bersih*harga_beli) as decimal(65,2)) as pembelian'))->whereBetween('tanggal_tagihan', [$from, $to])->first()->pembelian;
        $pembelian = $pembelian_awal + $pembelian;

        $supplier_awal = Supplier::select(DB::raw('sum(saldo_awal) as saldo_awal'))->first()->saldo_awal;
        $saldo_awal_biaya = Perkiraan::select(DB::raw('cast(sum(saldo_awal_debit) as decimal(65,2)) as saldo_awal_debit'))->Where('kode_akun', 'like', '555%')->first()->saldo_awal_debit;
        $biaya_debit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as debit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_debit', 'like', '555%')->first()->debit;
        $biaya_kredit = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as kredit'))->whereBetween('tanggal_jurnal', [$from, $to])->Where('kode_akun_kredit', 'like', '555%')->first()->kredit;

        $biaya = $saldo_awal_biaya + $biaya_debit - $biaya_kredit;

        $laba_tahun_berjalan = $penjualan + $pendapatan_bunga_bank + $pendapatan_lainnya - $pembelian - $biaya ;

        if($from < '2022-03-01'){
            $laba_ditahan = 988129159;
        }else if($from < '2022-04-01'){
            $laba_ditahan = $laba_ditahan_awal;
        }else{
            $laba_ditahan = $laba_ditahan_awal + $laba_tahun_berjalan;
        }

        $data['laba_ditahan'] = number_format($laba_ditahan, 2, ".", "");
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
