<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_product extends CI_Model
{

    public function serverside()
    {
        $this->load->library('custom');
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.i_product, a.e_product_name, b.e_product_groupname, a.f_active, a.i_company from tbl_product a, tbl_product_group b
        where a.i_company = b.i_company and a.i_product_group = b.i_product_group and a.i_company = '$i_company'");
        $datatables->hide('i_company');
        $datatables->edit('f_active', function ($data) {
            $i_product = $data['i_product'];
            $i_company = $data['i_company'];
            $f_active = $data['f_active'];

            $data_return = "";
            $selected1 = '';
            $selected2 = '';
            $isi = "'" . $i_product . "'," . $i_company . ",this";

            $data_return .= '<select id="f_active" name="f_active" class="form-control"
            onfocus="this.setAttribute(`PrvSelectedValue`,this.value);"
            onchange="change_status(' . $isi . ')">';

            if ($f_active == 't') {
                $selected1 = "selected='true'";
            } else {
                $selected2 = "selected='true'";
            }
            $data_return .= '<option value="t" ' . $selected1 . '>Active</option>';
            $data_return .= '<option value="f" ' . $selected2 . '>Inactive</option>';
            $data_return .= '</select>';

            return $data_return;
        });

        return $datatables->generate();
    }

}

/* End of file M_customer.php */