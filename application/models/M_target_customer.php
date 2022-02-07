<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_target_customer extends CI_Model
{

    public function serverside()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                a.i_periode,
                a.i_customer,
                b.e_customer_name,
                v_spb_target,
                v_nota_target,
                a.f_active
            FROM
                tbl_customer_target a
            INNER JOIN tbl_customer b ON
                (b.i_customer = a.i_customer
                    AND a.id_company = b.i_company)
            WHERE
                a.id_company = '$i_company'");
        $datatables->edit('f_active', function ($data) {
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="badge badge-success">Active</span>';
            } else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        });

        $datatables->edit('e_customer_name', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_periode = trim($data['i_periode']);
            $e_customer_name = $data['e_customer_name'];
            return '<a href="' . base_url() . 'target_customer/view/' . encrypt_url($i_customer) .'/'. encrypt_url($i_periode). '">' . $e_customer_name . '</a>';
        });

        $datatables->edit('v_spb_target', function ($data) {
            return 'Rp. '.number_format($data['v_spb_target']);
        });

        $datatables->edit('v_nota_target', function ($data) {
            return 'Rp. '.number_format($data['v_nota_target']);
        });

        $datatables->add('action', function ($data) {
            $i_customer = trim($data['i_customer']);
            $i_periode = trim($data['i_periode']);
            $data = '';
            $data .= "<a href='" . base_url() . 'target_customer/view/' . encrypt_url($i_customer) .'/'. encrypt_url($i_periode)."' title='Edit Data'><i class='fas fa-eye mr-2 text-success darken-4 fa-lg'></i></a>";
            $data .= "<a href='" . base_url() . 'target_customer/edit/' . encrypt_url($i_customer) .'/'. encrypt_url($i_periode)."' title='Edit Data'><i class='fas fa-edit text-primary darken-4 fa-lg'></i></a>";
            return $data;
            // return '<a href="#" onclick="change_password(' . encrypt_url($i_customer) . '); return false;" class="change" title="Edit"><i class="fas fa-pencil-ruler mr-3 fa-lg"></i></a>';
        });

        return $datatables->generate();
    }

    public function get_customer($cari)
    {
        return $this->db->query("SELECT i_customer, e_customer_name FROM tbl_customer WHERE f_active = 't' AND i_company = '$this->i_company' AND (i_customer ILIKE '%$cari%' OR e_customer_name ILIKE '%$cari%' ) ORDER BY 2", FALSE);
    }

    public function get_data($i_customer, $i_periode)
    {
        return $this->db->query("SELECT a.*, b.e_customer_name FROM tbl_customer_target a, tbl_customer b WHERE a.i_customer = b.i_customer AND a.id_company = b.i_company AND a.i_customer = '$i_customer' AND i_periode = '$i_periode' AND id_company = '$this->i_company'", FALSE);
    }

    public function simpan($i_customer, $i_periode, $v_spb_target, $v_nota_target)
    {
        $this->db->query("INSERT INTO tbl_customer_target (id_company, i_customer, i_periode, v_spb_target, v_nota_target, createdat) 
            VALUES ('$this->i_company', '$i_customer', '$i_periode','$v_spb_target', '$v_nota_target',now())
            ON CONFLICT (id_company, i_customer, i_periode) DO UPDATE 
            SET v_spb_target = excluded.v_spb_target, 
            v_nota_target = excluded.v_nota_target,
            modifiedat = now()
        ");
    }
}

/* End of file M_user_management.php */
