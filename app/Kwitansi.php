<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    protected $table = 'kwitansi';
    public $incrementing = false;
    public function detail_kwitansi()
    {
        return $this->hasMany('App\Detail_kwitansi','id_kwitansi');
    }
    public function last_kode(){
        $last = Kwitansi::select("kode_kwitansi")->orderBy("created_at", "desc")->first();
        dd($last->nomor_jurnal);
        if (!$last) {
            return 'KWP-' . date('Y') . '-000001-' . date('m') . '-MNB';
        } else {
            $no = intval(substr($last->nomor_jurnal, 9, 15)) + 1;
            
            if ($no < 10) {
                return 'KWP-' . date('Y') . '-00000' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 100) {
                return 'KWP-' . date('Y') . '-0000' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 1000) {
                return 'KWP-' . date('Y') . '-000' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 10000) {
                return 'KWP-' . date('Y') . '-00' . $no . '-' . date('m') . '-MNB';
            } elseif ($no < 100000) {
                return 'KWP-' . date('Y') . '-0' . $no . '-' . date('m') . '-MNB';
            } else {
                return 'KWP-' . date('Y') . '-' . $no . '-' . date('m') . '-MNB';
            }
        }
    }
    
    // protected $casts = [
    //     'ppn' => 'double',
    //     'sub_total' => 'double',
    // ];
}
