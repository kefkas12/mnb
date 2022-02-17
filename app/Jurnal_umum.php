<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Detail_jurnal_umum;

class Jurnal_umum extends Model
{
    protected $table = 'jurnal_umum';
    public $incrementing = false;

    public function detail_jurnal_umum()
    {
        return $this->hasMany('App\Detail_jurnal_umum','id_jurnal_umum');
    }
}
