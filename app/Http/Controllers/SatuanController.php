<?php

namespace App\Http\Controllers;

use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SatuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
            if($_GET['per_page'] == -1){
                $satuan = Satuan::count();
                $_GET['per_page'] = $satuan;
            }
            if(isset($_GET['search'])){
                $satuan = Satuan::Where('kode_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_satuan', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if(isset($_GET['sort'])){
                    $satuan = Satuan::Where('kode_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status_satuan', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if(isset($_GET['order'])){
                        $satuan = Satuan::Where('kode_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status_satuan', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            }else{
                if(isset($_GET['sort']) && isset($_GET['order'])){
                    $satuan = Satuan::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }

        

        return $satuan;
    }
    public function select(Request $request, $id)
    {
        $satuan = Satuan::where('id',$id)->get();

        return $satuan;
    }
    public function insert(Request $request)
    {
        $satuan = new Satuan;
        $satuan->id = Str::uuid()->toString();
        $satuan->kode_satuan = $request->kode_satuan;
        $satuan->nama_satuan = $request->nama_satuan;
        $satuan->keterangan_satuan = $request->keterangan_satuan;
        $satuan->status_satuan = $request->status_satuan;
        $satuan->save();

        return $satuan;
    }
    public function edit(Request $request, $id)
    {
        $satuan = Satuan::find($id);
        $satuan->kode_satuan = $request->kode_satuan;
        $satuan->nama_satuan = $request->nama_satuan;
        $satuan->keterangan_satuan = $request->keterangan_satuan;
        $satuan->status_satuan = $request->status_satuan;
        $satuan->save();

        return $satuan;
    }
    public function delete(Request $request, $id)
    {
        $satuan = Satuan::find($id);
        $satuan->delete();

        return $satuan;
    }
}
