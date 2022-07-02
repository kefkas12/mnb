<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_umum;
use App\Jurnal_umum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Jurnal_umumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kode_jurnal_umum()
    {
        $last = Jurnal_umum::select("nomor_jurnal")->orderBy("created_at", "desc")->first();
        if (!$last) {
            return 'JGN-' . date('Y') . '-000001-EKTRN';
        } else {
            $no = intval(substr($last->nomor_jurnal, 9, 14)) + 1;
            if ($no < 10) {
                return 'JGN-' . date('Y') . '-00000' . $no . '-EKTRN';
            } elseif ($no < 100) {
                return 'JGN-' . date('Y') . '-0000' . $no . '-EKTRN';
            } elseif ($no < 1000) {
                return 'JGN-' . date('Y') . '-000' . $no . '-EKTRN';
            } elseif ($no < 10000) {
                return 'JGN-' . date('Y') . '-00' . $no . '-EKTRN';
            } elseif ($no < 100000) {
                return 'JGN-' . date('Y') . '-0' . $no . '-EKTRN';
            } else {
                return 'JGN-' . date('Y') . '-' . $no . '-EKTRN';
            }
        }
    }
    public function kode_jurnal_umum_induk()
    {
        $last = Jurnal_umum::select("nomor_jurnal_induk")->orderBy("created_at", "desc")->first();
        if (!$last) {
            return 'JGN-' . date('Y') . '-000001-MNB';
        } else {
            $no = intval(substr($last->nomor_jurnal_induk, 9, 14)) + 1;
            if ($no < 10) {
                return 'JGN-' . date('Y') . '-00000' . $no . '-MNB';
            } elseif ($no < 100) {
                return 'JGN-' . date('Y') . '-0000' . $no . '-MNB';
            } elseif ($no < 1000) {
                return 'JGN-' . date('Y') . '-000' . $no . '-MNB';
            } elseif ($no < 10000) {
                return 'JGN-' . date('Y') . '-00' . $no . '-MNB';
            } elseif ($no < 100000) {
                return 'JGN-' . date('Y') . '-0' . $no . '-MNB';
            } else {
                return 'JGN-' . date('Y') . '-' . $no . '-MNB';
            }
        }
    }
    public function kode_jurnal_umum_last()
    {
        $last = Jurnal_umum::select("id", "nomor_jurnal")->orderBy("created_at", "desc")->first();
        return $last;
    }
    public function index()
    {
        ///api/kwitansi?per_page=5&page=1&search=&tanggal_dari=2022-02-01&tanggal_sampai=2022-02-12
        if ($_GET['per_page'] == -1) {
            $jurnal_umum = Jurnal_umum::count();
            $_GET['per_page'] = $jurnal_umum;
        }
        if (isset($_GET['sort'])) {
            $jurnal_umum = Jurnal_umum::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_jurnal_print', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('tanggal_bukti_kas', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_bukti', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_rekening_pengirim', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_rekening_penerima', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('deskripsi', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('id_supplier', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('id_customer', 'like', '%' . $_GET['search'] . '%')
                ->orderBy($_GET['sort'], 'desc')
                ->paginate($_GET['per_page']);
            if (isset($_GET['order'])) {
                $jurnal_umum = Jurnal_umum::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal_print', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_bukti_kas', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_bukti', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening_pengirim', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening_penerima', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('deskripsi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('id_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('id_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy($_GET['sort'], $_GET['order'])
                    ->paginate($_GET['per_page']);
            }
        } else if (isset($_GET['tanggal_dari'])) {
            if ($_GET['tanggal_dari'] != '') {
                $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
                $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
                $jurnal_umum = Jurnal_umum::whereBetween('tanggal_jurnal', [$from, $to])
                    ->paginate($_GET['per_page']);
            } else {
                $jurnal_umum = Jurnal_umum::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_jurnal', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_jurnal_print', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('tanggal_bukti_kas', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_bukti', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_pembayaran', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening_pengirim', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening_penerima', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('deskripsi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('id_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('id_customer', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
            }
        }


        return $jurnal_umum;
    }
    public function select(Request $request, $id)
    {
        $jurnal_umum = Jurnal_umum::where('id', $id)->get();

        return $jurnal_umum;
    }
    public function insert(Request $request)
    {
        $jurnal_umum = new Jurnal_umum;
        $jurnal_umum->id = Str::uuid()->toString();
        $jurnal_umum->nomor_jurnal_induk = $jurnal_umum->last_kode_induk();
        $jurnal_umum->nomor_jurnal = $jurnal_umum->last_kode();
        $jurnal_umum->tanggal_jurnal = date("Y-m-d", strtotime($request->tanggal_jurnal));
        $jurnal_umum->nomor_jurnal_print = $jurnal_umum->last_kode();
        $jurnal_umum->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_umum->nomor_bukti = $request->nomor_bukti;
        $jurnal_umum->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_umum->status = $request->status;
        $jurnal_umum->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_umum->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_umum->deskripsi = $request->deskripsi;
        $jurnal_umum->kode_akun_kredit = $request->kode_akun_kredit;
        $jurnal_umum->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        if ($request->id_supplier) {
            $jurnal_umum->id_supplier = $request->id_supplier;
        }
        if ($request->id_customer) {
            $jurnal_umum->id_customer = $request->id_customer;
        }
        $jurnal_umum->save();

        return $jurnal_umum;
    }
    public function edit(Request $request, $id)
    {
        $jurnal_umum = Jurnal_umum::find($id);
        // $jurnal_umum->nomor_jurnal_induk = $request->nomor_jurnal_induk;
        // $jurnal_umum->nomor_jurnal = $request->nomor_jurnal;
        $jurnal_umum->tanggal_jurnal = date("Y-m-d", strtotime($request->tanggal_jurnal));
        // $jurnal_umum->nomor_jurnal_print = $request->nomor_jurnal_print;
        $jurnal_umum->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_umum->nomor_bukti = $request->nomor_bukti;
        $jurnal_umum->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_umum->status = $request->status;
        $jurnal_umum->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_umum->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_umum->deskripsi = $request->deskripsi;
        // $jurnal_umum->kode_akun_kredit = $request->kode_akun_kredit;
        // $jurnal_umum->detail_kode_akun_kredit = $request->detail_kode_akun_kredit;
        $jurnal_umum->id_supplier = $request->id_supplier;
        $jurnal_umum->id_customer = $request->id_customer;
        $jurnal_umum->save();

        return $jurnal_umum;
    }
    public function delete(Request $request, $id)
    {
        $jurnal_umum = Jurnal_umum::find($id);
        $jurnal_umum->delete();
        $detail_jurnal_umum = Detail_jurnal_umum::where('id_jurnal_umum', $id)->delete();

        return $jurnal_umum;
    }

    public function all_jurnal(Request $request)
    {
        $jurnal_umum = Jurnal_umum::with('detail_jurnal_umum')->get();
        foreach ($jurnal_umum as $v) {
            $total_kredit = 0;
            foreach ($v->detail_jurnal_umum as $w) {
                $total_kredit += $w->sub_total;
            }
            $v->offsetSet('total_kredit', $total_kredit);
        }
        return $jurnal_umum;
    }

    public function bukti_kas(Request $request, $id_jurnal_umum)
    {
        $detail_jurnal_umum = new Detail_jurnal_umum;
        $bukti_kas = Detail_jurnal_umum::select(DB::raw('group_concat(keterangan SEPARATOR ", ") as keterangan'), DB::raw('SUM(sub_total) as sub_total'), DB::raw('group_concat(tanggal_jurnal SEPARATOR ", ") as tanggal_jurnal'))->where('id_jurnal_umum', $id_jurnal_umum)->get();
        $jurnal_umum = Jurnal_umum::where('id', $id_jurnal_umum)->first();
        $bukti_kas[0]->offsetSet('tanggal_jurnal', $jurnal_umum->tanggal_jurnal);
        $bukti_kas[0]->offsetSet('terbilang', $detail_jurnal_umum->terbilang($bukti_kas[0]->sub_total));

        $jenis_pembayaran = Jurnal_umum::select('jenis_pembayaran')->where('id', $id_jurnal_umum)->get();
        $bukti_kas[0]->offsetSet('jenis_pembayaran', $jenis_pembayaran[0]->jenis_pembayaran);
        return $bukti_kas;
    }
    public function select_jurnal(Request $request, $id)
    {
        $jurnal = Jurnal_umum::leftjoin('detail_jurnal_umum', 'jurnal_umum.id', '=', 'detail_jurnal_umum.id_jurnal_umum')->leftjoin('perkiraan', 'detail_jurnal_umum.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_umum.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit as kode_akun','jurnal_umum.detail_kode_akun_kredit as detail_kode_akun', DB::raw('SUM(detail_jurnal_umum.sub_total) as total_kredit'))->groupBy('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit', 'pk.nama_perkiraan','jurnal_umum.detail_kode_akun_kredit' )->where('jurnal_umum.id', $id)->with(['detail_jurnal_umum' => function ($query) {
            $query->select('id', 'id_jurnal_umum', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'detail_kode_akun_debit as detail_kode_akun', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
        }])->get();

        $detail_jurnal_umum = new Detail_jurnal_umum;
        $bukti_kas = Detail_jurnal_umum::select(DB::raw('group_concat(keterangan SEPARATOR ", ") as keterangan'), DB::raw('SUM(sub_total) as sub_total'), DB::raw('group_concat(tanggal_jurnal SEPARATOR ", ") as tanggal_jurnal'))->where('id_jurnal_umum', $id)->get();
        $bukti_kas[0]->offsetSet('terbilang', $detail_jurnal_umum->terbilang($bukti_kas[0]->sub_total));

        $jenis_pembayaran = Jurnal_umum::select('jenis_pembayaran')->where('id', $id)->get();
        $bukti_kas[0]->offsetSet('jenis_pembayaran', $jenis_pembayaran[0]->jenis_pembayaran);

        return $jurnal;
    }
    public function select_last_jurnal(Request $request)
    {
        $data['jurnal'] = Jurnal_umum::orderBy("created_at", "desc")->first();
        $data['detail'] = Detail_jurnal_umum::where('id_jurnal_umum',$data['jurnal']->id)->orderBy("created_at", "desc")->first();

        return $data;
    }

    public function jurnal(Request $request)
    {
        if (isset($_GET['tanggal_dari'])) {
            $from = date("Y-m-d", strtotime($_GET['tanggal_dari']));
            $to = date("Y-m-d", strtotime($_GET['tanggal_sampai']));
            if ($to) {
                $jurnal = Jurnal_umum::leftjoin('detail_jurnal_umum', 'jurnal_umum.id', '=', 'detail_jurnal_umum.id_jurnal_umum')->leftjoin('perkiraan', 'detail_jurnal_umum.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_umum.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit as kode_akun', 'pk.nama_perkiraan', DB::raw('SUM(detail_jurnal_umum.sub_total) as total_kredit'))->groupBy('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit', 'pk.nama_perkiraan')->whereBetween('jurnal_umum.tanggal_jurnal', [$from, $to])->orderBy('jurnal_umum.nomor_bukti','DESC')->with(['detail_jurnal_umum' => function ($query) {
                    $query->select('id', 'id_jurnal_umum', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'detail_kode_akun_debit as nama_perkiraan', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor')->orderBy('created_at','ASC');
                }])->get();
                // dd($jurnal);
            } else {
                $jurnal = Jurnal_umum::leftjoin('detail_jurnal_umum', 'jurnal_umum.id', '=', 'detail_jurnal_umum.id_jurnal_umum')->leftjoin('perkiraan', 'detail_jurnal_umum.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_umum.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit as kode_akun', 'pk.nama_perkiraan',  DB::raw('SUM(detail_jurnal_umum.sub_total) as total_kredit'))->groupBy('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit', 'pk.nama_perkiraan')->with(['detail_jurnal_umum' => function ($query) {
                    $query->select('id', 'id_jurnal_umum', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'detail_kode_akun_debit as nama_perkiraan', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
                }])->get();
            }
        } else {
            $jurnal = Jurnal_umum::leftjoin('detail_jurnal_umum', 'jurnal_umum.id', '=', 'detail_jurnal_umum.id_jurnal_umum')->leftjoin('perkiraan', 'detail_jurnal_umum.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_umum.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit as kode_akun', 'pk.nama_perkiraan',  DB::raw('SUM(detail_jurnal_umum.sub_total) as total_kredit'))->groupBy('jurnal_umum.id', 'jurnal_umum.tanggal_jurnal', 'jurnal_umum.nomor_jurnal_induk', 'jurnal_umum.nomor_bukti', 'jurnal_umum.kode_akun_kredit', 'pk.nama_perkiraan')->with(['detail_jurnal_umum' => function ($query) {
                $query->select('id', 'id_jurnal_umum', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'detail_kode_akun_debit as nama_perkiraan', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
            }])->get();
        }

        return $jurnal;
    }

}
