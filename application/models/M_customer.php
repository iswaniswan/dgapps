<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_customer extends CI_Model
{

    public function serverside()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.i_customer, a.e_customer_name, b.e_company_name, c.e_area_name, a.f_active from
        tbl_customer a, tbl_company b, tbl_area c
        where
        a.i_company = b.i_company
        and a.i_area = c.i_area
        and a.i_company = c.i_company
        and a.i_company = '$i_company'
        and a.i_area in(
            select i_area from tbl_user_area where username = '$username' and i_company = '$i_company'
        )
        order by a.i_area, a.i_customer");
        $datatables->edit('f_active', function ($data) {
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="badge badge-success">Active</span>';
            } else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        });

        $datatables->edit('i_customer', function ($data) {
            $i_customer = $data['i_customer'];
            return '<a href="' . base_url('customer/view/' . encrypt_url($i_customer)) . '">' . $i_customer . '</a>';
        });

        return $datatables->generate();
    }

    public function cek_data($id)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->get_where('tbl_customer', ['i_customer' => $id, 'i_company' => $i_company])->row_array();
    }

    public function data_customer($id)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->query("select a.i_customer, a.e_customer_name, a.e_customer_address, b.e_company_name, c.e_area_name, a.f_active, a.latitude, a.longitude from
        tbl_customer a, tbl_company b, tbl_area c
        where
        a.i_company = b.i_company
        and a.i_area = c.i_area
        and a.i_company = c.i_company
        and a.i_company = '$i_company'
        and a.i_customer = '$id'
        order by a.i_area, a.i_customer");
    }

    public function view_serverside($id)
    {
        $i_company = $this->session->userdata('i_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select b.e_name, a.createdat_checkin, a.createdat_checkout, (a.createdat_checkout - a.createdat_checkin) as durasi,
        (select i_spb from tbl_spb where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1) as i_spb,
        (select e_saran from tbl_customer_saran where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1) as e_saran,
        (select e_foto from tbl_customer_dokumentasi where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1) as e_foto
        from tbl_user b, tbl_customer f, tbl_customer_checkin a
        where a.username = b.username
        and a.i_company = b.i_company
        and a.i_company = f.i_company
        and a.i_customer = f.i_customer
        and a.i_customer = '$id'
        and a.i_company = '$i_company'");
        $datatables->edit('createdat_checkin', function ($data) {
            $createdat_checkin = $data['createdat_checkin'];
            if ($createdat_checkin == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat_checkin));
            }
        });

        $datatables->edit('createdat_checkout', function ($data) {
            $createdat_checkout = $data['createdat_checkout'];
            if ($createdat_checkout == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat_checkout));
            }
        });

        $datatables->add('action', function ($data) {
            $i_spb = trim($data['i_spb']);
            $e_saran = trim($data['e_saran']);
            $e_foto = trim($data['e_foto']);
            $data = '';

            if ($i_spb != '') {
                $data .= "<i class='fas fa-shopping-cart'></i>&nbsp;&nbsp;";
            }

            if ($e_saran != '') {
                $data .= "<i class='fas fa-inbox'></i>&nbsp;&nbsp;";
            }

            if ($e_foto != '') {
                $data .= "<i class='fas fa-image'></i>&nbsp;&nbsp;";
            }

            return $data;
        });

        $datatables->hide('i_spb');
        $datatables->hide('e_saran');
        $datatables->hide('e_foto');

        return $datatables->generate();
    }

    public function change_address($i_customer, $address, $latitude, $longitude)
    {
        $i_company = $this->session->userdata('i_company');

        $data = array(
            'e_customer_address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
        );

        $this->db->where('i_company', $i_company);
        $this->db->where('i_customer', $i_customer);

        $this->db->update('tbl_customer', $data);

    }

}

/* End of file M_customer.php */