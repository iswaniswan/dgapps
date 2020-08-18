<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Rest extends REST_Controller
{

    public function index_get()
    {
        echo "hai";
    }

    public function area_post()
    {

        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_area = $this->post('i_area');
        $e_area_name = $this->post('e_area_name');
        $f_active = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $i_area != '' && $e_area_name != '') {
            $this->db->select("i_area");
            $this->db->from("tbl_area");
            $this->db->where("i_area", $i_area);
            $this->db->where("i_company", $i_company);
            $cek_area = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_area->num_rows() > 0) {
                $data = array(
                    'e_area_name' => $e_area_name,
                    'f_active' => $f_active,
                    'modifiedat' => $datenow,
                );

                $this->db->where("i_area", $i_area);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_area', $data);
                $message = "Data area " . $i_area . " Berhasil di update";
            } else {
                $data = array(
                    'i_area' => $i_area,
                    'e_area_name' => $e_area_name,
                    'i_company' => $i_company,
                    'f_active' => $f_active,
                    'createdat' => $datenow,
                );
                $message = "Data area " . $i_area . " Berhasil di input";
                $this->db->insert('tbl_area', $data);

            }

            $this->response([
                'status' => true,
                'message' => $message,
            ], REST_Controller::HTTP_OK);

        } else {

            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);

        }

    }

    public function area_get()
    {

        $action = $this->get('action');
        $api_key = $this->get('api_key');
        $i_company = $this->get('i_company');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
        if ($action == 'list' && $cek_company->num_rows() > 0) {

            $this->db->select("i_area, e_area_name, f_active, createdat, modifiedat");
            $this->db->from("tbl_area");
            $this->db->where("i_company", $i_company);
            $data = $this->db->get()->result_array();

            $this->response([
                'status' => true,
                'data' => $data,
            ], REST_Controller::HTTP_OK);

        } else {

            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function customer_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_customer = $this->post('i_customer');
        $e_customer_name = $this->post('e_customer_name');
        $e_contact_name = $this->post('e_contact_name');
        $e_phone_number = $this->post('e_phone_number');
        $e_customer_address = $this->post('e_customer_address');
        $i_area = $this->post('i_area');
        $i_price_group = $this->post('i_price_group');
        $n_customer_discount1 = $this->post('n_customer_discount1');
        $n_customer_discount2 = $this->post('n_customer_discount2');
        $f_active = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $i_customer != '' && $e_customer_name != '' && $e_customer_address != ''
            && $i_area != '' && $i_price_group != '' && ($f_active == 'true' || $f_active == 'false')) {

            if ($n_customer_discount1 == '') {$n_customer_discount1 = 0;}
            if ($n_customer_discount2 == '') {$n_customer_discount2 = 0;}

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $this->db->select("i_customer");
            $this->db->from("tbl_customer");
            $this->db->where("i_customer", $i_customer);
            $this->db->where("i_area", $i_area);
            $this->db->where("i_company", $i_company);
            $cek_customer = $this->db->get();

            if ($cek_customer->num_rows() > 0) {

                $data = array(
                    'i_price_group' => $i_price_group,
                    'e_customer_name' => $e_customer_name,
                    'e_contact_name' => $e_contact_name,
                    'e_phone_number' => $e_phone_number,
                    'e_customer_address' => $e_customer_address,
                    'f_active' => $f_active,
                    'modifiedat' => $datenow,

                );

                $this->db->where("i_customer", $i_customer);
                $this->db->where("i_company", $i_company);
                $this->db->where("i_area", $i_area);
                $this->db->update('tbl_customer', $data);

                $data = array(
                    'n_customer_discount1' => $n_customer_discount1,
                    'n_customer_discount2' => $n_customer_discount2,
                    'modifiedat' => $datenow,
                );

                $this->db->where("i_customer", $i_customer);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_customer_discount', $data);

                $message = "Data Customer " . $i_customer . " Berhasil di update";
            } else {

                $data = array(
                    'i_customer' => $i_customer,
                    'i_company' => $i_company,
                    'i_area' => $i_area,
                    'i_price_group' => $i_price_group,
                    'e_customer_name' => $e_customer_name,
                    'e_contact_name' => $e_contact_name,
                    'e_phone_number' => $e_phone_number,
                    'e_customer_address' => $e_customer_address,
                    'f_active' => $f_active,
                    'createdat' => $datenow,

                );

                $this->db->insert('tbl_customer', $data);

                $data = array(
                    'i_customer' => $i_customer,
                    'i_company' => $i_company,
                    'n_customer_discount1' => $n_customer_discount1,
                    'n_customer_discount2' => $n_customer_discount2,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_customer_discount', $data);

                $message = "Data Customer " . $i_customer . " Berhasil di input";
            }

            $this->session->sess_destroy();
            $this->response([
                'status' => true,
                'message' => $message,
            ], REST_Controller::HTTP_OK);

        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function product_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_product = $this->post('i_product');
        $e_product_name = $this->post('e_product_name');
        $i_product_group = $this->post('i_product_group');
        $e_product_groupname = $this->post('e_product_groupname');
        $f_active = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $i_product != '' && $e_product_name != '' && $i_product_group != '' && $e_product_groupname != ''
            && ($f_active == 'true' || $f_active == 'false')) {

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $this->db->select("i_product");
            $this->db->from("tbl_product");
            $this->db->where("i_product", $i_product);
            $this->db->where("i_company", $i_company);
            $cek_product = $this->db->get();

            if ($cek_product->num_rows() > 0) {
                $data = array(
                    'i_product_group' => $i_product_group,
                    'e_product_name' => $e_product_name,
                    'f_active' => $f_active,
                    'modifiedat' => $datenow,

                );

                $this->db->where("i_product", $i_product);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_product', $data);

            } else {
                $data = array(
                    'i_product' => $i_product,
                    'i_company' => $i_company,
                    'i_product_group' => $i_product_group,
                    'e_product_name' => $e_product_name,
                    'f_active' => $f_active,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_product', $data);

            }

            $this->db->select("i_product_group");
            $this->db->from("tbl_product_group");
            $this->db->where("i_product_group", $i_product_group);
            $this->db->where("i_company", $i_company);
            $cek_product_group = $this->db->get();

            if ($cek_product->num_rows() > 0) {
                $data = array(
                    'e_product_groupname' => $e_product_groupname,
                    'modifiedat' => $datenow,

                );

                $this->db->where("i_product_group", $i_product_group);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_product_group', $data);
            } else {
                $data = array(
                    'i_product_group' => $i_product_group,
                    'i_company' => $i_company,
                    'e_product_groupname' => $e_product_groupname,
                    'f_active' => 'true',
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_product_group', $data);
            }
            $this->session->sess_destroy();
            $this->response([
                'status' => true,
                'message' => 'Berhasil',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function productprice_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_product = $this->post('i_product');
        $i_product_grade = $this->post('i_product_grade');
        $i_price_group = $this->post('i_price_group');
        $e_price_groupname = $this->post('e_price_groupname');
        $e_product_name = $this->post('e_product_name');
        $v_product_price = $this->post('v_product_price');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $i_product != '' && $i_product_grade != '' && $i_price_group != '' && $e_price_groupname != '' && $e_product_name != '' && $v_product_price != '') {

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            $this->db->select("i_product");
            $this->db->from("tbl_product_price");
            $this->db->where("i_product", $i_product);
            $this->db->where("i_price_group", $i_price_group);
            $this->db->where("i_company", $i_company);
            $cek_product = $this->db->get();

            if ($cek_product->num_rows() > 0) {
                $data = array(
                    'i_product_grade' => $i_product_grade,
                    'e_product_name' => $e_product_name,
                    'v_product_price' => $v_product_price,
                    'modifiedat' => $datenow,

                );

                $this->db->where("i_product", $i_product);
                $this->db->where("i_price_group", $i_price_group);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_product_price', $data);

            } else {
                $data = array(
                    'i_product' => $i_product,
                    'i_company' => $i_company,
                    'i_product_grade' => $i_product_grade,
                    'i_price_group' => $i_price_group,
                    'e_product_name' => $e_product_name,
                    'v_product_price' => $v_product_price,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_product_price', $data);

            }

            $this->db->select("i_price_group");
            $this->db->from("tbl_price_group");
            $this->db->where("i_price_group", $i_price_group);
            $this->db->where("i_company", $i_company);
            $cek_product_group = $this->db->get();

            if ($cek_product_group->num_rows() > 0) {
                $data = array(
                    'e_price_groupname' => $e_price_groupname,
                    'modifiedat' => $datenow,

                );

                $this->db->where("i_price_group", $i_price_group);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_price_group', $data);
            } else {
                $data = array(
                    'i_price_group' => $i_price_group,
                    'i_company' => $i_company,
                    'e_price_groupname' => $e_price_groupname,
                    'createdat' => $datenow,
                );

                $this->db->insert('tbl_price_group', $data);
            }
            $this->session->sess_destroy();
            $this->response([
                'status' => true,
                'message' => 'Berhasil',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function salesorder_get()
    {
        $action = $this->get('action');
        $api_key = $this->get('api_key');
        $i_company = $this->get('i_company');
        $starttime = $this->get('starttime');
        $endtime = $this->get('endtime');
        $fulfilled = $this->get('fulfilled');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
        if ($action == 'list' && $cek_company->num_rows() > 0 && ($fulfilled == 'true' || $fulfilled == 'false')) {

            $this->db->select("i_spb, i_customer, i_area, username, d_spb, i_product_group, i_price_group, e_remark, n_spb_discount1, n_spb_discount2, n_spb_discount3, v_spb_discount1, v_spb_discount2, v_spb_discount3, v_spb_discounttotal, v_spb_gross, v_spb_netto, f_status_transfer");
            $this->db->from("tbl_spb");
            $this->db->where("i_company", $i_company);
            $this->db->where("d_spb >= ", $starttime);
            $this->db->where("d_spb <= ", $endtime);
            $this->db->where("f_status_transfer", $fulfilled);
            $this->db->where("f_spb_cancel", 'false');
            $this->db->where("((now()- createdat) > '00:05:00')");
            $header = $this->db->get();

            $list = array();
            $key = 0;
            foreach ($header->result() as $riw) {
                $i_staff = $this->db->query("select i_staff from tbl_user where username = '$riw->username' and i_company = '$i_company'")->row()->i_staff;
                $this->db->select("i_product, e_product_name,  i_product_grade, n_order, v_unit_price, e_remark");
                $this->db->from("tbl_spb_item");
                $this->db->where("i_company", $i_company);
                $this->db->where("i_spb", $riw->i_spb);
                $this->db->where("i_area ", $riw->i_area);

                $items = $this->db->get()->result_array();

                $list[$key]['i_spb'] = $riw->i_spb;
                $list[$key]['i_customer'] = $riw->i_customer;
                $list[$key]['i_area'] = $riw->i_area;
                $list[$key]['i_staff'] = $i_staff;
                $list[$key]['d_spb'] = $riw->d_spb;
                $list[$key]['i_product_group'] = $riw->i_product_group;
                $list[$key]['i_price_group'] = $riw->i_price_group;
                $list[$key]['e_remark'] = $riw->e_remark;
                $list[$key]['n_spb_discount1'] = $riw->n_spb_discount1;
                $list[$key]['n_spb_discount2'] = $riw->n_spb_discount2;
                $list[$key]['n_spb_discount3'] = $riw->n_spb_discount3;
                $list[$key]['v_spb_discount1'] = $riw->v_spb_discount1;
                $list[$key]['v_spb_discount2'] = $riw->v_spb_discount2;
                $list[$key]['v_spb_discount3'] = $riw->v_spb_discount3;
                $list[$key]['v_spb_discounttotal'] = $riw->v_spb_discounttotal;
                $list[$key]['v_spb_gross'] = $riw->v_spb_gross;
                $list[$key]['v_spb_netto'] = $riw->v_spb_netto;
                $list[$key]['fulfilled'] = $riw->f_status_transfer;
                $list[$key]['items'] = $items;
                $key++;
            }

            $this->session->sess_destroy();
            $this->response([
                'status' => true,
                'data' => $list,
            ], REST_Controller::HTTP_OK);

        } else {

            $this->response([
                'status' => false,
                'message' => 'Parameter Salah !',
            ], REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function salesorder_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_spb = $this->post('i_spb');
        $i_area = $this->post('i_area');
        $i_customer = $this->post('i_customer');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'fulfill' && $cek_company->num_rows() > 0 && $i_spb != '' && $i_area != '' && $i_customer != '') {

            $data = array(
                'f_status_transfer' => 't',
                'modifiedat' => current_datetime(),
            );

            $this->db->where("i_company", $i_company);
            $this->db->where("i_spb", $i_spb);
            $this->db->where("i_area", $i_area);
            $this->db->where("i_customer", $i_customer);
            $this->db->update('tbl_spb', $data);
            $message = "Data SPB " . $i_spb . " Berhasil di Update";

            $this->session->sess_destroy();
            $this->response([
                'status' => true,
                'message' => $message,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function rrkh_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $i_area = $this->post('i_area');
        $username = $this->post('username');
        $d_rrkh = $this->post('d_rrkh');
        $i_customer = $this->post('i_customer');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $i_area != '' && $username != '' && $d_rrkh != '' && $i_customer != '') {

            $cek_username = $this->db->query("select username from tbl_user where i_staff = '$username' and i_area = '$i_area'");

            if ($cek_username->num_rows() > 0) {
                $username = $cek_username->row()->username;

                $this->db->select("i_area");
                $this->db->from("tbl_rrkh");
                $this->db->where("i_company", $i_company);
                $this->db->where("i_area", $i_area);
                $this->db->where("username", $username);
                $this->db->where("d_rrkh", $d_rrkh);
                $this->db->where("i_customer", $i_customer);
                $cek_rrkh = $this->db->get();

                if ($cek_rrkh->num_rows() > 0) {
                    $data = array(
                        'd_update' => current_datetime(),
                    );
                    $this->db->where("i_company", $i_company);
                    $this->db->where("i_area", $i_area);
                    $this->db->where("username", $username);
                    $this->db->where("d_rrkh", $d_rrkh);
                    $this->db->where("i_customer", $i_customer);
                    $this->db->update('tbl_rrkh', $data);
                    $message = "Data RRKH " . $i_area . " Berhasil di Update";
                } else {
                    $data = array(
                        'i_company' => $i_company,
                        'i_area' => $i_area,
                        'username' => $username,
                        'd_rrkh' => $d_rrkh,
                        'i_customer' => $i_customer,
                        'd_entry' => current_datetime(),
                    );
                    $message = "Data RRKH " . $i_area . " Berhasil di input";
                    $this->db->insert('tbl_rrkh', $data);

                }

                $this->session->sess_destroy();
                $this->response([
                    'status' => true,
                    'message' => $message,
                ], REST_Controller::HTTP_OK);

            }

        }
    }

    public function stokproduct_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $databrg = $this->post('data');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $databrg) {

            $this->db->query('delete from tbl_ic');
            $data = [];
            $i = 0;

            foreach ($databrg as $row) {
                $data[$i] = array(
                    'i_product' => $row['i_product'],
                    'i_company' => $i_company,
                    'i_store' => $row['i_store'],
                    'n_quantity' => $row['n_quantity_stock'],
                );
                $i++;
            }
            $this->db->insert_batch('tbl_ic', $data);
            $this->response([
                'status' => true,
                'message' => true,
            ], REST_Controller::HTTP_OK);
        }

    }

    public function productstatus_post()
    {
        $action = $this->post('action');
        $api_key = $this->post('api_key');
        $i_company = $this->post('i_company');
        $databrg = $this->post('data');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $databrg) {

            $i = 0;

            foreach ($databrg as $row) {

                $data = array(
                    'f_active' => $row['status_product'],
                    'modifiedat' => current_datetime(),
                );
                $this->db->where('i_product', $row['i_product']);
                $this->db->where('i_company', $i_company);

                $this->db->update('tbl_product', $data);

            }
            $this->response([
                'status' => true,
                'message' => true,
            ], REST_Controller::HTTP_OK);
        }

    }

}