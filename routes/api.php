<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', 'UserController@index');
Route::post('users', 'UserController@store');
Route::post('login', 'AuthController@login')->middleware('cors');


Route::group([

    'middleware' => ['api','jwt.verify','cors'],

], function ($router) {

    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'middleware' => ['cors'],
], function ($router) {
    Route::get('/master/customer', 'CustomerController@index');
    Route::get('/master/customer/{id}', 'CustomerController@select');
    Route::post('/master/customer', 'CustomerController@insert');
    Route::put('/master/customer/{id}', 'CustomerController@edit');
    Route::delete('/master/customer/{id}', 'CustomerController@delete');
    Route::get('/master/kode_customer', 'CustomerController@kode_customer');

    Route::get('/master/supplier', 'SupplierController@index');
    Route::get('/master/supplier/{id}', 'SupplierController@select');
    Route::post('/master/supplier', 'SupplierController@insert');
    Route::put('/master/supplier/{id}', 'SupplierController@edit');
    Route::delete('/master/supplier/{id}', 'SupplierController@delete');
    Route::get('/master/kode_supplier', 'SupplierController@kode_supplier');

    Route::get('/master/truck_petani', 'Truck_petaniController@index');
    Route::get('/master/truck_petani/{id}', 'Truck_petaniController@select');
    Route::post('/master/truck_petani', 'Truck_petaniController@insert');
    Route::put('/master/truck_petani/{id}', 'Truck_petaniController@edit');
    Route::delete('/master/truck_petani/{id}', 'Truck_petaniController@delete');

    Route::get('/master/satuan', 'SatuanController@index');
    Route::get('/master/satuan/{id}', 'SatuanController@select');
    Route::post('/master/satuan', 'SatuanController@insert');
    Route::put('/master/satuan/{id}', 'SatuanController@edit');
    Route::delete('/master/satuan/{id}', 'SatuanController@delete');

    Route::get('/master/perkiraan', 'PerkiraanController@index');
    Route::get('/master/perkiraan/{id}', 'PerkiraanController@select');
    Route::post('/master/perkiraan', 'PerkiraanController@insert');
    Route::put('/master/perkiraan/{id}', 'PerkiraanController@edit');
    Route::delete('/master/perkiraan/{id}', 'PerkiraanController@delete');

    Route::get('/perkiraan/kode_akun_induk', 'PerkiraanController@kode_akun_induk');
    Route::get('/coa', 'PerkiraanController@coa');

    Route::get('/master/perkiraan_level', 'Perkiraan_levelController@index');
    Route::get('/master/perkiraan_level/{id}', 'Perkiraan_levelController@select');
    Route::post('/master/perkiraan_level', 'Perkiraan_levelController@insert');
    Route::put('/master/perkiraan_level/{id}', 'Perkiraan_levelController@edit');
    Route::delete('/master/perkiraan_level/{id}', 'Perkiraan_levelController@delete');

    Route::get('/master/bank', 'BankController@index');
    Route::get('/master/bank/{id}', 'BankController@select');
    Route::post('/master/bank', 'BankController@insert');
    Route::put('/master/bank/{id}', 'BankController@edit');
    Route::delete('/master/bank/{id}', 'BankController@delete');

    Route::get('/master/kode_akun_debit', 'Kode_akun_debitController@index');
    Route::get('/master/kode_akun_debit/{id}', 'Kode_akun_debitController@select');
    Route::post('/master/kode_akun_debit', 'Kode_akun_debitController@insert');
    Route::put('/master/kode_akun_debit/{id}', 'Kode_akun_debitController@edit');
    Route::delete('/master/kode_akun_debit/{id}', 'Kode_akun_debitController@delete');

    Route::get('/master/kode_akun_kredit', 'Kode_akun_kreditController@index');
    Route::get('/master/kode_akun_kredit/{id}', 'Kode_akun_kreditController@select');
    Route::post('/master/kode_akun_kredit', 'Kode_akun_kreditController@insert');
    Route::put('/master/kode_akun_kredit/{id}', 'Kode_akun_kreditController@edit');
    Route::delete('/master/kode_akun_kredit/{id}', 'Kode_akun_kreditController@delete');

    Route::get('/kwitansi/keuntungan', 'KwitansiController@keuntungan_get');
    Route::post('/kwitansi/keuntungan', 'KwitansiController@keuntungan_insert');

    Route::get('/kwitansi', 'KwitansiController@index');
    Route::get('/kwitansi/{id}', 'KwitansiController@select');
    Route::post('/kwitansi', 'KwitansiController@insert');
    Route::put('/kwitansi/{id}', 'KwitansiController@edit');
    Route::delete('/kwitansi/{id}', 'KwitansiController@delete');
    Route::get('/kode_kwitansi', 'KwitansiController@kode_kwitansi');
    Route::get('/kode_kwitansi_last', 'KwitansiController@kode_kwitansi_last');

    Route::get('/detail_kwitansi', 'Detail_kwitansiController@index');
    // Route::get('/detail_kwitansi/nomor_jurnal/{id_kwitansi}', 'Detail_kwitansiController@select_by_nomor_jurnal');
    Route::get('/detail_kwitansi/{id}', 'Detail_kwitansiController@select');
    Route::post('/detail_kwitansi', 'Detail_kwitansiController@insert');
    Route::put('/detail_kwitansi/{id}', 'Detail_kwitansiController@edit');
    Route::delete('/detail_kwitansi/{id}', 'Detail_kwitansiController@delete');

    Route::get('/kwitansi/export/ongkos_bongkar/{id}', 'KwitansiController@ongkos_bongkar');
    Route::get('/kwitansi/export/kwitansi_bongkar/{id}', 'KwitansiController@kwitansi_bongkar');
    Route::get('/kwitansi/export/ba_plus_ppn/{id}', 'KwitansiController@ba_plus_ppn');
    Route::get('/kwitansi/export/ba_min_ppn/{id}', 'KwitansiController@ba_min_ppn');
    Route::get('/kwitansi/export/inv_plus_ppn/{id}', 'KwitansiController@inv_plus_ppn');
    Route::get('/kwitansi/export/inv_min_ppn/{id}', 'KwitansiController@inv_min_ppn');
    Route::get('/kwitansi/export/kwt_plus_ppn/{id}', 'KwitansiController@kwt_plus_ppn');
    Route::get('/kwitansi/export/kwt_min_ppn/{id}', 'KwitansiController@kwt_min_ppn');

    Route::get('export/kwitansi/report', 'KwitansiController@report');
    Route::get('export/kwitansi/report_petani', 'KwitansiController@report_petani');

    Route::get('/jurnal_umum', 'Jurnal_umumController@index');
    Route::get('/jurnal_umum/{id}', 'Jurnal_umumController@select');
    Route::post('/jurnal_umum', 'Jurnal_umumController@insert');
    Route::put('/jurnal_umum/{id}', 'Jurnal_umumController@edit');
    Route::delete('/jurnal_umum/{id}', 'Jurnal_umumController@delete');
    Route::get('/kode_jurnal_umum', 'Jurnal_umumController@kode_jurnal_umum');
    Route::get('/kode_jurnal_umum_induk', 'Jurnal_umumController@kode_jurnal_umum_induk');
    Route::get('/kode_jurnal_umum_last', 'Jurnal_umumController@kode_jurnal_umum_last');

    Route::get('/detail_jurnal_umum', 'Detail_jurnal_umumController@index');
    // Route::get('/detail_jurnal_umum/nomor_jurnal/{id_jurnal_umum}', 'Detail_jurnal_umumController@select_by_nomor_jurnal');
    Route::get('/detail_jurnal_umum/{id}', 'Detail_jurnal_umumController@select');
    Route::post('/detail_jurnal_umum', 'Detail_jurnal_umumController@insert');
    Route::put('/detail_jurnal_umum/{id}', 'Detail_jurnal_umumController@edit');
    Route::delete('/detail_jurnal_umum/{id}', 'Detail_jurnal_umumController@delete');

    Route::get('/bukti_kas/{id_jurnal_umum}', 'Jurnal_umumController@bukti_kas');
    Route::get('/all_jurnal', 'Jurnal_umumController@all_jurnal');

    Route::get('export/jurnal', 'Jurnal_umumController@jurnal');
    Route::get('export/jurnal/{id}', 'Jurnal_umumController@select_jurnal');

    Route::get('/jurnal_penyesuaian', 'Jurnal_penyesuaianController@index');
    Route::get('/jurnal_penyesuaian/{id}', 'Jurnal_penyesuaianController@select');
    Route::post('/jurnal_penyesuaian', 'Jurnal_penyesuaianController@insert');
    Route::put('/jurnal_penyesuaian/{id}', 'Jurnal_penyesuaianController@edit');
    Route::delete('/jurnal_penyesuaian/{id}', 'Jurnal_penyesuaianController@delete');

    Route::get('/provinsi', 'WilayahController@provinsi');
    Route::get('/kota/{id_provinsi}', 'WilayahController@kota');
    Route::get('/kecamatan/{id_provinsi}/{id_kota}', 'WilayahController@kecamatan');
    Route::get('/kelurahan/{id_provinsi}/{id_kota}/{id_kecamatan}', 'WilayahController@kelurahan');

    Route::get('/report/kwitansi', 'ReportController@kwitansi');
    Route::get('/report/omset/pks', 'ReportController@omset_pks');
    Route::get('/report/omset/ongkos_bongkar', 'ReportController@omset_ongkos_bongkar');
    Route::get('/report/modal/modal_usaha', 'ReportController@modal_usaha');
    Route::get('/report/modal/pembagian_kongsi', 'ReportController@pembagian_kongsi');
    Route::get('/report/pendapatan/pendapatan_usaha', 'ReportController@pendapatan_usaha');
    Route::get('/report/pendapatan/pendapatan_lain', 'ReportController@pendapatan_lain');
    Route::get('/report/pendapatan/pendapatan_uang_muka', 'ReportController@pendapatan_uang_muka');
    Route::get('/report/jurnal', 'Jurnal_umumController@jurnal');
    Route::get('/report/buku_besar', 'ReportController@buku_besar');
    Route::get('/report/hutang', 'ReportController@hutang');
    Route::get('/report/piutang', 'ReportController@piutang');
    Route::get('/report/giro', 'ReportController@giro');
    Route::get('/report/pajak_masukan', 'ReportController@pajak_masukan');
    Route::get('/report/pajak_keluaran', 'ReportController@pajak_keluaran');
    Route::get('/report/kas', 'ReportController@kas');

    Route::get('/report/laba_rugi', 'ReportController@laba_rugi');

    Route::get('/graph/kwitansi', 'GraphController@graph_kwitansi');
    Route::get('/graph/omset', 'GraphController@graph_omset');
});