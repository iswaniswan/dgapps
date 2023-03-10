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
        /* previous query, tanpa data upline */
        /*
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
        */
        /* query dengan data upline */
        $sql_user = "select a.username, a.e_name, b.e_area_name, c.e_role_name, a.username_upline, a.f_active 
                            from tbl_user a, tbl_area b, tbl_user_role c
                            where a.i_area = b.i_area
                            and a.i_company = b.i_company
                            and a.i_role = c.i_role
                            and a.i_company = c.i_company
                            and a.i_company = '$i_company'
                            and a.username in(
                                select distinct on (a.username) a.username 
                                from tbl_user_area a, tbl_user b 
                                where a.i_area in(
                                    select a.i_area 
                                    from tbl_user_area a 
                                    where a.username = '$username' and a.i_company = '$i_company'
                                )
                                and a.i_company = '$i_company'
                                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '1'
                                group by a.username
                            )";
        $sql_upline = "select distinct on (tu.username) tu.username, tu.e_name, tur.e_role_name  from tbl_user tu 
                            inner join tbl_user_role tur on tur.i_role=tu.i_role 
                            group by 1, 2, 3";
        $sql = "select q_user.username, q_user.e_name, q_user.e_area_name, q_user.e_role_name, concat(q_upline.e_name, ' - ', q_upline.e_role_name) as upline, q_user.f_active from (
                    $sql_user
                ) AS q_user LEFT JOIN ($sql_upline) AS q_upline ON q_user.username_upline=q_upline.username
                ORDER BY q_user.f_active DESC, q_user.username ASC";
        $datatables->query($sql);

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
        
        $where = "where i_company = '$i_company'";
        $filter_role = " and i_role > '$i_role'";
        if ($i_role == '1') {
            $filter_role = "";
        }

        $where .= $filter_role;

        $sql = "select * from tbl_user_role $where order by i_role asc";
                
        return $this->db->query($sql);
        //    return $this->db->select('*')->from('tbl_user_role')->where('i_company', $i_company, 'i_role >', $i_role)->order_by('i_role', 'asc')->get();
    }

    public function update($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email, $username_upline=null)
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
            'i_staff' => $i_staff,
            'modifiedat' => current_datetime(),
            'username_upline' => $username_upline
        );

        $this->db->where('username', $username);
        $this->db->where('i_company', $i_company);
        $this->db->update('tbl_user', $data);

    }

    public function simpan($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email, $e_password, $username_upline=null)
    {
        $this->load->library('custom');
        $e_password = $this->custom->password($e_password);
        $i_company = $this->session->userdata('i_company');

        if ($username_upline == null) {
            $username_upline = $this->session->userdata('username');
        }

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

    public function data_upline()
    {
        $i_company = $this->session->userdata('i_company');

        $sql = "SELECT tu.username, tu.e_name, tur.e_role_name  
                    FROM tbl_user tu 
                    INNER JOIN tbl_user_role tur ON tur.i_role=tu.i_role 
                    WHERE tu.i_company = '$i_company'
                    GROUP BY 1, 2, 3
                    ORDER BY 1 ASC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insert_user_area($data)
    {
        $this->db->insert('tbl_user_area', $data);
    }

    public function get_array_user_area($username)
    {
        $i_company = $this->session->userdata('i_company');
        $sql = "SELECT i_area FROM tbl_user_area WHERE i_company = '$i_company' AND username = '$username'";
        $query = $this->db->query($sql);

        $array = [];
        foreach ($query->result() as $row) {
            $array[] = $row->i_area;
        }
        return $array;
    }

    public function update_user_area($username, $array=[])
    {
        /** delete records first */
        

        $i_company = $this->session->userdata('i_company');

        $this->delete_user_area($username, $i_company);
        foreach ($array as $area) {
            $_area = [
                'username' => $username,
                'i_area' => $area,
                'i_company' => $i_company
            ];
            $this->insert_user_area($_area);
        }
    }

    public function delete_user_area($username, $i_company)
    {
        $this->db->where('username', $username);
        $this->db->where('i_company', $i_company);
        $this->db->delete('tbl_user_area');
    }

}

/* End of file M_user_management.php */
