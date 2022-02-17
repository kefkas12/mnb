<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_umum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Detail_jurnal_umumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $detail_jurnal_umum = Detail_jurnal_umum::count();
                $_GET['per_page'] = $detail_jurnal_umum;
            }
            $detail_jurnal_umum = Detail_jurnal_umum::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $detail_jurnal_umum = Detail_jurnal_umum::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $detail_jurnal_umum = Detail_jurnal_umum::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $detail_jurnal_umum = Detail_jurnal_umum::Where('id_jurnal_umum', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('detail_kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_detail', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('banyaknya', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('harga', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('sub_total', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('detail_kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan_supplier', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan_customer', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_karyawan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('alat_berat', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('peralatan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('truck', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('mobil', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('motor', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $detail_jurnal_umum = Detail_jurnal_umum::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }

        return $detail_jurnal_umum;
    }
    public function select_by_nomor_jurnal(Request $request, $id_jurnal_umum)
    {
        $detail_jurnal_umum = Detail_jurnal_umum::where('id_jurnal_umum', $id_jurnal_umum)->get();

        return $detail_jurnal_umum;
    }
    public function select(Request $request, $id)
    {
        $detail_jurnal_umum = Detail_jurnal_umum::where('id', $id)->get();

        return $detail_jurnal_umum;
    }
    public function insert(Request $request)
    {
        $detail_jurnal_umum = new Detail_jurnal_umum;
        $detail_jurnal_umum->id = Str::uuid()->toString();
        $detail_jurnal_umum->id_jurnal_umum = $request->id_jurnal_umum;
        $detail_jurnal_umum->nomor_jurnal = $request->nomor_jurnal;
        $detail_jurnal_umum->tanggal_jurnal = $request->tanggal_jurnal;
        $detail_jurnal_umum->kode_akun_kredit = $request->kode_akun_kredit;
        $detail_jurnal_umum->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        $detail_jurnal_umum->kode_detail = $request->kode_detail;
        $detail_jurnal_umum->banyaknya = $request->banyaknya;
        $detail_jurnal_umum->nama_satuan = $request->nama_satuan;
        $detail_jurnal_umum->harga = $request->harga;
        $detail_jurnal_umum->sub_total = $request->sub_total;
        $detail_jurnal_umum->keterangan = $request->keterangan;
        $detail_jurnal_umum->kode_akun_debit = $request->kode_akun_debit;
        $detail_jurnal_umum->detail_kode_akun_debit = $request->detail_kode_akun_debit;
        $detail_jurnal_umum->nama_perusahaan_supplier = $request->nama_perusahaan_supplier;
        $detail_jurnal_umum->nama_perusahaan_customer = $request->nama_perusahaan_customer;
        $detail_jurnal_umum->nama_karyawan = $request->nama_karyawan;
        $detail_jurnal_umum->alat_berat = $request->alat_berat;
        $detail_jurnal_umum->peralatan = $request->peralatan;
        $detail_jurnal_umum->truck = $request->truck;
        $detail_jurnal_umum->mobil = $request->mobil;
        $detail_jurnal_umum->motor = $request->motor;
        $detail_jurnal_umum->save();

        return $detail_jurnal_umum;
    }
    public function edit(Request $request, $id)
    {
        $detail_jurnal_umum = Detail_jurnal_umum::find($id);
        $detail_jurnal_umum->id_jurnal_umum = $request->id_jurnal_umum;
        $detail_jurnal_umum->nomor_jurnal = $request->nomor_jurnal;
        $detail_jurnal_umum->tanggal_jurnal = $request->tanggal_jurnal;
        $detail_jurnal_umum->kode_akun_kredit = $request->kode_akun_kredit;
        $detail_jurnal_umum->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        $detail_jurnal_umum->kode_detail = $request->kode_detail;
        $detail_jurnal_umum->banyaknya = $request->banyaknya;
        $detail_jurnal_umum->nama_satuan = $request->nama_satuan;
        $detail_jurnal_umum->harga = $request->harga;
        $detail_jurnal_umum->sub_total = $request->sub_total;
        $detail_jurnal_umum->keterangan = $request->keterangan;
        $detail_jurnal_umum->kode_akun_debit = $request->kode_akun_debit;
        $detail_jurnal_umum->detail_kode_akun_debit = $request->detail_kode_akun_debit;
        $detail_jurnal_umum->nama_perusahaan_supplier = $request->nama_perusahaan_supplier;
        $detail_jurnal_umum->nama_perusahaan_customer = $request->nama_perusahaan_customer;
        $detail_jurnal_umum->nama_karyawan = $request->nama_karyawan;
        $detail_jurnal_umum->alat_berat = $request->alat_berat;
        $detail_jurnal_umum->peralatan = $request->peralatan;
        $detail_jurnal_umum->truck = $request->truck;
        $detail_jurnal_umum->mobil = $request->mobil;
        $detail_jurnal_umum->motor = $request->motor;
        $detail_jurnal_umum->save();

        return $detail_jurnal_umum;
    }
    public function delete(Request $request, $id)
    {
        $detail_jurnal_umum = Detail_jurnal_umum::find($id);
        $detail_jurnal_umum->delete();

        return $detail_jurnal_umum;
    }
}
