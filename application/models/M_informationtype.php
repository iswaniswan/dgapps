<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_informationtype extends CI_Model
{

    public function serverside()
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT id, e_type_name, e_icon, e_color
            FROM tbl_information_type
            ORDER BY 2 ASC"
        );

        $datatables->add('action', function ($data) {
            $id = trim($data['id']);
            $data = '';
            // $data      .= "<a href='" . base_url($this->folder . '/view/' . encrypt_url($id)) . "' title='View Data'><i class='fas fa-eye text-success darken-4 fa-lg mr-2'></i></a>";
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
            'e_type_name' => $this->input->post('e_type_name'),
            'e_color' => "'".$this->input->post('e_color')."'",
        );

        $this->db->insert('tbl_information_type', $data);
    }

    public function cek_data($id)
    {
        return $this->db->get_where('tbl_information_type', ['id' => $id])->row_array();
    }

    public function data_edit($id)
    {
        return $this->db->get_where('tbl_information_type', ['id' => $id]);
    }

    public function update()
    {
        $data = array(
            'e_type_name' => $this->input->post('e_type_name'),
            'e_color' => "'".$this->input->post('e_color')."'",
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_information_type', $data);
    }

    public function changestatus($id)
    {
        $this->db->query("UPDATE tbl_information SET f_active = CASE WHEN f_active = TRUE THEN FALSE ELSE TRUE END WHERE id = '$id';");
    }
}

/* End of file M_user_customer.php */
