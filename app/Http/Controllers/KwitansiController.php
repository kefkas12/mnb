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

class KwitansiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kode_kwitansi()
    {
        $last = Kwitansi::select("kode_kwitansi")->orderBy("created_at", "desc")->first();
        if (!$last) {
            return 'KWP-' . date('Y') . '-00001-' . date('m') . '-MNB';
        } else {
            $no = intval(substr($last->nomor_jurnal, 9, 14)) + 1;
            if ($no < 10) {
                return 'KWP-' . date('Y') . '-0000' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 100) {
                return 'KWP-' . date('Y') . '-000' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 1000) {
                return 'KWP-' . date('Y') . '-00' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 10000) {
                return 'KWP-' . date('Y') . '-0' . $no . '-' . date('m') . '-MNB';
            } else {
                return 'KWP-' . date('Y') . '-' . $no . '-' . date('m') . '-MNB';
            }
        }
    }
    public function kode_kwitansi_last()
    {
        $last = Kwitansi::select("id", "nomor_kwitansi")->orderBy("created_at", "desc")->first();
        return $last;
    }
    public function index()
    {
        if (isset($_GET['per_page'])) {
            if ($_GET['per_page'] == -1) {
                $kwitansi = Kwitansi::count();
                $_GET['per_page'] = $kwitansi;
            }
            $kwitansi = Kwitansi::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')

                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                } else if (isset($_GET['tanggal_dari'])) {
                    if ($_GET['tanggal_dari'] != '') {
                        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
                        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
                        $kwitansi = Kwitansi::whereBetween('tanggal_kwitansi', [$from, $to])
                            ->paginate($_GET['per_page']);
                    } else {
                        $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
    
                        ->orderBy('created_at', 'desc')
                        ->paginate($_GET['per_page']);
                    }
                }
            } else if(isset($_GET['tanggal_dari'])){
                $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
                $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
                $kwitansi = Kwitansi::whereBetween('tanggal_kwitansi', [$from, $to])
                            ->paginate($_GET['per_page']);
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kwitansi = Kwitansi::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $kwitansi = Kwitansi::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $kwitansi = Kwitansi::Where('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanggal_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanda_tangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_invoice', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_seri_faktur_pajak', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('no_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_dpp_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_pph_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_ppn_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('total_nilai_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan_kwitansi', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kwitansi = Kwitansi::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }

        return $kwitansi;
    }
    public function select(Request $request, $id)
    {
        $kwitansi = Kwitansi::where('id', $id)->get();

        return $kwitansi;
    }
    public function keuntungan_get(Request $request)
    {
        $keuntungan = Keuntungan::get();

        return $keuntungan;
    }
    public function keuntungan_insert(Request $request)
    {
        $keuntungan = Keuntungan::find(1);
        $keuntungan->keuntungan = $request->keuntungan;
        $keuntungan->save();

        return $keuntungan;
    }
    
    public function insert(Request $request)
    {
        $kwitansi = new Kwitansi;
        $kwitansi->id = Str::uuid()->toString();
        $kwitansi->kode_kwitansi = $request->kode_kwitansi;
        $kwitansi->tanggal_kwitansi = date("Y-m-d", strtotime($request->tanggal_kwitansi));
        $kwitansi->jenis_pembayaran = $request->jenis_pembayaran;
        $kwitansi->status_kwitansi = $request->status_kwitansi;
        $kwitansi->nama_customer = $request->nama_customer;
        $kwitansi->nomor_rekening = $request->nomor_rekening;
        $kwitansi->tanda_tangan = $request->tanda_tangan;
        $kwitansi->no_invoice = $request->no_invoice;
        $kwitansi->no_seri_faktur_pajak = $request->no_seri_faktur_pajak;
        $kwitansi->no_kwitansi = $request->no_kwitansi;
        $kwitansi->total_dpp_kwitansi = $request->total_dpp_kwitansi;
        $kwitansi->total_pph_kwitansi = $request->total_pph_kwitansi;
        $kwitansi->total_ppn_kwitansi = $request->total_ppn_kwitansi;
        $kwitansi->total_nilai_kwitansi = $request->total_nilai_kwitansi;
        $kwitansi->keterangan_kwitansi = $request->keterangan_kwitansi;
        $kwitansi->save();

        return $kwitansi;
    }
    public function edit(Request $request, $id)
    {
        $kwitansi = Kwitansi::find($id);
        $kwitansi->kode_kwitansi = $request->kode_kwitansi;
        $kwitansi->tanggal_kwitansi = date("Y-m-d", strtotime($request->tanggal_kwitansi));
        $kwitansi->jenis_pembayaran = $request->jenis_pembayaran;
        $kwitansi->status_kwitansi = $request->status_kwitansi;
        $kwitansi->nama_customer = $request->nama_customer;
        $kwitansi->nomor_rekening = $request->nomor_rekening;
        $kwitansi->tanda_tangan = $request->tanda_tangan;
        $kwitansi->no_invoice = $request->no_invoice;
        $kwitansi->no_seri_faktur_pajak = $request->no_seri_faktur_pajak;
        $kwitansi->no_kwitansi = $request->no_kwitansi;
        $kwitansi->total_dpp_kwitansi = $request->total_dpp_kwitansi;
        $kwitansi->total_pph_kwitansi = $request->total_pph_kwitansi;
        $kwitansi->total_ppn_kwitansi = $request->total_ppn_kwitansi;
        $kwitansi->total_nilai_kwitansi = $request->total_nilai_kwitansi;
        $kwitansi->keterangan_kwitansi = $request->keterangan_kwitansi;
        $kwitansi->save();

        return $kwitansi;
    }
    public function delete(Request $request, $id)
    {
        $kwitansi = Kwitansi::find($id);
        $kwitansi->delete();

        return $kwitansi;
    }
    public function ongkos_bongkar(Request $request, $id)
    {
        $detail_kwitansi = Detail_kwitansi::select('tanggal','nomor','nomor_polisi','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','ongkos_bongkar')->where('id_kwitansi', $id)->get();

        return $detail_kwitansi;
    }
    public function kwitansi_bongkar(Request $request, $id)
    {
        $nominal = Detail_kwitansi::select(DB::raw('SUM(ongkos_bongkar) as nominal'))->where('id_kwitansi', $id)->get();
        $kwitansi = Kwitansi::where('id', $id)->first();
        $detail_kwitansi = new Detail_kwitansi;
        $nominal[0]->offsetSet('nama_customer', $kwitansi->nama_customer);
        $nominal[0]->offsetSet('terbilang', $detail_kwitansi->terbilang($nominal[0]->nominal));
        $nominal[0]->offsetSet('keterangan', $kwitansi->keterangan_kwitansi);
        $nominal[0]->offsetSet('tanggal', date("Y-m-d", strtotime($kwitansi->tanggal_kwitansi)));
        $nominal[0]->offsetSet('tanda_tangan', $kwitansi->tanda_tangan);

        return $nominal;
    }
    public function ba_plus_ppn(Request $request, $id)
    {
        $data['detail_kwitansi'] = Detail_kwitansi::select('tanggal','nomor_polisi','nomor','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan','dpp','ppn','pph',DB::raw('dpp+ppn-pph  as jumlah_yang_diterima'))->where('id_kwitansi', $id)->get();

        $data['kwitansi'] = Kwitansi::leftJoin('customer','kwitansi.nama_customer','=','customer.nama_perusahaan')->where('kwitansi.id', $id)->first();

        return $data;
    }
    public function ba_min_ppn(Request $request, $id)
    {
        $data['detail_kwitansi'] = Detail_kwitansi::select('tanggal','nomor_polisi','nomor','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan','dpp','pph',DB::raw('dpp-pph  as jumlah_yang_diterima'))->where('id_kwitansi', $id)->get();

        $data['kwitansi'] = Kwitansi::leftJoin('customer','kwitansi.nama_customer','=','customer.nama_perusahaan')->where('kwitansi.id', $id)->first();

        return $data;
    }

    public function inv_plus_ppn(Request $request, $id)
    {
        $data['detail_kwitansi'] = Detail_kwitansi::select('tanggal','nomor','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan',DB::raw('berat_bersih*harga_satuan  as jumlah'))->where('id_kwitansi', $id)->get();

        $data['kwitansi'] = Kwitansi::leftJoin('customer','kwitansi.nama_customer','=','customer.nama_perusahaan')->where('kwitansi.id', $id)->first();

        return $data;
    }
    public function inv_min_ppn(Request $request, $id)
    {
        $data['detail_kwitansi'] = Detail_kwitansi::select('tanggal','nomor','berat_bruto','satuan_berat_bruto','potongan','satuan_potongan','berat_bersih','satuan_berat_bersih','harga_satuan',DB::raw('berat_bersih*harga_satuan  as jumlah'))->where('id_kwitansi', $id)->get();

        $data['kwitansi'] = Kwitansi::leftJoin('customer','kwitansi.nama_customer','=','customer.nama_perusahaan')->where('kwitansi.id', $id)->first();

        return $data;
    }

    public function kwt_plus_ppn(Request $request, $id)
    {
        $nominal = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_satuan)-(0.25/100 * SUM(berat_bersih*harga_satuan) ) + (0.1 * SUM(berat_bersih*harga_satuan)) as nominal'))->where('id_kwitansi', $id)->get();
        $nominal_round = round($nominal[0]->nominal);
        $kwitansi = Kwitansi::where('id', $id)->first();
        $detail_kwitansi = new Detail_kwitansi;
        $nominal[0]->offsetSet('no_invoice',$kwitansi->no_invoice);
        $nominal[0]->offsetSet('nama_customer',$kwitansi->nama_customer);
        $nominal[0]->offsetSet('nomor_rekening',$kwitansi->nomor_rekening);
        $nominal[0]->offsetSet('keterangan_kwitansi',$kwitansi->keterangan_kwitansi);
        $nominal[0]->offsetSet('tanda_tangan',$kwitansi->tanda_tangan);
        $nominal[0]->offsetSet('tanggal_kwitansi',date("Y-m-d", strtotime($kwitansi->tanggal_kwitansi)));
        $nominal[0]->offsetSet('terbilang', $detail_kwitansi->terbilang($nominal[0]->nominal));
        $nominal[0]->offsetSet('nominal',$nominal_round);
        return $nominal;
    }
    public function kwt_min_ppn(Request $request, $id)
    {
        $nominal = Detail_kwitansi::select(DB::raw('SUM(berat_bersih*harga_satuan)-(0.25/100 * SUM(berat_bersih*harga_satuan)) as nominal'))->where('id_kwitansi', $id)->get();
        $nominal_round = round($nominal[0]->nominal);
        $kwitansi = Kwitansi::where('id', $id)->first();
        $detail_kwitansi = new Detail_kwitansi;
        $nominal[0]->offsetSet('no_invoice',$kwitansi->no_invoice);
        $nominal[0]->offsetSet('nama_customer',$kwitansi->nama_customer);
        $nominal[0]->offsetSet('nomor_rekening',$kwitansi->nomor_rekening);
        $nominal[0]->offsetSet('keterangan_kwitansi',$kwitansi->keterangan_kwitansi);
        $nominal[0]->offsetSet('tanda_tangan',$kwitansi->tanda_tangan);
        $nominal[0]->offsetSet('tanggal_kwitansi',date("Y-m-d", strtotime($kwitansi->tanggal_kwitansi)));
        $nominal[0]->offsetSet('terbilang', $detail_kwitansi->terbilang($nominal[0]->nominal));
        $nominal[0]->offsetSet('nominal',$nominal_round);
        return $nominal;
    }
    public function report(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        // $customer = $_GET['customer'];
        $data['report'] = Kwitansi::leftjoin('detail_kwitansi', 'kwitansi.id', '=', 'detail_kwitansi.id_kwitansi')->select('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'kwitansi.nama_customer',DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan) as decimal(65,2)) as dpp'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*0.1 as decimal(65,2)) as ppn'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)/400 as decimal(65,2)) as pph'),DB::raw('cast(SUM(detail_kwitansi.berat_bersih*detail_kwitansi.harga_satuan)*1.1 as decimal(65,2)) as sub_total'))->groupBy('kwitansi.no_kwitansi', 'kwitansi.tanggal_kwitansi', 'kwitansi.nama_customer')->whereBetween('kwitansi.tanggal_kwitansi', [$from, $to])->get();
        $data['tanggal'] = $from.' '.$to;
        return $data;
    }
    public function report_petani(Request $request)
    {
        $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
        $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
        // $customer = $_GET['customer'];
        $data['report'] = Detail_kwitansi::select('tanggal_tagihan as tanggal','nomor','nomor_polisi',DB::raw('cast(berat_bruto as decimal(65,2)) as berat_bruto'),'satuan_berat_bruto',DB::raw('cast(potongan as decimal(65,2)) as potongan'),'satuan_potongan',DB::raw('cast(berat_bersih as decimal(65,2)) as berat_bersih'),'satuan_berat_bersih', DB::raw('cast(harga_beli as decimal(65,2)) as harga_beli'), DB::raw('cast(berat_bersih*harga_beli as decimal(65,2)) as jumlah'))->whereBetween('tanggal_tagihan', [$from, $to])->orderBy('tanggal_tagihan','DESC')->get();

        $data['tanggal'] = $from.' '.$to;
        return $data;
    }

}
