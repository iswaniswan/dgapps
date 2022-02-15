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
        $i_company = $this->post('i_company');
        if($i_company =='3'){
            $action                 = $this->post('action');
            $api_key                = $this->post('api_key');
            $i_product              = $this->post('i_product');
            $i_product_group        = $this->post('i_product_group');
            $e_product_name         = $this->post('e_product_name');
            $product_seri           = $this->post('product_seri');
            $product_color          = $this->post('product_color');
            $product_catalog        = $this->post('product_catalog');
            $supplier_code          = $this->post('supplier_code');
            $product_group_code     = $this->post('product_group_code');
            $product_group_name     = $this->post('product_group_name');
            $product_subctgr_code   = $this->post('product_subctgr_code');
            $product_ctgr_code      = $this->post('product_ctgr_code');
            $sellable               = $this->post('sellable');
            $f_active               = $this->post('f_active');

            $this->db->select("i_company");
            $this->db->from("tbl_company");
            $this->db->where("api_key", $api_key);
            $this->db->where("i_company", $i_company);
            $this->db->where("f_active", 'true');
            $cek_company = $this->db->get();

            if ($action == 'create' && $cek_company->num_rows() > 0 && $i_product != '' && $e_product_name != '') {
                $this->db->select("i_product");
                $this->db->from("tbl_product");
                $this->db->where("i_product", $i_product);
                $this->db->where("i_company", $i_company);
                $cek_product = $this->db->get();

                $query = $this->db->query("SELECT current_timestamp as c");
                $row = $query->row();
                $datenow = $row->c;
      
                if ($cek_product->num_rows() > 0) {
                    $data = array(   
                                    'i_product_group'       => $i_product_group,
                                    'e_product_name'        => $e_product_name,
                                    'product_seri'          => $product_seri,
                                    'product_color'         => $product_color,
                                    'product_catalog'       => $product_catalog, 
                                    'supplier_code'         => $supplier_code,
                                    'product_group_code'    => $product_group_code,
                                    'product_group_name'    => $product_group_name,
                                    'product_subctgr_code'  => $product_subctgr_code,
                                    'product_ctgr_code'     => $product_ctgr_code,
                                    'sellable'              => $sellable,
                                    'f_active'              => $f_active,
                                    'modifiedat'            => $datenow,
                    );
                    $this->db->where("i_product", $i_product);
                    $this->db->where("i_company", $i_company);
                    $this->db->update('tbl_product', $data);
                    $message = "Data product " . $i_product . " Berhasil di update";
                } else {
                    $data = array(
                                    'i_company'             => $i_company,
                                    'i_product'             => $i_product,
                                    'i_product_group'       => $i_product_group,
                                    'e_product_name'        => $e_product_name,
                                    'product_seri'          => $product_seri,
                                    'product_color'         => $product_color,
                                    'product_catalog'       => $product_catalog, 
                                    'supplier_code'         => $supplier_code,
                                    'product_group_code'    => $product_group_code,
                                    'product_group_name'    => $product_group_name,
                                    'product_subctgr_code'  => $product_subctgr_code,
                                    'product_ctgr_code'     => $product_ctgr_code,
                                    'sellable'              => $sellable,
                                    'f_active'              => $f_active,
                                    'createdat'             => $datenow,
                    );
                    $message = "Data product " . $i_product . " Berhasil di input";
                    $this->db->insert('tbl_product', $data);
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
        }else{
            $action = $this->post('action');
            $api_key = $this->post('api_key');
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

#---MASTER
    public function employee_post()
    {
        $action         = $this->post('action');
        $api_key        = $this->post('api_key');
        $i_company      = $this->post('i_company');
        $ou_code        = $this->post('ou_code');
        $ou_name        = $this->post('ou_name');
        $employee_code  = $this->post('employee_code');
        $employee_name  = $this->post('employee_name');
        $employee_type  = $this->post('employee_type');
        $f_active       = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $employee_code != '' && $employee_name != '') {
            $this->db->select("employee_code");
            $this->db->from("tbl_employee");
            $this->db->where("employee_code", $employee_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'ou_code'       => $ou_code,
                                'ou_name'       => $ou_name,
                                'employee_name' => $employee_name,
                                'employee_type' => $employee_type,
                                'f_active'      => $f_active,
                                'modifiedat'    => $datenow,
                );

                $this->db->where("employee_code", $employee_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_employee', $data);
                $message = "Data karyawan " . $employee_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'     => $i_company,
                                'ou_code'       => $ou_code,
                                'ou_name'       => $ou_name,
                                'employee_code' => $employee_code,
                                'employee_name' => $employee_name,
                                'employee_type' => $employee_type,
                                'f_active'      => $f_active,
                                'createdat'     => $datenow,
                );
                $message = "Data karyawan " . $employee_code . " Berhasil di input";
                $this->db->insert('tbl_employee', $data);
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

    public function area_sales_post()
    {
        $action         = $this->post('action');
        $api_key        = $this->post('api_key');
        $i_company      = $this->post('i_company');
        $i_area         = $this->post('i_area');
        $e_area_name    = $this->post('e_area_name');
        $ou_type        = $this->post('ou_type');
        $ou_group       = $this->post('ou_group');
        $f_active       = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $i_area != '' && $e_area_name != '') {
            $this->db->select("i_area");
            $this->db->from("tbl_area_sales");
            $this->db->where("i_area", $i_area);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'e_area_name'   => $e_area_name,
                                'ou_name'       => $ou_name,
                                'ou_type'       => $ou_type,
                                'ou_group'      => $ou_group,
                                'f_active'      => $f_active,
                                'modifiedat'    => $datenow,
                );

                $this->db->where("i_area", $i_area);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_area_sales', $data);
                $message = "Data area sales " . $i_area . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'     => $i_company,
                                'i_area'        => $i_area,
                                'e_area_name'   => $e_area_name,
                                'ou_type'       => $ou_type,
                                'ou_group'      => $ou_group,
                                'f_active'      => $f_active,
                                'createdat'     => $datenow,
                );
                $message = "Data area sales " . $i_area . " Berhasil di input";
                $this->db->insert('tbl_area_sales', $data);
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

    public function teritorial_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $teritorial_code    = $this->post('teritorial_code');
        $teritorial_name    = $this->post('teritorial_name');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $teritorial_code != '' && $teritorial_name != '') {
            $this->db->select("teritorial_code");
            $this->db->from("tbl_teritorial");
            $this->db->where("teritorial_code", $teritorial_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'teritorial_name'   => $teritorial_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("teritorial_code", $teritorial_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_teritorial', $data);
                $message = "Data wilayah " . $teritorial_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'teritorial_code'   => $teritorial_code,
                                'teritorial_name'   => $teritorial_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data wilayah " . $teritorial_code . " Berhasil di input";
                $this->db->insert('tbl_teritorial', $data);
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

    public function province_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $province_code      = $this->post('province_code');
        $province_name      = $this->post('province_name');
        $teritorial_code    = $this->post('teritorial_code');
        $ou_group           = $this->post('ou_group');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $province_code != '' && $province_name != '') {
            $this->db->select("province_code");
            $this->db->from("tbl_province");
            $this->db->where("province_code", $province_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'province_name'   => $province_name,
                                'ou_group'        => $ou_group,
                                'f_active'        => $f_active,
                                'modifiedat'      => $datenow,
                );
                $this->db->where("province_code", $province_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_province', $data);
                $message = "Data provinsi " . $province_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'province_code'   => $province_code,
                                'province_name'   => $province_name,
                                'teritorial_code' => $teritorial_code,
                                'ou_group'        => $ou_group,
                                'f_active'        => $f_active,
                                'createdat'       => $datenow,
                );
                $message = "Data provinsi " . $province_code . " Berhasil di input";
                $this->db->insert('tbl_province', $data);
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

    public function city_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $i_city             = $this->post('i_city');
        $e_city_name        = $this->post('e_city_name');
        $province_code      = $this->post('province_code');
        $id_maps            = $this->post('id_maps');
        $latitude           = $this->post('latitude');
        $longitude          = $this->post('longitude');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $i_city != '' && $e_city_name != '') {
            $this->db->select("i_city");
            $this->db->from("tbl_city");
            $this->db->where("i_city", $i_city);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'e_city_name'     => $e_city_name,
                                'province_code'   => $province_code,
                                'id_maps'         => $id_maps,
                                'latitude'        => $latitude,
                                'longitude'       => $longitude,
                                'f_active'        => $f_active,
                                'modifiedat'      => $datenow,
                );
                $this->db->where("i_city", $i_city);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_city', $data);
                $message = "Data kota " . $i_city . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'i_city'          => $i_city,
                                'e_city_name'     => $e_city_name,
                                'province_code'   => $province_code,
                                'id_maps'         => $id_maps,
                                'latitude'        => $latitude,
                                'longitude'       => $longitude,
                                'f_active'        => $f_active,
                                'createdat'       => $datenow,
                );
                $message = "Data kota " . $i_city . " Berhasil di input";
                $this->db->insert('tbl_city', $data);
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

    public function area_cover_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $region_code        = $this->post('region_code');
        $region_name        = $this->post('region_name');
        $ou_group           = $this->post('ou_group');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $region_code != '' && $region_name != '') {
            $this->db->select("region_code");
            $this->db->from("tbl_area_cover");
            $this->db->where("region_code", $region_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'region_name'     => $region_name,
                                'ou_group'        => $ou_group,
                                'f_active'        => $f_active,
                                'modifiedat'      => $datenow,
                );
                $this->db->where("region_code", $region_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_area_cover', $data);
                $message = "Data area cover " . $region_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'region_code'     => $region_code,
                                'region_name'     => $region_name,
                                'ou_group'        => $ou_group,
                                'f_active'        => $f_active,
                                'createdat'       => $datenow,
                );
                $message = "Data area cover " . $region_code . " Berhasil di input";
                $this->db->insert('tbl_area_cover', $data);
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

    public function user_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $username           = $this->post('username');
        $e_password         = $this->post('e_password');
        $e_name             = $this->post('e_name');
        $phone              = $this->post('phone');
        $email              = $this->post('email');
        $address            = $this->post('address');
        $i_area             = $this->post('i_area');
        $i_role             = $this->post('i_role');
        $i_staff            = $this->post('i_staff');
        $username_upline    = $this->post('username_upline');
        $role_default       = $this->post('role_default');
        $policy_default     = $this->post('policy_default');
        $ou_code            = $this->post('ou_code');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $username != '' && $e_name != '') {
            $this->db->select("username");
            $this->db->from("tbl_user");
            $this->db->where("username", $username);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'e_name'            => $e_name,
                                'e_password'        => $e_password,
                                'phone'             => $phone,
                                'email'             => $email,
                                'address'           => $address,
                                'i_area'            => $i_area,
                                'i_role'            => $i_role,
                                'i_staff'           => $i_staff,
                                'username_upline'   => $username_upline,
                                'role_default'      => $role_default,
                                'policy_default'    => $policy_default,
                                'ou_code'           => $ou_code,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("username", $username);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_user', $data);
                $message = "Data user " . $username . " Berhasil di update";
  
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'username'          => $username,
                                'e_password'        => $e_password,
                                'e_name'            => $e_name,
                                'phone'             => $phone,
                                'email'             => $email,
                                'address'           => $address,
                                'i_area'            => $i_area,
                                'i_role'            => $i_role,
                                'i_staff'           => $i_staff,
                                'username_upline'   => $username_upline,
                                'role_default'      => $role_default,
                                'policy_default'    => $policy_default,
                                'ou_code'           => $ou_code,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data user " . $username . " Berhasil di input";
                $this->db->insert('tbl_user', $data);
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

    public function role_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $role_type          = $this->post('role_type');
        $role_name          = $this->post('role_name');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $role_type != '' && $role_name != '') {
            $this->db->select("role_type");
            $this->db->from("tbl_role");
            $this->db->where("role_type", $role_type);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'role_name'       => $role_name,
                                'f_active'        => $f_active,
                                'modifiedat'      => $datenow,
                );
                $this->db->where("role_type", $role_type);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_role', $data);
                $message = "Data role " . $role_type . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'role_type'       => $role_type,
                                'role_name'       => $role_name,
                                'f_active'        => $f_active,
                                'createdat'       => $datenow,
                );
                $message = "Data role " . $role_type . " Berhasil di input";
                $this->db->insert('tbl_role', $data);
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

    public function user_role_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $i_role             = $this->post('i_role');
        $e_role_name        = $this->post('e_role_name');
        $e_username         = $this->post('e_username');
        $policy_name        = $this->post('policy_name');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $i_role != '' && $e_role_name != '') {
            $this->db->select("i_role");
            $this->db->from("tbl_user_role");
            $this->db->where("i_role", $i_role);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'e_role_name'       => $e_role_name,
                                'e_username'        => $e_username,
                                'policy_name'       => $policy_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("i_role", $i_role);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_user_role', $data);
                $message = "Data user role " . $i_role . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'i_role'            => $i_role,
                                'e_role_name'       => $e_role_name,
                                'e_username'        => $e_username,
                                'policy_name'       => $policy_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data user role " . $i_role . " Berhasil di input";
                $this->db->insert('tbl_user_role', $data);
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

    public function brand_post()
    {
        $action         = $this->post('action');
        $api_key        = $this->post('api_key');
        $i_company      = $this->post('i_company');
        $brand_code     = $this->post('brand_code');
        $brand_name     = $this->post('brand_name');
        $f_active       = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $brand_code != '' && $brand_name != '') {
            $this->db->select("brand_code");
            $this->db->from("tbl_brand");
            $this->db->where("brand_code", $brand_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'brand_name'        => $brand_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("brand_code", $brand_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_brand', $data);
                $message = "Data brand " . $brand_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'brand_code'        => $brand_code,
                                'brand_name'        => $brand_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data brand " . $brand_code . " Berhasil di input";
                $this->db->insert('tbl_brand', $data);
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

    public function group_product_post()
    {
        $action               = $this->post('action');
        $api_key              = $this->post('api_key');
        $i_company            = $this->post('i_company');
        $group_brand_code     = $this->post('group_brand_code');
        $group_brand_name     = $this->post('group_brand_name');
        $f_active             = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $group_brand_code != '' && $group_brand_name != '') {
            $this->db->select("group_brand_code");
            $this->db->from("tbl_group_product");
            $this->db->where("group_brand_code", $group_brand_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'group_brand_name'  => $group_brand_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("group_brand_code", $group_brand_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_group_product', $data);
                $message = "Data group product " . $group_brand_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'group_brand_code'  => $group_brand_code,
                                'group_brand_name'  => $group_brand_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data group product " . $group_brand_code . " Berhasil di input";
                $this->db->insert('tbl_group_product', $data);
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

    public function sub_category_post()
    {
        $action               = $this->post('action');
        $api_key              = $this->post('api_key');
        $i_company            = $this->post('i_company');
        $product_subctgr_code = $this->post('product_subctgr_code');
        $product_subctgr_name = $this->post('product_subctgr_name');
        $product_ctgr_code    = $this->post('product_ctgr_code');
        $f_active             = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $product_subctgr_code != '' && $product_subctgr_name != '') {
            $this->db->select("product_subctgr_code");
            $this->db->from("tbl_sub_category");
            $this->db->where("product_subctgr_code", $product_subctgr_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'product_subctgr_name'  => $product_subctgr_name,
                                'product_ctgr_code'     => $product_ctgr_code,
                                'f_active'              => $f_active,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("product_subctgr_code", $product_subctgr_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_sub_category', $data);
                $message = "Data sub category " . $product_subctgr_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'             => $i_company,
                                'product_subctgr_code'  => $product_subctgr_code,
                                'product_subctgr_name'  => $product_subctgr_name,
                                'product_ctgr_code'     => $product_ctgr_code,
                                'f_active'              => $f_active,
                                'createdat'             => $datenow,
                );
                $message = "Data sub category " . $product_subctgr_code . " Berhasil di input";
                $this->db->insert('tbl_sub_category', $data);
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

    public function level_price_post()
    {
        $action               = $this->post('action');
        $api_key              = $this->post('api_key');
        $i_company            = $this->post('i_company');
        $level_price_code     = $this->post('level_price_code');
        $level_price_name     = $this->post('level_price_name');
        $f_active             = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $level_price_code != '' && $level_price_name != '') {
            $this->db->select("level_price_code");
            $this->db->from("tbl_level_price");
            $this->db->where("level_price_code", $level_price_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(
                                'level_price_name'  => $level_price_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("level_price_code", $level_price_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_level_price', $data);
                $message = "Data level price " . $level_price_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'level_price_code'  => $level_price_code,
                                'level_price_name'  => $level_price_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data level price " . $level_price_code . " Berhasil di input";
                $this->db->insert('tbl_level_price', $data);
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

    public function harga_jual_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $product_code       = $this->post('product_code');
        $product_name       = $this->post('product_name');
        $product_grade      = $this->post('product_grade');  
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $date_from          = $this->post('date_from');
        $date_to            = $this->post('date_to');
        $price_group_code   = $this->post('price_group_code');
        $price_group_name   = $this->post('price_group_name');
        $product_price      = $this->post('product_price');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $product_code != '' && $product_name != '') {
            $this->db->select("product_code");
            $this->db->from("tbl_harga_jual");
            $this->db->where("product_code", $product_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(                                
                                'product_name'      => $product_name,
                                'product_grade'     => $product_grade,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'price_group_code'  => $price_group_code,
                                'price_group_name'  => $price_group_name,
                                'product_price'     => $product_price,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("product_code", $product_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_harga_jual', $data);
                $message = "Data harga jual " . $product_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'product_code'      => $product_code,
                                'product_name'      => $product_name,
                                'product_grade'     => $product_grade,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'price_group_code'  => $price_group_code,
                                'price_group_name'  => $price_group_name,
                                'product_price'     => $product_price,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data harga jual " . $product_code . " Berhasil di input";
                $this->db->insert('tbl_harga_jual', $data);
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

    public function promo_sales_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $promo_sales_code   = $this->post('promo_sales_code');
        $promo_description  = $this->post('promo_description');
        $ou_code            = $this->post('ou_code');
        $date_from          = $this->post('date_from');
        $date_to            = $this->post('date_to');
        $add_discount       = $this->post('add_discount');
        $promo_type         = $this->post('promo_type');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $promo_sales_code != '' && $promo_description != '') {
            $this->db->select("promo_sales_code");
            $this->db->from("tbl_promo_sales");
            $this->db->where("promo_sales_code", $promo_sales_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'promo_description' => $promo_description,
                                'ou_code'           => $ou_code,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'add_discount'      => $add_discount,
                                'promo_type'        => $promo_type,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("promo_sales_code", $promo_sales_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_promo_sales', $data);
                $message = "Data promo sales " . $promo_sales_code . " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'promo_sales_code'  => $promo_sales_code,
                                'promo_description' => $promo_description,
                                'ou_code'           => $ou_code,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'add_discount'      => $add_discount,
                                'promo_type'        => $promo_type,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data promo sales " . $promo_sales_code . " Berhasil di input";
                $this->db->insert('tbl_promo_sales', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_promo_sales_item where promo_sales_code = '$promo_sales_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'promo_sales_code'      => $row['promo_sales_code'],
                                    'product_code'          => $row['product_code'],
                                    'add_discount'          => $row['add_discount'],
                                    'gross_sell_price'      => $row['gross_sell_price'],
                                    'nett_sell_price'       => $row['nett_sell_price'],
                    );
                    $this->db->insert('tbl_promo_sales_item', $data);
                }     
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

    public function warehouse_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $warehouse_code     = $this->post('warehouse_code');
        $warehouse_name     = $this->post('warehouse_name');
        $warehouse_type     = $this->post('warehouse_type');
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $warehouse_code != '' && $warehouse_name != '') {
            $this->db->select("warehouse_code");
            $this->db->from("tbl_warehouse");
            $this->db->where("warehouse_code", $warehouse_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'warehouse_name'    => $warehouse_name,
                                'warehouse_type'    => $warehouse_type,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("warehouse_code", $warehouse_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_warehouse', $data);
                $message = "Data gudang " . $warehouse_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'         => $i_company,
                                'warehouse_code'    => $warehouse_code,
                                'warehouse_name'    => $warehouse_name,
                                'warehouse_type'    => $warehouse_type,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data gudang " . $warehouse_code . " Berhasil di input";
                $this->db->insert('tbl_warehouse', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_warehouse_item where warehouse_code = '$warehouse_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'warehouse_code'        => $row['warehouse_code'],
                                    'warehouse_name'        => $row['warehouse_name'],
                                    'product_subctgr_code'  => $row['product_subctgr_code'],
                                    'product_ctgr_code'     => $row['product_ctgr_code'],
                    );
                    $this->db->insert('tbl_warehouse_item', $data);
                }  
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

    public function group_partner_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $group_partner_code = $this->post('group_partner_code');
        $group_partner_name = $this->post('group_partner_name');
        $f_active           = $this->post('f_active');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $group_partner_code != '' && $group_partner_name != '') {
            $this->db->select("group_partner_code");
            $this->db->from("tbl_group_partner");
            $this->db->where("group_partner_code", $group_partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'group_partner_name'    => $group_partner_name,
                                'f_active'              => $f_active,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("group_partner_code", $group_partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_group_partner', $data);
                $message = "Data group partner " . $group_partner_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'             => $i_company,
                                'group_partner_code'    => $group_partner_code,
                                'group_partner_name'    => $group_partner_name,
                                'f_active'              => $f_active,
                                'createdat'             => $datenow,
                );
                $message = "Data group partner " . $group_partner_code . " Berhasil di input";
                $this->db->insert('tbl_group_partner', $data);
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

    public function category_partner_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $partner_ctgr_code  = $this->post('partner_ctgr_code');
        $partner_ctgr_name  = $this->post('partner_ctgr_name');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_ctgr_code != '' && $partner_ctgr_name != '') {
            $this->db->select("partner_ctgr_code");
            $this->db->from("tbl_category_partner");
            $this->db->where("partner_ctgr_code", $partner_ctgr_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'partner_ctgr_name'    => $partner_ctgr_name,
                                'f_active'             => $f_active,
                                'modifiedat'           => $datenow,
                );
                $this->db->where("partner_ctgr_code", $partner_ctgr_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_category_partner', $data);
                $message = "Data kategori partner " . $partner_ctgr_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'            => $i_company,
                                'partner_ctgr_code'    => $partner_ctgr_code,
                                'partner_ctgr_name'    => $partner_ctgr_name,
                                'f_active'             => $f_active,
                                'createdat'            => $datenow,
                );
                $message = "Data kategori partner " . $partner_ctgr_code . " Berhasil di input";
                $this->db->insert('tbl_category_partner', $data);
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

    public function ranking_partner_post()
    {
        $action         = $this->post('action');
        $api_key        = $this->post('api_key');
        $i_company      = $this->post('i_company');
        $rank_code      = $this->post('rank_code');
        $rank_name      = $this->post('rank_name');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $rank_code != '' && $rank_name != '') {
            $this->db->select("rank_code");
            $this->db->from("tbl_ranking_partner");
            $this->db->where("rank_code", $rank_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'rank_name'    => $rank_name,
                                'modifiedat'   => $datenow,
                );
                $this->db->where("rank_code", $rank_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_ranking_partner', $data);
                $message = "Data ranking partner " . $rank_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'    => $i_company,
                                'rank_code'    => $rank_code,
                                'rank_name'    => $rank_name,
                                'createdat'    => $datenow,
                );
                $message = "Data ranking partner " . $rank_code . " Berhasil di input";
                $this->db->insert('tbl_ranking_partner', $data);
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

    public function industry_type_post()
    {
        $action               = $this->post('action');
        $api_key              = $this->post('api_key');
        $i_company            = $this->post('i_company');
        $industry_type_code   = $this->post('industry_type_code');
        $industry_type_name   = $this->post('industry_type_name');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $industry_type_code != '' && $industry_type_name != '') {
            $this->db->select("industry_type_code");
            $this->db->from("tbl_industry_type");
            $this->db->where("industry_type_code", $industry_type_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'industry_type_name'    => $industry_type_name,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("industry_type_code", $industry_type_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_industry_type', $data);
                $message = "Data tipe industri " . $industry_type_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'             => $i_company,
                                'industry_type_code'    => $industry_type_code,
                                'industry_type_name'    => $industry_type_name,
                                'createdat'             => $datenow,
                );
                $message = "Data tipe industri " . $industry_type_code . " Berhasil di input";
                $this->db->insert('tbl_industry_type', $data);
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

    public function line_business_post()
    {
        $action               = $this->post('action');
        $api_key              = $this->post('api_key');
        $i_company            = $this->post('i_company');
        $line_business_code   = $this->post('line_business_code');
        $line_business_name   = $this->post('line_business_name');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $line_business_code != '' && $line_business_name != '') {
            $this->db->select("line_business_code");
            $this->db->from("tbl_line_business");
            $this->db->where("line_business_code", $line_business_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'line_business_name'    => $line_business_name,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("line_business_code", $line_business_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_line_business', $data);
                $message = "Data line business " . $line_business_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'             => $i_company,
                                'line_business_code'    => $line_business_code,
                                'line_business_name'    => $line_business_name,
                                'createdat'             => $datenow,
                );
                $message = "Data line business " . $line_business_code . " Berhasil di input";
                $this->db->insert('tbl_line_business', $data);
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

    public function partner_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $external_name          = $this->post('external_name');
        $partner_ctgr_code      = $this->post('partner_ctgr_code');
        $line_business_code     = $this->post('line_business_code');
        $price_group_code       = $this->post('price_group_code');
        $industry_type_code     = $this->post('industry_type_code');
        $rank_code              = $this->post('rank_code');
        $ou_code                = $this->post('ou_code');
        $region_code            = $this->post('region_code');
        $region_name            = $this->post('region_name');
        $customer_group_type    = $this->post('customer_group_type');
        $customer_main_code     = $this->post('customer_main_code');
        $partner_relasi_code    = $this->post('partner_relasi_code');
        $partner_relasi_name    = $this->post('partner_relasi_name');
        $partner_type           = $this->post('partner_type');
        $payment_reference      = $this->post('payment_reference');
        $top_customer           = $this->post('top_customer');
        $top_external           = $this->post('top_external');
        $ammount_limit          = $this->post('ammount_limit');
        $payment_mode_code      = $this->post('payment_mode_code');
        $payment_day            = $this->post('payment_day');
        $payment_date           = $this->post('payment_date');
        $exclude_tax            = $this->post('exclude_tax');
        $customer_discount1     = $this->post('customer_discount1');
        $customer_discount2     = $this->post('customer_discount2');
        $account_bank_no        = $this->post('account_bank_no');
        $account_bank_name      = $this->post('account_bank_name');
        $bank_code              = $this->post('bank_code');
        $address_official       = $this->post('address_official');
        $customer_address       = $this->post('customer_address');
        $latitude               = $this->post('latitude');
        $longitude              = $this->post('longitude');
        $f_active               = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_code != '' && $partner_name != '') {
            $this->db->select("partner_code");
            $this->db->from("tbl_partner");
            $this->db->where("partner_code", $partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(   
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'external_name'         => $external_name,
                                'partner_ctgr_code'     => $partner_ctgr_code,
                                'line_business_code'    => $line_business_code,
                                'price_group_code'      => $price_group_code,
                                'industry_type_code'    => $industry_type_code,
                                'rank_code'             => $rank_code,
                                'ou_code'               => $ou_code,
                                'region_code'           => $region_code,
                                'region_name'           => $region_name,
                                'customer_group_type'   => $customer_group_type,
                                'customer_main_code'    => $customer_main_code,
                                'partner_relasi_code'   => $partner_relasi_code,
                                'partner_relasi_name'   => $partner_relasi_name,
                                'partner_type'          => $partner_type,
                                'payment_reference'     => $payment_reference,
                                'top_customer'          => $top_customer,
                                'top_external'          => $top_external,
                                'ammount_limit'         => $ammount_limit,
                                'payment_mode_code'     => $payment_mode_code,
                                'payment_day'           => $payment_day,
                                'payment_date'          => $payment_date,
                                'exclude_tax'           => $exclude_tax,
                                'customer_discount1'    => $customer_discount1,
                                'customer_discount2'    => $customer_discount2,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_code'             => $bank_code,
                                'address_official'      => $address_official,
                                'customer_address'      => $customer_address,
                                'latitude'              => $latitude,
                                'longitude'             => $longitude,
                                'f_active'              => $f_active,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("partner_code", $partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_partner', $data);
                $message = "Data partner " . $partner_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'             => $i_company,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'external_name'         => $external_name,
                                'partner_ctgr_code'     => $partner_ctgr_code,
                                'line_business_code'    => $line_business_code,
                                'price_group_code'      => $price_group_code,
                                'industry_type_code'    => $industry_type_code,
                                'rank_code'             => $rank_code,
                                'ou_code'               => $ou_code,
                                'region_code'           => $region_code,
                                'region_name'           => $region_name,
                                'customer_group_type'   => $customer_group_type,
                                'customer_main_code'    => $customer_main_code,
                                'partner_relasi_code'   => $partner_relasi_code,
                                'partner_relasi_name'   => $partner_relasi_name,
                                'partner_type'          => $partner_type,
                                'payment_reference'     => $payment_reference,
                                'top_customer'          => $top_customer,
                                'top_external'          => $top_external,
                                'ammount_limit'         => $ammount_limit,
                                'payment_mode_code'     => $payment_mode_code,
                                'payment_day'           => $payment_day,
                                'payment_date'          => $payment_date,
                                'exclude_tax'           => $exclude_tax,
                                'customer_discount1'    => $customer_discount1,
                                'customer_discount2'    => $customer_discount2,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_code'             => $bank_code,
                                'address_official'      => $address_official,
                                'customer_address'      => $customer_address,
                                'latitude'              => $latitude,
                                'longitude'             => $longitude,
                                'f_active'              => $f_active,
                                'createdat'             => $datenow,
                );
                $message = "Data partner " . $partner_code . " Berhasil di input";
                $this->db->insert('tbl_partner', $data);
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

    public function customer_contact_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $contact_person_name    = $this->post('contact_person_name');
        $contact_person_job     = $this->post('contact_person_job');
        $departement            = $this->post('departement');
        $job_level              = $this->post('job_level');
        $email                  = $this->post('email');
        $phone_number           = $this->post('phone_number');
        $ext_number             = $this->post('ext_number');
        $f_active               = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_code != '' && $partner_name != '') {
            $this->db->select("partner_code");
            $this->db->from("tbl_customer_contact");
            $this->db->where("partner_code", $partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'partner_name'          => $partner_name,
                                'contact_person_name'   => $contact_person_name,
                                'contact_person_job'    => $contact_person_job,
                                'departement'           => $departement,
                                'job_level'             => $job_level,
                                'email'                 => $email,
                                'phone_number'          => $phone_number,
                                'ext_number'            => $ext_number,
                                'f_active'              => $f_active,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("partner_code", $partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_customer_contact', $data);
                $message = "Data customer contact " . $partner_code . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'             => $i_company,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'contact_person_name'   => $contact_person_name,
                                'contact_person_job'    => $contact_person_job,
                                'departement'           => $departement,
                                'job_level'             => $job_level,
                                'email'                 => $email,
                                'phone_number'          => $phone_number,
                                'ext_number'            => $ext_number,
                                'f_active'              => $f_active,
                                'createdat'             => $datenow,
                );
                $message = "Data customer contact " . $partner_code . " Berhasil di input";
                $this->db->insert('tbl_customer_contact', $data);
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

    public function customer_tax_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $partner_code    = $this->post('partner_code');
        $partner_name    = $this->post('partner_name');
        $npwp_no         = $this->post('npwp_no');
        $npwp_name       = $this->post('npwp_name');
        $npwp_date       = $this->post('npwp_date');
        $npwp_city       = $this->post('npwp_city');
        $pkp             = $this->post('pkp');
        $f_active        = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_code != '' && $partner_name != '') {
            $this->db->select("partner_code");
            $this->db->from("tbl_customer_tax");
            $this->db->where("partner_code", $partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'partner_name'  => $partner_name,
                                'npwp_no'       => $npwp_no,
                                'npwp_name'     => $npwp_name,
                                'npwp_date'     => $npwp_date,
                                'npwp_city'     => $npwp_city,
                                'pkp'           => $pkp,
                                'f_active'      => $f_active,
                                'modifiedat'    => $datenow,
                );
                $this->db->where("partner_code", $partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_customer_tax', $data);
                $message = "Data customer tax " . $partner_code . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'     => $i_company,
                                'partner_code'  => $partner_code,
                                'partner_name'  => $partner_name,
                                'npwp_no'       => $npwp_no,
                                'npwp_name'     => $npwp_name,
                                'npwp_date'     => $npwp_date,
                                'npwp_city'     => $npwp_city,
                                'pkp'           => $pkp,
                                'f_active'      => $f_active,
                                'createdat'     => $datenow,
                );
                $message = "Data customer tax " . $partner_code . " Berhasil di input";
                $this->db->insert('tbl_customer_tax', $data);
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

    public function customer_relasi_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $partner_relasi_code    = $this->post('partner_relasi_code');
        $partner_relasi_name    = $this->post('partner_relasi_name');
        $billing_relasi         = $this->post('billing_relasi');
        $shipping_relasi        = $this->post('shipping_relasi');
        $f_active               = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_code != '' && $partner_name != '') {
            $this->db->select("partner_code");
            $this->db->from("tbl_customer_relasi");
            $this->db->where("partner_code", $partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(       
                                'partner_name'          => $partner_name,
                                'partner_relasi_code'   => $partner_relasi_code,
                                'partner_relasi_name'   => $partner_relasi_name,
                                'billing_relasi'        => $billing_relasi,
                                'shipping_relasi'       => $shipping_relasi,
                                'f_active'              => $f_active,
                                'modifiedat'            => $datenow,
                );
                $this->db->where("partner_code", $partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_customer_relasi', $data);
                $message = "Data customer relasi " . $partner_code . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'             => $i_company,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'partner_relasi_code'   => $partner_relasi_code,
                                'partner_relasi_name'   => $partner_relasi_name,
                                'billing_relasi'        => $billing_relasi,
                                'shipping_relasi'       => $shipping_relasi,
                                'f_active'              => $f_active,
                                'createdat'             => $datenow,
                );
                $message = "Data customer relasi " . $partner_code . " Berhasil di input";
                $this->db->insert('tbl_customer_relasi', $data);
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

    public function partner_address_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $partner_code           = $this->post('partner_code');
        $partner_address_desc   = $this->post('partner_address');
        $set_official           = $this->post('set_official');
        $partner_address        = $this->post('partner_address');
        $city_code              = $this->post('city_code');
        $city_name              = $this->post('city_name');
        $zip_code               = $this->post('zip_code');
        $province_code          = $this->post('province_code');
        $teritorial_code        = $this->post('teritorial_code');
        $country_name           = $this->post('country_name');
        $phone_number           = $this->post('phone_number');
        $flag_ship              = $this->post('flag_ship');
        $flag_bill              = $this->post('flag_bill');
        $flag_mail              = $this->post('flag_mail');
        $flag_official          = $this->post('flag_official');
        $flag_other             = $this->post('flag_other');
        $latitude               = $this->post('latitude');
        $longitude              = $this->post('longitude');
        $f_active               = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $partner_code != '' && $partner_address_desc != '') {
            $this->db->select("partner_code");
            $this->db->from("tbl_partner_address");
            $this->db->where("partner_code", $partner_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(      
                                'partner_address_desc'      => $partner_address_desc,
                                'set_official'              => $set_official,
                                'partner_address'           => $partner_address,
                                'city_code'                 => $city_code,
                                'city_name'                 => $city_name,
                                'zip_code'                  => $zip_code,
                                'province_code'             => $province_code,
                                'teritorial_code'           => $teritorial_code,
                                'country_name'              => $country_name,
                                'phone_number'              => $phone_number,
                                'flag_ship'                 => $flag_ship,
                                'flag_bill'                 => $flag_bill,
                                'flag_mail'                 => $flag_mail,
                                'flag_official'             => $flag_official,
                                'flag_other'                => $flag_other,
                                'latitude'                  => $latitude,
                                'longitude'                 => $longitude,
                                'f_active'                  => $f_active,
                                'modifiedat'                => $datenow,
                );
                $this->db->where("partner_code", $partner_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_partner_address', $data);
                $message = "Data partner address " . $partner_code . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'                 => $i_company,
                                'partner_code'              => $partner_code,   
                                'partner_address_desc'      => $partner_address_desc,
                                'set_official'              => $set_official,
                                'partner_address'           => $partner_address,
                                'city_code'                 => $city_code,
                                'city_name'                 => $city_name,
                                'zip_code'                  => $zip_code,
                                'province_code'             => $province_code,
                                'teritorial_code'           => $teritorial_code,
                                'country_name'              => $country_name,
                                'phone_number'              => $phone_number,
                                'flag_ship'                 => $flag_ship,
                                'flag_bill'                 => $flag_bill,
                                'flag_mail'                 => $flag_mail,
                                'flag_official'             => $flag_official,
                                'flag_other'                => $flag_other,
                                'latitude'                  => $latitude,
                                'longitude'                 => $longitude,
                                'f_active'                  => $f_active,
                                'createdat'                 => $datenow,
                );
                $message = "Data partner address " . $partner_code . " Berhasil di input";
                $this->db->insert('tbl_partner_address', $data);
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

    public function customer_salesman_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $customer_code      = $this->post('customer_code');
        $salesman_code      = $this->post('salesman_code');
        $customer_name      = $this->post('customer_name');
        $salesman_name      = $this->post('salesman_name');
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $region_code        = $this->post('region_code');
        $region_name        = $this->post('region_name');
        $date_from          = $this->post('date_from');
        $date_to            = $this->post('date_to');
        $group_brand_code   = $this->post('group_brand_code');
        $f_active           = $this->post('f_active');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $customer_code != '' && $salesman_code != '') {
            $this->db->select("customer_code");
            $this->db->from("tbl_customer_salesman");
            $this->db->where("customer_code", $customer_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'salesman_code'     => $salesman_code,
                                'customer_name'     => $customer_name,
                                'salesman_name'     => $salesman_name,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'region_code'       => $region_code,
                                'region_name'       => $region_name,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'group_brand_code'  => $group_brand_code,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("customer_code", $customer_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_customer_salesman', $data);
                $message = "Data customer salesman " . $customer_code . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'         => $i_company,
                                'customer_code'     => $customer_code,
                                'salesman_code'     => $salesman_code,
                                'customer_name'     => $customer_name,
                                'salesman_name'     => $salesman_name,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'region_code'       => $region_code,
                                'region_name'       => $region_name,
                                'date_from'         => $date_from,
                                'date_to'           => $date_to,
                                'group_brand_code'  => $group_brand_code,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data customer salesman " . $customer_code . " Berhasil di input";
                $this->db->insert('tbl_customer_salesman', $data);
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

    public function coa_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $code_account       = $this->post('code_account');
        $group_coa          = $this->post('group_coa');
        $coa_description    = $this->post('coa_description');
        $coa_sign           = $this->post('coa_sign');
        $coa_status         = $this->post('coa_status');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $code_account != '' && $group_coa != '') {
            $this->db->select("code_account");
            $this->db->from("tbl_coa");
            $this->db->where("code_account", $code_account);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'group_coa'         => $group_coa,
                                'coa_description'   => $coa_description,
                                'coa_sign'          => $coa_sign,
                                'coa_status'        => $coa_status,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("code_account", $code_account);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_coa', $data);
                $message = "Data coa " . $code_account . " Berhasil di update";
            } else { 
                $data = array(
                                'i_company'         => $i_company,
                                'code_account'      => $code_account,
                                'group_coa'         => $group_coa,
                                'coa_description'   => $coa_description,
                                'coa_sign'          => $coa_sign,
                                'coa_status'        => $coa_status,
                                'createdat'         => $datenow,
                );
                $message = "Data coa " . $code_account . " Berhasil di input";
                $this->db->insert('tbl_coa', $data);
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

    public function activity_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $activity_gl_code   = $this->post('activity_gl_code');
        $activity_gl_name   = $this->post('activity_gl_name');
        $code_account       = $this->post('code_account');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $activity_gl_code != '' && $activity_gl_name != '') {
            $this->db->select("activity_gl_code");
            $this->db->from("tbl_activity");
            $this->db->where("activity_gl_code", $activity_gl_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'activity_gl_name'  => $activity_gl_name,
                                'code_account'      => $code_account,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("activity_gl_code", $activity_gl_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_activity', $data);
                $message = "Data activity " . $activity_gl_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'         => $i_company,
                                'activity_gl_code'  => $activity_gl_code,
                                'activity_gl_name'  => $activity_gl_name,
                                'code_account'      => $code_account,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data activity " . $activity_gl_code . " Berhasil di input";
                $this->db->insert('tbl_activity', $data);
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

    public function activity_gl_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $doc_type_code      = $this->post('doc_type_code');
        $doc_type_name      = $this->post('doc_type_name');
        $activity_gl_code   = $this->post('activity_gl_code');
        $activity_gl_name   = $this->post('activity_gl_name');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $doc_type_code != '' && $doc_type_name != '') {
            $this->db->select("doc_type_code");
            $this->db->from("tbl_activity_gl");
            $this->db->where("doc_type_code", $doc_type_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'doc_type_name'     => $doc_type_name,
                                'activity_gl_code'  => $activity_gl_code,
                                'activity_gl_name'  => $activity_gl_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );   
                $this->db->where("doc_type_code", $doc_type_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_activity_gl', $data);
                $message = "Data activity gl " . $doc_type_code . " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'         => $i_company,
                                'doc_type_code'     => $doc_type_code,
                                'doc_type_name'     => $doc_type_name,
                                'activity_gl_code'  => $activity_gl_code,
                                'activity_gl_name'  => $activity_gl_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data activity gl " . $doc_type_code . " Berhasil di input";
                $this->db->insert('tbl_activity_gl', $data);
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

    public function user_policy_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $username           = $this->post('username');
        $username_rolename  = $this->post('username_rolename');
        $policy_type        = $this->post('policy_type');
        $policy_type_item   = $this->post('policy_type_item');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $username != '' && $username_rolename != '') {
            $this->db->select("username");
            $this->db->from("tbl_user_policy");
            $this->db->where("username", $username);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'username'              => $username,
                                'username_rolename'     => $username_rolename,
                                'policy_type'           => $policy_type,
                                'policy_type_item'      => $policy_type_item,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("username", $username);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_user_policy', $data);
                $message = "Data user policy " . $username . " Berhasil di update";
            } else {     
                $data = array(
                                'i_company'             => $i_company,
                                'username'              => $username,
                                'username_rolename'     => $username_rolename,
                                'policy_type'           => $policy_type,
                                'policy_type_item'      => $policy_type_item,
                                'createdat'             => $datenow,
                );
                $message = "Data user policy " . $username . " Berhasil di input";
                $this->db->insert('tbl_user_policy', $data);
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

    public function bank_post()
    {
        $action        = $this->post('action');
        $api_key       = $this->post('api_key');
        $i_company     = $this->post('i_company');
        $bank_code     = $this->post('bank_code');
        $bank_name     = $this->post('bank_name');
        $f_active      = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $bank_code != '' && $bank_name != '') {
            $this->db->select("bank_code");
            $this->db->from("tbl_bank");
            $this->db->where("bank_code", $bank_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(    
                                'bank_code'     => $bank_code,
                                'bank_name'     => $bank_name,
                                'f_active'      => $f_active,
                                'modifiedat'    => $datenow,
                );   
                $this->db->where("bank_code", $bank_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_bank', $data);
                $message = "Data bank " . $bank_code . " Berhasil di update";
            } else {     
                $data = array(
                                'i_company'     => $i_company,
                                'bank_code'     => $bank_code,
                                'bank_name'     => $bank_name,
                                'f_active'      => $f_active,
                                'createdat'     => $datenow,
                );
                $message = "Data bank " . $bank_code . " Berhasil di input";
                $this->db->insert('tbl_bank', $data);
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

    public function cash_bank_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $cash_bank_code     = $this->post('cash_bank_code');
        $cash_bank_name     = $this->post('cash_bank_name');
        $cash_bank_type     = $this->post('cash_bank_type');
        $bank_code          = $this->post('bank_code');
        $account_bank_no    = $this->post('account_bank_no');
        $account_bank_name  = $this->post('account_bank_name');
        $code_account       = $this->post('code_account');
        $credit_limit       = $this->post('credit_limit');
        $f_active           = $this->post('f_active');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $cash_bank_code != '' && $cash_bank_name != '') {
            $this->db->select("cash_bank_code");
            $this->db->from("tbl_cash_bank");
            $this->db->where("cash_bank_code", $cash_bank_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'cash_bank_name'    => $cash_bank_name,
                                'cash_bank_type'    => $cash_bank_type,
                                'bank_code'         => $bank_code,
                                'account_bank_no'   => $account_bank_no,
                                'account_bank_name' => $account_bank_name,
                                'code_account'      => $code_account,
                                'credit_limit'      => $credit_limit,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );   
                $this->db->where("cash_bank_code", $cash_bank_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_cash_bank', $data);
                $message = "Data cash bank " . $cash_bank_code . " Berhasil di update";
            } else {       
                $data = array(
                                'i_company'         => $i_company,
                                'cash_bank_code'    => $cash_bank_code,
                                'cash_bank_name'    => $cash_bank_name,
                                'cash_bank_type'    => $cash_bank_type,
                                'bank_code'         => $bank_code,
                                'account_bank_no'   => $account_bank_no,
                                'account_bank_name' => $account_bank_name,
                                'code_account'      => $code_account,
                                'credit_limit'      => $credit_limit,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data cash bank " . $cash_bank_code . " Berhasil di input";
                $this->db->insert('tbl_cash_bank', $data);
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

    public function payment_mode_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $payment_mode_code  = $this->post('payment_mode_code');
        $payment_mode_name  = $this->post('payment_mode_name');
        $f_active           = $this->post('f_active');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && ($f_active == 'true' || $f_active == 'false') && $payment_mode_code != '' && $payment_mode_name != '') {
            $this->db->select("payment_mode_code");
            $this->db->from("tbl_payment_mode");
            $this->db->where("payment_mode_code", $payment_mode_code);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'payment_mode_name' => $payment_mode_name,
                                'f_active'          => $f_active,
                                'modifiedat'        => $datenow,
                );   
                $this->db->where("payment_mode_code", $payment_mode_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_payment_mode', $data);
                $message = "Data payment mode " . $payment_mode_code . " Berhasil di update";
            } else {       
                $data = array(
                                'i_company'         => $i_company,
                                'payment_mode_code' => $payment_mode_code,
                                'payment_mode_name' => $payment_mode_name,
                                'f_active'          => $f_active,
                                'createdat'         => $datenow,
                );
                $message = "Data payment mode " . $payment_mode_code . " Berhasil di input";
                $this->db->insert('tbl_payment_mode', $data);
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

#//CASH BANK
    public function kbin_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $doc_type           = $this->post('doc_type');
        $kbin_no            = $this->post('kbin_no');
        $kbin_date          = $this->post('kbin_date');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $kb_name            = $this->post('kb_name');
        $bank_name          = $this->post('bank_name');
        $account_bank_no    = $this->post('account_bank_no');
        $payment_mode_code  = $this->post('payment_mode_code');
        $kbin_amount        = $this->post('kbin_amount');
        $total_cost_amount  = $this->post('total_cost_amount'); 
        $nett_amount        = $this->post('nett_amount');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $kbin_no != '') {
            $this->db->select("ou_code, kbin_no");
            $this->db->from("tbl_kbin");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("kbin_no", $kbin_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'doc_type'              => $doc_type,
                                'kbin_date'             => $kbin_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'account_bank_no'       => $account_bank_no,
                                'payment_mode_code'     => $payment_mode_code,
                                'kbin_amount'           => $kbin_amount,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("kbin_no", $kbin_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_kbin', $data);
                $message = "Data kas bank in " . $kbin_no . " Berhasil di update";
            } else {         
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'doc_type'              => $doc_type,
                                'kbin_no'               => $kbin_no,
                                'kbin_date'             => $kbin_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'account_bank_no'       => $account_bank_no,
                                'payment_mode_code'     => $payment_mode_code,
                                'kbin_amount'           => $kbin_amount,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data kas bank in " . $kbin_no . " Berhasil di input";
                $this->db->insert('tbl_kbin', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_kbin_item where kbin_no = '$kbin_no' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'kbin_no'           => $row['kbin_no'],
                                    'activity_gl_code'  => $row['activity_gl_code'],
                                    'cost_amount'       => $row['cost_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_kbin_item', $data);
                }   
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

    public function kbin_dt_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $doc_type           = $this->post('doc_type');
        $kbin_dt_no         = $this->post('kbin_dt_no');
        $kbin_dt_date       = $this->post('kbin_dt_date');
        $dt_no              = $this->post('dt_no');
        $dt_date            = $this->post('dt_date');
        $employee_code      = $this->post('employee_code');
        $employee_name      = $this->post('employee_name');
        $bank_name          = $this->post('bank_name');
        $kb_name            = $this->post('kb_name');
        $account_bank_no    = $this->post('account_bank_no');
        $payment_mode_code  = $this->post('payment_mode_code');
        $kbin_amount        = $this->post('kbin_amount');
        $total_cost_amount  = $this->post('total_cost_amount');
        $nett_amount        = $this->post('nett_amount');
        $username           = $this->post('username'); 
        $role_name          = $this->post('role_name');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $kbin_dt_no != '') {
            $this->db->select("ou_code, kbin_dt_no");
            $this->db->from("tbl_kbin_dt");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("kbin_dt_no", $kbin_dt_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'doc_type'              => $doc_type,
                                'kbin_dt_date'          => $kbin_dt_date,
                                'dt_no'                 => $dt_no,  
                                'dt_date'               => $dt_date,
                                'employee_code'         => $employee_code,
                                'employee_name'         => $employee_name,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'account_bank_no'       => $account_bank_no,
                                'payment_mode_code'     => $payment_mode_code,
                                'kbin_amount'           => $kbin_amount,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'username'              => $username,
                                'role_name'             => $role_name,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("kbin_dt_no", $kbin_dt_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_kbin_dt', $data);
                $message = "Data kas bank in daftar tagihan " . $kbin_dt_no . " Berhasil di update";
            } else {           
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'doc_type'              => $doc_type,
                                'kbin_dt_no'            => $kbin_dt_no,
                                'kbin_dt_date'          => $kbin_dt_date,
                                'dt_no'                 => $dt_no,  
                                'dt_date'               => $dt_date,
                                'employee_code'         => $employee_code,
                                'employee_name'         => $employee_name,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'account_bank_no'       => $account_bank_no,
                                'payment_mode_code'     => $payment_mode_code,
                                'kbin_amount'           => $kbin_amount,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'username'              => $username,
                                'role_name'             => $role_name,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data kas bank in daftar tagihan " . $kbin_dt_no . " Berhasil di input";
                $this->db->insert('tbl_kbin_dt', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_kbin_dt_item where kbin_dt_no = '$kbin_dt_no' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'kbin_dt_no'        => $row['kbin_dt_no'],
                                    'activity_gl_code'  => $row['activity_gl_code'],
                                    'cost_amount'       => $row['cost_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_kbin_dt_item', $data);
                }   
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

    public function conv_kbinother_kbinpartner_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $doc_type           = $this->post('doc_type');
        $conv_kbin_no       = $this->post('conv_kbin_no');
        $conv_kbin_date     = $this->post('conv_kbin_date');
        $ou_partner_code    = $this->post('ou_partner_code');
        $dt_no              = $this->post('dt_no');
        $dt_date            = $this->post('dt_date');
        $kb_name            = $this->post('kb_name');
        $bank_name          = $this->post('bank_name');
        $payment_mode_code  = $this->post('payment_mode_code');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $kb_amount          = $this->post('kb_amount');
        $total_cost_amount  = $this->post('total_cost_amount');
        $nett_amount        = $this->post('nett_amount');
        $conv_amount        = $this->post('conv_amount');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $conv_kbin_no != '') {
            $this->db->select("ou_code, conv_kbin_no");
            $this->db->from("tbl_conv_kbinother_kbinpartner");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("conv_kbin_no", $conv_kbin_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'doc_type'              => $doc_type,
                                'conv_kbin_date'        => $conv_kbin_date,
                                'dt_no'                 => $dt_no,
                                'dt_date'               => $dt_date,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'payment_mode_code'     => $payment_mode_code,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'kb_amount'             => $kb_amount,
                                'conv_amount'           => $conv_amount,
                                'ou_partner_code'       => $ou_partner_code,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("conv_kbin_no", $conv_kbin_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_conv_kbinother_kbinpartner', $data);
                $message = "Data conversion cash bank in other " . $conv_kbin_no . " Berhasil di update";
            } else {         
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'doc_type'              => $doc_type,
                                'conv_kbin_no'          => $conv_kbin_no,
                                'conv_kbin_date'        => $conv_kbin_date,
                                'dt_no'                 => $dt_no,
                                'dt_date'               => $dt_date,
                                'kb_name'               => $kb_name,
                                'bank_name'             => $bank_name,
                                'payment_mode_code'     => $payment_mode_code,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'kb_amount'             => $kb_amount,
                                'conv_amount'           => $conv_amount,
                                'ou_partner_code'       => $ou_partner_code,
                                'total_cost_amount'     => $total_cost_amount,
                                'nett_amount'           => $nett_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data conversion cash bank in other " . $conv_kbin_no . " Berhasil di input";
                $this->db->insert('tbl_conv_kbinother_kbinpartner', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_conv_kbinother_kbinpartner_item where conv_kbin_no = '$conv_kbin_no' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'conv_kbin_no'      => $row['conv_kbin_no'],
                                    'activity_gl_code'  => $row['activity_gl_code'],
                                    'cost_amount'       => $row['cost_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_conv_kbinother_kbinpartner_item', $data);
                }  
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

    public function conv_kbpartner_to_kbinother_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $doc_type           = $this->post('doc_type');
        $conv_kbin_no       = $this->post('conv_kbin_no');
        $conv_kbin_date     = $this->post('conv_kbin_date');
        $partner_group      = $this->post('partner_group');
        $partner_name       = $this->post('partner_name');
        $kb_name            = $this->post('kb_name');
        $conv_amount        = $this->post('conv_amount');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $conv_kbin_no != '') {
            $this->db->select("ou_code, conv_kbin_no");
            $this->db->from("tbl_conv_kbpartner_to_kbinother");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("conv_kbin_no", $conv_kbin_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'doc_type'              => $doc_type,     
                                'conv_kbin_date'        => $conv_kbin_date,
                                'partner_group'         => $partner_group,
                                'partner_name'          => $partner_name,
                                'kb_name'               => $kb_name,
                                'conv_amount'           => $conv_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("conv_kbin_no", $conv_kbin_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_conv_kbpartner_to_kbinother', $data);
                $message = "Data conversion cash bank partner to other " . $conv_kbin_no . " Berhasil di update";
            } else {         
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'doc_type'              => $doc_type,
                                'conv_kbin_no'          => $conv_kbin_no,
                                'conv_kbin_date'        => $conv_kbin_date,
                                'partner_group'         => $partner_group,
                                'partner_name'          => $partner_name,
                                'kb_name'               => $kb_name,
                                'conv_amount'           => $conv_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data conversion cash bank partner to other " . $conv_kbin_no . " Berhasil di input";
                $this->db->insert('tbl_conv_kbpartner_to_kbinother', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_conv_kbpartner_to_kbinother_item where conv_kbin_no = '$conv_kbin_no' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'conv_kbin_no'      => $row['conv_kbin_no'],
                                    'activity_gl_code'  => $row['activity_gl_code'],
                                    'cost_amount'       => $row['cost_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_conv_kbpartner_to_kbinother_item', $data);
                }  
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

    public function terima_cekgiro_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $doc_type           = $this->post('doc_type');
        $receive_no         = $this->post('receive_no');
        $receive_date       = $this->post('receive_date');
        $ref_doc_dt_no      = $this->post('ref_doc_dt_no');
        $ref_doc_dt_date    = $this->post('ref_doc_dt_date');
        $employee_code      = $this->post('employee_code');
        $employee_name      = $this->post('employee_name'); 
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $status_doc         = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $receive_no != '') {
            $this->db->select("ou_code, receive_no");
            $this->db->from("tbl_terima_cekgiro");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("receive_no", $receive_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'doc_type'                  => $doc_type,
                                'receive_date'              => $receive_date,
                                'ref_doc_dt_no'             => $ref_doc_dt_no,
                                'ref_doc_dt_date'           => $ref_doc_dt_date,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("receive_no", $receive_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_terima_cekgiro', $data);
                $message = "Data terima cek giro " . $receive_no . " Berhasil di update";
            } else {               
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'doc_type'                  => $doc_type,
                                'receive_no'                => $receive_no,
                                'receive_date'              => $receive_date,
                                'ref_doc_dt_no'             => $ref_doc_dt_no,
                                'ref_doc_dt_date'           => $ref_doc_dt_date,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data terima cek giro " . $receive_no . " Berhasil di input";
                $this->db->insert('tbl_terima_cekgiro', $data);
            }
            $query = $this->db->query("
                DELETE FROM tbl_terima_cekgiro_item where receive_no = '$receive_no' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'receive_no'            => $row['receive_no'],
                                    'partner_code'          => $row['partner_code'],
                                    'partner_name'          => $row['partner_name'],
                                    'payment_mode_code'     => $row['payment_mode_code'],
                                    'bank_name'             => $row['bank_name'],
                                    'cek_giro_no'           => $row['cek_giro_no'],
                                    'cek_giro_date'         => $row['cek_giro_date'],
                                    'realization_date'      => $row['realization_date'],
                                    'cek_giro_amount'       => $row['cek_giro_amount'],
                                    'remark'                => $row['remark'],
                    );
                    $this->db->insert('tbl_terima_cekgiro_item', $data);
                }    
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

    public function setor_giro_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $depositor_name     = $this->post('depositor_name');
        $depositor_date     = $this->post('depositor_date');
        $bank_code          = $this->post('bank_code');
        $bank_name          = $this->post('bank_name');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $cek_giro_no        = $this->post('cek_giro_no');
        $cek_giro_date      = $this->post('cek_giro_date');
        $cek_giro_amount    = $this->post('cek_giro_amount');
        $remark             = $this->post('remark');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $status             = $this->post('status');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $depositor_name != '') {
            $this->db->select("ou_code, depositor_name");
            $this->db->from("tbl_setor_giro");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("depositor_name", $depositor_name);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'depositor_date'    => $depositor_date,
                                'bank_code'         => $bank_code,
                                'bank_name'         => $bank_name,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'cek_giro_no'       => $cek_giro_no,
                                'cek_giro_date'     => $cek_giro_date,
                                'cek_giro_amount'   => $cek_giro_amount,
                                'remark'            => $remark,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'status'            => $status,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("depositor_name", $depositor_name);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_setor_giro', $data);
                $message = "Data setor giro " . $depositor_name . " Berhasil di update";
            } else {                 
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'depositor_name'    => $depositor_name,
                                'depositor_date'    => $depositor_date,
                                'bank_code'         => $bank_code,
                                'bank_name'         => $bank_name,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'cek_giro_no'       => $cek_giro_no,
                                'cek_giro_date'     => $cek_giro_date,
                                'cek_giro_amount'   => $cek_giro_amount,
                                'remark'            => $remark,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'status'            => $status,
                                'status_doc'        => $status_doc,
                                'createdat'         => $datenow,
                );
                $message = "Data setor giro " . $depositor_name . " Berhasil di input";
                $this->db->insert('tbl_setor_giro', $data);
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

    public function request_cashadvance_post()
    {
        $action                     = $this->post('action');
        $api_key                    = $this->post('api_key');
        $i_company                  = $this->post('i_company');
        $ou_code                    = $this->post('ou_code');
        $request_cashadvance_no     = $this->post('request_cashadvance_no');
        $request_cashadvance_date   = $this->post('request_cashadvance_date');
        $due_date                   = $this->post('due_date');
        $cash_type                  = $this->post('cash_type');
        $employee_code              = $this->post('employee_code');
        $employee_name              = $this->post('employee_name');
        $advance_amount             = $this->post('advance_amount');
        $username                   = $this->post('username');
        $role_name                  = $this->post('role_name');
        $remark                     = $this->post('remark');
        $status_doc                 = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $request_cashadvance_no != '') {
            $this->db->select("ou_code, request_cashadvance_no");
            $this->db->from("tbl_request_cashadvance");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("request_cashadvance_no", $request_cashadvance_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'request_cashadvance_date'  => $request_cashadvance_date,
                                'due_date'                  => $due_date,
                                'cash_type'                 => $cash_type,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'advance_amount'            => $advance_amount,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("request_cashadvance_no", $request_cashadvance_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_request_cashadvance', $data);
                $message = "Data request cash advance " . $request_cashadvance_no . " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'request_cashadvance_no'    => $request_cashadvance_no, 
                                'request_cashadvance_date'  => $request_cashadvance_date,
                                'due_date'                  => $due_date,
                                'cash_type'                 => $cash_type,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'advance_amount'            => $advance_amount,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data request cash advance " . $request_cashadvance_no . " Berhasil di input";
                $this->db->insert('tbl_request_cashadvance', $data);
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

    public function cashadvance_post()
    {
        $action                     = $this->post('action');
        $api_key                    = $this->post('api_key');
        $i_company                  = $this->post('i_company');
        $ou_code                    = $this->post('ou_code');
        $cashadvance_no             = $this->post('cashadvance_no');
        $cashadvance_date           = $this->post('cashadvance_date');
        $request_cashadvance_no     = $this->post('request_cashadvance_no');
        $request_cashadvance_date   = $this->post('request_cashadvance_date');
        $kbout_no                   = $this->post('kbout_no');
        $kbout_date                 = $this->post('kbout_date');
        $kbout_amount               = $this->post('kbout_amount');
        $employee_code              = $this->post('employee_code');
        $employee_name              = $this->post('employee_name');
        $total_allocated_amount     = $this->post('total_allocated_amount');
        $settlement_amount          = $this->post('settlement_amount');
        $username                   = $this->post('username');
        $role_name                  = $this->post('role_name');
        $remark                     = $this->post('remark');
        $status_doc                 = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();
  
        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $cashadvance_no != '') {
            $this->db->select("ou_code, cashadvance_no");
            $this->db->from("tbl_cashadvance");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("cashadvance_no", $cashadvance_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'cashadvance_date'          => $cashadvance_date,
                                'request_cashadvance_no'    => $request_cashadvance_no,
                                'request_cashadvance_date'  => $request_cashadvance_date,
                                'kbout_no'                  => $kbout_no,
                                'kbout_date'                => $kbout_date,
                                'kbout_amount'              => $kbout_amount,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'total_allocated_amount'    => $total_allocated_amount,
                                'settlement_amount'         => $settlement_amount,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("cashadvance_no", $cashadvance_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_cashadvance', $data);
                $message = "Data cash advance " . $cashadvance_no . " Berhasil di update";
            } else {     
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'cashadvance_no'            => $cashadvance_no,
                                'cashadvance_date'          => $cashadvance_date,
                                'request_cashadvance_no'    => $request_cashadvance_no,
                                'request_cashadvance_date'  => $request_cashadvance_date,
                                'kbout_no'                  => $kbout_no,
                                'kbout_date'                => $kbout_date,
                                'kbout_amount'              => $kbout_amount,
                                'employee_code'             => $employee_code,
                                'employee_name'             => $employee_name,
                                'total_allocated_amount'    => $total_allocated_amount,
                                'settlement_amount'         => $settlement_amount,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data cash advance " . $cashadvance_no . " Berhasil di input";
                $this->db->insert('tbl_cashadvance', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_cashadvance_item where cashadvance_no = '$cashadvance_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'cashadvance_no'        => $row['cashadvance_no'],
                                    'ou_rc'                 => $row['ou_rc'],
                                    'activity_gl_code'      => $row['activity_gl_code'],
                                    'segment'               => $row['segment'],
                                    'cost_amount'           => $row['cost_amount'],
                                    'allocated_amount'      => $row['allocated_amount'],
                                    'remark'                => $row['remark']
                    );
                    $this->db->insert('tbl_cashadvance_item', $data);
                }   
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

    public function paymentorder_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $paymentorder_no        = $this->post('paymentorder_no');
        $paymentorder_date      = $this->post('paymentorder_date');
        $reff_no                = $this->post('reff_no');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $group_partner          = $this->post('group_partner');
        $account_bank_no        = $this->post('account_bank_no');
        $account_bank_name      = $this->post('account_bank_name');
        $bank_name              = $this->post('bank_name');
        $total_amount           = $this->post('total_amount');
        $remark                 = $this->post('remark');
        $username               = $this->post('username');
        $role_name              = $this->post('role_name');
        $status_doc             = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $paymentorder_no != '') {
            $this->db->select("ou_code, paymentorder_no");
            $this->db->from("tbl_paymentorder");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("paymentorder_no", $paymentorder_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'paymentorder_date'     => $paymentorder_date,
                                'reff_no'               => $reff_no,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'group_partner'         => $group_partner,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount'          => $total_amount,
                                'remark'                => $remark,
                                'username'              => $username,
                                'role_name'             => $role_name,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("paymentorder_no", $paymentorder_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_paymentorder', $data);
                $message = "Data payment order " . $paymentorder_no . " Berhasil di update";
            } else {    



                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'paymentorder_no'       => $paymentorder_no,
                                'paymentorder_date'     => $paymentorder_date,
                                'reff_no'               => $reff_no,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'group_partner'         => $group_partner,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount'          => $total_amount,
                                'remark'                => $remark,
                                'username'              => $username,
                                'role_name'             => $role_name,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data payment order " . $paymentorder_no . " Berhasil di input";
                $this->db->insert('tbl_paymentorder', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_paymentorder_item where paymentorder_no = '$paymentorder_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'paymentorder_no'       => $row['paymentorder_no'],
                                    'activity_gl_code'      => $row['activity_gl_code'],
                                    'ou_rc'                 => $row['ou_rc'],
                                    'segment'               => $row['segment'],
                                    'cost_amount'           => $row['cost_amount'],
                                    'remark'                => $row['remark'],
                    );
                    $this->db->insert('tbl_paymentorder_item', $data);
                }   
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

    public function kbout_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $kbout_no               = $this->post('kbout_no');
        $kbout_date             = $this->post('kbout_date');
        $ref_doc_type           = $this->post('ref_doc_type');
        $reff_no                = $this->post('reff_no');
        $reff_date              = $this->post('reff_date');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $account_bank_no        = $this->post('account_bank_no');
        $account_bank_name      = $this->post('account_bank_name');
        $bank_name              = $this->post('bank_name');
        $total_amount_to_pay    = $this->post('total_amount_to_pay');
        $cash_amount            = $this->post('cash_amount');
        $bank_amount            = $this->post('bank_amount');
        $total_amount           = $this->post('total_amount');
        $rounding               = $this->post('rounding');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $kbout_no != '') {
            $this->db->select("ou_code, kbout_no");
            $this->db->from("tbl_kbout");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("kbout_no", $kbout_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'kbout_date'            => $kbout_date,
                                'ref_doc_type'          => $ref_doc_type,
                                'reff_no'               => $reff_no,
                                'reff_date'             => $reff_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount_to_pay'   => $total_amount_to_pay,
                                'cash_amount'           => $cash_amount,
                                'bank_amount'           => $bank_amount,
                                'total_amount'          => $total_amount,
                                'rounding'              => $rounding,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("kbout_no", $kbout_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_kbout', $data);
                $message = "Data kas bank out " . $kbout_no . " Berhasil di update";
            } else {           
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'kbout_no'              => $kbout_no,
                                'kbout_date'            => $kbout_date,
                                'ref_doc_type'          => $ref_doc_type,
                                'reff_no'               => $reff_no,
                                'reff_date'             => $reff_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount_to_pay'   => $total_amount_to_pay,
                                'cash_amount'           => $cash_amount,
                                'bank_amount'           => $bank_amount,
                                'total_amount'          => $total_amount,
                                'remark'                => $remark,
                                'rounding'              => $rounding,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data kas bank out " . $kbout_no . " Berhasil di input";
                $this->db->insert('tbl_kbout', $data);
            }
            $query = $this->db->query("
                DELETE FROM tbl_kbout_item where kbout_no = '$kbout_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'kbout_no'              => $row['kbout_no'],
                                    'bank_code'             => $row['bank_code'],
                                    'bank_name'             => $row['bank_name'],
                                    'account_bank_no'       => $row['account_bank_no'],
                                    'account_bank_name'     => $row['account_bank_no'],
                                    'payment_mode'          => $row['payment_mode'],
                                    'payment_ref_no'        => $row['payment_ref_no'],
                                    'payment_ref_date'      => $row['payment_ref_date'],
                                    'bank_amount'           => $row['bank_amount'],
                                    'cash_amount'           => $row['cash_amount'],
                                    'amount'                => $row['amount'],
                                    'remark'                => $row['remark'],
                    );   
                    $this->db->insert('tbl_kbout_item', $data);
                }   
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

    public function cashadvance_settlement_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $dok_no                 = $this->post('dok_no');
        $dok_date               = $this->post('dok_date');
        $reff_no                = $this->post('reff_no');
        $reff_date              = $this->post('reff_date');
        $partner_code           = $this->post('partner_code');
        $partner_name           = $this->post('partner_name');
        $account_bank_no        = $this->post('account_bank_no');
        $account_bank_name      = $this->post('account_bank_name');
        $bank_name              = $this->post('bank_name');
        $total_amount_to_pay    = $this->post('total_amount_to_pay');
        $cash_amount            = $this->post('cash_amount');
        $bank_amount            = $this->post('bank_amount');
        $total_amount           = $this->post('total_amount');
        $rounding               = $this->post('rounding');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $dok_no != '') {
            $this->db->select("ou_code, dok_no");
            $this->db->from("tbl_cashadvance_settlement");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("dok_no", $dok_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'dok_no'                => $dok_no,
                                'dok_date'              => $dok_date,
                                'reff_no'               => $reff_no,
                                'reff_date'             => $reff_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount_to_pay'   => $total_amount_to_pay,
                                'cash_amount'           => $cash_amount,
                                'bank_amount'           => $bank_amount,
                                'total_amount'          => $total_amount,
                                'rounding'              => $rounding,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("dok_no", $dok_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_cashadvance_settlement', $data);
                $message = "Data Follow Up cash advance settlement " . $dok_no . " Berhasil di update";
            } else {             
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'dok_no'                => $dok_no,
                                'dok_date'              => $dok_date,
                                'reff_no'               => $reff_no,
                                'reff_date'             => $reff_date,
                                'partner_code'          => $partner_code,
                                'partner_name'          => $partner_name,
                                'account_bank_no'       => $account_bank_no,
                                'account_bank_name'     => $account_bank_name,
                                'bank_name'             => $bank_name,
                                'total_amount_to_pay'   => $total_amount_to_pay,
                                'cash_amount'           => $cash_amount,
                                'bank_amount'           => $bank_amount,
                                'total_amount'          => $total_amount,
                                'rounding'              => $rounding,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data Follow Up cash advance settlement " . $dok_no . " Berhasil di input";
                $this->db->insert('tbl_cashadvance_settlement', $data);
            }
            $query = $this->db->query("
                DELETE FROM tbl_cashadvance_settlement_item where dok_no = '$dok_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'dok_no'                => $row['dok_no'],
                                    'kbout_no'              => $row['kbout_no'],
                                    'bank_code'             => $row['bank_code'],  
                                    'bank_name'             => $row['bank_name'],
                                    'account_bank_no'       => $row['account_bank_no'],
                                    'account_bank_name'     => $row['account_bank_name'],
                                    'payment_mode'          => $row['payment_mode'],
                                    'payment_ref_date'      => $row['payment_ref_date'],
                                    'bank_amount'           => $row['bank_amount'],
                                    'cash_amount'           => $row['cash_amount'],
                                    'amount'                => $row['amount'],
                                    'remark'                => $row['remark'],
                    );     
                    $this->db->insert('tbl_cashadvance_settlement_item', $data);
                }   
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

#--PIUTANG
    public function invoice_ar_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $ou_name                = $this->post('ou_name');
        $doc_type               = $this->post('doc_type');
        $invoice_no             = $this->post('invoice_no');
        $invoice_date           = $this->post('invoice_date');
        $due_date               = $this->post('due_date');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $so_no                  = $this->post('so_no');
        $so_date                = $this->post('so_date');
        $do_no                  = $this->post('do_no');
        $do_date                = $this->post('do_date');
        $salesman_name          = $this->post('salesman_name');
        $region_name            = $this->post('region_name');
        $city_name              = $this->post('city_name');
        $total_nett_amount      = $this->post('total_nett_amount');
        $total_pelunasan        = $this->post('total_pelunasan');
        $outstanding_amount     = $this->post('outstanding_amount');
        $aging                  = $this->post('aging');
        $product_group          = $this->post('product_group');
        $status_invoice         = $this->post('status_invoice');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $invoice_no != '') {
            $this->db->select("ou_code, invoice_no");
            $this->db->from("tbl_invoice_ar");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("invoice_no", $invoice_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'ou_name'                   => $ou_name,
                                'doc_type'                  => $doc_type,
                                'invoice_date'              => $invoice_date,
                                'due_date'                  => $due_date,
                                'customer_code'             => $customer_code,
                                'customer_name'             => $customer_name,
                                'so_no'                     => $so_no,
                                'so_date'                   => $so_date,
                                'do_no'                     => $do_no,  
                                'do_date'                   => $do_date,
                                'salesman_name'             => $salesman_name,
                                'region_name'               => $region_name,
                                'city_name'                 => $city_name,
                                'total_nett_amount'         => $total_nett_amount,
                                'total_pelunasan'           => $total_pelunasan,
                                'outstanding_amount'        => $outstanding_amount,
                                'aging'                     => $aging,
                                'product_group'             => $product_group,
                                'status_invoice'            => $status_invoice,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("invoice_no", $invoice_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_invoice_ar', $data);
                $message = "Data invoice ar " . $invoice_no . " Berhasil di update";
            } else {             
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'ou_name'                   => $ou_name,
                                'doc_type'                  => $doc_type,
                                'invoice_no'                => $invoice_no,
                                'invoice_date'              => $invoice_date,
                                'due_date'                  => $due_date,
                                'customer_code'             => $customer_code,
                                'customer_name'             => $customer_name,
                                'so_no'                     => $so_no,
                                'so_date'                   => $so_date,
                                'do_no'                     => $do_no,  
                                'do_date'                   => $do_date,
                                'salesman_name'             => $salesman_name,
                                'region_name'               => $region_name,
                                'city_name'                 => $city_name,
                                'total_nett_amount'         => $total_nett_amount,
                                'total_pelunasan'           => $total_pelunasan,
                                'outstanding_amount'        => $outstanding_amount,
                                'aging'                     => $aging,
                                'product_group'             => $product_group,
                                'status_invoice'            => $status_invoice,
                                'createdat'                 => $datenow,
                );
                $message = "Data invoice ar " . $invoice_no . " Berhasil di input";
                $this->db->insert('tbl_invoice_ar', $data);
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

    public function daftar_tagihan_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $dt_no                  = $this->post('dt_no');
        $dt_date                = $this->post('dt_date');
        $salesman_code          = $this->post('salesman_code');
        $salesman_name          = $this->post('salesman_name');
        $total_invoice_amount   = $this->post('total_invoice_amount');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $dt_no != '') {
            $this->db->select("ou_code, dt_no");
            $this->db->from("tbl_daftar_tagihan");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("dt_no", $dt_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'dt_date'                   => $dt_date,
                                'salesman_code'             => $salesman_code,
                                'salesman_name'             => $salesman_name,
                                'total_invoice_amount'      => $total_invoice_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("dt_no", $dt_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_daftar_tagihan', $data);
                $message = "Data daftar tagihan " . $dt_no . " Berhasil di update";
            } else {               
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'dt_no'                     => $dt_no,
                                'dt_date'                   => $dt_date,
                                'salesman_code'             => $salesman_code,
                                'salesman_name'             => $salesman_name,
                                'total_invoice_amount'      => $total_invoice_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data daftar tagihan " . $dt_no . " Berhasil di input";
                $this->db->insert('tbl_daftar_tagihan', $data);
            }
            $query = $this->db->query("
                DELETE FROM tbl_daftar_tagihan_item where dt_no = '$dt_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'dt_no'                 => $row['dt_no'],
                                    'doc_type'              => $row['doc_type'],
                                    'invoice_no'            => $row['invoice_no'],
                                    'invoice_date'          => $row['invoice_date'],
                                    'ref_remain_amount'     => $row['ref_remain_amount'],
                                    'os_invoice_amount'     => $row['os_invoice_amount'],
                                    'salesman_code'         => $row['salesman_code'],
                                    'customer_code'         => $row['customer_code'],
                                    'customer_name'         => $row['customer_name'],
                                    'region_name'           => $row['region_name'],
                                    'city_name'             => $row['city_name'],
                                    'remark'                => $row['remark'],
                    );
                    $this->db->insert('tbl_daftar_tagihan_item', $data);
                }     
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

    public function alloc_kbin_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $alloc_no               = $this->post('alloc_no');
        $alloc_date             = $this->post('alloc_date');
        $doc_type               = $this->post('doc_type');
        $pelunasan_type         = $this->post('pelunasan_type');
        $kbin_no                = $this->post('kbin_no');
        $kbin_date              = $this->post('kbin_date');
        $bank_name              = $this->post('bank_name');
        $giro_no                = $this->post('giro_no');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $kbin_amount            = $this->post('kbin_amount');
        $payment_amount         = $this->post('payment_amount');
        $remaining_amount       = $this->post('remaining_amount');
        $activity_gl            = $this->post('activity_gl');
        $ou_rc                  = $this->post('ou_rc');
        $segment                = $this->post('segment');
        $keep_remaining_amount  = $this->post('keep_remaining_amount');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $alloc_no != '') {
            $this->db->select("ou_code, alloc_no");
            $this->db->from("tbl_alloc_kbin");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("alloc_no", $alloc_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'alloc_date'                => $alloc_date,
                                'doc_type'                  => $doc_type,
                                'pelunasan_type'            => $pelunasan_type,
                                'kbin_no'                   => $kbin_no,
                                'kbin_date'                 => $kbin_date,
                                'bank_name'                 => $bank_name,
                                'giro_no'                   => $giro_no,
                                'customer_code'             => $customer_code,
                                'customer_name'             => $customer_name,
                                'kbin_amount'               => $kbin_amount,
                                'payment_amount'            => $payment_amount,
                                'remaining_amount'          => $remaining_amount,
                                'activity_gl'               => $activity_gl,
                                'ou_rc'                     => $ou_rc,
                                'segment'                   => $segment,
                                'keep_remaining_amount'     => $keep_remaining_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("alloc_no", $alloc_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_alloc_kbin', $data);
                $message = "Data alokasi kas bank in " . $alloc_no . " Berhasil di update";
            } else {        
          
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'alloc_no'                  => $alloc_no,
                                'alloc_date'                => $alloc_date,
                                'doc_type'                  => $doc_type,
                                'pelunasan_type'            => $pelunasan_type,
                                'kbin_no'                   => $kbin_no,
                                'kbin_date'                 => $kbin_date,
                                'bank_name'                 => $bank_name,
                                'giro_no'                   => $giro_no,
                                'customer_code'             => $customer_code,
                                'customer_name'             => $customer_name,
                                'kbin_amount'               => $kbin_amount,
                                'payment_amount'            => $payment_amount,
                                'remaining_amount'          => $remaining_amount,
                                'activity_gl'               => $activity_gl,
                                'ou_rc'                     => $ou_rc,
                                'segment'                   => $segment,
                                'keep_remaining_amount'     => $keep_remaining_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data alokasi kas bank in " . $alloc_no . " Berhasil di input";
                $this->db->insert('tbl_alloc_kbin', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_alloc_kbin_item where alloc_no = '$alloc_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'                     => $row['i_company'],
                                    'ou_code'                       => $row['ou_code'],
                                    'alloc_no'                      => $row['alloc_no'],
                                    'doc_type'                      => $row['doc_type'],
                                    'invoice_no'                    => $row['invoice_no'],
                                    'invoice_date'                  => $row['invoice_date'],
                                    'due_date'                      => $row['due_date'],
                                    'ref_remain_amount'             => $row['ref_remain_amount'],
                                    'allocated_invoice_amount'      => $row['allocated_invoice_amount'],  
                                    'payment_amount'                => $row['payment_amount'],
                                    'remark'                        => $row['remark'],
                    );
                    $this->db->insert('tbl_alloc_kbin_item', $data);
                }
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

    public function alloc_kbin_dt_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $alloc_dt_no            = $this->post('alloc_dt_no');
        $alloc_dt_date          = $this->post('alloc_dt_date');
        $doc_type               = $this->post('doc_type');
        $pelunasan_type         = $this->post('pelunasan_type');
        $employee_code          = $this->post('employee_code');
        $kbin_dt_no             = $this->post('kbin_dt_no');
        $kbin_dt_date           = $this->post('kbin_dt_date');
        $dt_no                  = $this->post('dt_no');
        $dt_date                = $this->post('dt_date');
        $kbin_amount            = $this->post('kbin_amount');
        $payment_amount         = $this->post('payment_amount');
        $remaining_amount       = $this->post('remaining_amount');
        $activity_gl            = $this->post('activity_gl');
        $ou_rc                  = $this->post('ou_rc');
        $segment                = $this->post('segment');
        $keep_remaining_amount  = $this->post('keep_remaining_amount');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $alloc_dt_no != '') {
            $this->db->select("ou_code, alloc_dt_no");
            $this->db->from("tbl_alloc_kbin_dt");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("alloc_dt_no", $alloc_dt_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'alloc_dt_no'               => $alloc_dt_no,
                                'alloc_dt_date'             => $alloc_dt_date,
                                'doc_type'                  => $doc_type,
                                'pelunasan_type'            => $pelunasan_type,
                                'employee_code'             => $employee_code,
                                'kbin_dt_no'                => $kbin_dt_no,
                                'kbin_dt_date'              => $kbin_dt_date,
                                'dt_no'                     => $dt_no,
                                'dt_date'                   => $dt_date,
                                'kbin_amount'               => $kbin_amount,
                                'payment_amount'            => $payment_amount,
                                'remaining_amount'          => $remaining_amount,
                                'activity_gl'               => $activity_gl,
                                'ou_rc'                     => $ou_rc,
                                'segment'                   => $segment,
                                'keep_remaining_amount'     => $keep_remaining_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'modifiedat'                => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("alloc_dt_no", $alloc_dt_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_alloc_kbin_dt', $data);
                $message = "Data alokasi kas bank in daftar tagihan" . $alloc_dt_no . " Berhasil di update";
            } else {           
                $data = array(
                                'i_company'                 => $i_company,
                                'ou_code'                   => $ou_code,
                                'alloc_dt_no'               => $alloc_dt_no,
                                'alloc_dt_date'             => $alloc_dt_date,
                                'doc_type'                  => $doc_type,
                                'pelunasan_type'            => $pelunasan_type,
                                'employee_code'             => $employee_code,
                                'kbin_dt_no'                => $kbin_dt_no,
                                'kbin_dt_date'              => $kbin_dt_date,
                                'dt_no'                     => $dt_no,
                                'dt_date'                   => $dt_date,
                                'kbin_amount'               => $kbin_amount,
                                'payment_amount'            => $payment_amount,
                                'remaining_amount'          => $remaining_amount,
                                'activity_gl'               => $activity_gl,
                                'ou_rc'                     => $ou_rc,
                                'segment'                   => $segment,
                                'keep_remaining_amount'     => $keep_remaining_amount,
                                'remark'                    => $remark,
                                'status_doc'                => $status_doc,
                                'createdat'                 => $datenow,
                );
                $message = "Data alokasi kas bank in daftar tagihan " . $alloc_dt_no . " Berhasil di input";
                $this->db->insert('tbl_alloc_kbin_dt', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_alloc_kbin_dt_item where alloc_dt_no = '$alloc_dt_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'                     => $row['i_company'],
                                    'ou_code'                       => $row['ou_code'],
                                    'alloc_dt_no'                   => $row['alloc_dt_no'],
                                    'ref_doc_type'                  => $row['ref_doc_type'],
                                    'invoice_no'                    => $row['invoice_no'],
                                    'invoice_date'                  => $row['invoice_date'],
                                    'due_date'                      => $row['due_date'],
                                    'customer_code'                 => $row['customer_code'],
                                    'customer_name'                 => $row['customer_name'],
                                    'ref_remain_amount'             => $row['ref_remain_amount'],
                                    'allocated_invoice_amount'      => $row['allocated_invoice_amount'],
                                    'payment_amount'                => $row['payment_amount'],
                                    'remark'                        => $row['remark'],
                    );
                    $this->db->insert('tbl_alloc_kbin_dt_item', $data);
                }
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

    public function alloc_creditar_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $alloc_kredit_no        = $this->post('alloc_kredit_no');
        $alloc_kredit_date      = $this->post('alloc_kredit_date');
        $doc_type               = $this->post('doc_type');
        $pelunasan_type         = $this->post('pelunasan_type');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $credit_invoice_no      = $this->post('credit_invoice_no');
        $credit_invoice_amount  = $this->post('credit_invoice_amount');
        $payment_amount         = $this->post('payment_amount');
        $remaining_amount       = $this->post('remaining_amount');
        $remark                 = $this->post('remark');
        $status_doc             = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $alloc_kredit_no != '') {
            $this->db->select("ou_code, alloc_kredit_no");
            $this->db->from("tbl_alloc_creditar");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("alloc_kredit_no", $alloc_kredit_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array( 
                                'alloc_kredit_date'     => $alloc_kredit_date,
                                'doc_type'              => $doc_type,
                                'pelunasan_type'        => $pelunasan_type,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'credit_invoice_no'     => $credit_invoice_no,
                                'credit_invoice_amount' => $credit_invoice_amount,
                                'payment_amount'        => $payment_amount,
                                'remaining_amount'      => $remaining_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("alloc_kredit_no", $alloc_kredit_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_alloc_creditar', $data);
                $message = "Data alokasi credit ar" . $alloc_kredit_no . " Berhasil di update";
            } else {              
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'alloc_kredit_no'       => $alloc_kredit_no,
                                'alloc_kredit_date'     => $alloc_kredit_date,
                                'doc_type'              => $doc_type,
                                'pelunasan_type'        => $pelunasan_type,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'credit_invoice_no'     => $credit_invoice_no,
                                'credit_invoice_amount' => $credit_invoice_amount,
                                'payment_amount'        => $payment_amount,
                                'remaining_amount'      => $remaining_amount,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data alokasi credit ar " . $alloc_kredit_no . " Berhasil di input";
                $this->db->insert('tbl_alloc_creditar', $data);
            }

            $query = $this->db->query("
                DELETE FROM tbl_alloc_creditar_item where alloc_kredit_no = '$alloc_kredit_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                        'i_company'                     => $row['i_company'],
                        'ou_code'                       => $row['ou_code'],
                        'alloc_kredit_no'               => $row['alloc_kredit_no'],
                        'ref_doc_type'                  => $row['ref_doc_type'],
                        'invoice_no'                    => $row['invoice_no'],
                        'invoice_date'                  => $row['invoice_date'],
                        'due_date'                      => $row['due_date'],
                        'ref_remain_amount'             => $row['ref_remain_amount'],
                        'allocated_invoice_amount'      => $row['allocated_invoice_amount'],
                        'payment_amount'                => $row['payment_amount'],
                        'remark'                        => $row['remark'],
                    );
                    $this->db->insert('tbl_alloc_creditar_item', $data);
                }  
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

    /*dihapus (tidak perlu ada)
    public function inquiry_ar_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $inquiry_no             = $this->post('inquiry_no');
        $inquiry_date           = $this->post('inquiry_date');
        $doc_type               = $this->post('doc_type');
        $due_date               = $this->post('due_date');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $so_no                  = $this->post('so_no');
        $so_date                = $this->post('so_date');
        $do_no                  = $this->post('do_no');
        $do_date                = $this->post('do_date');
        $ref_remain_amount      = $this->post('ref_remain_amount');
        $os_invoice_amount      = $this->post('os_invoice_amount');
        $aging                  = $this->post('aging');
        $status_proses_invoice  = $this->post('status_proses_invoice');
        $status_doc             = $this->post('status_doc');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $inquiry_no != '') {
            $this->db->select("ou_code, inquiry_no");
            $this->db->from("tbl_inquiry_ar");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("inquiry_no", $inquiry_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(                                 
                                'inquiry_date'          => $inquiry_date,
                                'doc_type'              => $doc_type,
                                'due_date'              => $due_date,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'so_no'                 => $so_no,
                                'so_date'               => $so_date,
                                'do_no'                 => $do_no,
                                'do_date'               => $do_date,
                                'ref_remain_amount'     => $ref_remain_amount,
                                'os_invoice_amount'     => $os_invoice_amount,
                                'aging'                 => $aging,
                                'status_proses_invoice' => $status_proses_invoice,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("inquiry_no", $inquiry_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_inquiry_ar', $data);
                $message = "Data inquiry ar " . $inquiry_no . " Berhasil di update";
            } else {          
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'inquiry_no'            => $inquiry_no,
                                'inquiry_date'          => $inquiry_date,
                                'doc_type'              => $doc_type,
                                'due_date'              => $due_date,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'so_no'                 => $so_no,
                                'so_date'               => $so_date,
                                'do_no'                 => $do_no,
                                'do_date'               => $do_date,
                                'ref_remain_amount'     => $ref_remain_amount,
                                'os_invoice_amount'     => $os_invoice_amount,
                                'aging'                 => $aging,
                                'status_proses_invoice' => $status_proses_invoice,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data inquiry ar " . $inquiry_no . " Berhasil di input";
                $this->db->insert('tbl_inquiry_ar', $data);
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
    }*/

    public function unalloc_kbin_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $kbin_no                = $this->post('kbin_no');
        $kbin_date              = $this->post('kbin_date');
        $doc_type               = $this->post('doc_type');
        $due_date               = $this->post('due_date');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $kbin_amount            = $this->post('kbin_amount');
        $os_kbin_amount         = $this->post('os_kbin_amount');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $ou_code != '' && $kbin_no != '') {
            $this->db->select("ou_code, kbin_no");
            $this->db->from("tbl_unalloc_kbin");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("kbin_no", $kbin_no);
            $this->db->where("i_company", $i_company);
            $cek_query = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_query->num_rows() > 0) {
                $data = array(                                 
                                'kbin_date'             => $kbin_date,
                                'doc_type'              => $doc_type,
                                'due_date'              => $due_date,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'kbin_amount'           => $kbin_amount,
                                'os_kbin_amount'        => $os_kbin_amount,
                                'modifiedat'            => $datenow,
                );   
                $this->db->where("ou_code", $ou_code);
                $this->db->where("kbin_no", $kbin_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_unalloc_kbin', $data);
                $message = "Data kas bank in unallocation " . $kbin_no . " Berhasil di update";
            } else {            
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'kbin_no'               => $kbin_no,
                                'kbin_date'             => $kbin_date,
                                'doc_type'              => $doc_type,
                                'due_date'              => $due_date,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'kbin_amount'           => $kbin_amount,
                                'os_kbin_amount'        => $os_kbin_amount,
                                'createdat'             => $datenow,
                );
                $message = "Data kas bank in unallocation " . $kbin_no . " Berhasil di input";
                $this->db->insert('tbl_unalloc_kbin', $data);
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

#---IVENTORY    
    public function return_note_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $return_note_no     = $this->post('return_note_no');
        $return_note_date   = $this->post('return_note_date');
        $rrs_no             = $this->post('rrs_no');
        $rrs_date           = $this->post('rrs_date');
        $warehouse_code     = $this->post('warehouse_code');
        $warehouse_name     = $this->post('warehouse_name');
        $customer_code      = $this->post('customer_code');
        $customer_name      = $this->post('customer_name');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');

        // var_dump($ou_code, $ou_name, $return_note_no, $return_note_date, $rrs_no, $rrs_date, $warehouse_code, $warehouse_name, $customer_code, $customer_name, $remark, $status_doc, $create_datetime, $username, $role_name);
        // die();
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0) {

            $this->db->select("return_note_no");
            $this->db->from("tbl_return_note");
            $this->db->where("return_note_no", $return_note_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_data = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;
            if ($cek_data->num_rows() > 0) {
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'return_note_no'    => $return_note_no,
                                'return_note_date'  => $return_note_date,
                                'rrs_no'            => $rrs_no,
                                'rrs_date'          => $rrs_date,
                                'warehouse_code'    => $warehouse_code,
                                'warehouse_name'    => $warehouse_name,
                                'customer_code'     => $customer_code,
                                'customer_name'     => $customer_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );

                $this->db->where("return_note_no", $return_note_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_return_note', $data);
                $message = "Data Return Note : " . $return_note_no . " Ou Code : " . $return_note_no . " Berhasil di update";
            } else {                
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'ou_name'           => $ou_name,
                                'return_note_no'    => $return_note_no,
                                'return_note_date'  => $return_note_date,
                                'rrs_no'            => $rrs_no,
                                'rrs_date'          => $rrs_date,
                                'warehouse_code'    => $warehouse_code,
                                'warehouse_name'    => $warehouse_name,
                                'customer_code'     => $customer_code,
                                'customer_name'     => $customer_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'createdat'         => $datenow,
                );
                $this->db->insert('tbl_return_note', $data);
                $message = "Data Return Note : " . $return_note_no . " Ou Code : " . $return_note_no . " Berhasil di input";

            }

            $query = $this->db->query("
                DELETE FROM tbl_return_note_item where return_note_no = '$return_note_no' and ou_code = '$ou_code' and i_company = '$i_company'
            ");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'ou_code'           => $row['ou_code'],
                                    'rrs_no'            => $row['rrs_no'],
                                    'return_note_no'    => $row['return_note_no'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'product_status'    => $row['product_status'],
                                    'qty'               => $row['qty'],
                                    'amount'            => $row['amount'],
                                    'remark'            => $row['remark'],
                                    'modifiedat'        => $datenow
                    );
                    $this->db->insert('tbl_return_note_item', $data);
                }
            }
            
            $this->response([
                'status' => true,
                'message' => $message,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter Salah!',
            ], REST_Controller::HTTP_NOT_FOUND);

        }
    }

    public function request_dlvgoods_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $request_dlv_no     = $this->post('request_dlv_no');
        $request_dlv_date   = $this->post('request_dlv_date');
        $est_return_date    = $this->post('est_return_date');
        $pic_name           = $this->post('pic_name');
        $warehouse_code     = $this->post('warehouse_code');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');
   
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

      
        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $request_dlv_no != '') {
            $this->db->select("request_dlv_no, ou_code");
            $this->db->from("tbl_request_dlvgoods");
            $this->db->where("request_dlv_no", $request_dlv_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(                                
                                'request_dlv_date'  => $request_dlv_date,
                                'est_return_date'   => $est_return_date,
                                'pic_name'          => $pic_name,
                                'warehouse_code'    => $warehouse_code,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );   
                $this->db->where("request_dlv_no", $request_dlv_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_request_dlvgoods', $data);
                $message = "Data Request delivery goods " .$request_dlv_no. " Berhasil di update";
            } else {   
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'request_dlv_no'    => $request_dlv_no,
                                'request_dlv_date'  => $request_dlv_date,
                                'est_return_date'   => $est_return_date,
                                'pic_name'          => $pic_name,
                                'warehouse_code'    => $warehouse_code,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'createdat'         => $datenow,
                );
                $message = "Data Request delevery goods " . $request_dlv_no . " Berhasil di input";
                $this->db->insert('tbl_request_dlvgoods', $data);
            }

            //ITEM
            $query = $this->db->query("DELETE FROM tbl_request_dlvgoods_item where ou_code = '$ou_code' and request_dlv_no = '$request_dlv_no' and i_company = '$i_company'");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'ou_code'           => $row['ou_code'],
                                    'request_dlv_no'    => $row['request_dlv_no'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_request'       => $row['qty_request'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_request_dlvgoods_item', $data);
                }
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

    public function dlvgoods_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $ou_code         = $this->post('ou_code');
        $dlv_no          = $this->post('dlv_no');
        $request_dlv_no  = $this->post('request_dlv_no');
        $dlv_date        = $this->post('dlv_date');
        $warehouse_code  = $this->post('warehouse_code');
        $flag_type       = $this->post('flag_type');
        $est_return_date = $this->post('est_return_date');
        $partner_code    = $this->post('partner_code');
        $partner_name    = $this->post('partner_name');
        $remark          = $this->post('remark');
        $status_doc      = $this->post('status_doc');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $dlv_no != '' && $request_dlv_no != '') {
            $this->db->select("dlv_no, ou_code");
            $this->db->from("tbl_dlv_goods");
            $this->db->where("dlv_no", $dlv_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'request_dlv_no'  => $request_dlv_no,
                                'dlv_date'        => $dlv_date,
                                'warehouse_code'  => $warehouse_code,
                                'flag_type'       => $flag_type,
                                'est_return_date' => $est_return_date,
                                'partner_code'    => $partner_code,
                                'partner_name'    => $partner_name,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'modifiedat'      => $datenow,
                );

                $this->db->where("dlv_no", $dlv_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_dlv_goods', $data);
                $message = "Data Delevery Goods " .$dlv_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'dlv_no'          => $dlv_no,
                                'ou_code'         => $ou_code,
                                'request_dlv_no'  => $request_dlv_no,
                                'dlv_date'        => $dlv_date,
                                'warehouse_code'  => $warehouse_code,
                                'flag_type'       => $flag_type,
                                'est_return_date' => $est_return_date,
                                'partner_code'    => $partner_code,
                                'partner_name'    => $partner_name,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'createdat'       => $datenow,
                );
                $message = "Data Delivery Goods " . $dlv_no . " Berhasil di input";
                $this->db->insert('tbl_dlv_goods', $data);
            }

            //ITEM
            $query = $this->db->query("DELETE FROM tbl_dlv_goods_item where ou_code = '$ou_code' and dlv_no = '$dlv_no' and i_company = '$i_company'");

            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'     => $row['i_company'],
                                    'ou_code'       => $row['ou_code'],
                                    'dlv_no'        => $row['dlv_no'],
                                    'product_code'  => $row['product_code'],
                                    'product_name'  => $row['product_name'],
                                    'qty_request'   => $row['qty_request'],
                                    'qty_dlv'       => $row['qty_dlv'],
                                    'remark'        => $row['remark']
                    );
                    $this->db->insert('tbl_dlv_goods_item', $data);
                }
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

    //REQUEST RETUR BRW
    public function request_returbrw_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $request_retur_no   = $this->post('request_retur_no');
        $request_retur_date = $this->post('request_retur_date');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $dlv_no             = $this->post('dlv_no');
        $dlv_date           = $this->post('dlv_date');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $request_retur_no != '') {
            $this->db->select("request_retur_no, ou_code, username, role_name");
            $this->db->from("tbl_request_returbrw");
            $this->db->where("request_retur_no", $request_retur_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("username", $username);
            $this->db->where("role_name", $role_name);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'request_retur_date' => $request_retur_date,
                                'partner_code'       => $partner_code,
                                'partner_name'       => $partner_name,
                                'dlv_no'             => $dlv_no,
                                'dlv_date'           => $dlv_date,
                                'remark'             => $remark,
                                'status_doc'         => $status_doc,
                                'modifiedat'         => $datenow,
                );

                $this->db->where("request_retur_no", $request_retur_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("username", $username);
                $this->db->where("role_name", $role_name);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_request_returbrw', $data);
                $message = "Data Request Retur Brw " .$request_retur_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'          => $i_company,
                                'ou_code'            => $ou_code,
                                'request_retur_no'   => $request_retur_no,
                                'request_retur_date' => $request_retur_date,
                                'partner_code'       => $partner_code,
                                'partner_name'       => $partner_name,
                                'dlv_no'             => $dlv_no,
                                'dlv_date'           => $dlv_date,
                                'remark'             => $remark,
                                'status_doc'         => $status_doc,
                                'username'           => $username,
                                'role_name'          => $role_name,
                                'createdat'          => $datenow,
                );
                $message = "Data Request Retur Brw " . $request_retur_no . " Berhasil di input";
                $this->db->insert('tbl_request_returbrw', $data);
            }

            //ITEM
            $query = $this->db->query("DELETE FROM tbl_request_returbrw_item where ou_code = '$ou_code' and request_retur_no = '$request_retur_no' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'ou_code'           => $row['ou_code'],
                                    'request_retur_no'  => $row['request_retur_no'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_dlv_borrow'    => $row['qty_dlv_borrow'],
                                    'qty_request'       => $row['qty_request'],
                                    'remark'            => $row['remark']
                    );
                    $this->db->insert('tbl_request_returbrw_item', $data);
                }
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

    // RETURN GOODS
    public function return_goods_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $return_goods_no    = $this->post('return_goods_no');
        $return_goods_date  = $this->post('return_goods_date');
        $request_retur_no   = $this->post('request_retur_no');
        $warehouse_code     = $this->post('warehouse_code');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $dlv_no             = $this->post('dlv_no');
        $dlv_date           = $this->post('dlv_date');
        $convert_sales      = $this->post('convert_sales');
        $receive_date       = $this->post('receive_date');
        $status_doc         = $this->post('status_doc');
        $remark             = $this->post('remark');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $return_goods_no != '') {
            $this->db->select("return_goods_no, ou_code");
            $this->db->from("tbl_return_goods");
            $this->db->where("return_goods_no", $return_goods_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'return_goods_date'  => $return_goods_date,
                                'request_retur_no'   => $request_retur_no,
                                'warehouse_code'     => $warehouse_code,
                                'partner_code'       => $partner_code,
                                'partner_name'       => $partner_name,
                                'dlv_no'             => $dlv_no,
                                'dlv_date'           => $dlv_date,
                                'convert_sales'      => $convert_sales,
                                'receive_date'       => $receive_date,
                                'status_doc'         => $status_doc,
                                'remark'             => $remark,
                                'modifiedat'         => $datenow,
                );

                $this->db->where("return_goods_no", $return_goods_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_return_goods', $data);
                $message = "Data Return Goods " .$return_goods_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'          => $i_company,
                                'ou_code'            => $ou_code,
                                'return_goods_no'    => $return_goods_no,
                                'return_goods_date'  => $return_goods_date,
                                'request_retur_no'   => $request_retur_no,
                                'warehouse_code'     => $warehouse_code,
                                'partner_code'       => $partner_code,
                                'partner_name'       => $partner_name,
                                'dlv_no'             => $dlv_no,
                                'dlv_date'           => $dlv_date,
                                'convert_sales'      => $convert_sales,
                                'receive_date'       => $receive_date,
                                'status_doc'         => $status_doc,
                                'remark'             => $remark,
                                'createdat'          => $datenow,
                );
                $message = "Data Return Goods " . $return_goods_no . " Berhasil di input";
                $this->db->insert('tbl_return_goods', $data);
            }
            //ITEM
            $query = $this->db->query("DELETE FROM tbl_return_goods_item where ou_code = '$ou_code' and return_goods_no = '$return_goods_no' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'ou_code'           => $row['ou_code'],
                                    'return_goods_no'   => $row['return_goods_no'],
                                    'dlv_no'            => $row['dlv_no'],
                                    'dlv_date'          => $row['dlv_date'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_dlv_goods'     => $row['qty_dlv_goods'],
                                    'qty_os_dlv_goods'  => $row['qty_os_dlv_goods'],
                                    'qty_return_goods'  => $row['qty_return_goods'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_return_goods_item', $data);
                }
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

    //DKB
    public function dkb_post()
    {
        $action            = $this->post('action');
        $api_key           = $this->post('api_key');
        $i_company         = $this->post('i_company');
        $ou_code           = $this->post('ou_code');
        $flag_internal     = $this->post('flag_internal');
        $dkb_no            = $this->post('dkb_no');
        $dkb_date          = $this->post('dkb_date');
        $expedition_no     = $this->post('expedition_no');
        $expedition_name   = $this->post('expedition_name');
        $region_name       = $this->post('region_name');
        $warehouse_code    = $this->post('warehouse_code');
        $employee_code     = $this->post('employee_code');
        $vechile_no        = $this->post('vechile_no');
        $do_amount         = $this->post('do_amount');
        $status_dkb        = $this->post('status_dkb');
        $remark            = $this->post('remark');
        $status_doc        = $this->post('status_doc');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $dkb_no != '') {
            $this->db->select("dkb_no, ou_code");
            $this->db->from("tbl_dkb");
            $this->db->where("dkb_no", $dkb_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'flag_internal'     => $flag_internal,
                                'dkb_date'          => $dkb_date,
                                'expedition_no'     => $expedition_no,
                                'expedition_name'   => $expedition_name,
                                'region_name'       => $region_name,
                                'warehouse_code'    => $warehouse_code,
                                'employee_code'     => $employee_code,
                                'vechile_no'        => $vechile_no,
                                'do_amount'         => $do_amount,
                                'status_dkb'        => $status_dkb,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );

                $this->db->where("dkb_no", $dkb_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_dkb', $data);
                $message = "Data DKB " .$dkb_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'flag_internal'     => $flag_internal,
                                'dkb_no'            => $dkb_no,
                                'dkb_date'          => $dkb_date,
                                'expedition_no'     => $expedition_no,
                                'expedition_name'   => $expedition_name,
                                'region_name'       => $region_name,
                                'warehouse_code'    => $warehouse_code,
                                'employee_code'     => $employee_code,
                                'vechile_no'        => $vechile_no,
                                'do_amount'         => $do_amount,
                                'status_dkb'        => $status_dkb,
                                'remark'            => $remark,
                                'createdat'         => $datenow,
                );
                $message = "Data DKB " . $dkb_no . " Berhasil di input";
                $this->db->insert('tbl_dkb', $data);
            }

            $query = $this->db->query("DELETE FROM tbl_dkb_item where ou_code = '$ou_code' and dkb_no = '$dkb_no' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'             => $row['i_company'],
                                    'ou_code'               => $row['ou_code'],
                                    'dkb_no'                => $row['dkb_no'],
                                    'do_no'                 => $row['do_no'],
                                    'do_date'               => $row['do_date'],
                                    'do_packinglist_no'     => $row['do_packinglist_no'],
                                    'do_amount'             => $row['do_amount'],
                                    'customer_code'         => $row['customer_code'],
                                    'customer_name'         => $row['customer_name'],
                                    'city_name'             => $row['city_name'],
                                    'qty_ball'              => $row['qty_ball'],
                                    'remark'                => $row['remark'],
                    );
                    $this->db->insert('tbl_dkb_item', $data);
                }   
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

    //DKB FINAL
    public function dkbfinal_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $ou_code         = $this->post('ou_code');
        $warehouse_code  = $this->post('warehouse_code');
        $dkb_no          = $this->post('dkb_no');
        $dkb_date        = $this->post('dkb_date');
        $resi_no         = $this->post('resi_no');
        $do_no           = $this->post('do_no');
        $do_date         = $this->post('do_date');
        $do_receipt      = $this->post('do_receipt');
        $do_amount       = $this->post('do_amount');
        $do_total_amount = $this->post('do_total_amount');
        $cost_amount     = $this->post('cost_amount');
        $remark          = $this->post('remark');
        $username        = $this->post('username');
        $role_name       = $this->post('role_name');
        $status_doc      = $this->post('status_doc');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $dkb_no != '') {
            $this->db->select("dkb_no, ou_code");
            $this->db->from("tbl_dkb_final");
            $this->db->where("dkb_no", $dkb_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'warehouse_code'  => $warehouse_code,
                                'dkb_date'        => $dkb_date,
                                'resi_no'         => $resi_no,
                                'do_no'           => $do_no,
                                'do_date'         => $do_date,
                                'do_receipt'      => $do_receipt,
                                'do_amount'       => $do_amount,
                                'cost_amount'     => $cost_amount,
                                'do_total_amount' => $do_total_amount,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'modifiedat'      => $datenow,
                );

                $this->db->where("dkb_no", $dkb_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_dkb_final', $data);
                $message = "Data Finalisasi DKB " .$dkb_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'ou_code'         => $ou_code,
                                'warehouse_code'  => $warehouse_code,
                                'dkb_no'          => $dkb_no,
                                'dkb_date'        => $dkb_date,
                                'resi_no'         => $resi_no,
                                'do_no'           => $do_no,
                                'do_date'         => $do_date,
                                'do_receipt'      => $do_receipt,
                                'do_amount'       => $do_amount,
                                'cost_amount'     => $cost_amount,
                                'do_total_amount' => $do_total_amount,
                                'remark'          => $remark,
                                'username'        => $username,
                                'role_name'       => $role_name,
                                'status_doc'      => $status_doc,
                                'createdat'       => $datenow,
                );
                $message = "Data Finalisasi DKB " . $dkb_no . " Berhasil di input";
                $this->db->insert('tbl_dkb_final', $data);

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

    //DKB RESI
    public function dkbresi_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $ou_code         = $this->post('ou_code');
        $dkb_no          = $this->post('dkb_no');
        $resi_no         = $this->post('resi_no');
        $employee_code   = $this->post('employee_code');
        $vehicle_no      = $this->post('vehicle_no');
        $expedition_no   = $this->post('expedition_no');
        $expedition_name = $this->post('expedition_name');
        $qty_ball        = $this->post('qty_ball');
        $do_amount       = $this->post('do_amount');
        $cost_amount     = $this->post('cost_amount');
        $receipt_date    = $this->post('receipt_date');
        $cost_percentage = $this->post('cost_percentage');
        $status_doc      = $this->post('status_doc');
        $remark          = $this->post('remark');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $dkb_no != '') {
            $this->db->select("dkb_no, ou_code, resi_no");
            $this->db->from("tbl_dkb_resi");
            $this->db->where("dkb_no", $dkb_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("resi_no", $resi_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'dkb_no'          => $dkb_no,
                                'employee_code'   => $employee_code,
                                'vehicle_no'      => $vehicle_no,
                                'expedition_no'   => $expedition_no,
                                'expedition_name' => $expedition_name,
                                'qty_ball'        => $qty_ball,
                                'do_amount'       => $do_amount,
                                'cost_amount'     => $cost_amount,
                                'receipt_date'    => $receipt_date,
                                'cost_percentage' => $cost_percentage,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'modifiedat'      => $datenow,
                );

                $this->db->where("dkb_no", $dkb_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("resi_no", $resi_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_dkb_resi', $data);
                $message = "Data DKB Resi " .$resi_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'ou_code'         => $ou_code,
                                'dkb_no'          => $dkb_no,
                                'resi_no'         => $resi_no,
                                'employee_code'   => $employee_code,
                                'vehicle_no'      => $vehicle_no,
                                'expedition_no'   => $expedition_no,
                                'expedition_name' => $expedition_name,
                                'qty_ball'        => $qty_ball,
                                'do_amount'       => $do_amount,
                                'cost_amount'     => $cost_amount,
                                'receipt_date'    => $receipt_date,
                                'cost_percentage' => $cost_percentage,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'createdat'       => $datenow,
                );
                $message = "Data DKB Resi " . $resi_no . " Berhasil di input";
                $this->db->insert('tbl_dkb_resi', $data);
            }

            $query = $this->db->query("DELETE FROM tbl_dkb_resi_item where resi_no = '$resi_no' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'resi_no'           => $row['resi_no'],
                                    'do_no'             => $row['do_no'],
                                    'do_date'           => $row['do_date'],
                                    'customer_code'     => $row['customer_code'],
                                    'customer_name'     => $row['customer_name'],
                                    'do_amount'         => $row['do_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_dkb_resi_item', $data);
                }   
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

    //DO RECEIPT
    public function do_receipt_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $ou_code         = $this->post('ou_code');
        $doc_no          = $this->post('doc_no');
        $doc_date        = $this->post('doc_date');
        $receipt_date    = $this->post('receipt_date');
        $do_no           = $this->post('do_no');
        $do_date         = $this->post('do_date');
        $so_no           = $this->post('so_no');
        $so_date         = $this->post('so_date');
        $warehouse_code  = $this->post('warehouse_code');
        $customer_code   = $this->post('customer_code');
        $customer_name   = $this->post('customer_name');
        $remark          = $this->post('remark');
        $status_doc      = $this->post('status_doc');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $doc_no != '') {
            $this->db->select("do_no, ou_code, doc_no");
            $this->db->from("tbl_do_receipt");
            $this->db->where("do_no", $do_no);
            $this->db->where("ou_code", $ou_code);
            $this->db->where("doc_no", $doc_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'doc_date'        => $doc_date,
                                'receipt_date'    => $receipt_date,
                                'do_no'           => $do_no,
                                'do_date'         => $do_date,
                                'so_no'           => $so_no,
                                'so_date'         => $so_date,
                                'warehouse_code'  => $warehouse_code,
                                'customer_code'   => $customer_code,
                                'customer_name'   => $customer_name,
                                'remark'          => $remark,
                                'status_doc'      => $status_doc,
                                'modifiedat'      => $datenow,
                );

                $this->db->where("do_no", $do_no);
                $this->db->where("ou_code", $ou_code);
                $this->db->where("doc_no", $doc_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_do_receipt', $data);
                $message = "Data DO Receipt " .$doc_no. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'doc_no'                => $doc_no,
                                'doc_date'              => $doc_date,
                                'receipt_date'          => $receipt_date,
                                'do_no'                 => $do_no,
                                'do_date'               => $do_date,
                                'so_no'                 => $so_no,
                                'so_date'               => $so_date,
                                'warehouse_code'        => $warehouse_code,
                                'customer_code'         => $customer_code,
                                'customer_name'         => $customer_name,
                                'remark'                => $remark,
                                'status_doc'            => $status_doc,
                                'createdat'             => $datenow,
                );
                $message = "Data DO Receipt " . $doc_no . " Berhasil di input";
                $this->db->insert('tbl_do_receipt', $data);
            }
            $query = $this->db->query("DELETE FROM tbl_do_receipt_item where doc_no = '$doc_no' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],
                                    'doc_no'            => $row['doc_no'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_dlv_do'        => $row['qty_dlv_do'],
                                    'qty_return'        => $row['qty_return'],
                                    'product_status'    => $row['product_status'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_do_receipt_item', $data);
                }   
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

    //STOK PRODUCT
    public function stok_product_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $product_code    = $this->post('product_code');
        $warehouse_code  = $this->post('warehouse_code');
        $qty             = $this->post('qty');
        $qty_balance     = $this->post('qty_balance');
        $qty_reserved    = $this->post('qty_reserved');
        $status_doc      = $this->post('status_doc');
        
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $product_code != '' && $warehouse_code != '') {
            $this->db->select("product_code, warehouse_code");
            $this->db->from("tbl_stok_product");
            $this->db->where("product_code", $product_code);
            $this->db->where("warehouse_code", $warehouse_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'warehouse_code'  => $warehouse_code,
                                'qty'             => $qty,
                                'qty_balance'     => $qty_balance,
                                'qty_reserved'    => $qty_reserved,
                                'status_doc'      => $status_doc,
                                'modifiedat'      => $datenow,
                );

                $this->db->where("product_code", $product_code);
                $this->db->where("warehouse_code", $warehouse_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_stok_product', $data);
                $message = "Data Stok Produk " .$product_code. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'product_code'    => $product_code,
                                'warehouse_code'  => $warehouse_code,
                                'qty'             => $qty,
                                'qty_balance'     => $qty_balance,
                                'qty_reserved'    => $qty_reserved,
                                'status_doc'      => $status_doc,
                                'createdat'       => $datenow,
                );
                $message = "Data Stok Produk " . $product_code . " Berhasil di input";
                $this->db->insert('tbl_stok_product', $data);

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

#--SALES
    //FINANCE INFO
    public function financeinfo_post()
    {
        $action          = $this->post('action');
        $api_key         = $this->post('api_key');
        $i_company       = $this->post('i_company');
        $customer_code   = $this->post('customer_code');
        $jumlah_plafon   = $this->post('jumlah_plafon');
        $so_belum_do     = $this->post('so_belum_do');
        $do_belum_invoice= $this->post('do_belum_invoice');
        $piutang         = $this->post('piutang');
        $piutang_jt      = $this->post('piutang_jt');
        $cashbank_in     = $this->post('cashbank_in');
        $giro            = $this->post('giro');
        $credit_note     = $this->post('credit_note');
        $sisa_plafon     = $this->post('sisa_plafon');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $customer_code != '') {
            $this->db->select("customer_code");
            $this->db->from("tbl_finance_info");
            $this->db->where("customer_code", $customer_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'customer_code'   => $customer_code,
                                'jumlah_plafon'   => $jumlah_plafon,
                                'so_belum_do'     => $so_belum_do,
                                'do_belum_invoice'=> $do_belum_invoice,
                                'piutang'         => $piutang,
                                'piutang_jt'      => $piutang_jt,
                                'cashbank_in'     => $cashbank_in,
                                'giro'            => $giro,
                                'credit_note'     => $credit_note,
                                'sisa_plafon'     => $sisa_plafon, 
                                'modifiedat'      => $datenow,
                );

                $this->db->where("customer_code", $customer_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_finance_info', $data);
                $message = "Data Finance Info " .$customer_code. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'       => $i_company,
                                'customer_code'   => $customer_code,
                                'jumlah_plafon'   => $jumlah_plafon,
                                'so_belum_do'     => $so_belum_do,
                                'do_belum_invoice'=> $do_belum_invoice,
                                'piutang'         => $piutang,
                                'piutang_jt'      => $piutang_jt,
                                'cashbank_in'     => $cashbank_in,
                                'giro'            => $giro,
                                'credit_note'     => $credit_note,
                                'sisa_plafon'     => $sisa_plafon, 
                                'createdat'       => $datenow,
                );
                $message = "Data Finance Info " . $customer_code . " Berhasil di input";
                $this->db->insert('tbl_finance_info', $data);

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

    //EDIT SO
    public function editso_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $region_code        = $this->post('region_code');
        $so_no_edit         = $this->post('so_no_edit');
        $so_date_edit       = $this->post('so_date_edit');
        $so_no              = $this->post('so_no');
        $so_date            = $this->post('so_date');
        $warehouse_code     = $this->post('warehouse_code');
        $flag_type_delivery = $this->post('flag_type_delivery');
        $group_brand_code   = $this->post('group_brand_code');
        $customer_code      = $this->post('customer_code');
        $customer_name      = $this->post('customer_name');
        $deskripsi_address  = $this->post('deskripsi_address');
        $customer_address   = $this->post('customer_address');
        $salesman_code      = $this->post('salesman_code');
        $status_rgto        = $this->post('status_rgto');
        $status_so          = $this->post('status_so');
        $remark             = $this->post('remark');
        $etd_so             = $this->post('etd_so');
        $so_gross_amount    = $this->post('so_gross_amount');
        $so_discount_amount = $this->post('so_discount_amount');
        $so_netto_amount    = $this->post('so_netto_amount');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $so_no_edit != '') {
            $this->db->select("ou_code, so_no_edit, so_no");
            $this->db->from("tbl_spb_edit");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("so_no_edit", $so_no_edit);
            $this->db->where("so_no", $so_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'ou_name'            => $ou_name,
                                'region_code'        => $region_code,
                                'so_date_edit'       => $so_date_edit,
                                'so_no'              => $so_no,
                                'so_date'            => $so_date,
                                'warehouse_code'     => $warehouse_code,
                                'flag_type_delivery' => $flag_type_delivery,
                                'group_brand_code'   => $group_brand_code,
                                'customer_code'      => $customer_code,
                                'customer_name'      => $customer_name,
                                'deskripsi_address'  => $deskripsi_address,
                                'customer_address'   => $customer_address,
                                'salesman_code'      => $salesman_code,
                                'status_rgto'        => $status_rgto,
                                'status_so'          => $status_so,
                                'remark'             => $remark,
                                'etd_so'             => $etd_so,
                                'so_gross_amount'    => $so_gross_amount,
                                'so_discount_amount' => $so_discount_amount,
                                'so_netto_amount'    => $so_netto_amount,
                                'status_doc'         => $status_doc,
                                'modifiedat'         => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("so_no_edit", $so_no_edit);
                $this->db->where("so_no", $so_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_spb_edit', $data);
                $message = "Data Edit SPB " .$so_no_edit. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'          => $i_company,
                                'ou_code'            => $ou_code,
                                'ou_name'            => $ou_name,
                                'region_code'        => $region_code,
                                'so_no_edit'         => $so_no_edit,
                                'so_date_edit'       => $so_date_edit,
                                'so_no'              => $so_no,
                                'so_date'            => $so_date,
                                'warehouse_code'     => $warehouse_code,
                                'flag_type_delivery' => $flag_type_delivery,   
                                'group_brand_code'   => $group_brand_code,
                                'customer_code'      => $customer_code,
                                'customer_name'      => $customer_name,
                                'deskripsi_address'  => $deskripsi_address,
                                'customer_address'   => $customer_address,
                                'salesman_code'      => $salesman_code,
                                'status_rgto'        => $status_rgto,
                                'status_so'          => $status_so,
                                'remark'             => $remark,
                                'etd_so'             => $etd_so,
                                'so_gross_amount'    => $so_gross_amount,
                                'so_discount_amount' => $so_discount_amount,
                                'so_netto_amount'    => $so_netto_amount,
                                'username'           => $username,
                                'role_name'          => $role_name,
                                'status_doc'         => $status_doc,
                                'createdat'          => $datenow,
                );
                $message = "Data Edit SPB " . $so_no_edit . " Berhasil di input";
                $this->db->insert('tbl_spb_edit', $data);
            }

            $query = $this->db->query("SELECT * FROM tbl_spb where i_spb = '$so_no' and d_spb = '$so_date' and  i_area = '$ou_code' and i_company = '$i_company'");
            if ($query->num_rows() > 0) {
                $data = array(                                   
                                'region_code'               => $region_code,
                                'warehouse_code'            => $warehouse_code,
                                'flag_type_delivery'        => $flag_type_delivery,   
                                'i_product_group'           => $group_brand_code,
                                'i_customer'                => $customer_code,
                                'flag_description_address'  => $deskripsi_address,
                                'flag_customer_address'     => $customer_address,
                                'i_staff'                   => $salesman_code,
                                'status_rgto'               => $status_rgto,
                                'status_so'                 => $status_so,
                                'e_remark'                  => $remark,
                                'etd_so'                    => $etd_so,
                                'v_spb_gross'               => $so_gross_amount,
                                'v_spb_discounttotal'       => $so_discount_amount,
                                'v_spb_netto'               => $so_netto_amount,
                                'username'                  => $username,
                                'role_name'                 => $role_name,
                );
                $this->db->where("i_area", $ou_code);
                $this->db->where("d_spb", $so_date);
                $this->db->where("i_spb", $so_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_spb', $data);
                //$message = "Data SPB " . $so_no . " Berhasil di update";
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

    //VOID SO
    public function voidso_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $ou_name            = $this->post('ou_name');
        $region_code        = $this->post('region_code');
        $so_no_edit         = $this->post('so_no_edit');
        $so_date_edit       = $this->post('so_date_edit');
        $so_no              = $this->post('so_no');
        $so_date            = $this->post('so_date');
        $warehouse_code     = $this->post('warehouse_code');
        $group_brand_code   = $this->post('group_brand_code');
        $customer_code      = $this->post('customer_code');
        $customer_name      = $this->post('customer_name');
        $salesman_code      = $this->post('salesman_code');
        $status_so          = $this->post('status_so');
        $remark             = $this->post('remark');
        $so_gross_amount    = $this->post('so_gross_amount');
        $so_discount_amount = $this->post('so_discount_amount');
        $so_netto_amount    = $this->post('so_netto_amount');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $so_no_edit != '') {
            $this->db->select("ou_code, so_no_edit, so_no");
            $this->db->from("tbl_void_so");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("so_no_edit", $so_no_edit);
            $this->db->where("so_no", $so_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                                'ou_name'            => $ou_name,
                                'region_code'        => $region_code,
                                'so_date_edit'       => $so_date_edit,
                                'so_no'              => $so_no,
                                'so_date'            => $so_date,
                                'warehouse_code'     => $warehouse_code,
                                'group_brand_code'   => $group_brand_code,
                                'customer_code'      => $customer_code,
                                'customer_name'      => $customer_name,
                                'salesman_code'      => $salesman_code,
                                'status_so'          => $status_so,
                                'remark'             => $remark,
                                'so_gross_amount'    => $so_gross_amount,
                                'so_discount_amount' => $so_discount_amount,
                                'so_netto_amount'    => $so_netto_amount,
                                'status_doc'         => $status_doc,
                                'modifiedat'         => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("so_no_edit", $so_no_edit);
                $this->db->where("so_no", $so_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_void_so', $data);
                $message = "Data Void SO " .$so_no_edit. " Berhasil di update";
            } else {
                $data = array(
                                'i_company'          => $i_company,
                                'ou_code'            => $ou_code,
                                'ou_name'            => $ou_name,
                                'region_code'        => $region_code,
                                'so_no_edit'         => $so_no_edit,
                                'so_date_edit'       => $so_date_edit,
                                'so_no'              => $so_no,
                                'so_date'            => $so_date,
                                'warehouse_code'     => $warehouse_code,
                                'group_brand_code'   => $group_brand_code,
                                'customer_code'      => $customer_code,
                                'customer_name'      => $customer_name,
                                'salesman_code'      => $salesman_code,
                                'status_so'          => $status_so,
                                'remark'             => $remark,
                                'so_gross_amount'    => $so_gross_amount,
                                'so_discount_amount' => $so_discount_amount,
                                'so_netto_amount'    => $so_netto_amount,
                                'username'           => $username,
                                'role_name'          => $role_name,
                                'status_doc'         => $status_doc,
                                'createdat'          => $datenow,
                );
                $message = "Data Void SO " . $so_no_edit . " Berhasil di input";
                $this->db->insert('tbl_void_so', $data);

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

    //DELIVERY ORDER
    public function deliveryorder_post()
    {
        $action                 = $this->post('action');
        $api_key                = $this->post('api_key');
        $i_company              = $this->post('i_company');
        $ou_code                = $this->post('ou_code');
        $ou_name                = $this->post('ou_name');
        $doc_no                 = $this->post('doc_no');
        $doc_date               = $this->post('doc_date');
        $so_no                  = $this->post('so_no');
        $so_date                = $this->post('so_date');
        $so_realease_date       = $this->post('so_realease_date');
        $so_no_reff             = $this->post('so_no_reff');
        $so_date_reff           = $this->post('so_date_reff');
        $customer_code          = $this->post('customer_code');
        $customer_name          = $this->post('customer_name');
        $warehouse_code         = $this->post('warehouse_code');
        $warehouse_name         = $this->post('warehouse_name');
        $partner_ship_code      = $this->post('partner_ship_code');
        $partner_ship_deskripsi = $this->post('partner_ship_deskripsi');
        $etd_so                 = $this->post('etd_so');
        $do_netto_amount        = $this->post('do_netto_amount');
        $qty_ball               = $this->post('qty_ball');
        $type_delivery          = $this->post('type_delivery');
        $dkb_no                 = $this->post('dkb_no');
        $dkb_date               = $this->post('dkb_date');
        $do_receive_no          = $this->post('do_receive_no');
        $do_receive_date        = $this->post('do_receive_date');
        $status_doc             = $this->post('status_doc');
        $receive_date           = $this->post('receive_date');
        $remark                 = $this->post('remark');


        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $doc_no != '') {
            $this->db->select("ou_code, doc_no, so_no");
            $this->db->from("tbl_delivery_order");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("doc_no", $doc_no);
            $this->db->where("so_no", $so_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                    'ou_name'                => $ou_name,
                    'doc_date'               => $doc_date,
                    'so_no'                  => $so_no,
                    'so_date'                => $so_date,
                    'so_realease_date'       => $so_realease_date,
                    'so_no_reff'             => $so_no_reff,
                    'so_date_reff'           => $so_date_reff,
                    'customer_code'          => $customer_code,
                    'customer_name'          => $customer_name,
                    'warehouse_code'         => $warehouse_code,
                    'warehouse_name'         => $warehouse_name,
                    'partner_ship_code'      => $partner_ship_code,
                    'partner_ship_deskripsi' => $partner_ship_deskripsi,
                    'etd_so'                 => $etd_so,
                    'do_netto_amount'        => $do_netto_amount,
                    'qty_ball'               => $qty_ball,
                    'type_delivery'          => $type_delivery,
                    'dkb_no'                 => $dkb_no,
                    'dkb_date'               => $dkb_date,
                    'do_receive_no'          => $do_receive_no,
                    'do_receive_date'        => $do_receive_date,
                    'status_doc'             => $status_doc,
                    'receive_date'           => $receive_date,
                    'remark'                 => $remark,
                    'modifiedat'             => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("doc_no", $doc_no);
                $this->db->where("so_no", $so_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_delivery_order', $data);
                $message = "Data Delivery Order " .$doc_no. " Berhasil di update";
            } else {
                $data = array(
                    'i_company'              => $i_company,
                    'ou_code'                => $ou_code,
                    'ou_name'                => $ou_name,
                    'doc_no'                 => $doc_no,
                    'doc_date'               => $doc_date,
                    'so_no'                  => $so_no,
                    'so_date'                => $so_date,
                    'so_realease_date'       => $so_realease_date,
                    'so_no_reff'             => $so_no_reff,
                    'so_date_reff'           => $so_date_reff,
                    'customer_code'          => $customer_code,
                    'customer_name'          => $customer_name,
                    'warehouse_code'         => $warehouse_code,
                    'warehouse_name'         => $warehouse_name,
                    'partner_ship_code'      => $partner_ship_code,
                    'partner_ship_deskripsi' => $partner_ship_deskripsi,
                    'etd_so'                 => $etd_so,
                    'do_netto_amount'        => $do_netto_amount,
                    'qty_ball'               => $qty_ball,
                    'type_delivery'          => $type_delivery,
                    'dkb_no'                 => $dkb_no,
                    'dkb_date'               => $dkb_date,
                    'do_receive_no'          => $do_receive_no,
                    'do_receive_date'        => $do_receive_date,
                    'status_doc'             => $status_doc,
                    'receive_date'           => $receive_date,
                    'remark'                 => $remark,
                );
                $message = "Data Delivery Order " . $doc_no . " Berhasil di input";
                $this->db->insert('tbl_delivery_order', $data);
            }

            $query = $this->db->query("DELETE FROM tbl_delivery_order_item where doc_no = '$doc_no' and ou_code = '$ou_code' and i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],                                    
                                    'ou_code'           => $row['ou_code'],
                                    'doc_no'            => $row['doc_no'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_do'            => $row['qty_do'],
                                    'qty_do_receive'    => $row['qty_do_receive'],
                                    'product_status'    => $row['product_status'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_delivery_order_item', $data);
                }   
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

    //SALES INVOICE
    public function salesinvoice_post()
    {
        $action           = $this->post('action');
        $api_key          = $this->post('api_key');
        $i_company        = $this->post('i_company');
        $ou_code          = $this->post('ou_code');
        $doc_type         = $this->post('doc_type');
        $doc_invoice_no   = $this->post('doc_invoice_no');
        $doc_invoice_date = $this->post('doc_invoice_date');
        $so_no_reff       = $this->post('so_no_reff');
        $so_date_reff     = $this->post('so_date_reff');
        $do_no_reff       = $this->post('do_no_reff');
        $do_date_reff     = $this->post('do_date_reff');
        $partner_code     = $this->post('partner_code');
        $partner_name     = $this->post('partner_name');
        $salesman_code    = $this->post('salesman_code');
        $salesman_name    = $this->post('salesman_name');
        $gross_amount     = $this->post('gross_amount');
        $discount_amount  = $this->post('discount_amount');
        $netto_amount     = $this->post('netto_amount');
        $due_date         = $this->post('due_date');
        $status_doc       = $this->post('status_doc');    

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $doc_type != '' && $doc_invoice_no != '') {
            $this->db->select("ou_code, doc_type, doc_invoice_no");
            $this->db->from("tbl_sales_invoice");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("doc_type", $doc_type);
            $this->db->where("doc_invoice_no", $doc_invoice_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(
                    'doc_type'         => $doc_type,
                    'doc_invoice_date' => $doc_invoice_date,
                    'so_no_reff'       => $so_no_reff,
                    'so_date_reff'     => $so_date_reff,
                    'do_no_reff'       => $do_no_reff,
                    'do_date_reff'     => $do_date_reff,
                    'partner_code'     => $partner_code,
                    'partner_name'     => $partner_name,
                    'salesman_code'    => $salesman_code,
                    'salesman_name'    => $salesman_name,
                    'gross_amount'     => $gross_amount,
                    'discount_amount'  => $discount_amount,
                    'netto_amount'     => $netto_amount,
                    'due_date'         => $due_date,
                    'status_doc'       => $status_doc,
                    'modifiedat'       => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("doc_type", $doc_type);
                $this->db->where("doc_invoice_no", $doc_invoice_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_sales_invoice', $data);
                $message = "Data Sales Invoice " .$doc_invoice_no. " Berhasil di update";
            } else {
                $data = array(
                    'i_company'        => $i_company,
                    'ou_code'          => $ou_code,
                    'doc_type'         => $doc_type,
                    'doc_invoice_no'   => $doc_invoice_no,
                    'doc_invoice_date' => $doc_invoice_date,
                    'so_no_reff'       => $so_no_reff,
                    'so_date_reff'     => $so_date_reff,
                    'do_no_reff'       => $do_no_reff,
                    'do_date_reff'     => $do_date_reff,
                    'partner_code'     => $partner_code,
                    'partner_name'     => $partner_name,
                    'salesman_code'    => $salesman_code,
                    'salesman_name'    => $salesman_name,
                    'gross_amount'     => $gross_amount,
                    'discount_amount'  => $discount_amount,
                    'netto_amount'     => $netto_amount,
                    'due_date'         => $due_date,
                    'status_doc'       => $status_doc,
                );
                $message = "Data Sales Invoice " . $doc_invoice_no . " Berhasil di input";
                $this->db->insert('tbl_sales_invoice', $data);
            }

            $query = $this->db->query("DELETE FROM tbl_sales_invoice_item where invoice_no = '$doc_invoice_no' and  i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],       
                                    'invoice_no'        => $row['invoice_no'],
                                    'invoice_date'      => $row['invoice_date'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_invoice'       => $row['qty_invoice'],
                                    'harga_item'        => $row['harga_item'],
                                    'discount_amount'   => $row['discount_amount'],
                                    'item_amount'       => $row['item_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_sales_invoice_item', $data);
                }   
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

    public function trackingso_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $salesman_code      = $this->post('salesman_code');
        $customer_code      = $this->post('customer_code');
        $group_brand_code   = $this->post('group_brand_code');
        $so_no_reff         = $this->post('so_no_reff');
        $so_date_reff       = $this->post('so_date_reff');
        $so_release_date    = $this->post('so_release_date');
        $do_no_reff         = $this->post('do_no_reff');
        $do_date_reff       = $this->post('do_date_reff');
        $qty_ball           = $this->post('qty_ball');
        $dkb_no             = $this->post('dkb_no');
        $dkb_date           = $this->post('dkb_date');
        $vechile_no         = $this->post('vechile_no');
        $expedisi_name      = $this->post('expedisi_name');
        $resi_no            = $this->post('resi_no');
        $cost_amount        = $this->post('cost_amount');
        $receive_date       = $this->post('receive_date');
        $do_receive_no      = $this->post('do_receive_no');
        $do_receive_date    = $this->post('do_receive_date');
        $invoice_no_reff    = $this->post('invoice_no_reff');
        $invoice_date_reff  = $this->post('invoice_date_reff');
        $total_amount       = $this->post('total_amount');
        $status_tracking    = $this->post('status_tracking');
        $status_doc         = $this->post('status_doc');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $salesman_code != '' && $customer_code != '') {
            $this->db->select("ou_code, salesman_code");
            $this->db->from("tbl_trackingso");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("salesman_code", $salesman_code);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(                                
                                'customer_code'         => $customer_code,
                                'group_brand_code'      => $group_brand_code,
                                'so_no_reff'            => $so_no_reff,
                                'so_date_reff'          => $so_date_reff,
                                'so_release_date'       => $so_release_date,
                                'do_no_reff'            => $do_no_reff,
                                'do_date_reff'          => $do_date_reff,
                                'qty_ball'              => $qty_ball,
                                'dkb_no'                => $dkb_no,
                                'dkb_date'              => $dkb_date,
                                'vechile_no'            => $vechile_no,
                                'expedisi_name'         => $expedisi_name,
                                'resi_no'               => $resi_no,
                                'cost_amount'           => $cost_amount,
                                'receive_date'          => $receive_date,
                                'do_receive_no'         => $do_receive_no,
                                'do_receive_date'       => $do_receive_date,
                                'invoice_no_reff'       => $invoice_no_reff,
                                'invoice_date_reff'     => $invoice_date_reff,
                                'total_amount'          => $total_amount,
                                'status_tracking'       => $status_tracking,
                                'status_doc'            => $status_doc,
                                'modifiedat'            => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("salesman_code", $salesman_code);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_trackingso', $data);
                $message = "Data Tracking so " .$salesman_code. " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'             => $i_company,
                                'ou_code'               => $ou_code,
                                'salesman_code'         => $salesman_code,
                                'customer_code'         => $customer_code,
                                'group_brand_code'      => $group_brand_code,
                                'so_no_reff'            => $so_no_reff,
                                'so_date_reff'          => $so_date_reff,
                                'so_release_date'       => $so_release_date,
                                'do_no_reff'            => $do_no_reff,
                                'do_date_reff'          => $do_date_reff,
                                'qty_ball'              => $qty_ball,
                                'dkb_no'                => $dkb_no,
                                'dkb_date'              => $dkb_date,
                                'vechile_no'            => $vechile_no,
                                'expedisi_name'         => $expedisi_name,
                                'resi_no'               => $resi_no,
                                'cost_amount'           => $cost_amount,
                                'receive_date'          => $receive_date,
                                'do_receive_no'         => $do_receive_no,
                                'do_receive_date'       => $do_receive_date,
                                'invoice_no_reff'       => $invoice_no_reff,
                                'invoice_date_reff'     => $invoice_date_reff,
                                'total_amount'          => $total_amount,
                                'status_tracking'       => $status_tracking,
                                'status_doc'            => $status_doc
                );
                $message = "Data Tracking so " . $salesman_code . " Berhasil di input";
                $this->db->insert('tbl_trackingso', $data);
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

    public function request_returnsales_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $rrs_no             = $this->post('rrs_no');
        $rrs_date           = $this->post('rrs_date');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $employee_code      = $this->post('employee_code');
        $employee_name      = $this->post('employee_name');
        $total_amount       = $this->post('total_amount');
        $username           = $this->post('username');
        $role_name          = $this->post('role_name');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $rrs_no != '') {
            $this->db->select("ou_code, rrs_no");
            $this->db->from("tbl_request_returnsales");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("rrs_no", $rrs_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(                               
                                'rrs_date'          => $rrs_date,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'employee_code'     => $employee_code,
                                'employee_name'     => $employee_name,
                                'total_amount'      => $total_amount,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );

                $this->db->where("ou_code", $ou_code);
                $this->db->where("rrs_no", $rrs_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_request_returnsales', $data);
                $message = "Data Request retur sales " .$rrs_no. " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'rrs_no'            => $rrs_no,
                                'rrs_date'          => $rrs_date,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'employee_code'     => $employee_code,
                                'employee_name'     => $employee_name,
                                'total_amount'      => $total_amount,
                                'username'          => $username,
                                'role_name'         => $role_name,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'createdat'         => $datenow,
                );
                $message = "Data Request retur sales " . $rrs_no . " Berhasil di input";
                $this->db->insert('tbl_request_returnsales', $data);
            }

            $query = $this->db->query("DELETE FROM tbl_request_returnsales_item where rrs_no = '$rrs_no' and  i_company = '$i_company'");
            if ($this->post('item')) {
                foreach ($this->post('item') as $row) {
                    $data = array(
                                    'i_company'         => $row['i_company'],       
                                    'rrs_no'            => $row['rrs_no'],
                                    'invoice_no'        => $row['invoice_no'],
                                    'invoice_date'      => $row['invoice_date'],
                                    'product_code'      => $row['product_code'],
                                    'product_name'      => $row['product_name'],
                                    'qty_os_do'         => $row['qty_os_do'],
                                    'qty_return'        => $row['qty_return'],
                                    'net_sell_price'    => $row['net_sell_price'],
                                    'net_item_amount'   => $row['net_item_amount'],
                                    'remark'            => $row['remark'],
                    );
                    $this->db->insert('tbl_request_returnsales_item', $data);
                }   
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

    public function returnsales_invoice_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $ou_code            = $this->post('ou_code');
        $rsi_no             = $this->post('rsi_no');
        $rsi_date           = $this->post('rsi_date');
        $return_note_no     = $this->post('return_note_no');
        $rrs_no             = $this->post('rrs_no');
        $rrs_date           = $this->post('rrs_date');
        $invoice_no         = $this->post('invoice_no');
        $invoice_date       = $this->post('invoice_date');
        $salesman_code      = $this->post('salesman_code');
        $partner_code       = $this->post('partner_code');
        $partner_name       = $this->post('partner_name');
        $nett_amount        = $this->post('nett_amount');
        $remark             = $this->post('remark');
        $status_doc         = $this->post('status_doc');
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0  && $ou_code != '' && $rsi_no != '') {
            $this->db->select("ou_code, rsi_no");
            $this->db->from("tbl_returnsales_invoice");
            $this->db->where("ou_code", $ou_code);
            $this->db->where("rsi_no", $rsi_no);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(   
                                'return_note_no'    => $return_note_no,
                                'rrs_no'            => $rrs_no,
                                'rrs_date'          => $rrs_date,
                                'rsi_date'          => $rsi_date,
                                'invoice_no'        => $invoice_no,
                                'invoice_date'      => $invoice_date,
                                'salesman_code'     => $salesman_code,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'nett_amount'       => $nett_amount,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );
  
                $this->db->where("ou_code", $ou_code);
                $this->db->where("rsi_no", $rsi_no);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_returnsales_invoice', $data);
                $message = "Data Request retur sales invoice " .$rsi_no. " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'         => $i_company,
                                'ou_code'           => $ou_code,
                                'return_note_no'    => $return_note_no,
                                'rrs_no'            => $rrs_no,
                                'rrs_date'          => $rrs_date,
                                'rsi_no'            => $rsi_no,
                                'rsi_date'          => $rsi_date,
                                'invoice_no'        => $invoice_no,
                                'invoice_date'      => $invoice_date,
                                'salesman_code'     => $salesman_code,
                                'partner_code'      => $partner_code,
                                'partner_name'      => $partner_name,
                                'nett_amount'       => $nett_amount,
                                'remark'            => $remark,
                                'status_doc'        => $status_doc,
                );
                $message = "Data Request retur sales invoice " . $rsi_no . " Berhasil di input";
                $this->db->insert('tbl_returnsales_invoice', $data);
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

    public function salesorder_transfer_post()
    {
        $action             = $this->post('action');
        $api_key            = $this->post('api_key');
        $i_company          = $this->post('i_company');
        $i_spb              = $this->post('i_spb');
        $i_area             = $this->post('i_area');
        $i_customer         = $this->post('i_customer');
        $status_doc         = $this->post('status_doc');
  
  
        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'create' && $cek_company->num_rows() > 0 && $i_spb != '') {
            $this->db->select("i_spb");
            $this->db->from("tbl_salesorder_transfer");
            $this->db->where("i_spb", $i_spb);
            $this->db->where("i_company", $i_company);
            $cek_dlv = $this->db->get();

            $query = $this->db->query("SELECT current_timestamp as c");
            $row = $query->row();
            $datenow = $row->c;

            if ($cek_dlv->num_rows() > 0) {
                $data = array(   
                                'i_company'         => $i_company,
                                'i_area'            => $i_area,
                                'i_customer'        => $i_customer,
                                'status_doc'        => $status_doc,
                                'modifiedat'        => $datenow,
                );
                $this->db->where("i_spb", $i_spb);
                $this->db->where("i_company", $i_company);
                $this->db->update('tbl_salesorder_transfer', $data);
                $message = "Data Sales order transfer " .$i_spb. " Berhasil di update";
            } else {  
                $data = array(
                                'i_company'         => $i_company,
                                'i_spb'             => $i_spb,
                                'i_area'            => $i_area,
                                'i_customer'        => $i_customer,
                                'status_doc'        => $status_doc,
                                'createdat'         => $datenow,
                );
                $message = "Data Sales order transfer " . $i_spb . " Berhasil di input";
                $this->db->insert('tbl_salesorder_transfer', $data);
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

    public function sales_order_post()
    {
        $action     = $this->post('action');
        $api_key    = $this->post('api_key');
        $i_company  = $this->post('i_company');
        $starttime  = $this->post('starttime');
        $endtime    = $this->post('endtime');
        $fulfilled  = $this->post('fulfilled');

        $this->db->select("i_company");
        $this->db->from("tbl_company");
        $this->db->where("api_key", $api_key);
        $this->db->where("i_company", $i_company);
        $this->db->where("f_active", 'true');
        $cek_company = $this->db->get();

        if ($action == 'list' && $cek_company->num_rows() > 0 && ($fulfilled == 'true' || $fulfilled == 'false')) {

            $this->db->select("i_spb, i_customer, i_area, username, d_spb, i_product_group, i_price_group, e_remark, n_spb_discount1, n_spb_discount2, n_spb_discount3, v_spb_discount1, v_spb_discount2, v_spb_discount3, v_spb_discounttotal, v_spb_gross, v_spb_netto, f_status_transfer, i_staff, warehouse_code, type_so, customer_bill_to, customer_ship_to, flag_description_address, flag_type_delivery, etd_so, role_name, status_doc, region_code, status_so, status_rgto");
  
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

                $list[$key]['i_spb']                     = $riw->i_spb;
                $list[$key]['i_customer']                = $riw->i_customer;
                $list[$key]['i_area']                    = $riw->i_area;
                $list[$key]['i_staff']                   = $i_staff;
                $list[$key]['d_spb']                     = $riw->d_spb;
                $list[$key]['i_product_group']           = $riw->i_product_group;
                $list[$key]['i_price_group']             = $riw->i_price_group;
                $list[$key]['e_remark']                  = $riw->e_remark;
                $list[$key]['n_spb_discount1']           = $riw->n_spb_discount1;
                $list[$key]['n_spb_discount2']           = $riw->n_spb_discount2;
                $list[$key]['n_spb_discount3']           = $riw->n_spb_discount3;
                $list[$key]['v_spb_discount1']           = $riw->v_spb_discount1;
                $list[$key]['v_spb_discount2']           = $riw->v_spb_discount2;
                $list[$key]['v_spb_discount3']           = $riw->v_spb_discount3;
                $list[$key]['v_spb_discounttotal']       = $riw->v_spb_discounttotal;
                $list[$key]['v_spb_gross']               = $riw->v_spb_gross;
                $list[$key]['v_spb_netto']               = $riw->v_spb_netto;
                $list[$key]['fulfilled']                 = $riw->f_status_transfer;
                $list[$key]['warehouse_code']            = $riw->warehouse_code;
                $list[$key]['type_so']                   = $riw->type_so;
                $list[$key]['customer_bill_to']          = $riw->customer_bill_to;
                $list[$key]['customer_ship_to']          = $riw->customer_ship_to;
                $list[$key]['flag_description_address']  = $riw->flag_description_address;
                $list[$key]['flag_type_delivery']        = $riw->flag_type_delivery;
                $list[$key]['etd_so']                    = $riw->etd_so;
                $list[$key]['role_name']                 = $riw->role_name;
                $list[$key]['region_code']               = $riw->region_code;
                $list[$key]['status_so']                 = $riw->status_so;
                $list[$key]['status_rgto']               = $riw->status_rgto;
                $list[$key]['status_doc']                = $riw->status_doc;
                $list[$key]['items']                     = $items;
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
}