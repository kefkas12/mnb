<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perkiraan extends Model
{
    protected $table = 'perkiraan';
    public $incrementing = false;

    public function saldo_awal($kode_akun){
        $saldo_awal = Perkiraan::select('kode_akun', 'nama_perkiraan', 'normal_balance', DB::raw('cast(saldo_awal_debit as decimal(65,2)) as saldo_awal_debit'), DB::raw('cast(saldo_awal_kredit as decimal(65,2)) as saldo_awal_kredit'))->Where('kode_akun', $kode_akun)->orderBy('kode_akun', 'ASC')->first();
        
        return $saldo_awal;
    }
}
