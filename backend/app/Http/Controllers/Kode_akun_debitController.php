<?php

namespace App\Http\Controllers;

use App\Kode_akun_debit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Kode_akun_debitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $kode_akun_debit = Kode_akun_debit::count();
                $_GET['per_page'] = $kode_akun_debit;
            }
            $kode_akun_debit = Kode_akun_debit::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kode_akun_debit = Kode_akun_debit::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $kode_akun_debit = Kode_akun_debit::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $kode_akun_debit = Kode_akun_debit::Where('kode_akun_debit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kode_akun_debit = Kode_akun_debit::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }

        return $kode_akun_debit;
    }
    public function select(Request $request, $id)
    {
        $kode_akun_debit = Kode_akun_debit::where('id',$id)->get();

        return $kode_akun_debit;
    }
    public function insert(Request $request)
    {
        $kode_akun_debit = new Kode_akun_debit;
        $kode_akun_debit->id = Str::uuid()->toString();
        $kode_akun_debit->kode_akun_debit = $request->kode_akun_debit;
        $kode_akun_debit->keterangan = $request->keterangan;
        $kode_akun_debit->status = $request->status;
        $kode_akun_debit->save();

        return $kode_akun_debit;
    }
    public function edit(Request $request, $id)
    {
        $kode_akun_debit = Kode_akun_debit::find($id);
        $kode_akun_debit->kode_akun_debit = $request->kode_akun_debit;
        $kode_akun_debit->keterangan = $request->keterangan;
        $kode_akun_debit->status = $request->status;
        $kode_akun_debit->save();

        return $kode_akun_debit;
    }
    public function delete(Request $request, $id)
    {
        $kode_akun_debit = Kode_akun_debit::find($id);
        $kode_akun_debit->delete();

        return $kode_akun_debit;
    }
}
