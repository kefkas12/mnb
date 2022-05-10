<?php

namespace App\Http\Controllers;

use App\Truck_petani;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Truck_petaniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
            if($_GET['per_page'] == -1){
                $truck_petani = Truck_petani::count();
                $_GET['per_page'] = $truck_petani;
            }
            if (isset($_GET['search'])) {
                $truck_petani = Truck_petani::Where('kode_truck_petani_mnb', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $truck_petani = Truck_petani::Where('kode_truck_petani_mnb', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $truck_petani = Truck_petani::Where('kode_truck_petani_mnb', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_polisi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $truck_petani = Truck_petani::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }


        return $truck_petani;
    }
    public function select(Request $request, $id)
    {
        $truck_petani = Truck_petani::where('id',$id)->get();

        return $truck_petani;
    }
    public function insert(Request $request)
    {
        $truck_petani = new Truck_petani;
        $truck_petani->id = Str::uuid()->toString();
        $truck_petani->kode_truck_petani_mnb = $request->kode_truck_petani_mnb;
        $truck_petani->nomor_polisi = $request->nomor_polisi;
        $truck_petani->status = $request->status;
        $truck_petani->save();

        return $truck_petani;
    }
    public function edit(Request $request, $id)
    {
        $truck_petani = Truck_petani::find($id);
        $truck_petani->kode_truck_petani_mnb = $request->kode_truck_petani_mnb;
        $truck_petani->nomor_polisi = $request->nomor_polisi;
        $truck_petani->status = $request->status;
        $truck_petani->save();

        return $truck_petani;
    }
    public function delete(Request $request, $id)
    {
        $truck_petani = Truck_petani::find($id);
        $truck_petani->delete();

        return $truck_petani;
    }
}
