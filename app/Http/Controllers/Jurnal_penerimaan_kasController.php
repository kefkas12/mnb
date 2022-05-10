<?php

namespace App\Http\Controllers;

use App\Detail_jurnal_penerimaan_kas;
use App\Jurnal_penerimaan_kas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Jurnal_penerimaan_kasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kode_jurnal_penerimaan_kas()
    {
        $last = Jurnal_penerimaan_kas::select("nomor_jurnal")->orderBy("created_at", "desc")->first();
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
    public function kode_jurnal_penerimaan_kas_induk()
    {
        $last = Jurnal_penerimaan_kas::select("nomor_jurnal_induk")->orderBy("created_at", "desc")->first();
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
    public function kode_jurnal_penerimaan_kas_last()
    {
        $last = Jurnal_penerimaan_kas::select("id", "nomor_jurnal")->orderBy("created_at", "desc")->first();
        return $last;
    }
    public function index()
    {

            if ($_GET['per_page'] == -1) {
                $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::count();
                $_GET['per_page'] = $jurnal_penerimaan_kas;
            }
            if (isset($_GET['search'])) {
                $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
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
                if (isset($_GET['sort'])) {
                    $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
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
                        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
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
                        $from = $_GET['tanggal_dari'];
                        $to = $_GET['tanggal_sampai'];
                        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::whereBetween('tanggal_jurnal', [$from, $to])
                            ->paginate($_GET['per_page']);
                    } else {
                        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::Where('nomor_jurnal_induk', 'like', '%' . $_GET['search'] . '%')
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
            } else if (isset($_GET['tanggal_dari'])) {
                $from = $_GET['tanggal_dari'];
                $to = $_GET['tanggal_sampai'];
                $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::whereBetween('tanggal_jurnal', [$from, $to])
                    ->paginate($_GET['per_page']);
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }


        return $jurnal_penerimaan_kas;
    }
    public function select(Request $request, $id)
    {
        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::where('id', $id)->get();

        return $jurnal_penerimaan_kas;
    }
    public function insert(Request $request)
    {
        $jurnal_penerimaan_kas = new Jurnal_penerimaan_kas;
        $jurnal_penerimaan_kas->id = Str::uuid()->toString();
        $jurnal_penerimaan_kas->nomor_jurnal_induk = $request->nomor_jurnal_induk;
        $jurnal_penerimaan_kas->nomor_jurnal = $request->nomor_jurnal;
        $jurnal_penerimaan_kas->tanggal_jurnal = $request->tanggal_jurnal;
        $jurnal_penerimaan_kas->nomor_jurnal_print = $request->nomor_jurnal_print;
        $jurnal_penerimaan_kas->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_penerimaan_kas->nomor_bukti = $request->nomor_bukti;
        $jurnal_penerimaan_kas->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_penerimaan_kas->status = $request->status;
        $jurnal_penerimaan_kas->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_penerimaan_kas->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_penerimaan_kas->deskripsi = $request->deskripsi;
        $jurnal_penerimaan_kas->kode_akun_kredit = $request->kode_akun_kredit;
        $jurnal_penerimaan_kas->id_supplier = $request->id_supplier;
        $jurnal_penerimaan_kas->id_customer = $request->id_customer;
        $jurnal_penerimaan_kas->save();

        return $jurnal_penerimaan_kas;
    }
    public function edit(Request $request, $id)
    {
        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::find($id);
        $jurnal_penerimaan_kas->nomor_jurnal_induk = $request->nomor_jurnal_induk;
        $jurnal_penerimaan_kas->nomor_jurnal = $request->nomor_jurnal;
        $jurnal_penerimaan_kas->tanggal_jurnal = $request->tanggal_jurnal;
        $jurnal_penerimaan_kas->nomor_jurnal_print = $request->nomor_jurnal_print;
        $jurnal_penerimaan_kas->tanggal_bukti_kas = $request->tanggal_bukti_kas;
        $jurnal_penerimaan_kas->nomor_bukti = $request->nomor_bukti;
        $jurnal_penerimaan_kas->jenis_pembayaran = $request->jenis_pembayaran;
        $jurnal_penerimaan_kas->status = $request->status;
        $jurnal_penerimaan_kas->nomor_rekening_pengirim = $request->nomor_rekening_pengirim;
        $jurnal_penerimaan_kas->nomor_rekening_penerima = $request->nomor_rekening_penerima;
        $jurnal_penerimaan_kas->deskripsi = $request->deskripsi;
        $jurnal_penerimaan_kas->kode_akun_kredit = $request->kode_akun_kredit;
        $jurnal_penerimaan_kas->id_supplier = $request->id_supplier;
        $jurnal_penerimaan_kas->id_customer = $request->id_customer;
        $jurnal_penerimaan_kas->save();

        return $jurnal_penerimaan_kas;
    }
    public function delete(Request $request, $id)
    {
        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::find($id);
        $jurnal_penerimaan_kas->delete();

        return $jurnal_penerimaan_kas;
    }

    public function all_jurnal(Request $request)
    {
        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::with('detail_jurnal_penerimaan_kas')->get();
        foreach ($jurnal_penerimaan_kas as $v) {
            $total_kredit = 0;
            foreach ($v->detail_jurnal_penerimaan_kas as $w) {
                $total_kredit += $w->sub_total;
            }
            $v->offsetSet('total_kredit', $total_kredit);
        }
        return $jurnal_penerimaan_kas;
    }

    public function bukti_kas(Request $request, $id_jurnal_penerimaan_kas)
    {
        $detail_jurnal_penerimaan_kas = new Detail_jurnal_penerimaan_kas;
        $bukti_kas = Detail_jurnal_penerimaan_kas::select(DB::raw('group_concat(keterangan SEPARATOR ", ") as keterangan'), DB::raw('SUM(sub_total) as sub_total'), DB::raw('group_concat(tanggal_jurnal SEPARATOR ", ") as tanggal_jurnal'))->where('id_jurnal_penerimaan_kas', $id_jurnal_penerimaan_kas)->get();
        $jurnal_penerimaan_kas = Jurnal_penerimaan_kas::where('id', $id_jurnal_penerimaan_kas)->first();
        $bukti_kas[0]->offsetSet('tanggal_jurnal', $jurnal_penerimaan_kas->tanggal_jurnal);
        $bukti_kas[0]->offsetSet('terbilang', $detail_jurnal_penerimaan_kas->terbilang($bukti_kas[0]->sub_total));

        $jenis_pembayaran = Jurnal_penerimaan_kas::select('jenis_pembayaran')->where('id', $id_jurnal_penerimaan_kas)->get();
        $bukti_kas[0]->offsetSet('jenis_pembayaran', $jenis_pembayaran[0]->jenis_pembayaran);
        return $bukti_kas;
    }

    public function select_jurnal(Request $request, $id)
    {
        $jurnal = Jurnal_penerimaan_kas::leftjoin('detail_jurnal_penerimaan_kas', 'jurnal_penerimaan_kas.id', '=', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas')->leftjoin('perkiraan', 'detail_jurnal_penerimaan_kas.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_penerimaan_kas.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit as kode_akun', DB::raw('SUM(detail_jurnal_penerimaan_kas.sub_total) as total_kredit'))->groupBy('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit', 'pk.nama_perkiraan')->where('jurnal_penerimaan_kas.id', $id)->with(['detail_jurnal_penerimaan_kas' => function ($query) {
            $query->select('id', 'id_jurnal_penerimaan_kas', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
        }])->get();

        $detail_jurnal_penerimaan_kas = new Detail_jurnal_penerimaan_kas;
        $bukti_kas = Detail_jurnal_penerimaan_kas::select(DB::raw('group_concat(keterangan SEPARATOR ", ") as keterangan'), DB::raw('SUM(sub_total) as sub_total'), DB::raw('group_concat(tanggal_jurnal SEPARATOR ", ") as tanggal_jurnal'))->where('id_jurnal_penerimaan_kas', $id)->get();
        $bukti_kas[0]->offsetSet('terbilang', $detail_jurnal_penerimaan_kas->terbilang($bukti_kas[0]->sub_total));

        $jenis_pembayaran = Jurnal_penerimaan_kas::select('jenis_pembayaran')->where('id', $id)->get();
        $bukti_kas[0]->offsetSet('jenis_pembayaran', $jenis_pembayaran[0]->jenis_pembayaran);

        return $jurnal;
    }

    public function jurnal(Request $request)
    {
        if (isset($_GET['tanggal_dari'])) {
            $from = $_GET['tanggal_dari'];
            $to = $_GET['tanggal_sampai'];
            if ($to) {
                $jurnal = Jurnal_penerimaan_kas::leftjoin('detail_jurnal_penerimaan_kas', 'jurnal_penerimaan_kas.id', '=', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas')->leftjoin('perkiraan', 'detail_jurnal_penerimaan_kas.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_penerimaan_kas.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit as kode_akun', DB::raw('SUM(detail_jurnal_penerimaan_kas.sub_total) as total_kredit'))->groupBy('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit', 'pk.nama_perkiraan')->whereBetween('jurnal_penerimaan_kas.tanggal_jurnal', [$from, $to])->with(['detail_jurnal_penerimaan_kas' => function ($query) {
                    $query->select('id', 'id_jurnal_penerimaan_kas', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
                }])->get();
            } else {
                $jurnal = Jurnal_penerimaan_kas::leftjoin('detail_jurnal_penerimaan_kas', 'jurnal_penerimaan_kas.id', '=', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas')->leftjoin('perkiraan', 'detail_jurnal_penerimaan_kas.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_penerimaan_kas.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit as kode_akun', DB::raw('SUM(detail_jurnal_penerimaan_kas.sub_total) as total_kredit'))->groupBy('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit', 'pk.nama_perkiraan')->with(['detail_jurnal_penerimaan_kas' => function ($query) {
                    $query->select('id', 'id_jurnal_penerimaan_kas', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
                }])->get();
            }
        } else {
            $jurnal = Jurnal_penerimaan_kas::leftjoin('detail_jurnal_penerimaan_kas', 'jurnal_penerimaan_kas.id', '=', 'detail_jurnal_penerimaan_kas.id_jurnal_penerimaan_kas')->leftjoin('perkiraan', 'detail_jurnal_penerimaan_kas.kode_akun_debit', '=', 'perkiraan.kode_akun')->leftjoin('perkiraan as pk', 'jurnal_penerimaan_kas.kode_akun_kredit', '=', 'pk.kode_akun')->select('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit as kode_akun', DB::raw('SUM(detail_jurnal_penerimaan_kas.sub_total) as total_kredit'))->groupBy('jurnal_penerimaan_kas.id', 'jurnal_penerimaan_kas.tanggal_jurnal', 'jurnal_penerimaan_kas.nomor_jurnal_induk', 'jurnal_penerimaan_kas.nomor_bukti', 'jurnal_penerimaan_kas.kode_akun_kredit', 'pk.nama_perkiraan')->with(['detail_jurnal_penerimaan_kas' => function ($query) {
                $query->select('id', 'id_jurnal_penerimaan_kas', 'nomor_jurnal', 'kode_detail', 'banyaknya', 'nama_satuan', 'harga', 'sub_total', 'keterangan', 'kode_akun_debit as kode_akun', 'nama_perusahaan_supplier', 'nama_perusahaan_customer', 'nama_karyawan', 'alat_berat', 'peralatan', 'truck', 'mobil', 'motor');
            }])->get();
        }

        return $jurnal;
    }
}
