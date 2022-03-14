<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_pengeluaran_kas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Detail_jurnal_pengeluaran_kasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::count();
                $_GET['per_page'] = $detail_jurnal_pengeluaran_kas;
            }
            $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                    $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                    $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                    $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::Where('id_jurnal_pengeluaran_kas', 'like', '%' . $_GET['search'] . '%')
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
                    $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }

        return $detail_jurnal_pengeluaran_kas;
    }
    public function select_by_nomor_jurnal(Request $request, $id_jurnal_pengeluaran_kas)
    {
        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::where('id_jurnal_pengeluaran_kas', $id_jurnal_pengeluaran_kas)->get();

        return $detail_jurnal_pengeluaran_kas;
    }
    public function select(Request $request, $id)
    {
        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::where('id', $id)->get();

        return $detail_jurnal_pengeluaran_kas;
    }
    public function insert(Request $request)
    {
        $detail_jurnal_pengeluaran_kas = new Detail_jurnal_pengeluaran_kas;
        $detail_jurnal_pengeluaran_kas->id = Str::uuid()->toString();
        $detail_jurnal_pengeluaran_kas->id_jurnal_pengeluaran_kas = $request->id_jurnal_pengeluaran_kas;
        $detail_jurnal_pengeluaran_kas->nomor_jurnal = $request->nomor_jurnal;
        $detail_jurnal_pengeluaran_kas->tanggal_jurnal = $request->tanggal_jurnal;
        $detail_jurnal_pengeluaran_kas->kode_akun_kredit = $request->kode_akun_kredit;
        $detail_jurnal_pengeluaran_kas->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        $detail_jurnal_pengeluaran_kas->kode_detail = $request->kode_detail;
        $detail_jurnal_pengeluaran_kas->banyaknya = $request->banyaknya;
        $detail_jurnal_pengeluaran_kas->nama_satuan = $request->nama_satuan;
        $detail_jurnal_pengeluaran_kas->harga = $request->harga;
        $detail_jurnal_pengeluaran_kas->sub_total = $request->sub_total;
        $detail_jurnal_pengeluaran_kas->keterangan = $request->keterangan;
        $detail_jurnal_pengeluaran_kas->kode_akun_debit = $request->kode_akun_debit;
        $detail_jurnal_pengeluaran_kas->detail_kode_akun_debit = $request->detail_kode_akun_debit;
        $detail_jurnal_pengeluaran_kas->nama_perusahaan_supplier = $request->nama_perusahaan_supplier;
        $detail_jurnal_pengeluaran_kas->nama_perusahaan_customer = $request->nama_perusahaan_customer;
        $detail_jurnal_pengeluaran_kas->nama_karyawan = $request->nama_karyawan;
        $detail_jurnal_pengeluaran_kas->alat_berat = $request->alat_berat;
        $detail_jurnal_pengeluaran_kas->peralatan = $request->peralatan;
        $detail_jurnal_pengeluaran_kas->truck = $request->truck;
        $detail_jurnal_pengeluaran_kas->mobil = $request->mobil;
        $detail_jurnal_pengeluaran_kas->motor = $request->motor;
        $detail_jurnal_pengeluaran_kas->save();

        return $detail_jurnal_pengeluaran_kas;
    }
    public function edit(Request $request, $id)
    {
        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::find($id);
        $detail_jurnal_pengeluaran_kas->id_jurnal_pengeluaran_kas = $request->id_jurnal_pengeluaran_kas;
        $detail_jurnal_pengeluaran_kas->nomor_jurnal = $request->nomor_jurnal;
        $detail_jurnal_pengeluaran_kas->tanggal_jurnal = $request->tanggal_jurnal;
        $detail_jurnal_pengeluaran_kas->kode_akun_kredit = $request->kode_akun_kredit;
        $detail_jurnal_pengeluaran_kas->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        $detail_jurnal_pengeluaran_kas->kode_detail = $request->kode_detail;
        $detail_jurnal_pengeluaran_kas->banyaknya = $request->banyaknya;
        $detail_jurnal_pengeluaran_kas->nama_satuan = $request->nama_satuan;
        $detail_jurnal_pengeluaran_kas->harga = $request->harga;
        $detail_jurnal_pengeluaran_kas->sub_total = $request->sub_total;
        $detail_jurnal_pengeluaran_kas->keterangan = $request->keterangan;
        $detail_jurnal_pengeluaran_kas->kode_akun_debit = $request->kode_akun_debit;
        $detail_jurnal_pengeluaran_kas->detail_kode_akun_debit = $request->detail_kode_akun_debit;
        $detail_jurnal_pengeluaran_kas->nama_perusahaan_supplier = $request->nama_perusahaan_supplier;
        $detail_jurnal_pengeluaran_kas->nama_perusahaan_customer = $request->nama_perusahaan_customer;
        $detail_jurnal_pengeluaran_kas->nama_karyawan = $request->nama_karyawan;
        $detail_jurnal_pengeluaran_kas->alat_berat = $request->alat_berat;
        $detail_jurnal_pengeluaran_kas->peralatan = $request->peralatan;
        $detail_jurnal_pengeluaran_kas->truck = $request->truck;
        $detail_jurnal_pengeluaran_kas->mobil = $request->mobil;
        $detail_jurnal_pengeluaran_kas->motor = $request->motor;
        $detail_jurnal_pengeluaran_kas->save();

        return $detail_jurnal_pengeluaran_kas;
    }
    public function delete(Request $request, $id)
    {
        $detail_jurnal_pengeluaran_kas = Detail_jurnal_pengeluaran_kas::find($id);
        $detail_jurnal_pengeluaran_kas->delete();

        return $detail_jurnal_pengeluaran_kas;
    }
}
