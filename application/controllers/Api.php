<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller
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
        echo 'HAI API';
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

        $this->db->query("update tbl_customer_checkin set latitude_checkout = latitude_checkin, longitude_checkout = longitude_checkin, createdat_checkout = CAST(to_char(d_checkin,'yyyy-mm-dd')||' 23:59:00' as TIMESTAMP)
        where longitude_checkout IS NULL and d_checkin < CURRENT_DATE
        ");

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

    public function listcity_post()
    {

        $i_company = $this->post('i_company');
        $username = $this->post('username');
        $i_area = $this->post('i_area');

        $cek_city=$this->db->query (" dselect a.i_city, a.id_maps from tbl_city a, tbl_area b 
                            where a.i_company=b.i_company and a.id_maps=b.id_maps and a.i_company='$i_company' and a.f_active='t' and b.e_area_name='$i_area' ");
//        $cek_city = $this->db->get();

        if ($cek_city->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka daftar kota');

            $data = $this->db->query("sselect e_city_name as value from tbl_city where i_company = '$i_company'
            and f_active = 't' and id_maps in( select id_maps from tbl_area where i_company='$i_company' and f_active='t' and e_area_name='$i_area' )
            order by id_maps asc");

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
                'message' => 'Perusahaan Anda Tidak Terdaftar ! Silahkan Logout Dulu yaaaa !',
            ], REST_Controller::HTTP_OK);
        }

    }

    public function listtagihan_post()
    {
        $i_company = $this->post('i_company');
        $kodesales = $this->post('kodesales');
        $username = $this->post('username');

        if($i_company=='1'){
          $DB2 = $this->load->database('dgu', TRUE);
        }
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
        if ($cek_company->num_rows() > 0) {
            $i_company = $i_company;
            $this->Logger->write($i_company, $username, 'Apps Membuka Menu Sales Order');
            $data = $DB2->query("select b.e_customer_name, a.i_nota, a.v_sisa from tm_nota a, tr_customer b where not a.i_nota is null and a.f_nota_cancel='f' and a.i_salesman='$kodesales' 
            and a.v_sisa>0 and a.i_customer=b.i_customer order by a.i_nota asc");

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
            $DB2->close();

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
        $cari = str_replace("'", "''", strtoupper($this->post('cari')));
        $e_area_name = $this->post('i_area');
        $i_company = $this->post('i_company');
        $username = $this->post('username');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, coalesce(c.n_customer_discount1,0) as n_customer_discount1 , coalesce(c.n_customer_discount2,0) as n_customer_discount2, a.i_area ");
            $this->db->from("tbl_customer a");
            $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company", 'left');
            $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            $this->db->where("b.e_area_name", $e_area_name);
            $this->db->where("a.f_active", 'true');
            $this->db->where("a.i_company", $i_company);
            $this->db->where("(a.i_customer ilike '%$cari%' or a.e_customer_name ilike '%$cari%')");
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

        $cari_detail = explode(" ", $cari);

        //echo sizeof($pieces). '<br>'; // piece2

        $and = ' a.f_active = true ';
        foreach($cari_detail as $row) {
            $and .= " AND a.e_product_name ILIKE '%".$row."%' ";
        }

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
                $i_price_group = $this->db->query("select trim(i_price_group) as i_price_group from tbl_customer where i_customer = '$i_customer' and i_company = '$i_company'")->row()->i_price_group;

                $data_area = $this->db->query("select i_store, f_stock from tbl_area where i_company = '$i_company' and i_area = '$i_area'")->row();
                $i_store = $data_area->i_store;
                $f_stock = $data_area->f_stock;
                if ($i_company != '3' || $i_company != 3) {
                    if ($f_stock == 't') {
                        $query = $this->db->query("
                            SELECT a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            inner join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and trim(b.i_price_group) = '$i_price_group' and a.i_company = '$i_company' and a.f_active = true 
                            and (a.i_product ilike '%$cari%' or ($and))
                            order by a.e_product_name ASC
                        ");

                    } else {
                         $query = $this->db->query("
                            SELECT a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, 0 as n_quantity
                            from tbl_product a
                            inner join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '00'
                            where a.i_product_group = '$i_product_group' and trim(b.i_price_group) = '$i_price_group' and a.i_company = '$i_company' and a.f_active = true 
                            and (a.i_product ilike '%$cari%' or ($and))
                            order by a.e_product_name ASC
                        ");
                    }
                } else {
                    $i_price_group_new = substr($i_price_group,0,2)."00";
                    if ($f_stock == 't') {
                        $query = $this->db->query("
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and trim(b.i_price_group) = trim('$i_price_group')
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            union all
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group_new' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            and a.i_product not in (select a.i_product from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%'))
                        ");
                    } else {
                         $query = $this->db->query("
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group,  99 as n_quantity 
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            union all
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, 99 as n_quantity 
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group_new' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            and a.i_product not in (select a.i_product from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%'))
                        ");
                    }
                }
                

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
            $this->db->order_by("e_product_groupname", "asc");
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
                $n_customer_discount1 = (float) $data_diskon->n_customer_discount1;
                $n_customer_discount2 = (float) $data_diskon->n_customer_discount2;

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
                $n_customer_discount1 = (float) $data_diskon->n_customer_discount1;
                $n_customer_discount2 = (float) $data_diskon->n_customer_discount2;

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

            // $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, c.n_customer_discount1, c.n_customer_discount2, a.i_area, a.latitude, a.longitude ");
            // $this->db->from("tbl_customer a");
            // $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            // $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            // $this->db->where("a.i_area", $i_area);
            // $this->db->where("a.f_active", 'true');
            // $this->db->where("a.i_company", $i_company);
            // $this->db->where("(a.i_customer like '%$cari%' or a.e_customer_name like '%$cari%')");
            // // $this->db->like('a.i_customer', $cari);
            // // $this->db->or_like('a.e_customer_name', $cari);

            // $query = $this->db->get();
            $query = $this->db->query("select x.* from(
                select a.i_customer, a.e_customer_name, a.e_customer_address, a.i_price_group, c.n_customer_discount1, c.n_customer_discount2, a.i_area, a.latitude, a.longitude,
                (6371000 * acos(
                                cos( radians(CAST ( a.latitude AS numeric )) )
                              * cos( radians( CAST ( $latitude AS numeric) ) )
                              * cos( radians( CAST ( $longitude AS numeric)) - radians(CAST ( a.longitude AS numeric )) )
                              + sin( radians(CAST ( a.latitude AS numeric )) )
                              * sin( radians( CAST ( $latitude AS numeric) ) )
                                ) ) as distance
                from tbl_customer a, tbl_customer_discount c, tbl_area b
                where a.i_customer = c.i_customer and a.i_company = c.i_company
                and a.i_area = b.i_area and a.i_company = b.i_company
                and a.f_active = 't'
                and a.i_company = '$i_company'
                and a.i_area in(select i_area from tbl_user_area where username = '$username' and i_company = '$i_company')
                and (a.i_customer ilike '%$cari%' or a.e_customer_name ilike '%$cari%')
                and a.latitude != ''
                and a.longitude != ''
                ) as x
                where x.distance <= 1000");

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

                // $cek_jarak = $this->custom->hitung_jarak($latitude, $longitude, $latitudecustomer, $longitudecustomer);

                // if($cari == ''){
                // if ($cek_jarak <= 1000) {

                $list[$key]['i_customer'] = $i_customer;
                $list[$key]['e_customer_name'] = $e_customer_name;
                $list[$key]['e_customer_address'] = $e_customer_address;
                $list[$key]['i_price_group'] = $i_price_group;
                $list[$key]['n_customer_discount1'] = $n_customer_discount1;
                $list[$key]['n_customer_discount2'] = $n_customer_discount2;
                $list[$key]['i_area'] = $i_area;
                $list[$key]['jarak'] = number_format($row->distance, 0, ",", "") . " m";

                $key++;
                // }
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

            // $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude ");
            // $this->db->from("tbl_customer a");
            // $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            // $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            // $this->db->where("a.i_area", $i_area);
            // $this->db->where("a.f_active", 'true');
            // $this->db->where("a.i_company", $i_company);
            // $this->db->like('a.i_customer', $i_customer);
            // $query = $this->db->get();
            if ($i_company == '6') {
                $query = $this->db->query("
                    SELECT 'Kode Lang : ' || a.i_customer 
                    ||chr(10)|| 'Disc 1 : ' || c.n_customer_discount1 || CHR(10) || 'Disc 2 : ' || c.n_customer_discount2 
                    ||chr(10)|| d.e_price_groupname
                    as i_customer , a.e_customer_name, 
                    a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude from tbl_customer a
                    inner join tbl_customer_discount c on (a.i_customer = c.i_customer and a.i_company = c.i_company)
                    inner join tbl_area b on (a.i_area = b.i_area and a.i_company = b.i_company)
                    inner join tbl_price_group d on (a.i_price_group = d.i_price_group and a.i_company = d.i_company)
                    where a.i_area = '$i_area' and a.f_active = true and a.i_company = '$i_company' and a.i_customer like '$i_customer'
                ");
            } else {
                $query = $this->db->query("
                    SELECT a.i_customer 
                    ||chr(10)|| 'Disc 1 : ' || c.n_customer_discount1 || CHR(10) || 'Disc 2 : ' || c.n_customer_discount2 
                    ||chr(10)|| d.e_price_groupname
                    as i_customer , a.e_customer_name, 
                    a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude from tbl_customer a
                    inner join tbl_customer_discount c on (a.i_customer = c.i_customer and a.i_company = c.i_company)
                    inner join tbl_area b on (a.i_area = b.i_area and a.i_company = b.i_company)
                    inner join tbl_price_group d on (a.i_price_group = d.i_price_group and a.i_company = d.i_company)
                    where a.i_area = '$i_area' and a.f_active = true and a.i_company = '$i_company' and a.i_customer like '$i_customer'
                ");
            }
            
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


    public function detailcustomer_demo_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        // $i_customer = "24114";
        // $i_area = "24";
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $this->Logger->write($i_company, $username, 'Apps Membuka Informasi Pelanggan :' . $i_customer);

            // $this->db->select("a.i_customer, a.e_customer_name, a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude ");
            // $this->db->from("tbl_customer a");
            // $this->db->join("tbl_customer_discount c", "a.i_customer = c.i_customer and a.i_company = c.i_company");
            // $this->db->join("tbl_area b", "a.i_area = b.i_area and a.i_company = b.i_company");
            // $this->db->where("a.i_area", $i_area);
            // $this->db->where("a.f_active", 'true');
            // $this->db->where("a.i_company", $i_company);
            // $this->db->like('a.i_customer', $i_customer);
            // $query = $this->db->get();
            if ($i_company == '6') {

                $query = array();
                $key = 0;
                $cust_info = $this->db->query("
                    SELECT 'Kode Lang : ' || a.i_customer 
                    ||chr(10)|| 'Disc 1 : ' || c.n_customer_discount1 || CHR(10) || 'Disc 2 : ' || c.n_customer_discount2 
                    ||chr(10)|| d.e_price_groupname
                    as i_customer , a.e_customer_name, 
                    a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude from tbl_customer a
                    inner join tbl_customer_discount c on (a.i_customer = c.i_customer and a.i_company = c.i_company)
                    inner join tbl_area b on (a.i_area = b.i_area and a.i_company = b.i_company)
                    inner join tbl_price_group d on (a.i_price_group = d.i_price_group and a.i_company = d.i_company)
                    where a.i_area = '$i_area' and a.f_active = true and a.i_company = '$i_company' and a.i_customer like '$i_customer'
                ");

                foreach ($cust_info->result() as $riw) {
                    $query[$key]['i_customer'] = $riw->i_customer;
                    $query[$key]['e_customer_name'] = $riw->e_customer_name;
                    $query[$key]['e_customer_address'] = $riw->e_customer_address;
                    $query[$key]['e_area_name'] = $riw->e_area_name;
                    $query[$key]['i_area'] = $riw->i_area;
                    $query[$key]['latitude'] = $riw->latitude;
                    $query[$key]['longitude'] = $riw->longitude;
                    $key++;
                }

                 //list sub kategori
                $subkategori = $this->db->query("
                    select e_product_categoryname, rata
                    from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                    $$
                        with cte as (
                            select to_char(b.d_nota, 'yyyymm') as periode, e.e_product_categoryname  , sum(a.n_deliver) as total from tm_nota_item a
                            inner join tm_nota b on (a.i_nota = b.i_nota and a.i_area = b.i_area)
                            inner join tr_product c on (a.i_product = c.i_product)
                            inner join tr_product_category e on (c.i_product_category  = e.i_product_category)
                            where b.f_nota_cancel = false and b.d_nota >= (current_date - interval '6 month')::date and b.i_customer in ('$i_customer')
                            group by 1,2
                            order by 3 desc 
                        )
                        select e_product_categoryname, sum(total)/count(distinct periode) as total from cte group by 1 order by 2 desc
                    $$
                    ) AS nilai (
                        e_product_categoryname varchar, rata numeric
                    )
                ", FALSE);

                if ($subkategori->num_rows() > 0) {
                    $single = '';
                    foreach ($subkategori->result() as $sub) {
                        $single .= $sub->e_product_categoryname . "  (". number_format($sub->rata) . " pcs) \n";
                    }
                    $query[$key]['i_customer'] = $single;
                    $query[$key]['e_customer_name'] = "Rata Order By Sub Category";
                    $query[$key]['e_customer_address'] = "**data dari nota 6 bulan terakhir" ;
                    $key++;
                }

                $usertoko = $this->db->query("select username from tbl_user_toko_item where i_customer = '$i_customer' and id_company = '$i_company' limit 1");
                if ($usertoko->num_rows() > 0) {
                    $usertoko = $usertoko->row()->username;
                    $periode = date('Y');

                    $data = $this->db->query("
                         select a.username, a.e_name , b.i_customer, coalesce(c.v_nota_target,0) as v_nota_target, c.i_periode from tbl_user_toko a
                         inner join tbl_user_toko_item b on (a.username = b.username)
                         inner join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                         where a.username = '$usertoko' and c.i_periode = '$periode'
                    ", FALSE);

                    $total = 0;
                    $list_customer = array();
                    if ($data->num_rows() > 0) {
                        foreach ($data->result() as $row) {
                            $total += $row->v_nota_target;
                            array_push($list_customer, "'".$row->i_customer."'");
                        }
                        $arrayTxt = implode(',', $list_customer);

                        //list program
                        $data2 = $this->db->query("
                            select v_nota_netto, v_sisa, v_spb 
                            from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                            $$
                             select sum(v_nota_netto) as v_nota_netto , sum(v_sisa) as v_sisa , sum(v_spb) as v_spb from (
                                  select coalesce(sum(v_nota_netto), 0) as v_nota_netto , coalesce(sum(v_sisa), 0) as v_sisa, 0 as v_spb from tm_nota 
                                  where f_nota_cancel = false and to_char(d_nota , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                                  union all 
                                  select 0 as v_nota_netto , 0 as v_sisa, coalesce(sum(v_spb), 0) as v_spb from tm_spb 
                                  where f_spb_cancel = false and to_char(d_spb , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                            ) as x 
                            $$
                            ) AS nilai (
                                v_nota_netto numeric, v_sisa numeric, v_spb numeric
                            )
                        ", FALSE)->row();
                        $query[$key]['i_customer'] = "Tahun ". date('Y');
                        $query[$key]['e_customer_name'] = "Program Semarak 7th Omiland";
                        $query[$key]['e_customer_address'] = "Target : Rp. ". number_format($total) . "\n" . "Pencapaian : Rp. ".number_format($data2->v_nota_netto). "\n" . "Persentasi : " . number_format($data2->v_nota_netto / $total * 100,2) . " %\n"."Total Sisa Nota Belum Bayar : Rp. " .number_format($data2->v_sisa) ;
                        // $query[$key]['e_area_name'] = "";
                        // $query[$key]['i_area'] = "";
                        // $query[$key]['latitude'] = "";
                        // $query[$key]['longitude'] = "";
                        $key++;

                        



                        //list daftar tagihan
                        $item = $this->db->query("
                            select i_nota , e_color , d_nota, d_jatuh_tempo, v_nota_netto , v_sisa , v_bayar , e_remark
                            from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                            $$
                            with cte as (
                                 select i_customer , i_nota, i_sj , d_nota, d_jatuh_tempo , 
                                 current_date - d_jatuh_tempo as selisih,
                                 v_nota_netto , v_sisa , v_nota_netto - v_sisa as v_bayar from tm_nota
                                 where v_sisa > 0 and f_nota_cancel = false and to_char(d_nota , 'yyyy') = '$periode' and i_customer in ($arrayTxt) /*('02564', '02221', '02579', '02265')*/
                                 order by  d_jatuh_tempo asc,v_sisa desc
                            )
                            select coalesce(a.i_nota,'') || ' / ' || a.i_sj || ' [' || a.i_customer || ']' as i_nota, a.d_nota, a.d_jatuh_tempo , 
                                 case 
                                      when v_sisa > 0 and selisih between 1 and 7 then '#279b37'
                                      when v_sisa > 0 and selisih between 8 and 15 then '#ffdd00'
                                      when v_sisa > 0 and selisih > 15 then '#e4002b'
                                      else '#000000'
                                 end as e_color,a.v_nota_netto , a.v_sisa ,a.v_bayar , coalesce(b.e_remark1, '') || ' - ' || coalesce(b.e_remark2, '') as e_remark from cte a
                            left join (
                                 select distinct on (a.i_nota) a.i_nota , 
                                 coalesce(c.e_pelunasan_remark, '') as e_remark1, coalesce(a.e_remark,'') as e_remark2 from tm_pelunasan_item a
                                 inner join tm_pelunasan b on (a.i_pelunasan = b.i_pelunasan and a.i_area = b.i_area)
                                 left join tr_pelunasan_remark c on (a.i_pelunasan_remark = c.i_pelunasan_remark)
                                 where a.i_nota in (select i_nota from cte) and b.f_pelunasan_cancel = false 
                                 order by a.i_nota , b.d_entry desc
                            ) as b on a.i_nota = b.i_nota
                            $$
                            ) AS nilai (
                                 i_nota varchar(100), d_nota date, d_jatuh_tempo date, e_color varchar(20), v_nota_netto numeric, v_sisa numeric, v_bayar numeric, e_remark varchar(200)
                            )    
                        ", FALSE);

                        foreach ($item->result() as $list) {
                            $query[$key]['i_customer'] = "Jatuh Tempo ". $list->d_jatuh_tempo;
                            $query[$key]['e_customer_name'] = $list->i_nota;
                            $query[$key]['e_customer_address'] = "Sisa Rp. " . number_format($list->v_sisa) . " Dari Total Rp. " . number_format($list->v_nota_netto);
                            $key++;
                        }

                    }





                }

            } else {
                $query = $this->db->query("
                    SELECT a.i_customer 
                    ||chr(10)|| 'Disc 1 : ' || c.n_customer_discount1 || CHR(10) || 'Disc 2 : ' || c.n_customer_discount2 
                    ||chr(10)|| d.e_price_groupname
                    as i_customer , a.e_customer_name, 
                    a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude from tbl_customer a
                    inner join tbl_customer_discount c on (a.i_customer = c.i_customer and a.i_company = c.i_company)
                    inner join tbl_area b on (a.i_area = b.i_area and a.i_company = b.i_company)
                    inner join tbl_price_group d on (a.i_price_group = d.i_price_group and a.i_company = d.i_company)
                    where a.i_area = '$i_area' and a.f_active = true and a.i_company = '$i_company' and a.i_customer like '$i_customer'
                ")->result_array();
            }
            
            // echo sizeof($query);
            // die();

            if (sizeof($query) > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query,
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

    
    //Baru dari sini
    public function customerinformation_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        // $i_customer = "24114";
        // $i_area = "24";
        $this->db->select("*");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $db_host =  $cek_company->row()->db_host;
            $db_user =  $cek_company->row()->db_user;
            $db_password =  $cek_company->row()->db_password;
            $db_name =  $cek_company->row()->db_name;
            $db_port =  $cek_company->row()->db_port;
            // var_dump($db_host);
            // die();

            $this->Logger->write($i_company, $username, 'Apps Membuka Informasi Pelanggan :' . $i_customer);

            if ($i_company == '6' || ($i_company == '1' && $username == 'admin') || ($i_company == '7' && $username == 'admin') ) {

                $query = array();
                $key = 0;
                $cust_info = $this->db->query("
                    SELECT * FROM
                     dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                     $$
                     with cte as (
                        select array[
                            a.i_customer ,a.e_customer_name, coalesce(b.e_customer_groupname,'-'), c.e_customer_classname, (d.i_price_group),
                            e.n_customer_discount1::char(5) || '% , ' || e.n_customer_discount2::char(5) || '%',  f.e_customer_ownername || ' - ' || f.e_customer_ownerphone ,
                            to_char(g.v_flapond, 'FMRp 999,999,999,990D00')::text, a.n_customer_toplength || ' Hari', case when f_customer_pkp = true then 'PKP' else 'Non PKP' end
                            ] as e_customer_name from tr_customer a
                        left join tr_customer_group b on (a.i_customer = b.i_customer_group)
                        inner join tr_customer_class c on (a.i_customer_class = c.i_customer_class)
                        inner join tr_price_group d on (a.i_price_group = d.i_price_group)
                        inner join tr_customer_discount e on (a.i_customer = e.i_customer)
                        left join tr_customer_owner f on (a.i_customer = f.i_customer)
                        left join tr_customer_groupar g on (a.i_customer = g.i_customer)
                        where a.i_customer = '$i_customer'
                    )
                    /*SELECT * FROM
                      unnest(
                        ARRAY['Kode Customer', 'Nama Customer'],
                        (select e_customer_name from cte)
                      ) AS data(i_customer,e_customer_name);*/
                    SELECT un1.val::text as i_customer, un2.val::text as e_customer_name
                    FROM unnest(ARRAY['Kode Customer', 'Nama Customer', 'Group Pelanggan', 'Tipe', 'Kode Harga', 'Diskon', 'Kontak', 'Plafon', 'TOP', 'Status PKP']) WITH ORDINALITY un1 (val, ord)
                    FULL JOIN unnest((select e_customer_name from cte)) WITH ORDINALITY un2 (val, ord) ON un2.ord = un1.ord;
                     $$
                     ) AS datas (
                          i_customer text,
                          e_customer_name text
                     ) 
                ");

                foreach ($cust_info->result() as $riw) {
                    $query[$key]['i_customer'] = $riw->i_customer;
                    $query[$key]['e_customer_name'] = $riw->e_customer_name;
                    $key++;
                }

            } else {
                $query = $this->db->query("
                    SELECT a.i_customer 
                    ||chr(10)|| 'Disc 1 : ' || c.n_customer_discount1 || CHR(10) || 'Disc 2 : ' || c.n_customer_discount2 
                    ||chr(10)|| d.e_price_groupname
                    as i_customer , a.e_customer_name, 
                    a.e_customer_address, b.e_area_name, a.i_area, a.latitude, a.longitude from tbl_customer a
                    inner join tbl_customer_discount c on (a.i_customer = c.i_customer and a.i_company = c.i_company)
                    inner join tbl_area b on (a.i_area = b.i_area and a.i_company = b.i_company)
                    inner join tbl_price_group d on (a.i_price_group = d.i_price_group and a.i_company = d.i_company)
                    where a.i_area = '$i_area' and a.f_active = true and a.i_company = '$i_company' and a.i_customer like '$i_customer'
                ")->result_array();
            }
            
            // echo sizeof($query);
            // die();

            if (sizeof($query) > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query,
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


    public function targettoko_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        // $i_customer = "02171";
        // $i_area = "02";
        $this->db->select("*");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0 ) {

            $i_company = $i_company;
            $username = $username;
            $db_host =  $cek_company->row()->db_host;
            $db_user =  $cek_company->row()->db_user;
            $db_password =  $cek_company->row()->db_password;
            $db_name =  $cek_company->row()->db_name;
            $db_port =  $cek_company->row()->db_port;
            // var_dump($db_host);
            // die();

            $this->Logger->write($i_company, $username, 'Apps Membuka Customer Card :' . $i_customer);

            $query = array();
            $key = 0;

            $customerHead = $this->db->query("select i_customer, e_customer_name, e_customer_address from tbl_customer where i_customer = '$i_customer' and i_company = '$i_company';")->row();
            $query['head']['i_customer'] = $customerHead->i_customer;
            $query['head']['e_customer_name'] = $customerHead->e_customer_name;
            $query['head']['e_customer_address'] = $customerHead->e_customer_address;

            if ($i_company == '6' || ($i_company == '1' && $username == 'admin') || ($i_company == '7' && $username == 'admin')) {
                $usertoko = $this->db->query("select username from tbl_user_toko_item where i_customer = '$i_customer' and id_company = '$i_company' limit 1");
                if ($usertoko->num_rows() > 0) {
                    $usertoko = $usertoko->row()->username;
                    $periode = date('Y');

                    $data = $this->db->query("
                         select a.username, a.e_name , b.i_customer, coalesce(c.v_nota_target,0) as v_nota_target, c.i_periode from tbl_user_toko a
                         inner join tbl_user_toko_item b on (a.username = b.username)
                         inner join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
                         where a.username = '$usertoko' and c.i_periode = '$periode'
                    ", FALSE);

                    $total = 0;
                    $list_customer = array();
                    if ($data->num_rows() > 0) {
                        foreach ($data->result() as $row) {
                            $total += $row->v_nota_target;
                            array_push($list_customer, "'".$row->i_customer."'");
                        }
                        $arrayTxt = implode(',', $list_customer);

                        //list program
                        $data2 = $this->db->query("
                            select v_nota_netto, v_sisa, v_spb 
                            from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                            $$
                             select sum(v_nota_netto) as v_nota_netto , sum(v_sisa) as v_sisa , sum(v_spb) as v_spb from (
                                  select coalesce(sum(v_nota_netto), 0) as v_nota_netto , coalesce(sum(v_sisa), 0) as v_sisa, 0 as v_spb from tm_nota 
                                  where f_nota_cancel = false and to_char(d_nota , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                                  union all 
                                  select 0 as v_nota_netto , 0 as v_sisa, coalesce(sum(v_spb), 0) as v_spb from tm_spb 
                                  where f_spb_cancel = false and to_char(d_spb , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                            ) as x 
                            $$
                            ) AS nilai (
                                v_nota_netto numeric, v_sisa numeric, v_spb numeric
                            )
                        ", FALSE)->row();
                        $query['head']['i_periode'] = "Tahun ". date('Y');
                        $query['head']['e_name'] = $data->row()->e_name;
                        $query['head']['e_title'] = "Target Toko ";
                        $query['head']['v_target'] = "Rp. ". number_format($total);
                        $query['head']['v_pencapaian'] = "Rp. ". number_format($data2->v_nota_netto);
                        $query['head']['v_persentasi'] = number_format($data2->v_nota_netto / $total * 100,2) . "";
                        $query['head']['v_sisa'] = "Rp. ". number_format($data2->v_sisa);
                        //$key++;
                    }
                }

                $datanota = $this->db->query("
                    select v_nota_netto FROM
                     dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                     $$
                         with cte as (
                             select (to_char(to_char(current_date - interval '11 Month', 'yyyy-mm-01')::date + (interval '1' month * generate_series(0,11)), 'yyyymm')) as mon
                           )
                           select json_agg(coalesce(v_nota_netto,0)) as v_nota_netto  from cte a
                           left join (
                               SELECT i_customer, to_char(d_nota, 'yyyymm') as mon , sum(v_nota_netto/1000000)::numeric(15,6) as v_nota_netto  from tm_nota 
                               where i_customer = '$i_customer' and f_nota_cancel = false and d_nota between to_char(current_date - interval '11 Month', 'yyyy-mm-01')::date and current_date
                               group by 1,2
                               order by 2 asc
                           ) as b on (a.mon = b.mon )
                     $$
                     ) AS datas (
                          v_nota_netto json
                     ) 
                ", FALSE)->row();

                // $query['detail'] =  str_replace(']', '', str_replace('[', '', $datanota->datanota));

                $labelnota = $this->db->query("select json_agg(mon) as mon from (
                                select (to_char(to_char(current_date - interval '11 Month', 'yyyy-mm-01')::date + (interval '1' month * generate_series(0,11)), 'Mon')) as mon
                            ) as x")->row();
                $query['chart']['labels'] =  json_decode($labelnota->mon, TRUE);
                //$query['chart']['datasets'] =  array(array('data' => array(11,22,33,44,55,66,77,88,99,10,11,12)));
                $query['chart']['datasets'] =  array(
                                                    array(
                                                        'data' => json_decode($datanota->v_nota_netto, TRUE)
                                                    ),
                                                );


                // $kategori = $this->db->query("
                //     select e_product_classname, total 
                //     from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                //     $$
                //         with cte as (
                //             select to_char(b.d_nota, 'yyyymm') as periode, d.e_product_classname  , sum(a.n_deliver) as total from tm_nota_item a
                //             inner join tm_nota b on (a.i_nota = b.i_nota and a.i_area = b.i_area)
                //             inner join tr_product c on (a.i_product = c.i_product)
                //             inner join tr_product_class d on (c.i_product_class = d.i_product_class)
                //             where b.f_nota_cancel = false and b.d_nota >= (current_date - interval '6 month')::date and b.i_customer in ('$i_customer')
                //             group by 1,2
                //             order by 3 desc 
                //         )
                //         select e_product_classname, sum(total) as total from cte group by 1 order by 2 desc
                //     $$
                //     ) AS nilai (
                //         e_product_classname varchar, total numeric
                //     )
                // ", FALSE);

                // if ($kategori->num_rows() > 0) {
                //     $key=0;
                //     $max = array_sum(array_column($kategori->result_array(),'total'));
                //     foreach ($kategori->result() as $kat) {
                //         // if ($key == 0) {
                //         //     $max = $kat->total;
                //         // }
                //         $query['kategori'][$key]['kategori'] = $kat->e_product_classname;
                //         $query['kategori'][$key]['max'] = $max;
                //         $query['kategori'][$key]['total'] = $kat->total;
                //         $query['kategori'][$key]['progress'] = $kat->total/$max;
                //         $key++;
                //     }
                    
                // }

                $subkategori = $this->db->query("
                    select e_product_categoryname, total 
                    from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                    $$
                        with cte as (
                            select to_char(b.d_nota, 'yyyymm') as periode, e.e_product_categoryname  , sum(a.n_deliver) as total from tm_nota_item a
                            inner join tm_nota b on (a.i_nota = b.i_nota and a.i_area = b.i_area)
                            inner join tr_product c on (a.i_product = c.i_product)
                            inner join tr_product_category e on (c.i_product_category  = e.i_product_category)
                            where b.f_nota_cancel = false and b.d_nota >= (current_date - interval '6 month')::date and b.i_customer in ('$i_customer')
                            group by 1,2
                            order by 3 desc 
                        )
                        select e_product_categoryname, sum(total) as total from cte group by 1 order by 2 desc
                    $$
                    ) AS nilai (
                        
                        e_product_categoryname varchar, total numeric
                    )
                ", FALSE);

                if ($subkategori->num_rows() > 0) {
                    $key=0;
                    $max = array_sum(array_column($subkategori->result_array(),'total'));
                    foreach ($subkategori->result() as $kat) {
                        $query['subkategori'][$key]['subkategori'] = $kat->e_product_categoryname;
                        $query['subkategori'][$key]['max'] = $max;
                        $query['subkategori'][$key]['total'] = $kat->total;
                        $query['subkategori'][$key]['progress'] = $kat->total/$max;
                        $key++;
                    }
                    
                }

                // $seri = $this->db->query("
                //     select e_product_seriname, total 
                //     from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                //     $$
                //         with cte as (
                //             select to_char(b.d_nota, 'yyyymm') as periode, d.e_product_seriname , sum(a.n_deliver) as total from tm_nota_item a
                //             inner join tm_nota b on (a.i_nota = b.i_nota and a.i_area = b.i_area)
                //             inner join tr_product c on (a.i_product = c.i_product)
                //             inner join tr_product_seri d on (c.i_product_seri  = d.i_product_seri)
                //             where b.f_nota_cancel = false and b.d_nota >= (current_date - interval '6 month')::date and b.i_customer in ('$i_customer')
                //             group by 1,2
                //             order by 3 desc 
                //         )
                //         select e_product_seriname, sum(total) as total from cte group by 1 order by 2 desc
                //     $$
                //     ) AS nilai (
                //         e_product_seriname varchar, total numeric
                //     )
                // ", FALSE);

                // if ($seri->num_rows() > 0) {
                //     $key=0;
                //     $max = array_sum(array_column($seri->result_array(),'total'));
                //     foreach ($seri->result() as $row) {
                //         $query['seri'][$key]['seri'] = $row->e_product_seriname;
                //         $query['seri'][$key]['max'] = $max;
                //         $query['seri'][$key]['total'] = $row->total;
                //         $query['seri'][$key]['progress'] = $row->total/$max;
                //         $key++;
                //     }
                    
                // }

            }


            
            
            // echo sizeof($query);
            // die();

            if (sizeof($query) > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query,
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



    public function piutang_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

         // $i_customer = "02171";
        // $i_area = "02";
        $this->db->select("*");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $i_company = $i_company;
            $username = $username;
            $db_host =  $cek_company->row()->db_host;
            $db_user =  $cek_company->row()->db_user;
            $db_password =  $cek_company->row()->db_password;
            $db_name =  $cek_company->row()->db_name;
            $db_port =  $cek_company->row()->db_port;
            // var_dump($db_host);
            // die();
            $this->Logger->write($i_company, $username, 'Apps Membuka Informasi Pelanggan :' . $i_customer);

            $query = array();

            $query['list'] = null; 
            $key = 0;

            if ($i_company == '6') {
        
                //list daftar tagihan
                $item = $this->db->query("
                    select i_nota , e_color, e_icon , d_nota, d_jatuh_tempo, v_nota_netto , v_sisa , v_bayar , e_remark
                    from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                    $$
                    with cte as (
                         select i_customer , i_nota, i_sj , d_nota, d_jatuh_tempo , 
                         current_date - d_jatuh_tempo as selisih,
                         v_nota_netto , v_sisa , v_nota_netto - v_sisa as v_bayar from tm_nota
                         where d_nota is not null and v_sisa > 0 and f_nota_cancel = false and i_customer in ('$i_customer') /*('02564', '02221', '02579', '02265')*/
                         order by  d_jatuh_tempo asc,v_sisa desc
                    )
                    select coalesce(a.i_nota,'') || ' / ' || a.i_sj as i_nota, a.d_nota, a.d_jatuh_tempo , 
                         case 
                              when v_sisa > 0 and selisih between 1 and 7 then '#279b37'
                              when v_sisa > 0 and selisih between 8 and 15 then '#ffdd00'
                              when v_sisa > 0 and selisih > 15 then '#e4002b'
                              else '#000000'
                         end as e_color,
                         case 
                              when v_sisa > 0 and selisih between 1 and 7 then 'warning'
                              when v_sisa > 0 and selisih between 8 and 15 then 'warning'
                              when v_sisa > 0 and selisih > 15 then 'warning'
                              else 'info'
                         end as e_icon
                         ,a.v_nota_netto , a.v_sisa ,a.v_bayar , coalesce(b.e_remark1, '') || ' - ' || coalesce(b.e_remark2, '') as e_remark from cte a
                    left join (
                         select distinct on (a.i_nota) a.i_nota , 
                         coalesce(c.e_pelunasan_remark, '') as e_remark1, coalesce(a.e_remark,'') as e_remark2 from tm_pelunasan_item a
                         inner join tm_pelunasan b on (a.i_pelunasan = b.i_pelunasan and a.i_area = b.i_area)
                         left join tr_pelunasan_remark c on (a.i_pelunasan_remark = c.i_pelunasan_remark)
                         where a.i_nota in (select i_nota from cte) and b.f_pelunasan_cancel = false 
                         order by a.i_nota , b.d_entry desc
                    ) as b on a.i_nota = b.i_nota
                    order by d_jatuh_tempo asc
                    $$
                    ) AS nilai (
                         i_nota varchar(100), d_nota date, d_jatuh_tempo date, e_color varchar(20), e_icon varchar(20),v_nota_netto numeric, v_sisa numeric, v_bayar numeric, e_remark varchar(200)
                    )    
                ", FALSE);

                $saldo_piutang = 0;
                foreach ($item->result() as $list) {
                    $saldo_piutang += $list->v_sisa;
                    $query['list'][$key]['d_jatuh_tempo'] = "Jatuh Tempo : ". $list->d_jatuh_tempo;
                    $query['list'][$key]['i_nota'] = $list->i_nota;
                    $query['list'][$key]['v_sisa'] = number_format($list->v_sisa);
                    $query['list'][$key]['v_netto'] = number_format($list->v_nota_netto);
                    $query['list'][$key]['e_color'] = $list->e_color;
                    $query['list'][$key]['e_icon'] = $list->e_icon;
                    $query['list'][$key]['e_remark'] = $list->e_remark;
                    $query['list'][$key]['e_text'] = $list->e_remark;
                    $key++;
                }

                $header = $this->db->query("
                        with cte as (
                             select array[i_customer, e_customer_name, n_customer_toplength::text || ' Hari', sumketerlambatan::text || ' Hari', nota_count::text , rata_keterlambatan::text || ' Hari' ,
                             case when is_normal = true then 'Wajar' else 'Tidak Wajar' end, to_char(v_flapond, 'FMRp 999,999,999,990D00')::text, to_char(v_flapond - $saldo_piutang, 'FMRp 999,999,999,990D00')::text] as e_data 
                             from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                             $$
                                select x3.i_customer, x3.e_customer_name, x3.n_customer_toplength, sumketerlambatan, nota_count, rata_keterlambatan,
                                case
                                    when n_customer_toplength = 0 then false
                                    when (n_customer_toplength >= 30 and n_customer_toplength <= 35) and rata_keterlambatan <= 15 then true
                                    when n_customer_toplength = 45 and rata_keterlambatan <= 15 then true
                                    when n_customer_toplength = 60 and rata_keterlambatan <= 10 then true
                                    else false
                                end as is_normal, b.v_flapond 
                                    from(
                                    select i_customer, e_customer_name, n_customer_toplength, sum(sumketerlambatan) sumketerlambatan, sum(nota_count) nota_count,
                                        round((sum(sumketerlambatan)/sum(nota_count))) rata_keterlambatan from(
                                        select row_number() over(partition by i_customer order by substring(i_nota, 1,7) desc) as rownumber, i_customer, e_customer_name, n_customer_toplength
                                            , substring(i_nota, 1,7) inota
                                            , sum(1) as nota_count
                                            , sum(d_cair-d_sj_receive-n_customer_toplength) as sumketerlambatan
                                            from(
                                            select a.i_customer, c.e_customer_name, a.i_nota, c.n_customer_toplength, a.v_sisa,
                                            case
                                                when b.i_jenis_bayar = '01' then g.d_giro_cair
                                                when b.i_jenis_bayar = '03' then kum.d_kum
                                                when b.i_jenis_bayar != '01' and b.i_jenis_bayar != '03' then b.d_bukti
                                            end as d_cair,
                                            case
                                                when a.d_sj_receive is null then a.d_nota
                                                when a.d_sj_receive is not null then a.d_sj_receive
                                            end as d_sj_receive
                                            from tm_nota a
                                            inner join (
                                                select pli.i_pelunasan, pli.i_area, pli.i_nota, pli.v_jumlah, pl.i_giro, pl.d_giro, pl.d_bukti, pl.d_cair, i_jenis_bayar
                                                from tm_pelunasan_item pli
                                                inner join tm_pelunasan pl on (pli.i_pelunasan=pl.i_pelunasan and pli.i_area=pl.i_area)
                                                where (pl.i_jenis_bayar='02' and pl.v_jumlah > 10000) or (pl.i_jenis_bayar not in('02','04','10'))
                                            ) b on(a.i_nota=b.i_nota and a.i_area=b.i_area)
                                            left join tm_kum kum on(kum.i_kum=b.i_giro and kum.i_area=b.i_area)
                                            left join tm_giro g on(g.i_giro=b.i_giro and g.i_area=b.i_area)
                                            left  join tr_customer c on(a.i_customer=c.i_customer)
                                            where f_nota_cancel='f' and c.f_customer_aktif='t' and a.i_customer = '$i_customer'
                                            order by 1, 3
                                        ) x1
                                        group by 2, 3, 4, 5
                                    ) x2
                                    where rownumber <= 6
                                    group by 1, 2, 3
                                ) x3
                                inner join tr_customer_groupar b on (x3.i_customer = b.i_customer)  
                             $$
                             ) AS datas (
                                  i_customer varchar(255), e_customer_name varchar(255), n_customer_toplength numeric, sumketerlambatan numeric , nota_count numeric , 
                                  rata_keterlambatan numeric ,is_normal boolean, v_flapond numeric
                             ) 
                        )
                        SELECT un1.val::text as e_label, un2.val::text as e_data
                        FROM unnest(ARRAY['Kode Customer', 'Nama Customer', 'TOP', 'Total Keterlambatan', 'Jumlah Nota', 'Rata - Rata Keterlambatan', 'TOP Terhadap Rata Rata', 'Plafon', 'Limit']) WITH ORDINALITY un1 (val, ord)
                        FULL JOIN unnest((select e_data from cte)) WITH ORDINALITY un2 (val, ord) ON un2.ord = un1.ord;
                    ", false);
                

                if ($header->num_rows() > 0) {
                    $key = 0;
                    foreach($header->result() as $row) {
                         $query['head'][$key]['e_label'] = $row->e_label;
                         $query['head'][$key]['e_data'] = $row->e_data;
                         $key++;
                    }
                }


            } else if ( ($i_company == '1' && $username == 'admin') || ($i_company == '7' && $username == 'admin') ) {

                 //list daftar tagihan
                $item = $this->db->query("
                    select i_nota , e_color, e_icon , d_nota, d_jatuh_tempo, v_nota_netto , v_sisa , v_bayar , e_remark
                    from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                    $$
                    with cte as (
                         select i_customer , i_nota, i_sj , d_nota, d_jatuh_tempo , 
                         current_date - d_jatuh_tempo as selisih,
                         v_nota_netto , v_sisa , v_nota_netto - v_sisa as v_bayar from tm_nota
                         where d_nota is not null and v_sisa > 0 and f_nota_cancel = false and i_customer in ('$i_customer') /*('02564', '02221', '02579', '02265')*/
                         order by  d_jatuh_tempo asc,v_sisa desc
                    )
                    select coalesce(a.i_nota,'') || ' / ' || a.i_sj as i_nota, a.d_nota, a.d_jatuh_tempo , 
                         case 
                              when v_sisa > 0 and selisih between 1 and 7 then '#279b37'
                              when v_sisa > 0 and selisih between 8 and 15 then '#ffdd00'
                              when v_sisa > 0 and selisih > 15 then '#e4002b'
                              else '#000000'
                         end as e_color,
                         case 
                              when v_sisa > 0 and selisih between 1 and 7 then 'warning'
                              when v_sisa > 0 and selisih between 8 and 15 then 'warning'
                              when v_sisa > 0 and selisih > 15 then 'warning'
                              else 'info'
                         end as e_icon
                         ,a.v_nota_netto , a.v_sisa ,a.v_bayar , b.e_remark from cte a
                    left join (
                         select distinct on (a.i_nota) a.i_nota , 
                         coalesce(a.e_remark , '') as e_remark from tm_alokasi_item a
                         inner join tm_alokasi b on (a.i_alokasi  = b.i_alokasi and a.i_area = b.i_area and a.i_kbank = b.i_kbank)
                         where a.i_nota in (select i_nota from cte) and b.f_alokasi_cancel = false 
                         order by a.i_nota , b.d_entry desc
                    ) as b on a.i_nota = b.i_nota
                    order by d_jatuh_tempo asc
                    $$
                    ) AS nilai (
                         i_nota varchar(100), d_nota date, d_jatuh_tempo date, e_color varchar(20), e_icon varchar(20),v_nota_netto numeric, v_sisa numeric, v_bayar numeric, e_remark varchar(200)
                    )    
                ", FALSE);

                $saldo_piutang = 0;
                foreach ($item->result() as $list) {
                    $saldo_piutang += $list->v_sisa;
                    $query['list'][$key]['d_jatuh_tempo'] = "Jatuh Tempo : ". $list->d_jatuh_tempo;
                    $query['list'][$key]['i_nota'] = $list->i_nota;
                    $query['list'][$key]['v_sisa'] = number_format($list->v_sisa);
                    $query['list'][$key]['v_netto'] = number_format($list->v_nota_netto);
                    $query['list'][$key]['e_color'] = $list->e_color;
                    $query['list'][$key]['e_icon'] = $list->e_icon;
                    $query['list'][$key]['e_remark'] = $list->e_remark;
                    $query['list'][$key]['e_text'] = $list->e_remark;
                    $key++;
                }

                $header = $this->db->query("
                        with cte as (
                             select array[i_customer, e_customer_name, n_customer_toplength::text || ' Hari', nota_count::text , rata_keterlambatan::text || ' Hari' ,
                             is_normal, to_char(v_flapond, 'FMRp 999,999,999,990D00')::text, to_char(v_limit ,'FMRp 999,999,999,990D00')::text] as e_data 
                             from dblink('host=$db_host user=$db_user password=$db_password dbname=$db_name port=$db_port',
                             $$
                                select a.i_customer, a.e_customer_name, a.n_customer_toplength, coalesce(c.jumlah_nota,0) as jumlah_nota, coalesce(b.n_ratatelat, 0) as n_ratatelat, 
                                case
                                    when n_customer_toplength = 0 then 'Tidak Wajar'
                                    when (n_customer_toplength >= 30 and n_customer_toplength <= 35) and coalesce(b.n_ratatelat, 0) <= 15 then 'Wajar'
                                    when n_customer_toplength = 45 and coalesce(b.n_ratatelat, 0) <= 15 then 'Wajar'
                                    when n_customer_toplength = 60 and coalesce(b.n_ratatelat, 0) <= 10 then 'Wajar'
                                    else 'Tidak Wajar'
                                end as is_normal,
                                coalesce(b.v_flapond, 0) as v_flapond,  coalesce(b.v_saldo, 0) as limit from tr_customer a 
                                left join tr_customer_groupar b on (a.i_customer = b.i_customer)
                                left join (
                                    select i_customer, count(i_customer) as jumlah_nota  from tm_nota where f_nota_cancel = false and i_customer = '$i_customer' and to_char(d_nota, 'yyyy') = to_char(current_date, 'yyyy') group by 1
                                ) as c on (a.i_customer = c.i_customer)
                                where a.i_customer = '$i_customer'
                             $$
                             ) AS datas (
                                  i_customer varchar(255), e_customer_name varchar(255), n_customer_toplength numeric, nota_count numeric , 
                                  rata_keterlambatan numeric ,is_normal varchar(255), v_flapond numeric, v_limit numeric
                             ) 
                        )
                        SELECT un1.val::text as e_label, un2.val::text as e_data
                        FROM unnest(ARRAY['Kode Customer', 'Nama Customer', 'TOP', 'Jumlah Nota ' || to_char(current_date, 'yyyy'), 'Rata - Rata Keterlambatan', 'TOP Terhadap Rata Rata', 'Plafon', 'Limit']) WITH ORDINALITY un1 (val, ord)
                        FULL JOIN unnest((select e_data from cte)) WITH ORDINALITY un2 (val, ord) ON un2.ord = un1.ord;
                    ", false);
                

                if ($header->num_rows() > 0) {
                    $key = 0;
                    foreach($header->result() as $row) {
                         $query['head'][$key]['e_label'] = $row->e_label;
                         $query['head'][$key]['e_data'] = $row->e_data;
                         $key++;
                    }
                }
            }
            
            // echo sizeof($query);
            // die();

            if (sizeof($query) > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query,
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
                'message' => 'Perusahaan Tidak Terdaftar / Tidak Aktif',
            ], REST_Controller::HTTP_OK);
        }
    }


    public function infolainnya_post()
    {
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $i_area = $this->post('i_area');
        $username = $this->post('username');

        $i_customer = "02171";
        $i_area = "02";
        $this->db->select("*");
        $this->db->from("tbl_company");
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($cek_company->num_rows() > 0) {

            $this->Logger->write($i_company, $username, 'Apps Membuka Informasi Lain Lain :');

            $query = $this->db->query("
                SELECT a.e_title , a.e_deskripsi, 'Informasi Ini Aktif Sampai ' || to_char(a.d_end, 'dd FMMonth yyyy') as e_aktif, 
                b.e_icon from tbl_information a
                inner join tbl_information_type b on (a.id_type = b.id)
                where a.i_company = '$i_company' and f_active = true and current_date between d_start and d_end 
                order by d_end asc, a.id asc
            ",false)->result();

            if (sizeof($query) > 0) {
                $this->response([
                    'status' => true,
                    'data' => $query,
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
                'message' => 'Perusahaan Tidak Terdaftar / Tidak Aktif',
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

        $basic = $this->post('basic') ? $this->post('basic') : false;
        $card = $this->post('card') ? $this->post('card') : false;
        $piutang = $this->post('piutang') ? $this->post('piutang') : false;
        $infolainnya = $this->post('infolainnya') ? $this->post('infolainnya') : false;
        $sales = $this->post('sales') ? $this->post('sales') : false;

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
                'basic' => $basic,
                'card' => $card,
                'piutang' => $piutang,
                'infolainnya' => $infolainnya,
                'sales' => $sales,
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

        $type = $this->post('type') ? $this->post('type') : null ;
        $detail = $this->post('detail') ? $this->post('detail') : null;

        if ($type) {
            $type = $this->db->query("SELECT id from tbl_dokumentasi_type where e_dokumentasi_name = '$type' ")->row()->id;
        }

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
                    'id_dokumentasi_type' => $type,
                    'e_detail' => $detail,
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


    public function listdokumentasitype_post()
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
            $this->Logger->write($i_company, $username, 'Apps List Dokumentasi Type');

            $this->db->select("e_dokumentasi_name as value");
            $this->db->from("tbl_dokumentasi_type");
            $this->db->order_by("n_order", "asc");
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
            $cek_data = $this->db->query("select username, e_saran from tbl_customer_saran where i_company = '$i_company' and i_saran_type = '$i_saran_type' and i_customer = '$i_customer' and d_saran = '$tgl_sekarang'");

            if ($cek_data->num_rows() > 0) {
                $e_saran = $e_saran . " & ". $cek_data->row()->e_saran;
                $this->db->query("update tbl_customer_saran set e_saran = '$e_saran' where i_company = '$i_company' and i_saran_type = '$i_saran_type' and i_customer = '$i_customer' and d_saran = '$tgl_sekarang' ");
                $this->response([
                    'status' => true,
                    'message' => 'Data Berhasil Di Update !',
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
            and a.i_area in (select i_area from tbl_user_area tua where username = '$username' and i_company = '$i_company')
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


    public function koordinat_post()
    {
        echo "string";('kesini');
        // $data = $this->db->query("
        //     Select * from tr_customer_tmp limit 2
        // ")->result();

        // foreach ($data as $row) {
        //     echo $row->i_customer. ' | '. $row->e_customer_name. ' | '. $row->e_area_name. ' | '. $row->i_company. ' | ';
        //     # code...
        // }

    }


    public function searchproductcoba_post()
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

        $cari_detail = explode(" ", $cari);

        //echo sizeof($pieces). '<br>'; // piece2

        $and = ' a.f_active = true ';
        foreach($cari_detail as $row) {
            $and .= " AND a.e_product_name ILIKE '%".$row."%' ";
        }


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
                if ($i_company != '3' || $i_company != 3) {
                    if ($f_stock == 't') {

                        $query = $this->db->query("
                            SELECT a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            inner join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '00'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' and a.i_company = '$i_company' and a.f_active = true 
                            and (a.i_product ilike '%$cari%' or ($and))
                            order by a.e_product_name ASC
                        ");

                    } else {
                         $query = $this->db->query("
                            SELECT a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            inner join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '00'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' and a.i_company = '$i_company' and a.f_active = true 
                            and (a.i_product ilike '%$cari%' or ($and))
                            order by a.e_product_name ASC
                        ");
                    }

                    //$query = $this->db->get();
                } else {
                    $i_price_group_new = substr($i_price_group,0,2)."00";
                    if ($f_stock == 't') {
                        $query = $this->db->query("
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            union all
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, coalesce(c.n_quantity,0) as n_quantity
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group_new' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            and a.i_product not in (select a.i_product from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            left join tbl_ic c on b.i_product = c.i_product and b.i_company = c.i_company and c.i_store = '$i_store'
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%'))
                        ");
                    } else {
                         $query = $this->db->query("
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group,  99 as n_quantity 
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            union all
                            select a.i_product, a.i_product_group, a.e_product_name, b.v_product_price, b.i_price_group, 99 as n_quantity 
                            from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group_new' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%')
                            and a.i_product not in (select a.i_product from tbl_product a
                            left join tbl_product_price b on a.i_product = b.i_product and a.i_company = b.i_company
                            where a.i_product_group = '$i_product_group' and b.i_price_group = '$i_price_group' 
                            and a.i_company = '$i_company' and f_active = 't' and (a.i_product like '%$cari%' or a.e_product_name like '%$cari%'))
                        ");
                    }
                }
                

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


}
