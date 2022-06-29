<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan_kas extends Model
{
    protected $table = 'laporan_kas';

    public function generate_laporan_kas(){
        Laporan_kas::truncate();
        $detail_jurnal_umum = Detail_jurnal_umum::orderBy('created_at','ASC')->get();
        foreach($detail_jurnal_umum as $v){
            if($v->kode_akun_debit == '111.001'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_kas = new Laporan_kas;
                $laporan_kas->id_detail_jurnal_umum = $v->id;
                $laporan_kas->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                
                $laporan_kas->nomor_bukti = $jurnal_umum->nomor_bukti;
                $laporan_kas->keterangan = $v->keterangan;
                $laporan_kas->debet = $v->sub_total;
                $laporan_kas->kredit = 0;
                $laporan_kas->save();
            }elseif($v->kode_akun_kredit == '111.001'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_kas = new Laporan_kas;
                $laporan_kas->id_detail_jurnal_umum = $v->id;
                $laporan_kas->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                
                $laporan_kas->nomor_bukti = $jurnal_umum->nomor_bukti;
                $laporan_kas->keterangan = $v->keterangan;
                $laporan_kas->debet = 0;
                $laporan_kas->kredit = $v->sub_total;
                $laporan_kas->save();
            }
        }
        
    }
}
