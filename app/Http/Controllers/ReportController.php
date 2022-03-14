<?php

namespace App\Http\Controllers;

use App\Kwitansi;
use App\Detail_kwitansi;
use App\Perkiraan;
use App\Satuan;
use App\Detail_jurnal_umum;
use App\Detail_jurnal_pengeluaran_kas;
use App\Detail_jurnal_penerimaan_kas;
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
            $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.nama_customer',DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn '),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'))->groupBy('kwitansi.nama_customer')->groupBy('nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
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
        
        $all = $_GET['all'];
        if($all == ''){
            $kode_akun = $_GET['kode_akun'];
            if($kode_akun == ''){
                $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debet = "0" ,-saldo_awal_kredit  , saldo_awal_debet)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun','nama_perkiraan')->get();
            }else{
                $saldo_awal = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->Where('tipe_akun', 'Detail')->first();
                if($kode_akun == '310.001'){
                    $data['saldo_awal'] = '('.$saldo_awal->saldo_awal_kredit.')';
                }else{
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;
                }
            }
        }else{
            $data['report'] = Perkiraan::select('kode_akun','nama_perkiraan', DB::raw('cast(sum( if( saldo_awal_debet = "0" ,-saldo_awal_kredit  , saldo_awal_debet)) as decimal(65,2)) as saldo'))->Where('kode_akun', 'like', '31%')->Where('tipe_akun', 'Detail')->groupBy('kode_akun','nama_perkiraan')->get();
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
    public function pendapatan_usaha(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $kode_akun = $_GET['kode_akun'];
        if($kode_akun == ''){
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit','detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '41%')->groupBy('kode_akun_kredit','detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        }else{
            
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal','keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }
            
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function pendapatan_lain(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $kode_akun = $_GET['kode_akun'];
        if($kode_akun == ''){
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit','detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '42%')->groupBy('kode_akun_kredit','detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        }else{
            $data['jm'] = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit',$kode_akun)->first();
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal','keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function pendapatan_uang_muka(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $kode_akun = $_GET['kode_akun'];
        if($kode_akun == ''){
            $data['report'] = Detail_jurnal_umum::select('kode_akun_kredit','detail_kode_akun_kredit', DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', 'like', '43%')->groupBy('kode_akun_kredit','detail_kode_akun_kredit')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        }else{
            $data['report'] = Detail_jurnal_umum::select('tanggal_jurnal','keterangan', DB::raw('cast(sub_total as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
            $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', $kode_akun)->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
            $data['saldo_awal'] = $saldo_awal->saldo;
        }
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function buku_besar_hutang_supplier(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $supplier = $_GET['supplier'];
        
        $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier','kode_akun_debit','kode_akun_kredit','keterangan','sub_total')->Where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier',$supplier)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        $saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
        $data['saldo_awal'] = $saldo_awal->saldo;
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function buku_besar_piutang_customer(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $customer = $_GET['customer'];
        
        $data['report'] = Kwitansi::select('tanggal_kwitansi','keterangan_kwitansi',DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as saldo '))->where('nama_customer',$customer)->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
        $saldo_awal = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as saldo'))->where('nama_customer',$customer)->whereDate('kwitansi.tanggal_kwitansi','<',$from)->first();
        $data['saldo_awal'] = $saldo_awal->saldo;
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function hutang(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $supplier = $_GET['supplier'];
        
        $all = $_GET['all'];
        if($all == ''){
            if($supplier == ''){
                $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
                
            }else{
                $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier','kode_akun_debit','kode_akun_kredit','keterangan','sub_total')->Where('kode_akun_kredit', '220.001')->where('nama_perusahaan_supplier',$supplier)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
                $saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
                $data['saldo_awal'] = $saldo_awal->saldo;
            }
        }else{
            $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        }
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    public function piutang(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        $customer = $_GET['customer'];
        
        $all = $_GET['all'];
        if($all == ''){
            if($customer == ''){
                $data['report'] = Kwitansi::select('nama_customer', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_customer')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
            }else{
                $data['report'] = Kwitansi::select('tanggal_kwitansi','keterangan_kwitansi',DB::raw('(total_dpp_kwitansi + total_ppn_kwitansi) as saldo '))->where('nama_customer',$customer)->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
                $saldo_awal = Kwitansi::select(DB::raw('sum( total_dpp_kwitansi + total_ppn_kwitansi) as saldo'))->where('nama_customer',$customer)->whereDate('kwitansi.tanggal_kwitansi','<',$from)->first();
                $data['saldo_awal'] = $saldo_awal->saldo;
            }
        }else{
            $data['report'] = Kwitansi::select('nama_customer', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_customer')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        }
        
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
            $kode_akun = $_GET['kode_akun'];
            if($kode_akun == ''){
                //$saldo_awal = Detail_jurnal_umum::select(DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as saldo'))->Where('kode_akun_kredit', '220.001')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
                
                $saldo_awal_kas = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
                
                $jk = Detail_jurnal_pengeluaran_kas::select( DB::raw('cast(sum(sub_total) as decimal(65,2)) as total') )->whereBetween('tanggal_jurnal', [$from, $to])->first();
                
                $report_debit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->where('detail_jurnal_umum.kode_akun_debit','111.001')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
                
                $report_kredit_kas = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->where('detail_jurnal_umum.kode_akun_kredit','111.001')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
                
                $saldo_awal_1 = 0;
                $jk_1 = 0;
                $report_debit_1 = 0;
                $report_kredit_1 = 0;
                
                $saldo_awal_1 = $saldo_awal_kas->saldo_awal_debet;
                
                if( $jk ){
                    $jk_1 = $jk->total;
                }
                if( $report_debit_kas ){
                    $report_debit_1 = $report_debit_kas->total;
                }
                if( $report_kredit_kas ){
                    $report_kredit_1 = $report_kredit_kas->total;
                }
                
                
                $data['report_kas'] = number_format($saldo_awal_1-$jk_1+$report_debit_1-$report_kredit_1 , 2,",",".");
                
                $saldo_awal_bank = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
                
                $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->first();
                
                $report_debit_bank = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->where('detail_jurnal_umum.kode_akun_kredit','112.101')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
                
                $report_kredit_bank = Detail_jurnal_umum::select(DB::raw('cast(detail_jurnal_umum.sub_total as decimal(65,2)) as total'))->where('detail_jurnal_umum.kode_akun_debit','112.101')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
                
                $saldo_awal_2 = 0;
                $jm_2 = 0;
                $report_debit_2 = 0;
                $report_kredit_2 = 0;
                
                $saldo_awal_2 = $saldo_awal_bank->saldo_awal_kredit;
                
                if( $jm ){
                    $jm_2 = $jm->total;
                }
                if( $report_debit_bank ){
                    $report_debit_2 = $report_debit_bank->total;
                }
                if( $report_kredit_bank ){
                    $report_kredit_2 = $report_kredit_bank->total;
                }
                
                $data['report_bank'] = number_format(-$saldo_awal_2+$jm_2-$report_debit_2+$report_kredit_2, 2,",",".");
                
            }else{
                if($kode_akun == '111.001'){
                    $data['jurnal_pengeluaran'] = Detail_jurnal_pengeluaran_kas::select('tanggal_jurnal', 'kode_akun_debit', 'keterangan', 'sub_total' )->whereBetween('tanggal_jurnal', [$from, $to])->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum','detail_jurnal_umum.id_jurnal_umum','=','jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal' ,'detail_jurnal_umum.kode_akun_debit','detail_jurnal_umum.kode_akun_kredit','jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->where('detail_jurnal_umum.kode_akun_debit',$kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit',$kode_akun)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
                    
                    $saldo_awal = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_debet;
                }else if($kode_akun == '112.101'){
                    //tv check again
                    $data['jurnal_penerimaan'] = Detail_jurnal_penerimaan_kas::select('tanggal_jurnal', 'kode_akun_kredit', 'keterangan', 'sub_total' )->whereBetween('tanggal_jurnal', [$from, $to])->get();
                    $data['report'] = Detail_jurnal_umum::leftjoin('jurnal_umum','detail_jurnal_umum.id_jurnal_umum','=','jurnal_umum.id')->select('detail_jurnal_umum.tanggal_jurnal' ,'detail_jurnal_umum.kode_akun_debit','detail_jurnal_umum.kode_akun_kredit','jurnal_umum.nomor_bukti', 'detail_jurnal_umum.keterangan', 'detail_jurnal_umum.sub_total')->where('detail_jurnal_umum.kode_akun_debit',$kode_akun)->orWhere('detail_jurnal_umum.kode_akun_kredit',$kode_akun)->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
                    
                    $saldo_awal = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '112.101')->Where('tipe_akun', 'Detail')->first();
                    $data['saldo_awal'] = $saldo_awal->saldo_awal_kredit;
                }
            }
        }else{
            $data['report'] = Perkiraan::leftjoin('detail_jurnal_umum', 'perkiraan.kode_akun', '=', 'detail_jurnal_umum.kode_akun_debit')->select('perkiraan.kode_akun', 'perkiraan.nama_perkiraan' , DB::raw('cast(perkiraan.saldo_awal_debet + sum(detail_jurnal_umum.sub_total) as decimal(65,2)) as saldo'))->Where('perkiraan.kode_akun', 'like', '111%')->Where('perkiraan.tipe_akun', 'Detail')->orWhere('perkiraan.kode_akun', 'like', '112%')->Where('perkiraan.tipe_akun', 'Detail')->groupBy('perkiraan.kode_akun','perkiraan.nama_perkiraan','perkiraan.saldo_awal_debet')->get();
        }
         
        return $data;
    }
    
    public function laba_rugi(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $data['penjualan'] = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_satuan) as penjualan'))->whereBetween('tanggal_tagihan', [$from, $to])->first();
        
        $data['penjualan'] = $data['penjualan']->penjualan;
        //pendapatan_lainnya
        
        $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.001')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
        if($saldo_awal->saldo){
            $saldo_awal = $saldo_awal->saldo;
        }else{
            $saldo_awal = 0;
        }
        $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit','420.001')->first();
        if($jm->total){
            $jm = $jm->total;
        }else{
            $jm = 0;
        }
        $pendapatan_lainnya = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.001')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
        if($pendapatan_lainnya->saldo){
            $pendapatan_lainnya = $pendapatan_lainnya->saldo;
        }else{
            $pendapatan_lainnya = 0;
        }
        
        $data['pendapatan_lainnya'] = $saldo_awal+$jm+$pendapatan_lainnya;
            
        //pendapatan_bunga_bank
        
        $saldo_awal = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.002')->whereDate('detail_jurnal_umum.tanggal_jurnal','<',$from)->first();
        if($saldo_awal->saldo){
            $saldo_awal = $saldo_awal->saldo;
        }else{
            $saldo_awal = 0;
        }
        $jm = Detail_jurnal_penerimaan_kas::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as total'))->whereBetween('tanggal_jurnal', [$from, $to])->where('kode_akun_kredit','420.002')->first();
        if($jm->total){
            $jm = $jm->total;
        }else{
            $jm = 0;
        }
        $pendapatan_bunga_bank = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'))->Where('kode_akun_kredit', '420.002')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->first();
        if($pendapatan_bunga_bank->saldo){
            $pendapatan_bunga_bank = $pendapatan_bunga_bank->saldo;
        }else{
            $pendapatan_bunga_bank = 0;
        }
        
        $data['pendapatan_bunga_bank'] = $saldo_awal+$jm+$pendapatan_bunga_bank;
            
        $data['pembelian'] = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_beli) as pembelian'))->whereBetween('tanggal_tagihan', [$from, $to])->first();
        
        $data['pembelian'] = $data['pembelian']->pembelian;
        
        $data['biaya_operasional'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->Where('kode_akun_debit', 'like', '51%')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
            
        $data['biaya_non_operasional'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->Where('kode_akun_debit', 'like', '52%')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
        
        $data['biaya_administrasi_umum'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->Where('kode_akun_debit', 'like', '53%')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
        
        $data['biaya_pajak'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->Where('kode_akun_debit', 'like', '54%')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
        
        $data['biaya_lain'] = Detail_jurnal_umum::select(DB::raw('cast(sum(sub_total) as decimal(65,2)) as saldo'), 'kode_akun_debit', 'detail_kode_akun_debit')->Where('kode_akun_debit', 'like', '55%')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->groupBy('kode_akun_debit', 'detail_kode_akun_debit')->get();
        return $data;
    }
    public function neraca(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $modal = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '310.001')->Where('tipe_akun', 'Detail')->first();
        
        $kas = Perkiraan::select('kode_akun','nama_perkiraan','normal_balance', DB::raw('cast(saldo_awal_debet as decimal(65,2)) as saldo_awal_debet'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', '111.001')->Where('tipe_akun', 'Detail')->first();
        
         
        $data['kas'] = $kas->saldo_awal_debet;
        $data['bank'] = 0;
        $data['piutang_dagang'] = 0;
        $data['uang_muka_pajak'] = 0;
        
        $data['hutang_dagang'] = 0;
        $data['hutang_pajak'] = 0;
        $data['modal'] = $modal->saldo_awal_kredit;
        $data['laba_ditahan'] = 0;
        $data['laba_tahun_berjalan'] = 0;
        return $data;
    }
    
    
    public function pembagian(Request $request)
    {
        $from = $_GET['tanggal_dari'];
        $to = $_GET['tanggal_sampai'];
        
        $data['report'] = Detail_jurnal_umum::select('nama_perusahaan_supplier', DB::raw('sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total)) as harga_pks'), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99 as decimal(65,0)) as harga_petani') , DB::raw(' sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))-cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99 as decimal(65,0)) as pendapatan_cv '),DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total, -sub_total ))*0.99/10000 as decimal(65,0))*10000 as jumlah'), DB::raw('cast(sum( if( kode_akun_debit = "610.001" , sub_total , -sub_total))*0.99-cast(sum( if( kode_akun_debit = "610.001" , sub_total, -sub_total ))*0.99/10000 as decimal(65,0))*10000 as decimal(65,2)) as sisa'))->Where('kode_akun_kredit', '220.001')->groupBy('nama_perusahaan_supplier')->whereBetween('detail_jurnal_umum.tanggal_jurnal', [$from, $to])->get();
        
        $data['tanggal'] = $from.' '.$to;
        
        $datediff = strtotime($to) - strtotime($from);
        
        $data['periode_hari'] = round($datediff / (60 * 60 * 24))+1;

        return $data;
    }
    
}
