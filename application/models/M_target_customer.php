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
            $data      .= "<a href='" . base_url() . 'target_customer/edit/' . encrypt_url($i_customer) .'/'. encrypt_url($i_periode)."' title='Edit Data'><i class='fas fa-pencil-ruler text-success darken-4 fa-lg'></i></a>";
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

    public function change_password($username, $password)
    {
        $this->load->library('custom');

        $i_company = $this->session->userdata('i_company');

        $password = $this->custom->password($password);

        $data = array(
            'e_password' => $password,
        );

        $this->db->where('username', $username);
        $this->db->where('i_company', $i_company);
        $this->db->update('tbl_user', $data);

    }

    public function cek_data($id)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->get_where('tbl_user', ['username' => $id, 'i_company' => $i_company])->row_array();
    }

    public function data_user($id)
    {
        $i_company = $this->session->userdata('i_company');

        return $this->db->query("select username, i_staff, e_name, phone, email, address, i_area, i_role, f_active, username_upline from tbl_user where username = '$id'
        and i_company = '$i_company'");

    }

    public function data_area()
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->select('*')->from('tbl_area')->where('f_active', 't')->where('i_company', $i_company)->order_by('i_area', 'asc')->get();
    }

    public function data_role()
    {
        $i_company = $this->session->userdata('i_company');
        $i_role = $this->session->userdata('i_role');
        return $this->db->query("select * from tbl_user_role where i_company = '$i_company' and i_role > '$i_role' order by i_role asc");
        //    return $this->db->select('*')->from('tbl_user_role')->where('i_company', $i_company, 'i_role >', $i_role)->order_by('i_role', 'asc')->get();
    }

    public function update($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email)
    {
        $i_company = $this->session->userdata('i_company');

        $data = array(
            'i_role' => $i_role,
            'i_area' => $i_area,
            'f_active' => $f_active,
            'address' => $address,
            'e_name' => $e_name,
            'phone' => $phone,
            'email' => $email,
            'modifiedat' => current_datetime(),
        );

        $this->db->where('username', $username);
        $this->db->where('i_company', $i_company);
        $this->db->update('tbl_user', $data);

    }

}

/* End of file M_user_management.php */
