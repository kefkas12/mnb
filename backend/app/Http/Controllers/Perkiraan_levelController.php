<?php

namespace App\Http\Controllers;

use App\Perkiraan_level;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Perkiraan_levelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if(isset($_GET['per_page'])){
            if($_GET['per_page'] == -1){
                $perkiraan_level = Perkiraan_level::count();
                $_GET['per_page'] = $perkiraan_level;
            }
            $perkiraan_level = Perkiraan_level::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if(isset($_GET['search'])){
                $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if(isset($_GET['sort'])){
                    $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if(isset($_GET['order'])){
                        $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            }else{
                if(isset($_GET['sort']) && isset($_GET['order'])){
                    $perkiraan_level = Perkiraan_level::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        }else{
            $perkiraan_level = Perkiraan_level::orderBy('created_at', 'desc')->paginate();
            if(isset($_GET['search'])){
                $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if(isset($_GET['sort'])){
                    $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy($_GET['sort'], 'desc')
                    ->paginate();
                    if(isset($_GET['order'])){
                        $perkiraan_level = Perkiraan_level::Where('kode_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('keterangan_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status_perkiraan_level', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            }else{
                if(isset($_GET['sort']) && isset($_GET['order'])){
                    $perkiraan_level = Perkiraan_level::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }
        

        return $perkiraan_level;
    }
    public function select(Request $request, $id)
    {
        $perkiraan_level = Perkiraan_level::where('id',$id)->get();

        return $perkiraan_level;
    }
    public function insert(Request $request)
    {
        $perkiraan_level = new Perkiraan_level;
        $perkiraan_level->id = Str::uuid()->toString();
        $perkiraan_level->kode_perkiraan_level = $request->kode_perkiraan_level;
        $perkiraan_level->nama_perkiraan_level = $request->nama_perkiraan_level;
        $perkiraan_level->keterangan_perkiraan_level = $request->keterangan_perkiraan_level;
        $perkiraan_level->status_perkiraan_level = $request->status_perkiraan_level;
        $perkiraan_level->save();

        return $perkiraan_level;
    }
    public function edit(Request $request, $id)
    {
        $perkiraan_level = Perkiraan_level::find($id);
        $perkiraan_level->kode_perkiraan_level = $request->kode_perkiraan_level;
        $perkiraan_level->nama_perkiraan_level = $request->nama_perkiraan_level;
        $perkiraan_level->keterangan_perkiraan_level = $request->keterangan_perkiraan_level;
        $perkiraan_level->status_perkiraan_level = $request->status_perkiraan_level;
        $perkiraan_level->save();

        return $perkiraan_level;
    }
    public function delete(Request $request, $id)
    {
        $perkiraan_level = Perkiraan_level::find($id);
        $perkiraan_level->delete();

        return $perkiraan_level;
    }
}
