<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        ///api/kwitansi?per_page=5&page=1&search=&tanggal_dari=2022-02-01&tanggal_sampai=2022-02-12

        if ($_GET['per_page'] == -1) {
            $bank = Bank::count();
            $_GET['per_page'] = $bank;
        }
        if (isset($_GET['search'])) {
            $bank = Bank::Where('kode_nama_bank', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nama_bank', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('keterangan_nama_bank', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nama_pemilik_rekening', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($_GET['per_page']);
            if (isset($_GET['sort'])) {
                $bank = Bank::Where('kode_nama_bank', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_bank', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_nama_bank', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_pemilik_rekening', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy($_GET['sort'], 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['order'])) {
                    $bank = Bank::Where('kode_nama_bank', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_bank', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_nama_bank', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_pemilik_rekening', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_rekening', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            if (isset($_GET['sort']) && isset($_GET['order'])) {
                $bank = Bank::orderBy($_GET['sort'], $_GET['order'])
                    ->paginate($_GET['per_page']);
            }
        }


        return $bank;
    }
    public function select(Request $request, $id)
    {
        $bank = Bank::where('id', $id)->get();

        return $bank;
    }
    public function insert(Request $request)
    {
        $bank = new Bank;
        $bank->id = Str::uuid()->toString();
        $bank->kode_nama_bank = $request->kode_nama_bank;
        $bank->nama_bank = $request->nama_bank;
        $bank->keterangan_nama_bank = $request->keterangan_nama_bank;
        $bank->nama_pemilik_rekening = $request->nama_pemilik_rekening;
        $bank->nomor_rekening = $request->nomor_rekening;
        $bank->status = $request->status;
        $bank->save();

        return $bank;
    }
    public function edit(Request $request, $id)
    {
        $bank = Bank::find($id);
        $bank->kode_nama_bank = $request->kode_nama_bank;
        $bank->nama_bank = $request->nama_bank;
        $bank->keterangan_nama_bank = $request->keterangan_nama_bank;
        $bank->nama_pemilik_rekening = $request->nama_pemilik_rekening;
        $bank->nomor_rekening = $request->nomor_rekening;
        $bank->status = $request->status;
        $bank->save();

        return $bank;
    }
    public function delete(Request $request, $id)
    {
        $bank = Bank::find($id);
        $bank->delete();

        return $bank;
    }
}
