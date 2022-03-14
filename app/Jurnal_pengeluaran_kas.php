<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Detail_jurnal_pengeluaran_kas;

class Jurnal_pengeluaran_kas extends Model
{
    protected $table = 'jurnal_pengeluaran_kas';
    public $incrementing = false;

    public function detail_jurnal_pengeluaran_kas()
    {
        return $this->hasMany('App\Detail_jurnal_pengeluaran_kas','id_jurnal_pengeluaran_kas');
    }
}
