<?php

namespace App\Http\Controllers;

use App\Jurnal_penyesuaian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Jurnal_penyesuaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if($_GET['per_page']){
            $jurnal_penyesuaian = Jurnal_penyesuaian::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
        }else{
            $jurnal_penyesuaian = Jurnal_penyesuaian::orderBy('created_at', 'desc')->paginate();
        }

        return $jurnal_penyesuaian;
    }
    public function select(Request $request, $id)
    {
        $jurnal_penyesuaian = Jurnal_penyesuaian::where('id',$id)->get();

        return $jurnal_penyesuaian;
    }
    public function insert(Request $request)
    {
        $jurnal_penyesuaian = new Jurnal_penyesuaian;
        $jurnal_penyesuaian->id = Str::uuid()->toString();
        $jurnal_penyesuaian->nomor_jurnal_induk = $request->nomor_jurnal_induk;
        $jurnal_penyesuaian->nomor_jurnal = $request->nomor_jurnal;
        $jurnal_penyesuaian->tanggal_jurnal = $request->tanggal_jurnal;
        $jurnal_penyesuaian->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_penyesuaian->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_penyesuaian->nomor_jurnal_print = $request->nomor_jurnal_print;
        $jurnal_penyesuaian->nomor_bukti = $request->nomor_bukti;
        $jurnal_penyesuaian->status = $request->status;
        $jurnal_penyesuaian->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_penyesuaian->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_penyesuaian->deskripsi = $request->deskripsi;
        $jurnal_penyesuaian->kode_akun_kredit = $request->kode_akun_kredit;
        $jurnal_penyesuaian->id_supplier = $request->id_supplier;
        $jurnal_penyesuaian->id_customer = $request->id_customer;
        $jurnal_penyesuaian->save();

        return $jurnal_penyesuaian;
    }
    public function edit(Request $request, $id)
    {
        $jurnal_penyesuaian = Jurnal_penyesuaian::find($id);
        $jurnal_penyesuaian->nomor_jurnal_induk = $request->nomor_jurnal_induk;
        $jurnal_penyesuaian->nomor_jurnal = $request->nomor_jurnal;
        $jurnal_penyesuaian->tanggal_jurnal = $request->tanggal_jurnal;
        $jurnal_penyesuaian->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_penyesuaian->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_penyesuaian->nomor_jurnal_print = $request->nomor_jurnal_print;
        $jurnal_penyesuaian->nomor_bukti = $request->nomor_bukti;
        $jurnal_penyesuaian->status = $request->status;
        $jurnal_penyesuaian->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_penyesuaian->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_penyesuaian->deskripsi = $request->deskripsi;
        $jurnal_penyesuaian->kode_akun_kredit = $request->kode_akun_kredit;
        $jurnal_penyesuaian->id_supplier = $request->id_supplier;
        $jurnal_penyesuaian->id_customer = $request->id_customer;
        $jurnal_penyesuaian->save();

        return $jurnal_penyesuaian;
    }
    public function delete(Request $request, $id)
    {
        $jurnal_penyesuaian = Jurnal_penyesuaian::find($id);
        $jurnal_penyesuaian->delete();

        return $jurnal_penyesuaian;
    }
}
