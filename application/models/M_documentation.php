<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_documentation extends CI_Model
{

    public function data_documentation()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        /** previous query */
        /*
        $datatables->query("select a.e_foto, b.e_customer_name, upper(c.e_name) as e_name, a.createdat, '$i_company' as i_company from tbl_customer_dokumentasi a, tbl_customer b, tbl_user c
        where a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_company = c.i_company
        and a.username = c.username
        and a.i_company = '$i_company'
        and a.username in(
            select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username
        )
        order by a.createdat desc");


        /** query dengan tambahan detail dokumen dan tipe dokumen */
        $sql_customer = "select a.e_foto, b.e_customer_name, upper(c.e_name) as e_name, a.createdat, '$i_company' as i_company from tbl_customer_dokumentasi a, tbl_customer b, tbl_user c
                            where a.i_company = b.i_company
                            and a.i_customer = b.i_customer
                            and a.i_company = c.i_company
                            and a.username = c.username
                            and a.i_company = '$i_company'
                            and a.username in(
                                select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                                    select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                                    )
                                    and a.i_company = '$i_company'
                                    and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                                    group by a.username
                            )
                            order by a.createdat desc";
        $sql_dokumen = "SELECT tcd.e_foto, tcd.e_detail, tdt.e_dokumentasi_name, tdt.f_active  FROM tbl_customer_dokumentasi tcd 
                            LEFT JOIN tbl_dokumentasi_type tdt ON tdt.id=tcd.id_dokumentasi_type
                            WHERE tcd.e_detail IS NOT NULL
                            AND tcd.id_dokumentasi_type IS NOT NULL ";
        $sql = "SELECT q_customer.e_foto, q_dokumen.e_dokumentasi_name, q_dokumen.e_detail, q_customer.e_customer_name, q_customer.e_name, q_customer.createdat, q_customer.i_company
                FROM ($sql_customer) AS q_customer LEFT JOIN ($sql_dokumen) AS q_dokumen ON q_dokumen.e_foto=q_customer.e_foto
                ORDER BY q_customer.createdat DESC";
        $datatables->query($sql);

        $datatables->hide('i_company');

        $datatables->edit('createdat', function ($data) {
            $createdat = $data['createdat'];
            if ($createdat == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat));
            }
        });

        $datatables->edit('e_foto', function ($data) {
            $e_foto = $data['e_foto'];
            $foto = "'$e_foto'";
            $i_company = $data['i_company'];
            $url = "'assets/images/dokumentasi/$i_company/$e_foto'";
            /*$imgData = base64_encode(file_get_contents(base_url() . 'assets/images/dokumentasi/' . $i_company . '/' . $e_foto));
            $src = 'data:image/jpg;base64,' . $imgData; */
            return '<a href="#" onclick="click_image('.$url.','.$foto.'); return false;"><i class="icon-images2 mr-2"></i>'.$e_foto.'</a>'
            ;
            /* return '<a href="' . $src . '" data-popup="lightbox">
                <img src="' . $src . '" alt="" class="img-preview rounded">
            </a>'
            ; */
        });

        $datatables->edit('e_detail', function ($data) {
           $e_detail = $data['e_detail'];
           return $e_detail == 'null' ? '' : $e_detail;
        });

        return $datatables->generate();
    }

    public function data_checkin()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select a.e_foto, b.e_customer_name, UPPER(c.e_name) as e_name, a.createdat_checkin, '$i_company' as i_company from tbl_customer_checkin a,
        tbl_customer b, tbl_user c
        where a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_company = c.i_company
        and a.username = c.username
        and a.i_company = '$i_company'
        and a.username in(
            select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and (b.i_role >= '3' or b.i_role = '1')
                group by a.username
        )
        order by a.createdat_checkin desc");

        $datatables->hide('i_company');

        $datatables->edit('createdat_checkin', function ($data) {
            $createdat_checkin = $data['createdat_checkin'];
            if ($createdat_checkin == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat_checkin));
            }
        });

        // $datatables->edit('e_foto', function ($data) {
        //     $e_foto = $data['e_foto'];
        //     $i_company = $data['i_company'];
        //     $imgData = base64_encode(file_get_contents(base_url() . 'assets/images/checkinselfie/' . $i_company . '/' . $e_foto));
        //     $src = 'data:image/jpg;base64,' . $imgData;
        //     return '<a href="' . $src . '" data-popup="lightbox"><i class="icon-images2 icon-2x mr-2"></i>'.$e_foto.'</a>';
        //     /* return '<a href="' . $src . '" data-popup="lightbox">
        //     <img src="' . $src . '" alt="" class="img-preview rounded">
        // </a>'; */
        // });

        $datatables->edit('e_foto', function ($data) {
            $e_foto = $data['e_foto'];
            $foto = "'$e_foto'";
            $i_company = $data['i_company'];
            $url = "'assets/images/checkinselfie/$i_company/$e_foto'";
            /*$imgData = base64_encode(file_get_contents(base_url() . 'assets/images/dokumentasi/' . $i_company . '/' . $e_foto));
            $src = 'data:image/jpg;base64,' . $imgData; */
            return '<a href="#" onclick="click_image('.$url.','.$foto.'); return false;"><i class="icon-images2 mr-2"></i>'.$e_foto.'</a>'
            ;
            /* return '<a href="' . $src . '" data-popup="lightbox">
                <img src="' . $src . '" alt="" class="img-preview rounded">
            </a>'
            ; */
        });

        return $datatables->generate();
    }

    public function serverside()
    {
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select b.e_customer_name, upper(c.e_name) as e_name, d.e_saran_typename, a.e_saran, a.e_respons, e.e_name as name, a.createdat, a.modifiedat, a.d_saran, a.i_saran_type,
        a.i_customer from tbl_customer b, tbl_user c, tbl_saran_type d, tbl_customer_saran a
        left join tbl_user e on(a.i_company = e.i_company and a.username_respons = e.username)
        where a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_company = c.i_company
        and a.username = c.username
        and a.i_company = d.i_company
        and a.i_saran_type = d.i_saran_type
        and a.i_company = '$i_company'
        and a.username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
            select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
            )
            and a.i_company = '$i_company'
            and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
            group by a.username)
        order by a.createdat desc");

        $datatables->hide('d_saran');
        $datatables->hide('i_saran_type');
        $datatables->hide('i_customer');

        $datatables->edit('createdat', function ($data) {
            $createdat = $data['createdat'];
            if ($createdat == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($createdat));
            }
        });

        $datatables->edit('modifiedat', function ($data) {
            $modifiedat = $data['modifiedat'];
            if ($modifiedat == '') {
                return '';
            } else {
                return date("d F Y H:i:s", strtotime($modifiedat));
            }
        });

        $datatables->edit('e_customer_name', function ($data) {
            $e_customer_name = $data['e_customer_name'];
            $d_saran = $data['d_saran'];
            $i_saran_type = $data['i_saran_type'];
            $i_customer = $data['i_customer'];

            return '<a href="' . base_url('documentation/view/' . encrypt_url($i_customer) . '/' . encrypt_url($i_saran_type) . '/' . encrypt_url($d_saran)) . '">' . $e_customer_name . '</a>';

        });

        return $datatables->generate();
    }

    public function cek_data($i_customer, $i_saran_type, $d_saran)
    {
        $i_company = $this->session->userdata('i_company');
        return $this->db->query("select b.e_customer_name, c.e_name, a.e_saran, a.e_respons, d.e_saran_typename, a.createdat, a.d_saran, a.i_saran_type,
        a.i_customer from tbl_customer_saran a, tbl_customer b, tbl_user c, tbl_saran_type d
        where a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_company = c.i_company
        and a.username = c.username
        and a.i_company = d.i_company
        and a.i_saran_type = d.i_saran_type
        and a.i_company = '$i_company'
        and a.i_customer = '$i_customer'
        and a.i_saran_type = '$i_saran_type'
        and a.d_saran = '$d_saran'
        order by a.createdat desc");
    }

    public function update($i_customer, $i_saran_type, $d_saran, $response)
    {
        $i_company = $this->session->userdata('i_company');
        $username = $this->session->userdata('username');

        $cek = $this->db->query("select b.e_customer_name, c.e_name, a.e_saran, a.e_respons, d.e_saran_typename, a.createdat, a.d_saran, a.i_saran_type,
        a.i_customer from tbl_customer_saran a, tbl_customer b, tbl_user c, tbl_saran_type d
        where a.i_company = b.i_company
        and a.i_customer = b.i_customer
        and a.i_company = c.i_company
        and a.username = c.username
        and a.i_company = d.i_company
        and a.i_saran_type = d.i_saran_type
        and a.i_company = '$i_company'
        and a.i_customer = '$i_customer'
        and a.i_saran_type = '$i_saran_type'
        and a.d_saran = '$d_saran'
        and a.e_respons isnull
        order by a.createdat desc");

        if ($cek->num_rows() > 0) {

            $data = array(
                'e_respons' => $response,
                'username_respons' => $username,
                'modifiedat' => current_datetime(),
            );

            $this->db->where('i_customer', $i_customer);
            $this->db->where('i_saran_type', $i_saran_type);
            $this->db->where('d_saran', $d_saran);

            $this->db->update('tbl_customer_saran', $data);
        }
    }

}

/* End of file M_documentation.php */