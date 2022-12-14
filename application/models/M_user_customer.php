<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_user_customer extends CI_Model
{

    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT DISTINCT
               a.username,
                e_name,
                e_password,
                a.f_active
            FROM
                tbl_user_toko a, tbl_user_toko_item b
            WHERE a.username = b.username
            AND b.id_company = '$this->i_company'
                ", FALSE);
        $datatables->edit('f_active', function ($data) {
            $link = "'".base_url() . "user-customer'";
            $username = "'" . encrypt_url(trim($data['username'])) . "'";
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="badge badge-success" onclick="changestatus('.$link.','.$username.');">Active</span>';
            } else {
                return '<span class="badge badge-danger" onclick="changestatus('.$link.','.$username.');">Inactive</span>';
            }
        });

        $datatables->edit('username', function ($data) {
            $username = $data['username'];
            return '<a title="View Data" href="' . base_url('user-customer/view/' . encrypt_url($username)) . '">' . $username . '</a>';
        });

        $datatables->edit('e_password', function ($data) {
            return decrypt_password($data['e_password']);
        });

        $datatables->add('action', function ($data) {
            $username = trim($data['username']);
            $data = '';
            /* return '<a href="#" onclick="change_password(' . $username . '); return false;" class="change">Change Password</a>'; */
            $data      .= "<a href='" . base_url() . 'user-customer/view/' . encrypt_url($username) . "' title='View Data'><i class='fas fa-eye text-success darken-4 fa-lg mr-2'></i></a>";
            $data      .= "<a href='" . base_url() . 'user_customer/edit/' . encrypt_url($username) . "' title='Edit Data'><i class='fas fa-edit text-primary darken-4 fa-lg'></i></a>";
            return $data;
        });

        return $datatables->generate();
    }

    public function get_customer($cari)
    {
        return $this->db->query("SELECT i_customer, e_customer_name FROM tbl_customer WHERE /*f_active = 't' AND*/ i_company = '$this->i_company' AND (i_customer ILIKE '%$cari%' OR e_customer_name ILIKE '%$cari%' ) ORDER BY 2", FALSE);
    }

    public function simpan($username, $e_name, $e_password, $i_customer)
    {
        /* $this->load->library('custom');
        $e_password = $this->custom->password($e_password); */

        $data = array(
            'username' => $username,
            'e_password' => encrypt_password($e_password),
            'e_name' => $e_name,
            'createdat' => current_datetime(),
        );

        $this->db->insert('tbl_user_toko', $data);

        if (is_array($i_customer) || is_object($i_customer)) {
            foreach ($i_customer as $customer) {
                $data = array(
                    'id_company' => $this->i_company,
                    'username' => $username,
                    'i_customer' => $customer,
                    'createdat' => current_datetime(),
                );
                $this->db->insert('tbl_user_toko_item', $data);
            }
        }
    }

    public function cek_data($username)
    {
        return $this->db->get_where('tbl_user_toko', ['username' => $username])->row_array();
    }

    public function data_toko($username)
    {
        return $this->db->query("SELECT username, e_name, e_password FROM tbl_user_toko WHERE username = '$username'", FALSE);
    }

    public function data_customer($username)
    {
        return $this->db->query("SELECT a.i_customer, e_customer_name FROM tbl_user_toko_item a, tbl_customer b WHERE a.i_customer = b.i_customer AND b.i_company = a.id_company AND username = '$username'", FALSE);
    }

    public function update($username, $username_old, $e_name, $e_password, $i_customer)
    {
        if ($e_password != '' || $e_password != null) {
            $data = array(
                'username' => $username,
                'e_password' => encrypt_password($e_password),
                'e_name' => $e_name,
                'modifiedat' => current_datetime(),
            );
        } else {
            $data = array(
                'username' => $username,
                'e_name' => $e_name,
                'modifiedat' => current_datetime(),
            );
        }
        $this->db->where('username', $username_old);
        $this->db->update('tbl_user_toko', $data);

        if (is_array($i_customer) || is_object($i_customer)) {
            $this->db->where('username', $username_old);
            $this->db->delete('tbl_user_toko_item');
            foreach ($i_customer as $customer) {
                $data = array(
                    'id_company' => $this->i_company,
                    'username' => $username,
                    'i_customer' => $customer,
                    'createdat' => current_datetime(),
                    'modifiedat' => current_datetime(),
                );
                $this->db->insert('tbl_user_toko_item', $data);
            }
        }
    }

    public function changestatus($id)
	{
		$this->db->select('f_active');
		$this->db->from('tbl_user_toko');
		$this->db->where('username', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$status = $query->row()->f_active;
		} else {
			$status = 'f';
		}
		if ($status == 'f') {
			$fstatus = 't';
		} else {
			$fstatus = 'f';
		}
		$table = array(
			'f_active' => $fstatus,
		);
		$this->db->where('username', $id);
		$this->db->update('tbl_user_toko', $table);
	}

    public function download_user()
    {
        return $this->db->query("SELECT DISTINCT
                a.username,
                e_name,
                e_password,
                case when a.f_active = 't' then 'Aktif' else 'Tidak Aktif' end as f_active
            FROM
                tbl_user_toko a, tbl_user_toko_item b
            WHERE a.username = b.username
            AND b.id_company = '$this->i_company'
            ORDER BY 2");
    }
}

/* End of file M_user_customer.php */
