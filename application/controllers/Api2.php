<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api2 extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('upload');
    }

    public function index_get()
    {

    }

    public function userlocation_post()
    {
        $username = $this->post('username');
        $i_company = $this->post('i_company');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $data = array(
                'username' => $username,
                'i_company' => $i_company,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'createdat' => current_datetime(),
            );

            $this->db->insert('tbl_user_location', $data);

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function login_post()
    {
        $this->load->library('custom');

        $username = $this->post('username');
        $password = $this->custom->password($this->post('password'));
        $i_company = $this->post('i_company');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {
            $where = array(
                'username' => $username,
                'e_password' => $password,
                'i_company' => $i_company,
                'f_active' => 'true',
            );
            $data_user = $this->db->get_where('tbl_user', $where);
            if ($data_user->num_rows() > 0) {

                $app_version = $this->db->query("select config_value from tbl_config where config_name = 'app_version'")->row()->config_value;

                $query = $this->db->query("SELECT current_timestamp as c");
                $row = $query->row();
                $datenow = $row->c;

                $this->db->select("username");
                $this->db->from("tbl_user_login");
                $this->db->where("username", $username);
                $this->db->where("i_company", $i_company);
                $this->db->where("d_login", date('Y-m-d'));
                $cek_data_login = $this->db->get();

                if ($cek_data_login->num_rows() == 0) {
                    $data = array(
                        'username' => $username,
                        'i_company' => $i_company,
                        'd_login' => date('Y-m-d'),
                        // 'e_foto'        => basename($_FILES["fileToUpload"]["name"]),
                        'e_foto' => '',
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'createdat' => $datenow,
                    );

                    $this->db->insert('tbl_user_login', $data);
                }

                $data_user = $data_user->row();
                $user = array(
                    'username' => $data_user->username,
                    'e_name' => $data_user->e_name,
                    'i_area' => $data_user->i_area,
                    'i_company' => $data_user->i_company,
                    'i_staff' => $data_user->i_staff,
                    'email' => $data_user->email,
                    'phone' => $data_user->phone,
                    'app_version' => $app_version,
                );

                $i_company = $data_user->i_company;
                $username = $data_user->username;
                $this->Logger->write($i_company, $username, 'Apps Login');

                $this->response([
                    'status' => true,
                    'data' => $user,
                    'message' => 'Berhasil Login ^_^',
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'data' => [],
                    'message' => 'Username / Password Salah :p',
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listarea_post()
    {
        $i_company = $this->post('i_company');
        $username = $this->post('username');
        $i_area = $this->post('i_area');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Menu Sales Order');

            // $this->db->select("e_area_name as value");
            // $this->db->from("tbl_area");
            // $this->db->where("i_company", $i_company);
            // $this->db->where("i_area", $i_area);
            // $this->db->where("f_active", 'true');
            // $this->db->order_by("i_area", "asc");
            // $data = $this->db->get();

            $data = $this->db->query("select e_area_name as value from tbl_area where i_company = '$i_company'
            and f_active = 't' and i_area in(
            select i_area from tbl_user_area where username = '$username' and i_company = '$i_company'
            )
            order by i_area asc");

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function loginselfie_post()
    {
        $username = $this->post('username');
        $i_company = $this->post('i_company');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');

        $target_dir = "assets/images/loginselfie/$i_company/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $this->db->select("username");
            $this->db->from("tbl_user_login");
            $this->db->where("username", $username);
            $this->db->where("i_company", $i_company);
            $this->db->where("d_login", date('Y-m-d'));
            $cek_data_login = $this->db->get();

            if ($cek_data_login->num_rows() == 0) {
                $data = array(
                    'username' => $username,
                    'i_company' => $i_company,
                    'd_login' => date('Y-m-d'),
                    'e_foto' => basename($_FILES["fileToUpload"]["name"]),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_user_login', $data);
            }

            $this->response([
                'status' => true,
                'message' => 'Berhasil Upload Foto ^_^',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal Upload :p',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function cari_pelanggan_post()
    {
        $cari = strtoupper($this->post('cari'));
        $e_area_name = $this->post('i_area');
        $i_company = $this->post('i_company');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, c.n_customer_discount1, c.n_customer_discount2, a.i_area ");
            $this->db->from("tbl_customer a");
            $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            $this->db->where("b.e_area_name", $e_area_name);
            $this->db->where("a.f_active", 'true');
            $this->db->where("a.i_company", $i_company);
            $this->db->where("(a.i_customer like '%$cari%' or a.e_customer_name like '%$cari%')");
            $query = $this->db->get();

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Cari Pelanggan : ' . $cari . ' Area : ' . $e_area_name);

            if ($query->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data Ditemukan',
                    'data' => $query->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {

                $this->response([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => [],
                ], REST_Controller::HTTP_OK);

            }
        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function searchproduct_post()
    {
        $cari = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));
        $i_company = $this->post('i_company');
        $i_product_group = $this->post('i_product_group');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $i_promo = $this->post('i_promo');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {
            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Cari Product : ' . $cari);

            if (($i_promo != '') || ($i_promo != null)) {
                $data_promo = $this->db->query("select * from tbl_promo where i_promo = '$i_promo' and i_company = '$i_company'")->row();
                $f_all_product = $data_promo->f_all_product;
                $i_price_group = $data_promo->i_price_group;
                $i_product_group_promo = $data_promo->i_product_group;
                $i_promo_type = $data_promo->i_promo_type;

                if ($f_all_product == 'f') {
                    if (($i_promo_type == '1') || ($i_promo_type == '3')) {
                        if ($i_product_group == $i_product_group_promo) {
                            $this->db->select("a.i_product, a.i_product_group, a.e_product_name, b.v_product_price as harga, 99 as n_quantity ");
                            $this->db->from("tbl_product a");
                            $this->db->join("tbl_product_price b", "a.i_product = b.i_product and a.i_company = b.i_company");
                            $this->db->where("a.i_product_group", $i_product_group);
                            $this->db->where("b.i_price_group", $i_price_group);
                            $this->db->where("a.i_company", $i_company);
                            $this->db->where("a.f_active", 't');
                            $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");
                            $query = $this->db->get();
                        } else {
                            $this->db->select("a.i_product, a.i_product_group, a.e_product_name, c.v_unit_price as harga, 99 as n_quantity ");
                            $this->db->from("tbl_product a");
                            $this->db->join("tbl_product_price b", "a.i_product = b.i_product and a.i_company = b.i_company");
                            $this->db->join("tbl_promo_item c", "a.i_product = c.i_product and a.i_company = c.i_company");
                            $this->db->where("a.i_product_group", $i_product_group);
                            $this->db->where("b.i_price_group", $i_price_group);
                            $this->db->where("c.i_promo", $i_promo);
                            $this->db->where("a.i_company", $i_company);
                            $this->db->where("a.f_active", 't');
                            $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");
                            $query = $this->db->get();
                        }
                    } else {
                        $this->db->select("a.i_product, a.i_product_group, a.e_product_name, b.v_unit_price as harga, 99 as n_quantity");
                        $this->db->from("tbl_product a");
                        $this->db->join("tbl_promo_item b", "a.i_product = b.i_product and a.i_company = b.i_company");
                        $this->db->where("a.i_product_group", $i_product_group);
                        $this->db->where("b.i_promo", $i_promo);
                        $this->db->where("a.i_company", $i_company);
                        $this->db->where("a.f_active", 't');
                        $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");
                        $query = $this->db->get();
                    }

                    if ($query->num_rows() > 0) {
                        $list = array();
                        $key = 0;
                        foreach ($query->result() as $riw) {
                            $list[$key]['i_product'] = $riw->i_product;
                            $list[$key]['e_product_name'] = $riw->e_product_name;
                            $list[$key]['v_product_price'] = number_format($riw->harga, 0, ",", ".");
                            $list[$key]['n_quantity_stock'] = $riw->n_quantity;

                            $key++;
                        }

                        $this->response([
                            'status' => true,
                            'message' => 'Data Ditemukan',
                            'data' => $list,
                            'i_price_group' => $i_price_group,
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Data Tidak Ditemukan',
                            'data' => [],
                            'i_price_group' => $i_price_group,
                        ], REST_Controller::HTTP_OK);
                    }

                } else {
                    $this->db->select("a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, 99 as n_quantity ");
                    $this->db->from("tbl_product a");
                    $this->db->join("tbl_product_price b", "a.i_product = b.i_product and a.i_company = b.i_company");
                    $this->db->where("a.i_product_group", $i_product_group);
                    $this->db->where("b.i_price_group", $i_price_group);
                    $this->db->where("a.i_company", $i_company);
                    $this->db->where("a.f_active", 't');
                    $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");
                    $query = $this->db->get();

                    if ($query->num_rows() > 0) {
                        $list = array();
                        $key = 0;
                        foreach ($query->result() as $riw) {
                            $list[$key]['i_product'] = $riw->i_product;
                            $list[$key]['e_product_name'] = $riw->e_product_name;
                            $list[$key]['v_product_price'] = number_format($riw->v_product_price, 0, ",", ".");
                            $list[$key]['n_quantity_stock'] = $riw->n_quantity;

                            $key++;
                        }

                        $this->response([
                            'status' => true,
                            'message' => 'Data Ditemukan',
                            'data' => $list,
                            'i_price_group' => $i_price_group,
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Data Tidak Ditemukan',
                            'data' => [],
                            'i_price_group' => $i_price_group,
                        ], REST_Controller::HTTP_OK);
                    }
                }
            } else {
                $i_price_group = $this->db->query("select i_price_group from tbl_customer where i_customer = '$i_customer' and i_company = '$i_company'")->row()->i_price_group;

                $data_area = $this->db->query("select i_store, f_stock from tbl_area where i_company = '$i_company' and i_area = '$i_area'")->row();
                $i_store = $data_area->i_store;
                $f_stock = $data_area->f_stock;
                if ($f_stock == 't') {
                    $this->db->select("a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, c.n_quantity ");
                    $this->db->from("tbl_product a");
                    $this->db->join("tbl_product_price b", "a.i_product = b.i_product and a.i_company = b.i_company");
                    $this->db->join("tbl_ic c", "a.i_product = c.i_product and a.i_company = c.i_company and b.i_product = c.i_product and b.i_company = c.i_company");
                    $this->db->where("a.i_product_group", $i_product_group);
                    $this->db->where("b.i_price_group", $i_price_group);
                    $this->db->where("a.i_company", $i_company);
                    $this->db->where("a.f_active", 't');
                    $this->db->where("c.i_store", $i_store);
                    $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");

                } else {
                    $this->db->select("a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, 99 as n_quantity ");
                    $this->db->from("tbl_product a");
                    $this->db->join("tbl_product_price b", "a.i_product = b.i_product and a.i_company = b.i_company");
                    $this->db->where("a.i_product_group", $i_product_group);
                    $this->db->where("trim(b.i_price_group)", $i_price_group);
                    $this->db->where("a.i_company", $i_company);
                    $this->db->where("a.f_active", 't');
                    $this->db->where("(a.i_product like '%$cari%' or a.e_product_name like '%$cari%')");
                }

                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    $list = array();
                    $key = 0;
                    foreach ($query->result() as $riw) {
                        $list[$key]['i_product'] = $riw->i_product;
                        $list[$key]['e_product_name'] = $riw->e_product_name;
                        $list[$key]['v_product_price'] = number_format($riw->v_product_price, 0, ",", ".");
                        $list[$key]['n_quantity_stock'] = $riw->n_quantity;

                        $key++;
                    }

                    $this->response([
                        'status' => true,
                        'message' => 'Data Ditemukan',
                        'data' => $list,
                        'i_price_group' => $i_price_group,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Data Tidak Ditemukan',
                        'data' => [],
                        'i_price_group' => $i_price_group,
                    ], REST_Controller::HTTP_OK);
                }
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function listproductgroup_post()
    {
        $i_company = $this->post('i_company');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Daftar Product Group');

            $this->db->select("i_product_group, e_product_groupname");
            $this->db->from("tbl_product_group");
            $this->db->where("f_active", 'true');
            $this->db->where("i_company", $i_company);
            $this->db->order_by("i_product_group", "asc");
            $data = $this->db->get();

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function reviewspb_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_promo = $this->post('i_promo');
        $v_gross = (int) $this->post('v_gross');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Review SPB customer : ' . $i_customer);

            if (($i_promo != '') || ($i_promo != null)) {

                $data_promo = $this->db->query("select * from tbl_promo where i_promo = '$i_promo' and i_company = '$i_company'")->row();
                $f_all_product = $data_promo->f_all_product;
                $i_price_group = $data_promo->i_price_group;
                $i_product_group_promo = $data_promo->i_product_group;
                $i_promo_type = $data_promo->i_promo_type;
                $e_promo_name = $data_promo->e_promo_name;
                $n_promo_discount1 = $data_promo->n_promo_discount1;

                $data_diskon = $this->db->query("select * from tbl_customer_discount where i_customer = '$i_customer' and i_company = '$i_company'")->row();
                $n_customer_discount1 = (int) $data_diskon->n_customer_discount1;
                $n_customer_discount2 = (int) $data_diskon->n_customer_discount2;

                if (($i_promo_type == '1') || ($i_promo_type == '6')) {
                    $vdis1 = 0;
                    $vdis2 = 0;
                    $vdis3 = ($v_gross * $n_promo_discount1) / 100;
                    $vtotdis = ($vdis1 + $vdis2 + $vdis3);
                    $vnetto = ($v_gross - $vtotdis);

                    $data = array(
                        'v_gross' => $v_gross,
                        'n_customer_discount1' => 0,
                        'n_customer_discount2' => 0,
                        'n_customer_discount3' => $n_promo_discount1,
                        'vdis1' => $vdis1,
                        'vdis2' => $vdis2,
                        'vdis3' => $vdis3,
                        'vtotdis' => $vtotdis,
                        'vnetto' => $vnetto,
                        'e_promo_name' => $e_promo_name,
                    );
                } elseif ($i_promo_type == '2') {
                    $vdis1 = 0;
                    $vdis2 = 0;
                    $vdis3 = 0;
                    $vtotdis = ($vdis1 + $vdis2 + $vdis3);
                    $vnetto = ($v_gross - $vtotdis);

                    $data = array(
                        'v_gross' => $v_gross,
                        'n_customer_discount1' => 0,
                        'n_customer_discount2' => 0,
                        'n_customer_discount3' => 0,
                        'vdis1' => $vdis1,
                        'vdis2' => $vdis2,
                        'vdis3' => $vdis3,
                        'vtotdis' => $vtotdis,
                        'vnetto' => $vnetto,
                        'e_promo_name' => $e_promo_name,
                    );
                } elseif (($i_promo_type == '3') || ($i_promo_type == '5')) {
                    $vdis1 = ($v_gross * $n_customer_discount1) / 100;
                    $vdis2 = (($v_gross - $vdis1) * $n_customer_discount2) / 100;
                    $vdis3 = (($v_gross - ($vdis1 + $vdis2)) * $n_promo_discount1) / 100;
                    $vtotdis = ($vdis1 + $vdis2 + $vdis3);
                    $vnetto = ($v_gross - $vtotdis);

                    $data = array(
                        'v_gross' => $v_gross,
                        'n_customer_discount1' => $n_customer_discount1,
                        'n_customer_discount2' => $n_customer_discount2,
                        'n_customer_discount3' => $n_promo_discount1,
                        'vdis1' => $vdis1,
                        'vdis2' => $vdis2,
                        'vdis3' => $vdis3,
                        'vtotdis' => $vtotdis,
                        'vnetto' => $vnetto,
                        'e_promo_name' => $e_promo_name,
                    );
                } elseif ($i_promo_type == '4') {
                    $vdis1 = ($v_gross * $n_customer_discount1) / 100;
                    $vdis2 = (($v_gross - $vdis1) * $n_customer_discount2) / 100;
                    $vtotdis = ($vdis1 + $vdis2);
                    $vnetto = ($v_gross - $vtotdis);

                    $data = array(
                        'v_gross' => $v_gross,
                        'n_customer_discount1' => $n_customer_discount1,
                        'n_customer_discount2' => $n_customer_discount2,
                        'n_customer_discount3' => 0,
                        'vdis1' => $vdis1,
                        'vdis2' => $vdis2,
                        'vdis3' => 0,
                        'vtotdis' => $vtotdis,
                        'vnetto' => $vnetto,
                        'e_promo_name' => $e_promo_name,
                    );
                }

            } else {
                $data_diskon = $this->db->query("select * from tbl_customer_discount where i_customer = '$i_customer' and i_company = '$i_company'")->row();
                $n_customer_discount1 = (int) $data_diskon->n_customer_discount1;
                $n_customer_discount2 = (int) $data_diskon->n_customer_discount2;

                $vdis1 = ($v_gross * $n_customer_discount1) / 100;
                $vdis2 = (($v_gross - $vdis1) * $n_customer_discount2) / 100;
                $vtotdis = ($vdis1 + $vdis2);
                $vnetto = ($v_gross - $vtotdis);

                $data = array(
                    'v_gross' => $v_gross,
                    'n_customer_discount1' => $n_customer_discount1,
                    'n_customer_discount2' => $n_customer_discount2,
                    'n_customer_discount3' => 0,
                    'vdis1' => $vdis1,
                    'vdis2' => $vdis2,
                    'vdis3' => 0,
                    'vtotdis' => $vtotdis,
                    'vnetto' => $vnetto,
                    'e_promo_name' => '',
                );
            }

            $this->response([
                'status' => true,
                'data' => $data,
            ], REST_Controller::HTTP_OK);

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function inputspb_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $username = $this->post('username');
        $i_area = $this->post('i_area');
        $i_product_group = $this->post('i_product_group');
        $i_price_group = $this->post('i_price_group');
        $d_spb = date('Y-m-d');
        $n_spb_discount1 = $this->post('n_spb_discount1');
        $n_spb_discount2 = $this->post('n_spb_discount2');
        $n_spb_discount3 = $this->post('n_spb_discount3');
        $v_spb_discount1 = $this->post('v_spb_discount1');
        $v_spb_discount2 = $this->post('v_spb_discount2');
        $v_spb_discount3 = $this->post('v_spb_discount3');
        $v_spb_discounttotal = $this->post('v_spb_discounttotal');
        $v_spb_gross = $this->post('v_spb_gross');
        $v_spb_netto = $this->post('v_spb_netto');
        $e_remark = $this->post('e_remark');
        $i_promo = $this->post('i_promo');
        $databrg = json_decode($this->post('databrg'));

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $e_periode = date('Ym');
            $this->load->library('custom');
            $i_spb = $this->custom->runningnumber('SPB', $i_company, $i_area, $e_periode);

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $data = array(
                'i_spb' => $i_spb,
                'i_company' => $i_company,
                'i_customer' => $i_customer,
                'i_promo' => $i_promo,
                'username' => $username,
                'i_area' => $i_area,
                'i_product_group' => $i_product_group,
                'i_price_group' => $i_price_group,
                'd_spb' => $d_spb,
                'n_spb_discount1' => $n_spb_discount1,
                'n_spb_discount2' => $n_spb_discount2,
                'n_spb_discount3' => $n_spb_discount3,
                'v_spb_discount1' => $v_spb_discount1,
                'v_spb_discount2' => $v_spb_discount2,
                'v_spb_discount3' => $v_spb_discount3,
                'v_spb_discounttotal' => $v_spb_discounttotal,
                'v_spb_gross' => $v_spb_gross,
                'v_spb_netto' => $v_spb_netto,
                'e_remark' => $e_remark,
                'createdat' => $datenow,
            );

            $this->db->insert('tbl_spb', $data);

            foreach ($databrg as $row) {
                $data = array(
                    'i_spb' => $i_spb,
                    'i_company' => $i_company,
                    'i_product' => $row->i_product,
                    'i_product_grade' => 'A',
                    'n_order' => $row->qty,
                    'v_unit_price' => $row->v_unit_price,
                    'e_product_name' => $row->e_product_name,
                    'i_area' => $i_area,
                    'e_remark' => $row->e_remark,
                );
                $this->db->insert('tbl_spb_item', $data);
            }

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Input SPB : ' . $i_spb . ' Area :' . $i_area);

            $this->response([
                'status' => true,
                'message' => 'Data Berhasil Di Simpan',
            ], REST_Controller::HTTP_OK);

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listspb_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $cari = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Cari SPB : ' . $cari . ' Pelanggan :' . $i_customer);

            $this->db->select("i_spb, createdat, f_status_transfer, v_spb_netto, i_area");
            $this->db->from("tbl_spb");
            $this->db->where("i_customer", $i_customer);
            $this->db->where("i_company", $i_company);
            $this->db->where("f_spb_cancel", 'f');
            $this->db->like('i_spb', $cari);
            $this->db->order_by("createdat", "desc");

            $data = $this->db->get();

            if ($data->num_rows() > 0) {
                $list = array();
                $key = 0;
                foreach ($data->result() as $riw) {

                    $d_spb = date("d F Y H:i", strtotime($riw->createdat));
                    if ($riw->f_status_transfer == 't') {
                        $f_status_transfer = 'Transfer';
                    } else {
                        $f_status_transfer = 'Pending';
                    }
                    $list[$key]['i_spb'] = $riw->i_spb;
                    $list[$key]['d_spb'] = $d_spb;
                    $list[$key]['i_area'] = $riw->i_area;
                    $list[$key]['v_spb_netto'] = number_format($riw->v_spb_netto, 0, ",", ".");
                    $list[$key]['f_status_transfer'] = $f_status_transfer;

                    $key++;
                }

                $this->response([
                    'status' => true,
                    'data' => $list,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function detailspb_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_spb = $this->post('i_spb');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Detail SPB : ' . $i_spb . ' Pelanggan :' . $i_customer);

            $data = $this->db->query("select a.* from tbl_spb_item a, tbl_spb b
            where a.i_spb = b.i_spb
            and a.i_company = b.i_company
            and a.i_area = b.i_area
            and b.i_customer = '$i_customer'
            and a.i_spb = '$i_spb'
            and a.i_company = '$i_company'
            and a.i_area = '$i_area'");

            if ($data->num_rows() > 0) {
                $databrg = array();
                $key = 0;
                $jumlah_brg = 0;
                foreach ($data->result() as $riw) {

                    $databrg[$key]['i_product'] = $riw->i_product;
                    $databrg[$key]['e_product_name'] = $riw->e_product_name;
                    $databrg[$key]['n_order'] = $riw->n_order;
                    $databrg[$key]['v_unit_price'] = number_format($riw->v_unit_price, 0, ",", ".");
                    $databrg[$key]['e_remark'] = $riw->e_remark;

                    $key++;
                    $jumlah_brg++;
                }

                $dataheader = $this->db->query("select * from tbl_spb
                where
                 i_customer = '$i_customer'
                and i_spb = '$i_spb'
                and i_company = '$i_company'
                and i_area = '$i_area'")->row();

                $e_promo_name = '';

                $cek_promo = $this->db->query("select e_promo_name from tbl_promo where i_promo = '$dataheader->i_promo' and i_company = '$i_company'");

                if ($cek_promo->num_rows() > 0) {
                    $e_promo_name = $cek_promo->row()->e_promo_name;
                }

                $this->response([
                    'status' => true,
                    'i_spb' => $dataheader->i_spb,
                    'n_spb_discount1' => number_format($dataheader->n_spb_discount1, 0, ",", "."),
                    'n_spb_discount2' => number_format($dataheader->n_spb_discount2, 0, ",", "."),
                    'n_spb_discount3' => number_format($dataheader->n_spb_discount3, 0, ",", "."),
                    'v_spb_discounttotal' => number_format($dataheader->v_spb_discounttotal, 0, ",", "."),
                    'v_spb_gross' => number_format($dataheader->v_spb_gross, 0, ",", "."),
                    'v_spb_netto' => number_format($dataheader->v_spb_netto, 0, ",", "."),
                    'e_remark' => $dataheader->e_remark,
                    'dataitem' => $databrg,
                    'jumlah_brg' => $jumlah_brg,
                    'f_status_transfer' => $dataheader->f_status_transfer,
                    'e_promo_name' => $e_promo_name,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function cekinsearchcustomer_post()
    {
        $i_company = $this->post('i_company');
        $cari = str_replace("'", "", htmlspecialchars(strtoupper($this->post('keyword')), ENT_QUOTES));
        $i_area = $this->post('i_area');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Menu CheckIn, Cari :' . $cari);

            $this->load->library('custom');

            $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, c.n_customer_discount1, c.n_customer_discount2, a.i_area, a.latitude, a.longitude ");
            $this->db->from("tbl_customer a");
            $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            $this->db->where("a.i_area", $i_area);
            $this->db->where("a.f_active", 'true');
            $this->db->where("a.i_company", $i_company);
            $this->db->where("(a.i_customer like '%$cari%' or a.e_customer_name like '%$cari%')");
            // $this->db->like('a.i_customer', $cari);
            // $this->db->or_like('a.e_customer_name', $cari);

            $query = $this->db->get();
            $list = array();
            $key = 0;
            foreach ($query->result() as $row) {
                $i_customer = $row->i_customer;
                $e_customer_name = $row->e_customer_name;
                $e_customer_address = $row->e_customer_address;
                $i_price_group = $row->i_price_group;
                $n_customer_discount1 = $row->n_customer_discount1;
                $n_customer_discount2 = $row->n_customer_discount2;
                $i_area = $row->i_area;
                $latitudecustomer = $row->latitude;
                $longitudecustomer = $row->longitude;

                $cek_jarak = $this->custom->hitung_jarak($latitude, $longitude, $latitudecustomer, $longitudecustomer);

                // if($cari == ''){
                if ($cek_jarak <= 1000) {

                    $list[$key]['i_customer'] = $i_customer;
                    $list[$key]['e_customer_name'] = $e_customer_name;
                    $list[$key]['e_customer_address'] = $e_customer_address;
                    $list[$key]['i_price_group'] = $i_price_group;
                    $list[$key]['n_customer_discount1'] = $n_customer_discount1;
                    $list[$key]['n_customer_discount2'] = $n_customer_discount2;
                    $list[$key]['i_area'] = $i_area;
                    $list[$key]['jarak'] = number_format($cek_jarak, 0, ",", "") . " m";

                    $key++;
                }
                // }else{

                //     $list[$key]['i_customer'] = $i_customer;
                //     $list[$key]['e_customer_name'] = $e_customer_name;
                //     $list[$key]['e_customer_address'] = $e_customer_address;
                //     $list[$key]['i_price_group'] = $i_price_group;
                //     $list[$key]['n_customer_discount1'] = $n_customer_discount1;
                //     $list[$key]['n_customer_discount2'] = $n_customer_discount2;
                //     $list[$key]['i_area'] = $i_area;
                //     $list[$key]['jarak'] = number_format($cek_jarak,0,",","")." m";

                //     $key++;
                // }
            }
            $this->response([
                'status' => true,
                'data' => $list,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function checkinselfie_post()
    {
        $username = $this->post('username');
        $i_area = $this->post('i_area');
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');

        $target_dir = "assets/images/checkinselfie/$i_company/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $config['upload_path'] = './assets/images/checkinselfie/' . $i_company . '/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['encrypt_name'] = false; //Enkripsi nama yang terupload
        $this->upload->initialize($config);

        if (!empty($_FILES['fileToUpload']['name'])) {

            if ($this->upload->do_upload('fileToUpload')) {
                $gbr = $this->upload->data();
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = FCPATH . 'assets/images/checkinselfie/' . $i_company . '/' . $gbr['file_name'];
                $config['create_thumb'] = false;
                $config['maintain_ratio'] = false;
                $config['quality'] = '50%';
                $config['width'] = 864;
                $config['height'] = 1152;
                $config['new_image'] = FCPATH . '/assets/images/checkinselfie/' . $i_company . '/' . $gbr['file_name'];
                $config['rotation_angle'] = '180'; //

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $gambar = $gbr['file_name'];

                $query = $this->db->query("SELECT current_timestamp as c");
                $row = $query->row();
                $datenow = $row->c;

                $data = array(
                    'username' => $username,
                    'i_company' => $i_company,
                    'i_customer' => $i_customer,
                    'e_foto' => $gambar,
                    'd_checkin' => date('Y-m-d'),
                    'latitude_checkin' => $latitude,
                    'longitude_checkin' => $longitude,
                    'createdat_checkin' => $datenow,
                    'i_area' => $i_area,
                );

                $this->db->insert('tbl_customer_checkin', $data);

                $i_company = $i_company;
                $username = $username;
                $this->Logger->write($i_company, $username, 'Apps CheckIn Pelanggan :' . $i_customer);

                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Upload Foto ^_^',
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Gagal Upload :p',
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal Upload :p',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function detailcustomer_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Informasi Pelanggan :' . $i_customer);

            $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude ");
            $this->db->from("tbl_customer a");
            $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            $this->db->where("a.i_area", $i_area);
            $this->db->where("a.f_active", 'true');
            $this->db->where("a.i_company", $i_company);
            $this->db->like('a.i_customer', $i_customer);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal Upload :p',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function batalspb_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $i_spb = $this->post('i_spb');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $this->db->select("*");
            $this->db->from("tbl_spb");
            $this->db->where('i_spb', $i_spb);
            $this->db->where("i_customer", $i_customer);
            $this->db->where("i_company", $i_company);
            $this->db->where("i_area", $i_area);

            $data = $this->db->get();

            if ($data->num_rows() > 0) {

                $data_spb = $data->row();

                if ($data_spb->f_status_transfer == 'f') {

                    $query = $this->db->query("SELECT current_timestamp as c");
                    $row = $query->row();
                    $datenow = $row->c;

                    $data_update = array(
                        'f_spb_cancel' => 't',
                        'modifiedat' => $datenow,
                    );

                    $this->db->where('i_spb', $i_spb);
                    $this->db->where("i_customer", $i_customer);
                    $this->db->where("i_company", $i_company);
                    $this->db->where("i_area", $i_area);

                    $this->db->update('tbl_spb', $data_update);

                    $i_company = $i_company;
                    $username = $username;
                    $this->Logger->write($i_company, $username, 'Apps Batal SPB :' . $i_spb . ' Area : ' . $i_area);

                    $this->response([
                        'status' => true,
                        'data' => [],
                        'message' => 'SPB Berhasil Di Batalkan ',
                    ], REST_Controller::HTTP_OK);

                } else {
                    $this->response([
                        'status' => true,
                        'data' => [],
                        'message' => 'Gagal Batal !! SPB Sudah Di Transfer',
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    'data' => [],
                    'message' => 'Data Tidak Ada !',
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal Upload :p',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function checkout_post()
    {
        $username = $this->post('username');
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $data = array(
                'latitude_checkout' => $latitude,
                'longitude_checkout' => $longitude,
                'createdat_checkout' => $datenow,
            );

            $this->db->where('username', $username);
            $this->db->where('i_company', $i_company);
            $this->db->where('i_customer', $i_customer);
            $this->db->where('d_checkin', date('Y-m-d'));
            $this->db->where('createdat_checkout', null);
            $this->db->update('tbl_customer_checkin', $data);

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps CheckOut Pelanggan :' . $i_customer);

            $this->response([
                'status' => true,
                'message' => 'Berhasil Checkout',
            ], REST_Controller::HTTP_OK);

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Data Tidak Ada !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function customerdokumentasi_post()
    {
        $username = $this->post('username');
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');

        $target_dir = "assets/images/dokumentasi/$i_company/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $config['upload_path'] = './assets/images/dokumentasi/' . $i_company . '/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['encrypt_name'] = false; //Enkripsi nama yang terupload
        $this->upload->initialize($config);

        if (!empty($_FILES['fileToUpload']['name'])) {

            if ($this->upload->do_upload('fileToUpload')) {
                $gbr = $this->upload->data();
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/images/dokumentasi/' . $i_company . '/' . $gbr['file_name'];
                $config['create_thumb'] = false;
                $config['maintain_ratio'] = false;
                $config['quality'] = '50%';
                $config['width'] = 864;
                $config['height'] = 1152;
                $config['new_image'] = './assets/images/dokumentasi/' . $i_company . '/' . $gbr['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $gambar = $gbr['file_name'];

                $query = $this->db->query("SELECT current_timestamp as c");
                $row = $query->row();
                $datenow = $row->c;

                $data = array(
                    'username' => $username,
                    'i_company' => $i_company,
                    'i_customer' => $i_customer,
                    'd_dokumentasi' => date('Y-m-d'),
                    'e_foto' => $gambar,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_customer_dokumentasi', $data);

                $i_company = $i_company;
                $username = $username;
                $this->Logger->write($i_company, $username, 'Apps Membuat Dokumentasi Pelanggan :' . $i_customer);

                $this->response([
                    'status' => true,
                    'message' => 'Berhasil Upload Foto ^_^',
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Gagal Upload :p',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listdokumentasi_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Dokumentasi List Pelanggan :' . $i_customer);

            $data = $this->db->query("select x.d_dokumentasi from (
                select d_dokumentasi from tbl_customer_dokumentasi where i_customer = '$i_customer' and i_company = '$i_company'
                group by d_dokumentasi
                union all
                select d_saran from tbl_customer_saran where i_customer = '$i_customer' and i_company = '$i_company'
                group by d_saran
                ) as x group by x.d_dokumentasi
                order by x.d_dokumentasi desc");

            if ($data->num_rows() > 0) {
                $list = array();
                $key = 0;
                foreach ($data->result() as $riw) {

                    $d_dokumentasi = date("d F Y", strtotime($riw->d_dokumentasi));
                    $list[$key]['d_dokumentasi1'] = $d_dokumentasi;
                    $list[$key]['d_dokumentasi2'] = $riw->d_dokumentasi;

                    $key++;
                }

                $this->response([
                    'status' => true,
                    'data' => $list,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listdokumentasidetail_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $d_dokumentasi = $this->post('d_dokumentasi');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Dokumentasi List Detail Pelanggan :' . $i_customer . ' Tgl : ' . $d_dokumentasi);

            $this->db->select("e_foto");
            $this->db->from("tbl_customer_dokumentasi");
            $this->db->where("i_customer", $i_customer);
            $this->db->where("i_company", $i_company);
            $this->db->where("d_dokumentasi", $d_dokumentasi);

            $data = $this->db->get();

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listpromo_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Promo List ');

            $data = $this->db->query("select x.* from(
                select *, current_date - d_promo_finish as expired from tbl_promo
                ) as x
                where x.expired <= 0
                and x.f_all_customer = 't'
                and x.f_all_area = 't'
                union all
                select x.* from(
                select *, current_date - d_promo_finish as expired from tbl_promo
                ) as x
                inner join tbl_promo_area b on(x.i_company = b.i_company and x.i_promo = b.i_promo and x.i_promo_type = b.i_promo_type and b.i_area = '$i_area')
                where x.expired <= 0
                union all
                select x.* from(
                select *, current_date - d_promo_finish as expired from tbl_promo
                ) as x
                inner join tbl_promo_customer b on(x.i_company = b.i_company and x.i_promo = b.i_promo and x.i_promo_type = b.i_promo_type and b.i_customer = '$i_customer')
                where x.expired <= 0");

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function listsarantype_post()
    {
        $i_company = $this->post('i_company');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps List Saran Type');

            $this->db->select("e_saran_typename as value");
            $this->db->from("tbl_saran_type");
            $this->db->where("i_company", $i_company);
            $this->db->order_by("i_saran_type", "asc");
            $data = $this->db->get();

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => true,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function insertsaran_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $e_saran = $this->post('e_saran');
        $e_saran_typename = $this->post('e_saran_typename');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Input Saran Pelanggan : ' . $i_customer . ' Type Saran : ' . $e_saran_typename);

            $i_saran_type = $this->db->query("select i_saran_type from tbl_saran_type where e_saran_typename = '$e_saran_typename' and i_company = '$i_company'")->row()->i_saran_type;
            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $tgl_sekarang = date('Y-m-d');
            $cek_data = $this->db->query("select username from tbl_customer_saran where i_company = '$i_company' and i_saran_type = '$i_saran_type' and i_customer = '$i_customer' and d_saran = '$tgl_sekarang'");

            if ($cek_data->num_rows() > 0) {
                $this->response([
                    'status' => false,
                    'message' => 'Data Sudah Ada !',
                ], REST_Controller::HTTP_OK);
            } else {
                $data = array(
                    'username' => $username,
                    'i_company' => $i_company,
                    'i_customer' => $i_customer,
                    'i_saran_type' => $i_saran_type,
                    'd_saran' => $tgl_sekarang,
                    'e_saran' => $e_saran,
                    'e_respons' => '',
                    'username_respons' => '',
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_customer_saran', $data);

                $this->response([
                    'status' => true,
                    'message' => 'Data Berhasil Di Simpan',
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function listsaran_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $d_saran = $this->post('d_saran');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka List Saran Pelanggan : ' . $i_customer . ' Tgl Saran : ' . $d_saran);

            $data = $this->db->query("select a.i_saran_type, b.e_saran_typename from tbl_customer_saran a, tbl_saran_type b where
            a.i_company = b.i_company
            and a.i_saran_type= b.i_saran_type
            and a.i_company = '$i_company'
            and a.i_customer = '$i_customer'
            and a.d_saran = '$d_saran'");

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function detailsaran_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $d_saran = $this->post('d_saran');
        $i_saran_type = $this->post('i_saran_type');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka List Detail Saran Pelanggan : ' . $i_customer . ' Tgl Saran : ' . $d_saran);

            $data = $this->db->query("select a.i_saran_type, b.e_saran_typename, a.e_saran, a.e_respons, a.username_respons from tbl_customer_saran a, tbl_saran_type b where
            a.i_company = b.i_company
            and a.i_saran_type= b.i_saran_type
            and a.i_company = '$i_company'
            and a.i_customer = '$i_customer'
            and a.d_saran = '$d_saran'
            and a.i_saran_type = '$i_saran_type'");

            if ($data->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'data' => $data->result_array(),
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function cekinsearchcustomerrrkh_post()
    {
        $i_company = $this->post('i_company');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Rute Hari ini');

            $sekarang = date('Y-m-d');
            $query = $this->db->query("select a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, c.n_customer_discount1, c.n_customer_discount2, a.i_area, a.latitude, a.longitude
            from tbl_customer a, tbl_customer_discount c, tbl_area b, tbl_rrkh d
            where a.i_customer = c.i_customer
            and a.i_company = c.i_company
            and a.i_area = b.i_area
            and a.i_company = b.i_company
            and a.i_company = d.i_company
            and a.i_area = d.i_area
            and a.i_customer = d.i_customer
            and a.i_area ='$i_area'
            and a.f_active = 'true'
            and a.i_company = '$i_company'
            and d.d_rrkh = '$sekarang'
            and d.username = '$username'");
            $list = array();
            $key = 0;

            foreach ($query->result() as $row) {
                $i_customer = $row->i_customer;
                $e_customer_name = $row->e_customer_name;
                $e_customer_address = $row->e_customer_address;
                $i_price_group = $row->i_price_group;
                $n_customer_discount1 = $row->n_customer_discount1;
                $n_customer_discount2 = $row->n_customer_discount2;
                $i_area = $row->i_area;
                $latitudecustomer = $row->latitude;
                $longitudecustomer = $row->longitude;

                $list[$key]['i_customer'] = $i_customer;
                $list[$key]['e_customer_name'] = $e_customer_name;
                $list[$key]['e_customer_address'] = $e_customer_address;
                $list[$key]['i_price_group'] = $i_price_group;
                $list[$key]['n_customer_discount1'] = $n_customer_discount1;
                $list[$key]['n_customer_discount2'] = $n_customer_discount2;
                $list[$key]['i_area'] = $i_area;
                $list[$key]['jarak'] = number_format(0, 0, ",", "") . " m";

                $key++;
            }
            $this->response([
                'status' => true,
                'data' => $list,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'data' => [],
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu !',
            ], REST_Controller::HTTP_OK);
        }
    }

}