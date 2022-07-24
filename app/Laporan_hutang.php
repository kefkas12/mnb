<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan_hutang extends Model
{
    protected $table = 'laporan_hutang';

    public function generate_laporan_hutang(){
        Laporan_hutang::truncate();
        $detail_jurnal_umum = Detail_jurnal_umum::orderBy('created_at','ASC')->get();
        foreach($detail_jurnal_umum as $v){
            if($v->kode_akun_debit == '220.001'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_hutang = new Laporan_hutang;
                $laporan_hutang->id_detail_jurnal_umum = $v->id;
                $laporan_hutang->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                if($jurnal_umum->id_supplier)
                    $laporan_hutang->supplier = $jurnal_umum->id_supplier;
                else
                    $laporan_hutang->supplier = $v->nama_perusahaan_supplier;
                $laporan_hutang->keterangan = $v->keterangan;
                $laporan_hutang->debit = $v->sub_total;
                $laporan_hutang->kredit = 0;
                $laporan_hutang->save();
            }elseif($v->kode_akun_kredit == '220.001'){
                $jurnal_umum = Jurnal_umum::where('id',$v->id_jurnal_umum)->first();
                $laporan_hutang = new Laporan_hutang;
                $laporan_hutang->id_detail_jurnal_umum = $v->id;
                $laporan_hutang->tanggal_jurnal = $jurnal_umum->tanggal_jurnal;
                $laporan_hutang->supplier = $jurnal_umum->id_supplier;
                $laporan_hutang->keterangan = $v->keterangan;
                $laporan_hutang->debit = 0;
                $laporan_hutang->kredit = $v->sub_total;
                $laporan_hutang->save();
            }
        }
    }
}
