<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Detail_jurnal_penerimaan_kas;

class Jurnal_penerimaan_kas extends Model
{
    protected $table = 'jurnal_penerimaan_kas';
    public $incrementing = false;

    public function detail_jurnal_penerimaan_kas()
    {
        return $this->hasMany('App\Detail_jurnal_penerimaan_kas','id_jurnal_penerimaan_kas');
    }
}
