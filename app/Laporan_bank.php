<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan_bank extends Model
{
    protected $table = 'laporan_bank';

    public function generate_laporan_bank(){
        Laporan_bank::truncate();
        $detail_jurnal_umum = Detail_jurnal_umum::orderBy('created_at','ASC')->get();
        foreach($detail_jurnal_umum as $v){
            if($v->kode_akun_debit == '112.101'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_bank = new Laporan_bank;
                $laporan_bank->id_detail_jurnal_umum = $v->id;
                $laporan_bank->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                
                $laporan_bank->nomor_bukti = $jurnal_umum->nomor_bukti;
                $laporan_bank->keterangan = $v->keterangan;
                $laporan_bank->debit = $v->sub_total;
                $laporan_bank->kredit = 0;
                $laporan_bank->save();
            }elseif($v->kode_akun_kredit == '112.101'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_bank = new Laporan_bank;
                $laporan_bank->id_detail_jurnal_umum = $v->id;
                $laporan_bank->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                
                $laporan_bank->nomor_bukti = $jurnal_umum->nomor_bukti;
                $laporan_bank->keterangan = $v->keterangan;
                $laporan_bank->debit = 0;
                $laporan_bank->kredit = $v->sub_total;
                $laporan_bank->save();
            }
        }
    }
}
