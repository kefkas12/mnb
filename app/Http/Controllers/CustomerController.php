<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function kode_customer()
    {
        $last = Customer::select("kode_customer_mnb")->orderBy("created_at", "desc")->first();
        if (!$last) {
            return 'CUS-0000001-MNB';
        } else {
            $no = intval(substr($last->kode_customer_mnb, 4, 7)) + 1;
            if ($no < 10) {
                return 'CUS-000000' . $no . '-MNB';
            } elseif ($no < 100) {
                return 'CUS-00000' . $no . '-MNB';
            } elseif ($no < 1000) {
                return 'CUS-0000' . $no . '-MNB';
            } elseif ($no < 10000) {
                return 'CUS-000' . $no . '-MNB';
            } elseif ($no < 100000) {
                return 'CUS-00' . $no . '-MNB';
            } elseif ($no < 1000000) {
                return 'CUS-0' . $no . '-MNB';
            } else {
                return 'CUS-' . $no . '-MNB';
            }
        }
    }
    public function index()
    {
        ///api/kwitansi?per_page=5&page=1&search=&tanggal_dari=2022-02-01&tanggal_sampai=2022-02-12
        if (isset($_GET['per_page'])) {
            if($_GET['per_page'] == -1){
                $customer = Customer::count();
                $_GET['per_page'] = $customer;
            }
            $customer = Customer::orderBy('created_at', 'desc')->paginate($_GET['per_page']);
            if (isset($_GET['search'])) {
                $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                    ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate($_GET['per_page']);
                if (isset($_GET['sort'])) {
                    $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                        ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate($_GET['per_page']);
                    if (isset($_GET['order'])) {
                        $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                            ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate($_GET['per_page']);
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $customer = Customer::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate($_GET['per_page']);
                }
            }
        } else {
            $customer = Customer::orderBy('created_at', 'desc')->paginate();
            if (isset($_GET['search'])) {
                $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                    ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                    ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                    ->orderBy('created_at', 'desc')
                    ->paginate();
                if (isset($_GET['sort'])) {
                    $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                        ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                        ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                        ->orderBy($_GET['sort'], 'desc')
                        ->paginate();
                    if (isset($_GET['order'])) {
                        $customer = Customer::Where('kode_customer_mnb', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('jenis_badan_usaha', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('nama_perusahaan', 'like', '%' . $_GET['search'] . '%')
                            ->orWhere('npwp_customer', 'like', '%' . $_GET['search'] . '%')
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
                            ->orWhere('kode_customer_induk', 'like', '%' . $_GET['search'] . '%')
                            ->orderBy($_GET['sort'], $_GET['order'])
                            ->paginate();
                    }
                }
            } else {
                if (isset($_GET['sort']) && isset($_GET['order'])) {
                    $customer = Customer::orderBy($_GET['sort'], $_GET['order'])
                        ->paginate();
                }
            }
        }


        return $customer;
    }
    public function select(Request $request, $id)
    {
        $customer = Customer::where('id', $id)->get();

        return $customer;
    }
    public function insert(Request $request)
    {
        $customer = new Customer;
        $customer->id = Str::uuid()->toString();
        $customer->kode_customer_mnb = $request->kode_customer_mnb;
        $customer->badan_usaha = $request->badan_usaha;
        $customer->jenis_badan_usaha = $request->jenis_badan_usaha;
        $customer->nama_perusahaan = $request->nama_perusahaan;
        $customer->npwp_customer = $request->npwp_customer;
        $customer->alamat = $request->alamat;
        $customer->nomor_telepon = $request->nomor_telepon;
        $customer->nomor_fax = $request->nomor_fax;
        $customer->email = $request->email;
        $customer->website = $request->website;
        $customer->nama_pemilik = $request->nama_pemilik;
        $customer->jenis_usaha = $request->jenis_usaha;
        $customer->status = $request->status;
        $customer->nama_contact_person = $request->nama_contact_person;
        $customer->jabatan_contact_person = $request->jabatan_contact_person;
        $customer->nomor_telepon_contact_person = $request->nomor_telepon_contact_person;
        $customer->nama_provinsi = $request->nama_provinsi;
        $customer->nama_kabupaten = $request->nama_kabupaten;
        $customer->kode_pos = $request->kode_pos;
        $customer->kode_customer_induk = $request->kode_customer_induk;
        $customer->save();

        return $customer;
    }
    public function edit(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->kode_customer_mnb = $request->kode_customer_mnb;
        $customer->badan_usaha = $request->badan_usaha;
        $customer->jenis_badan_usaha = $request->jenis_badan_usaha;
        $customer->nama_perusahaan = $request->nama_perusahaan;
        $customer->npwp_customer = $request->npwp_customer;
        $customer->alamat = $request->alamat;
        $customer->nomor_telepon = $request->nomor_telepon;
        $customer->nomor_fax = $request->nomor_fax;
        $customer->email = $request->email;
        $customer->website = $request->website;
        $customer->nama_pemilik = $request->nama_pemilik;
        $customer->jenis_usaha = $request->jenis_usaha;
        $customer->status = $request->status;
        $customer->nama_contact_person = $request->nama_contact_person;
        $customer->jabatan_contact_person = $request->jabatan_contact_person;
        $customer->nomor_telepon_contact_person = $request->nomor_telepon_contact_person;
        $customer->nama_provinsi = $request->nama_provinsi;
        $customer->nama_kabupaten = $request->nama_kabupaten;
        $customer->kode_pos = $request->kode_pos;
        $customer->kode_customer_induk = $request->kode_customer_induk;
        $customer->save();

        return $customer;
    }
    public function delete(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return $customer;
    }
}
