<?php

namespace App\Http\Controllers;

use App\Kwitansi;
use App\Detail_kwitansi;
use App\Perkiraan;
use App\Satuan;
use App\Detail_jurnal_umum;
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
        
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $all = $_GET['all'];
        if($all == ''){
            $customer = $_GET['nama_customer'];
            if($customer == ''){
                $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer',DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'), 'detail_kwitansi.keterangan')->groupBy('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', 'detail_kwitansi.keterangan')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
            }else{
                $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer',DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'), 'detail_kwitansi.keterangan')->groupBy('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'detail_kwitansi.nama_customer', 'detail_kwitansi.keterangan')->where('detail_kwitansi.nama_customer', $customer)->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
            }
        }else{
            $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.nama_customer',DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn '),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'),'kwitansi.keterangan_kwitansi')->groupBy('kwitansi.nama_customer','kwitansi.keterangan_kwitansi')->groupBy('nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
        }

        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function omset_pks(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $all = $_GET['all'];
        if($all == ''){
            $pks = $_GET['nama_customer'];
            if($pks == ''){
                $data['report'] = Detail_kwitansi::select('tanggal','nomor','nomor_polisi','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan',DB::raw('berat_bersih*harga_satuan  as jumlah'))->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
            }else{
                $data['report'] = Detail_kwitansi::select('tanggal','nomor','nomor_polisi','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan',DB::raw('berat_bersih*harga_satuan  as jumlah'))->where('detail_kwitansi.nama_customer', $pks)->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
            }
        }else{
            $data['report'] = Detail_kwitansi::select('nama_customer',DB::raw('cast(sum(berat_bruto) as decimal(65,2)) as berat_bruto'),'satuan_berat_bruto',DB::raw('cast(sum(potongan) as decimal(65,2)) as potongan'),'satuan_potongan',DB::raw('cast(sum(berat_bersih) as decimal(65,2)) as berat_bersih'),'satuan_berat_bersih',DB::raw('cast(sum(berat_bersih*harga_satuan) as decimal(65,2)) as jumlah'))->groupBy('nama_customer','satuan_berat_bruto','satuan_potongan','satuan_berat_bersih')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
        } 
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function omset_ongkos_bongkar(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $all = $_GET['all'];
        if($all == ''){
            $pks = $_GET['nama_customer'];
            if($pks == ''){
                $data['report'] = Detail_kwitansi::select('tanggal','nomor','nomor_polisi','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','ongkos_bongkar')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
            }else{
                $data['report'] = Detail_kwitansi::select('tanggal','nomor','nomor_polisi','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','ongkos_bongkar')->where('detail_kwitansi.nama_customer', $pks)->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
            }
        }else{
            $data['report'] = Detail_kwitansi::select('nama_customer',DB::raw('cast(sum(berat_bruto) as decimal(65,2)) as berat_bruto'),'satuan_berat_bruto',DB::raw('cast(sum(potongan) as decimal(65,2)) as potongan'),'satuan_potongan',DB::raw('cast(sum(berat_bersih) as decimal(65,2)) as berat_bersih'),'satuan_berat_bersih',DB::raw('cast(sum(ongkos_bongkar) as decimal(65,2)) as ongkos_bongkar'))->groupBy('nama_customer','satuan_berat_bruto','satuan_potongan','satuan_berat_bersih')->whereBetween('detail_kwitansi.tanggal_tagihan', [$from, $to])->get();
        }

        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function modal_usaha(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $kode_akun = $_GET['kode_akun'];
        if($kode_akun == ''){
            $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->get();
        }else{
            $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->get();
        }
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function pembagian_kongsi(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $kode_akun = $_GET['kode_akun'];
        if($kode_akun == ''){
            $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', 'like', '32%')->Where('tipe_akun', 'Detail')->get();
        }else{
            $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->get();
        }
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function pendapatan(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $data['report'] = Detail_jurnal_umum::select('kode_akun_debit','kode_akun_kredit', DB::raw('sum(sub_total) as saldo'))->Where('kode_akun_debit', 'like', '4%')->orWhere('kode_akun_kredit', 'like', '4%')->groupBy('kode_akun_debit','kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function buku_besar(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $perkiraan = $_GET['perkiraan'];
        
        $data['report'] = Detail_jurnal_umum::select('kode_akun_debit','kode_akun_kredit', DB::raw('sum(sub_total) as saldo'))->Where('kode_akun_debit', $perkiraan)->orWhere('kode_akun_kredit',$perkiraan)->groupBy('kode_akun_debit','kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function hutang(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        //$perkiraan = $_GET['perkiraan'];
        
        $data['report'] = Detail_jurnal_umum::select('kode_akun_debit','kode_akun_kredit', DB::raw('sum(sub_total) as saldo'))->Where('kode_akun_debit', 'like', '2%')->orWhere('kode_akun_kredit', 'like', '2%')->groupBy('kode_akun_debit','kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function piutang(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        //$perkiraan = $_GET['perkiraan'];
        
        $data['report'] = Detail_jurnal_umum::select('kode_akun_debit','kode_akun_kredit', DB::raw('sum(sub_total) as saldo'))->Where('kode_akun_debit', 'like', '1%')->orWhere('kode_akun_kredit', 'like', '1%')->groupBy('kode_akun_debit','kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function pajak_keluaran(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi','kwitansi.no_seri_faktur_pajak', 'kwitansi.tanggal_kwitansi','kwitansi.keterangan_kwitansi', 'kwitansi.nama_customer',DB::raw('SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'))->groupBy('kwitansi.no_kwitansi','kwitansi.no_seri_faktur_pajak', 'kwitansi.tanggal_kwitansi','kwitansi.keterangan_kwitansi', 'kwitansi.nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();    
        return $data;
    }
    public function kas(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $all = $_GET['all'];
        if($all == ''){
            $data['report'] = Detail_jurnal_umum::leftjoin('perkiraan', 'detail_jurnal_umum.kode_akun_debit', '=', 'perkiraan.kode_akun')->select('perkiraan.kode_akun','perkiraan.nama_perkiraan', DB::raw('cast(perkiraan.saldo_awal_debet + sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as saldo'))->Where('detail_jurnal_umum.kode_akun_debit', 'like', '111%')->Where('perkiraan.tipe_akun', 'Detail')->orWhere('detail_jurnal_umum.kode_akun_kredit', 'like', '112%')->Where('perkiraan.tipe_akun', 'Detail')->groupBy('perkiraan.kode_akun','perkiraan.nama_perkiraan','perkiraan.saldo_awal_debet')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();  
        }else{
            $data['report'] = Perkiraan::leftjoin('detail_jurnal_umum', 'perkiraan.kode_akun', '=', 'detail_jurnal_umum.kode_akun_debit')->select('perkiraan.kode_akun', 'perkiraan.nama_perkiraan' , DB::raw('cast(perkiraan.saldo_awal_debet + sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as saldo'))->Where('perkiraan.kode_akun', 'like', '111%')->Where('perkiraan.tipe_akun', 'Detail')->orWhere('perkiraan.kode_akun', 'like', '112%')->Where('perkiraan.tipe_akun', 'Detail')->groupBy('perkiraan.kode_akun','perkiraan.nama_perkiraan','perkiraan.saldo_awal_debet')->get();
        }
         
        return $data;
    }
    
    public function laba_rugi(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
         
        $data['report'] = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_beli) as pembelian'),DB::raw('SUM(berat_bersih*harga_satuan) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->get();
        return $data;
    }
}
