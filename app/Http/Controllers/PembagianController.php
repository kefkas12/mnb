<?php

namespace App\Http\Controllers;

use App\Pembagian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PembagianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['pembagian'] = Pembagian::get();
        return $data;
    }
    
    public function insert(Request $request)
    {
        
        $pembagian = new Pembagian;
        $pembagian->nama = $request->nama;
        $pembagian->save();
        $jumlah_pembagian = Pembagian::count();
        $data_pembagian = Pembagian::get();
        if($jumlah_pembagian > 0){
            $persentase = 100/$jumlah_pembagian;
            for($i=0;$i<$jumlah_pembagian;$i++){
                $id = $data_pembagian[$i]->id;
                $new_pembagian = Pembagian::find($id);
                $new_pembagian->persentase = $persentase;
                $new_pembagian->save();
            }    
        }
        
        

        return $pembagian;
    }
    public function edit(Request $request, $id)
    {
        $pembagian = Pembagian::find($id);
        $pembagian->nama = $request->nama;
        $jumlah = Pembagian::select(DB::raw('SUM(persentase) as persentase'))->where('id','!=',$id)->first();
        $max_edit = 100 - $jumlah->persentase;
        if($pembagian->persentase <= $max_edit){
            $pembagian->persentase = $request->persentase;
            $pembagian->save();
            return $pembagian;
        }else{
            return 'Persentase Harus Lebih Kecil';
        }
        

        
    }
    public function delete(Request $request, $id)
    {
        $pembagian = Pembagian::find($id);
        $pembagian->delete();
        $jumlah_pembagian = Pembagian::count();
        $data_pembagian = Pembagian::get();
        if($jumlah_pembagian > 0){
            $persentase = 100/$jumlah_pembagian;
            for($i=0;$i<$jumlah_pembagian;$i++){
                $id = $data_pembagian[$i]->id;
                $new_pembagian = Pembagian::find($id);
                $new_pembagian->persentase = $persentase;
                $new_pembagian->save();
            }    
        }
        return $pembagian;
    }
}
