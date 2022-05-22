<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Detail_jurnal_umum;

class Jurnal_umum extends Model
{
    protected $table = 'jurnal_umum';
    public $incrementing = false;

    public function jurnal_umum(){
        return Jurnal_umum::orderBy("created_at", "desc")->first();
    }

    public function detail_jurnal_umum()
    {
        return $this->hasMany('App\Detail_jurnal_umum','id_jurnal_umum');
    }
    public function last_kode_induk(){
        if (!$this->jurnal_umum()) {
            return 'JGN-' . date('Y') . '-000001-MNB';
        } else {
            $no = intval(substr($this->jurnal_umum()->nomor_jurnal_induk, 9, 14)) + 1;
            if ($no < 10) {
                return 'JGN-' . date('Y') . '-00000' . $no . '-MNB';
            } elseif ($no < 100) {
                return 'JGN-' . date('Y') . '-0000' . $no . '-MNB';
            } elseif ($no < 1000) {
                return 'JGN-' . date('Y') . '-000' . $no . '-MNB';
            } elseif ($no < 10000) {
                return 'JGN-' . date('Y') . '-00' . $no . '-MNB';
            } elseif ($no < 100000) {
                return 'JGN-' . date('Y') . '-0' . $no . '-MNB';
            } else {
                return 'JGN-' . date('Y') . '-' . $no . '-MNB';
            }
        }
    }
    public function last_kode(){
        if (!$this->jurnal_umum()) {
            return 'JGN-' . date('Y') . '-000001-EKTRN';
        } else {
            $no = intval(substr($this->jurnal_umum()->nomor_jurnal, 9, 14)) + 1;
            if ($no < 10) {
                return 'JGN-' . date('Y') . '-00000' . $no . '-EKTRN';
            } elseif ($no < 100) {
                return 'JGN-' . date('Y') . '-0000' . $no . '-EKTRN';
            } elseif ($no < 1000) {
                return 'JGN-' . date('Y') . '-000' . $no . '-EKTRN';
            } elseif ($no < 10000) {
                return 'JGN-' . date('Y') . '-00' . $no . '-EKTRN';
            } elseif ($no < 100000) {
                return 'JGN-' . date('Y') . '-0' . $no . '-EKTRN';
            } else {
                return 'JGN-' . date('Y') . '-' . $no . '-EKTRN';
            }
        }
    }
}
