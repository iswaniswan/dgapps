<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_user_management extends CI_Model
{

    public function serverside()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.username, a.e_name, b.e_area_name, c.e_role_name, a.f_active from tbl_user a, tbl_area b, tbl_user_role c
        where
        a.i_area = b.i_area
        and a.i_company = b.i_company
        and a.i_role = c.i_role
        and a.i_company = c.i_company
        and a.i_company = '$i_company'
        and a.username in(select distinct on (a.username) a.username from tbl_user_area a, tbl_user b where a.i_area in(
            select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
            )
            and a.i_company = '$i_company'
            and a.username = b.username and a.i_company = b.i_company and b.i_role >= '1'
            group by a.username)");
        $datatables->edit('f_active', function ($data) {
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="badge badge-success">Active</span>';
            } else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        });

        $datatables->edit('username', function ($data) {
            $username = $data['username'];
            return '<a href="' . base_url('user-management/view/' . encrypt_url($username)) . '">' . $username . '</a>';
        });

        $datatables->add('action', function ($data) {
            $username = "'" . trim($data['username']) . "'";
            return '<a href="#" onclick="change_password(' . $username . '); return false;" class="change">Change Password</a>';
        });

        return $datatables->generate();
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

    public function simpan($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email, $e_password)
    {
        $this->load->library('custom');
        $e_password = $this->custom->password($e_password);
        $i_company = $this->session->userdata('i_company');
        $username_upline = $this->session->userdata('username');

        $data = array(
            'username' => $username,
            'e_password' => $e_password,
            'e_name' => $e_name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'i_area' => $i_area,
            'i_role' => $i_role,
            'i_company' => $i_company,
            'i_staff' => $i_staff,
            'f_active' => $f_active,
            'createdat' => current_datetime(),
            'username_upline' => $username_upline,
        );

        $this->db->insert('tbl_user', $data);

    }

}

/* End of file M_user_management.php */
