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
    
    // protected $casts = [
    //     'ppn' => 'double',
    //     'sub_total' => 'double',
    // ];
}
