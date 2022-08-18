<?php

namespace App\Http\Controllers;

use App\Kwitansi;
use App\Detail_kwitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Detail_kwitansiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        if ($_GET['per_page'] == -1) {
            $detail_kwitansi = Detail_kwitansi::count();
            $_GET['per_page'] = $detail_kwitansi;
        }
        if (isset($_GET['search'])) {
            $detail_kwitansi = Detail_kwitansi::Where('id_kwitansi', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('tanggal_tagihan', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('tanggal', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('berat_bruto', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('satuan_berat_bruto', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('potongan', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('satuan_potongan', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('berat_bersih', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('satuan_berat_bersih', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('harga_satuan', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('ongkos_bongkar', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('harga_beli', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('dpp', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('pph', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('ppn', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('total', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($_GET['per_page']);
            if (isset($_GET['sort'])) {
                $detail_kwitansi = Detail_kwitansi::Where('id_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_tagihan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('berat_bruto', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('satuan_berat_bruto', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('potongan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('satuan_potongan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('berat_bersih', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('satuan_berat_bersih', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('harga_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('ongkos_bongkar', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('harga_beli', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('dpp', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('pph', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('ppn', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('total', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy($_GET['sort'], 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['order'])) {
                    $detail_kwitansi = Detail_kwitansi::Where('id_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_kwitansi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_tagihan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('berat_bruto', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('satuan_berat_bruto', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('potongan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('satuan_potongan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('berat_bersih', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('satuan_berat_bersih', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('harga_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('ongkos_bongkar', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('harga_beli', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('dpp', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('pph', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('ppn', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('total', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            if (isset($_GET['sort']) && isset($_GET['order'])) {
                $detail_kwitansi = Detail_kwitansi::orderBy($_GET['sort'], $_GET['order'])
                    ->paginate($_GET['per_page']);
            }
        }


        return $detail_kwitansi;
    }
    public function select_by_nomor_kwitansi(Request $request, $id_kwitansi)
    {
        $detail_kwitansi = Detail_kwitansi::where('id_kwitansi', $id_kwitansi)->get();

        return $detail_kwitansi;
    }
    public function select(Request $request, $id)
    {
        $detail_kwitansi = Detail_kwitansi::where('id', $id)->get();

        return $detail_kwitansi;
    }
    public function insert(Request $request)
    {
        $detail_kwitansi = new Detail_kwitansi;
        $detail_kwitansi->id = Str::uuid()->toString();
        $detail_kwitansi->id_kwitansi = $request->id_kwitansi;
        $detail_kwitansi->kode_kwitansi = $request->kode_kwitansi;
        $detail_kwitansi->tanggal_tagihan = date("Y-m-d", strtotime($request->tanggal_tagihan));
        $detail_kwitansi->jenis_pembayaran = $request->jenis_pembayaran;
        $detail_kwitansi->nama_customer = $request->nama_customer;
        $detail_kwitansi->kode_detail = $request->kode_detail;
        $detail_kwitansi->tanggal = date("Y-m-d", strtotime($request->tanggal));
        $detail_kwitansi->nomor = $request->nomor;
        $detail_kwitansi->berat_bruto = $request->berat_bruto;
        $detail_kwitansi->satuan_berat_bruto = $request->satuan_berat_bruto;
        $detail_kwitansi->potongan = $request->potongan;
        $detail_kwitansi->satuan_potongan = $request->satuan_potongan;
        $detail_kwitansi->berat_bersih = $request->berat_bersih;
        $detail_kwitansi->satuan_berat_bersih = $request->satuan_berat_bersih;
        $detail_kwitansi->harga_satuan = $request->harga_satuan;
        $detail_kwitansi->nomor_polisi = $request->nomor_polisi;
        $detail_kwitansi->ongkos_bongkar = $request->ongkos_bongkar;
        $detail_kwitansi->harga_beli = $request->harga_beli;
        $detail_kwitansi->dpp = $request->dpp;
        $detail_kwitansi->pph = $request->pph;
        $detail_kwitansi->ppn = $request->ppn;
        $detail_kwitansi->total = $request->total;
        $detail_kwitansi->keterangan = $request->keterangan;
        $detail_kwitansi->status = $request->status;
        $detail_kwitansi->save();

        $kwitansi = Kwitansi::find($request->id_kwitansi);
        $kwitansi->total_dpp_kwitansi = $kwitansi->total_dpp_kwitansi + $request->dpp;
        $kwitansi->total_pph_kwitansi = $kwitansi->total_pph_kwitansi + $request->pph;
        $kwitansi->total_ppn_kwitansi = $kwitansi->total_ppn_kwitansi + $request->ppn;
        $kwitansi->total_nilai_kwitansi = $kwitansi->total_nilai_kwitansi + $request->total;

        //periode
        $periode = Detail_kwitansi::where('id_kwitansi', $request->id_kwitansi)->orderBy('tanggal', 'ASC')->get();
        if (count($periode) == 1) {
            $kwitansi->keterangan_kwitansi = 'Penjualan tbs ke ' . $request->nama_customer . ' periode ' . date("d-m-Y", strtotime($request->tanggal));
        } else {
            $kwitansi->keterangan_kwitansi = 'Penjualan tbs ke ' . $request->nama_customer . ' periode ' . date("d-m-Y", strtotime($periode[0]->tanggal)) . ' sd ' . date("d-m-Y", strtotime($periode[count($periode) - 1]->tanggal));
        }

        $kwitansi->save();

        return $detail_kwitansi;
    }
    public function edit(Request $request, $id)
    {
        $detail_kwitansi = Detail_kwitansi::find($id);
        $kwitansi = Kwitansi::find($detail_kwitansi->id_kwitansi);

        $kwitansi->total_dpp_kwitansi = $kwitansi->total_dpp_kwitansi - $detail_kwitansi->dpp + $request->dpp;
        $kwitansi->total_pph_kwitansi = $kwitansi->total_pph_kwitansi - $detail_kwitansi->pph + $request->pph;
        $kwitansi->total_ppn_kwitansi = $kwitansi->total_ppn_kwitansi - $detail_kwitansi->ppn + $request->ppn;
        $kwitansi->total_nilai_kwitansi = $kwitansi->total_nilai_kwitansi - $detail_kwitansi->total + $request->total;


        $detail_kwitansi->kode_kwitansi = $request->kode_kwitansi;
        $detail_kwitansi->tanggal_tagihan = date("Y-m-d", strtotime($request->tanggal_tagihan));
        $detail_kwitansi->jenis_pembayaran = $request->jenis_pembayaran;
        $detail_kwitansi->nama_customer = $request->nama_customer;
        $detail_kwitansi->kode_detail = $request->kode_detail;
        $detail_kwitansi->tanggal = date("Y-m-d", strtotime($request->tanggal));
        $detail_kwitansi->nomor = $request->nomor;
        $detail_kwitansi->berat_bruto = $request->berat_bruto;
        $detail_kwitansi->satuan_berat_bruto = $request->satuan_berat_bruto;
        $detail_kwitansi->potongan = $request->potongan;
        $detail_kwitansi->satuan_potongan = $request->satuan_potongan;
        $detail_kwitansi->berat_bersih = $request->berat_bersih;
        $detail_kwitansi->satuan_berat_bersih = $request->satuan_berat_bersih;
        $detail_kwitansi->harga_satuan = $request->harga_satuan;
        $detail_kwitansi->nomor_polisi = $request->nomor_polisi;
        $detail_kwitansi->ongkos_bongkar = $request->ongkos_bongkar;
        $detail_kwitansi->harga_beli = $request->harga_beli;
        $detail_kwitansi->dpp = $request->dpp;
        $detail_kwitansi->pph = $request->pph;
        $detail_kwitansi->ppn = $request->ppn;
        $detail_kwitansi->total = $request->total;
        $detail_kwitansi->keterangan = $request->keterangan;
        $detail_kwitansi->status = $request->status;
        $detail_kwitansi->save();

        //periode
        $periode = Detail_kwitansi::where('id_kwitansi', $request->id_kwitansi)->orderBy('tanggal', 'ASC')->get();
        if (count($periode) == 1) {
            $kwitansi->keterangan_kwitansi = 'Penjualan tbs ke ' . $request->nama_customer . ' periode ' . date("d-m-Y", strtotime($request->tanggal));
        } else {
            $kwitansi->keterangan_kwitansi = 'Penjualan tbs ke ' . $request->nama_customer . ' periode ' . date("d-m-Y", strtotime($periode[0]->tanggal)) . ' sd ' . date("d-m-Y", strtotime($periode[count($periode) - 1]->tanggal));
        }

        $kwitansi->save();

        return $detail_kwitansi;
    }
    public function delete(Request $request, $id)
    {
        $detail_kwitansi = Detail_kwitansi::find($id);
        $detail_kwitansi->delete();

        return $detail_kwitansi;
    }
}
