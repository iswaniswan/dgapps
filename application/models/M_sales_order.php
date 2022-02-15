<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_sales_order extends CI_Model
{

    public function serverside()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.i_spb, a.i_area, b.e_customer_name, c.e_area_name, d.e_name, a.createdat, a.f_spb_cancel, a.f_status_transfer from tbl_spb a, tbl_customer b, tbl_area c, tbl_user d
        where
        a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_area = b.i_area
        and a.i_company = c.i_company
        and a.i_area = c.i_area
        and a.i_company = d.i_company
        and a.username = d.username
        and a.i_company = '$i_company'
        and a.i_area in(
            select i_area from tbl_user_area where username = '$username' and i_company = '$i_company'
        )
        order by a.i_spb desc");

        $datatables->hide('f_spb_cancel');
        $datatables->hide('i_area');

        $datatables->edit('createdat', function ($data) {
            $createdat = $data['createdat'];
            if ($createdat == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat));
            }
        });

        $datatables->edit('f_status_transfer', function ($data) {
            $f_spb_cancel = $data['f_spb_cancel'];
            $f_status_transfer = $data['f_status_transfer'];

            if ($f_spb_cancel == 't') {
                return '<span class="badge badge-danger">Cancel</span>';
            } elseif ($f_status_transfer == 'f') {
                return '<span class="badge badge-warning">Pending</span>';
            } elseif ($f_status_transfer == 't') {
                return '<span class="badge badge-success">Transfer</span>';
            }
        });

        $datatables->edit('i_spb', function ($data) {
            $i_spb = $data['i_spb'];
            $i_area = $data['i_area'];
            return '<a href="' . base_url('sales-order/view/' . encrypt_url($i_spb)) . '/' . encrypt_url($i_area) . '">' . $i_spb . '</a>';
        });

        return $datatables->generate();
    }

    public function cek_data($i_spb, $i_area)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->query("select a.i_spb, a.i_area, a.i_promo, a.i_customer, b.e_customer_name, b.e_customer_address, b.e_phone_number, a.username, d.i_staff,
        d.e_name, d.phone, a.f_spb_cancel, a.f_status_transfer, a.createdat, a.e_remark, a.v_spb_discounttotal, a.v_spb_gross, a.v_spb_netto
        from tbl_spb a, tbl_customer b, tbl_user d where a.i_company = b.i_company and a.i_company = d.i_company
        and a.i_customer = b.i_customer and a.i_area = b.i_area and a.username = d.username
        and a.i_company = '$i_company' and a.i_spb = '$i_spb' and a.i_area = '$i_area'");
    }

    public function detail_spb($i_spb, $i_area)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->query("select i_product, e_product_name, v_unit_price, n_order, e_remark from tbl_spb_item
        where
        i_company = '$i_company'
        and i_spb = '$i_spb'
        and i_area = '$i_area'");
    }

    public function cancel_so($i_spb, $i_area)
    {
        $i_company = $this->session->userdata('i_company');
        $data = array(
            'f_spb_cancel' => 't',
        );

        $this->db->where('i_spb', $i_spb);
        $this->db->where('i_area', $i_area);
        $this->db->where('i_company', $i_company);
        $this->db->update('tbl_spb', $data);
    }

}

/* End of file M_user_management.php */