<?php

namespace App\Http\Controllers;

use App\Perkiraan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PerkiraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $perkiraan = Perkiraan::count();
                $_GET['per_page'] = $perkiraan;
            }
            $perkiraan = Perkiraan::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $perkiraan = Perkiraan::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $perkiraan = Perkiraan::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $perkiraan = Perkiraan::Where('kode_akun', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_akun_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perkiraan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('neraca', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('posisi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('normal_balance', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_debit_r', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('saldo_awal_kredit_r', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $perkiraan = Perkiraan::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }



        return $perkiraan;
    }
    
    public function kode_akun_induk(Request $request)
    {
        $kode_akun_induk = Perkiraan::select('kode_akun')->where('status', 'Active')->get();

        return $kode_akun_induk;
    }
    
    public function coa(Request $request)
    {
        $perkiraan = Perkiraan::where('status', 'Active')->get();

        $debit = Perkiraan::where('status', 'Active')->sum('saldo_awal_debit');
        $kredit = Perkiraan::where('status', 'Active')->sum('saldo_awal_kredit');

        $perkiraan->offsetSet('total_debit', $debit);
        $perkiraan->offsetSet('total_kredit', $kredit);

        return $perkiraan;
    }
    
    public function select(Request $request, $id)
    {
        $perkiraan = Perkiraan::where('id', $id)->get();

        return $perkiraan;
    }
    public function insert(Request $request)
    {
        $perkiraan = new Perkiraan;
        $perkiraan->id = Str::uuid()->toString();
        $perkiraan->kode_akun = $request->kode_akun;
        $perkiraan->tipe_akun = $request->tipe_akun;
        $perkiraan->perkiraan_level = $request->perkiraan_level;
        $perkiraan->kode_akun_induk = $request->kode_akun_induk;
        $perkiraan->nama_perkiraan_akun_induk = $request->nama_perkiraan_akun_induk;
        $perkiraan->nama_perkiraan = $request->nama_perkiraan;
        $perkiraan->neraca = $request->neraca;
        $perkiraan->saldo_awal_debit = $request->saldo_awal_debit;
        $perkiraan->saldo_awal_kredit = $request->saldo_awal_kredit;
        $perkiraan->posisi = $request->posisi;
        $perkiraan->normal_balance = $request->normal_balance;
        $perkiraan->saldo_awal_debit_r = $request->saldo_awal_debit_r;
        $perkiraan->saldo_awal_kredit_r = $request->saldo_awal_kredit_r;
        $perkiraan->status = $request->status;
        $perkiraan->save();

        return $perkiraan;
    }
    public function edit(Request $request, $id)
    {
        $perkiraan = Perkiraan::find($id);
        $perkiraan->kode_akun = $request->kode_akun;
        $perkiraan->tipe_akun = $request->tipe_akun;
        $perkiraan->perkiraan_level = $request->perkiraan_level;
        $perkiraan->kode_akun_induk = $request->kode_akun_induk;
        $perkiraan->nama_perkiraan_akun_induk = $request->nama_perkiraan_akun_induk;
        $perkiraan->nama_perkiraan = $request->nama_perkiraan;
        $perkiraan->neraca = $request->neraca;
        $perkiraan->saldo_awal_debit = $request->saldo_awal_debit;
        $perkiraan->saldo_awal_kredit = $request->saldo_awal_kredit;
        $perkiraan->posisi = $request->posisi;
        $perkiraan->normal_balance = $request->normal_balance;
        $perkiraan->saldo_awal_debit_r = $request->saldo_awal_debit_r;
        $perkiraan->saldo_awal_kredit_r = $request->saldo_awal_kredit_r;
        $perkiraan->status = $request->status;
        $perkiraan->save();

        return $perkiraan;
    }
    public function delete(Request $request, $id)
    {
        $perkiraan = Perkiraan::find($id);
        $perkiraan->delete();

        return $perkiraan;
    }
}
