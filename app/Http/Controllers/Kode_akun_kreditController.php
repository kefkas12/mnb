<?php

namespace App\Http\Controllers;

use App\Kode_akun_kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Kode_akun_kreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $kode_akun_kredit = Kode_akun_kredit::count();
                $_GET['per_page'] = $kode_akun_kredit;
            }
            $kode_akun_kredit = Kode_akun_kredit::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kode_akun_kredit = Kode_akun_kredit::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $kode_akun_kredit = Kode_akun_kredit::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $kode_akun_kredit = Kode_akun_kredit::Where('kode_akun_kredit', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('klasifikasi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $kode_akun_kredit = Kode_akun_kredit::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }

        return $kode_akun_kredit;
    }
    public function select(Request $request, $id)
    {
        $kode_akun_kredit = Kode_akun_kredit::where('id',$id)->get();

        return $kode_akun_kredit;
    }
    public function insert(Request $request)
    {
        $kode_akun_kredit = new Kode_akun_kredit;
        $kode_akun_kredit->id = Str::uuid()->toString();
        $kode_akun_kredit->kode_akun_kredit = $request->kode_akun_kredit;
        $kode_akun_kredit->klasifikasi = $request->klasifikasi;
        $kode_akun_kredit->keterangan = $request->keterangan;
        $kode_akun_kredit->status = $request->status;
        $kode_akun_kredit->save();

        return $kode_akun_kredit;
    }
    public function edit(Request $request, $id)
    {
        $kode_akun_kredit = Kode_akun_kredit::find($id);
        $kode_akun_kredit->kode_akun_kredit = $request->kode_akun_kredit;
        $kode_akun_kredit->klasifikasi = $request->klasifikasi;
        $kode_akun_kredit->keterangan = $request->keterangan;
        $kode_akun_kredit->status = $request->status;
        $kode_akun_kredit->save();

        return $kode_akun_kredit;
    }
    public function delete(Request $request, $id)
    {
        $kode_akun_kredit = Kode_akun_kredit::find($id);
        $kode_akun_kredit->delete();

        return $kode_akun_kredit;
    }
}
