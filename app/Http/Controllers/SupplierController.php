<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kode_supplier(){
        $last = Supplier::select("kode_supplier_mnb")->orderBy("created_at", "desc")->first();
        if (!$last) {
			return 'SUP-0000001-MNB';
		} else {
			$no = intval(substr($last->kode_supplier_mnb, 4, 7)) + 1;
			if ($no < 10) {
				return 'SUP-000000'.$no.'-MNB';
			} elseif ($no < 100) {
			    return 'SUP-00000'.$no.'-MNB';
			} elseif ($no < 1000) {
			    return 'SUP-0000'.$no.'-MNB';
			} elseif ($no < 10000) {
			    return 'SUP-000'.$no.'-MNB';
			} elseif ($no < 100000) {
			    return 'SUP-00'.$no.'-MNB';
			} elseif ($no < 1000000) {
			    return 'SUP-0'.$no.'-MNB';
			} else {
			    return 'SUP-'.$no.'-MNB';
			}
		}
    }
    public function index()
    {
        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $supplier = Supplier::count();
                $_GET['per_page'] = $supplier;
            }
            $supplier = Supplier::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $supplier = Supplier::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $supplier = Supplier::orderBy('created_at', 'desc')->paginate();

            $supplier = Supplier::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $supplier = Supplier::Where('kode_supplier_mnb', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('npwp_supplier', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('alamat', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_telepon', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_fax', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('email', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('website', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_pemilik', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('status', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jabatan_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nomor_telepon_contact_person', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_provinsi', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_kabupaten', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_pos', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('kode_supplier_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $supplier = Supplier::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }


        return $supplier;
    }
    public function select(Request $request, $id)
    {
        $supplier = Supplier::where('id', $id)->get();

        return $supplier;
    }
    public function insert(Request $request)
    {
        $supplier = new Supplier;
        $supplier->id = Str::uuid()->toString();
        $supplier->kode_supplier_mnb = $request->kode_supplier_mnb;
        $supplier->badan_usaha = $request->badan_usaha;
        $supplier->jenis_badan_usaha = $request->jenis_badan_usaha;
        $supplier->nama_perusahaan = $request->nama_perusahaan;
        $supplier->npwp_supplier = $request->npwp_supplier;
        $supplier->alamat = $request->alamat;
        $supplier->nomor_telepon = $request->nomor_telepon;
        $supplier->nomor_fax = $request->nomor_fax;
        $supplier->email = $request->email;
        $supplier->website = $request->website;
        $supplier->nama_pemilik = $request->nama_pemilik;
        $supplier->jenis_usaha = $request->jenis_usaha;
        $supplier->status = $request->status;
        $supplier->nama_contact_person = $request->nama_contact_person;
        $supplier->jabatan_contact_person = $request->jabatan_contact_person;
        $supplier->nomor_telepon_contact_person = $request->nomor_telepon_contact_person;
        $supplier->nama_provinsi = $request->nama_provinsi;
        $supplier->nama_kabupaten = $request->nama_kabupaten;
        $supplier->kode_pos = $request->kode_pos;
        $supplier->kode_supplier_induk = $request->kode_supplier_induk;
        $supplier->save();

        return $supplier;
    }
    public function edit(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->kode_supplier_mnb = $request->kode_supplier_mnb;
        $supplier->badan_usaha = $request->badan_usaha;
        $supplier->jenis_badan_usaha = $request->jenis_badan_usaha;
        $supplier->nama_perusahaan = $request->nama_perusahaan;
        $supplier->npwp_supplier = $request->npwp_supplier;
        $supplier->alamat = $request->alamat;
        $supplier->nomor_telepon = $request->nomor_telepon;
        $supplier->nomor_fax = $request->nomor_fax;
        $supplier->email = $request->email;
        $supplier->website = $request->website;
        $supplier->nama_pemilik = $request->nama_pemilik;
        $supplier->jenis_usaha = $request->jenis_usaha;
        $supplier->status = $request->status;
        $supplier->nama_contact_person = $request->nama_contact_person;
        $supplier->jabatan_contact_person = $request->jabatan_contact_person;
        $supplier->nomor_telepon_contact_person = $request->nomor_telepon_contact_person;
        $supplier->nama_provinsi = $request->nama_provinsi;
        $supplier->nama_kabupaten = $request->nama_kabupaten;
        $supplier->kode_pos = $request->kode_pos;
        $supplier->kode_supplier_induk = $request->kode_supplier_induk;
        $supplier->save();

        return $supplier;
    }
    public function delete(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return $supplier;
    }
}
