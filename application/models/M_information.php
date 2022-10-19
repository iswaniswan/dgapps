<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_information extends CI_Model
{

    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT a.id, b.e_type_name, to_char(d_start, 'DD FMMonth YYYY') d_start, to_char(d_end, 'DD FMMonth YYYY') d_end, e_title, e_deskripsi, f_active
            FROM tbl_information a
            INNER JOIN tbl_information_type b ON (b.id = a.id_type)
            WHERE a.i_company = '$this->i_company'
            ORDER BY d_end ASC
        "
        );
        $datatables->edit('f_active', function ($data) {
            $link = "'" . base_url() . "information'";
            $id = "'" . encrypt_url(trim($data['id'])) . "'";
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="btn badge badge-success" onclick="changestatus(' . $link . ',' . $id . ');">Active</span>';
            } else {
                return '<span class="btn badge badge-danger" onclick="changestatus(' . $link . ',' . $id . ');">Inactive</span>';
            }
        });

        $datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $data = '';
            $data      .= "<a href='" . base_url($this->folder . '/view/' . encrypt_url($id)) . "' title='View Data'><i class='fas fa-eye text-success darken-4 fa-lg mr-2'></i></a>";
            $data      .= "<a href='" . base_url($this->folder . '/edit/' . encrypt_url($id)) . "' title='Edit Data'><i class='fas fa-edit text-primary darken-4 fa-lg'></i></a>";
            return $data;
        });

        return $datatables->generate();
    }

    public function get_type($cari)
    {
        return $this->db->query("SELECT id, e_type_name FROM tbl_information_type WHERE (e_type_name ILIKE '%$cari%') ORDER BY 2", FALSE);
    }

    public function simpan()
    {
        $data = array(
            'i_company' => $this->i_company,
            'id_type' => $this->input->post('id_type'),
            'd_start' => $this->input->post('d_start'),
            'd_end' => $this->input->post('d_end'),
            'e_title' => $this->input->post('e_title'),
            'e_deskripsi' => $this->input->post('e_description'),
        );

        $this->db->insert('tbl_information', $data);
    }

    public function cek_data($id)
    {
        return $this->db->get_where('tbl_information', ['id' => $id])->row_array();
    }

    public function data_edit($id)
    {
        return $this->db->query(
            "SELECT a.*, b.e_type_name 
            FROM tbl_information a 
            INNER JOIN tbl_information_type b ON (b.id = a.id_type)
            WHERE a.id = '$id'
        "
        );
    }

    public function update()
    {
        $data = array(
            'id_type' => $this->input->post('id_type'),
            'd_start' => $this->input->post('d_start'),
            'd_end' => $this->input->post('d_end'),
            'e_title' => $this->input->post('e_title'),
            'e_deskripsi' => $this->input->post('e_description'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_information', $data);
    }

    public function changestatus($id)
    {
        $this->db->query("UPDATE tbl_information SET f_active = CASE WHEN f_active = TRUE THEN FALSE ELSE TRUE END WHERE id = '$id';");
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
