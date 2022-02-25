<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_banner extends CI_Model
{

    function serverside()
    {
        $i_company = $this->session->userdata('i_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT e_path, e_remark, d_start, d_end, f_active FROM tbl_banner WHERE id_company = '$i_company'");
        $datatables->edit('f_active', function ($data) {
            $link = "'" . base_url() . "banner'";
            $id = "'" . encrypt_url(trim($data['e_path'])) . "'";
            $f_active = $data['f_active'];
            if ($f_active == 't') {
                return '<span class="badge badge-success" onclick="changestatus(' . $link . ',' . $id . ');">Active</span>';
            } else {
                return '<span class="badge badge-danger" onclick="changestatus(' . $link . ',' . $id . ');">Inactive</span>';
            }
        });
        $datatables->add('action', function ($data) {
            $id = trim($data['e_path']);
            $data = '';
            /* $data .= "<a href='" . base_url() . 'banner/view/' . encrypt_url($id)."' title='Edit Data'><i class='fas fa-eye mr-2 text-success darken-4 fa-lg'></i></a>"; */
            $data .= "<a href='" . base_url() . 'banner/edit/' . encrypt_url($id) . "' title='Edit Data'><i class='fas fa-edit text-primary darken-4 fa-lg'></i></a>";
            return $data;
        });

        return $datatables->generate();
    }

    public function simpan($image, $note, $d_start, $d_end)
    {
        $i_company = $this->session->userdata('i_company');

        $data = array(
            'id_company' => $i_company,
            'e_path' => $image,
            'e_remark' => $note,
            'd_start' => $d_start,
            'd_end' => $d_end,
        );

        $this->db->insert('tbl_banner', $data);
    }

    public function update($image, $image_old, $note, $d_start, $d_end)
    {
        $i_company = $this->session->userdata('i_company');

        $data = array(
            'id_company' => $i_company,
            'e_path' => $image,
            'e_remark' => $note,
            'd_start' => $d_start,
            'd_end' => $d_end,
            'modifiedat' => current_datetime(),
        );
        $this->db->where('e_path', $image_old);
        $this->db->update('tbl_banner', $data);
    }

    public function changestatus($id)
    {
        $this->db->select('f_active');
        $this->db->from('tbl_banner');
        $this->db->where('e_path', $id);
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
        $this->db->where('e_path', $id);
        $this->db->update('tbl_banner', $table);
    }
}

/* End of file M_user_management.php */
