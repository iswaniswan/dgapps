<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_staff extends CI_Model {

    function list_user($cari){
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');
        $i_role = $this->session->userdata('i_role');

        if ( ($i_role == '4' || $i_role == '3') ) {
            //for ($i=1;$i<=5;$i++) {
            
            $query = $this->db->query("select * from tbl_user where username_upline = '$username' ");
            $sql = "select * from tbl_user where username = '$username'";

            if ($query->num_rows() > 0) {
                $sql .= " union all select * from tbl_user where username_upline = '$username' ";
                foreach ($query->result() as $row) {
                    $query2 = $this->db->query(" select * from tbl_user where username_upline = '$row->username'");

                    if ($query2->num_rows() > 0) {
                        $sql .= "union all select * from tbl_user where username_upline = '$row->username' ";
                        foreach ($query2->result() as $row2) {
                             $query3 = $this->db->query("select * from tbl_user where username_upline = '$row2->username'");

                            if ($query3->num_rows() > 0) {
                                $sql .= " union all select * from tbl_user where username_upline = '$row2->username' ";
                            }
                        }
                    }
                    
                }  
            }

            return $this->db->query($sql);
            //}
        } else {
            return $this->db->query("select * from tbl_user where i_company = '$i_company' and f_active = 'true' 
            and not i_staff isnull and username in(
                select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                    select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                    )
                    and a.i_company = '$i_company' and b.e_name ilike '%$cari%'
                    and a.username = b.username and a.i_company = b.i_company and (b.i_role >= '3' or (b.username = 'ganni' or b.username = 'suyadi' or b.username = 'admin')   )
                    group by a.username
            )");
        }
        

    }

    function data_staff($id){
        $i_company = $this->session->userdata('i_company');
        
        return $this->db->query("select a.i_staff, a.e_name, a.phone, b.e_role_name, a.f_summary_sales from tbl_user a, tbl_user_role b
        where a.i_role = b.i_role
        and a.i_company= b.i_company
        and a.username ='$id' and a.i_company = '$i_company' ");
    }

    function view_serverside($id){
        $i_company = $this->session->userdata('i_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select x.e_customer_name, x.createdat_checkin, x.createdat_checkout, x.durasi, x.i_spb, x.e_saran, x.e_foto from(
            select b.e_customer_name, a.createdat as createdat_checkin, a.createdat as createdat_checkout, NULL as durasi, a.i_spb, NULL as e_saran, NULL as e_foto from tbl_spb a, tbl_customer b
            where a.i_company = b.i_company
            and a.i_customer = b.i_customer
            and a.i_area = b.i_area
            and a.username = '$id'
            and a.i_company = '$i_company'
            and a.i_spb||a.i_customer||a.i_area not in(
            select c.i_spb||c.i_customer||c.i_area from tbl_user b, tbl_customer f, tbl_customer_checkin a
            left join tbl_spb c on(a.i_company = c.i_company and a.i_customer = c.i_customer and a.username = c.username and c.createdat > a.createdat_checkin and c.createdat < a.createdat_checkout)
            left join tbl_customer_saran d on(a.i_company = d.i_company and a.i_customer = d.i_customer and a.username = d.username and d.createdat > a.createdat_checkin and d.createdat < a.createdat_checkout)
            left join tbl_customer_dokumentasi e on(a.i_company = e.i_company and a.i_customer = e.i_customer and a.username = e.username and e.createdat > a.createdat_checkin and e.createdat < a.createdat_checkout)
            where a.username = b.username
            and a.i_company = b.i_company
            and a.i_company = f.i_company 
            and a.i_customer = f.i_customer
            and a.username = '$id'
            and a.i_company = '$i_company'
            and not c.i_spb isnull
            )
            union all
            
            
            select f.e_customer_name, a.createdat_checkin, a.createdat_checkout, (a.createdat_checkout - a.createdat_checkin) as durasi,
            (select i_spb from tbl_spb where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1),
            (select e_saran from tbl_customer_saran where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1),
            (select e_foto from tbl_customer_dokumentasi where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1)  
            from tbl_user b, tbl_customer f, tbl_customer_checkin a
            where a.username = b.username
            and a.i_company = b.i_company
            and a.i_company = f.i_company 
            and a.i_customer = f.i_customer
            and a.username = '$id'
            and a.i_company = '$i_company'
            
            ) as x
            order by x.createdat_checkin desc");
        
        $datatables->edit('createdat_checkin', function ($data) {
            $createdat_checkin = $data['createdat_checkin'];
            if($createdat_checkin == ''){
                return '';
            }else{
                return date("d F Y H:i:s", strtotime($createdat_checkin) );
            }
        });

        $datatables->edit('durasi', function ($data) {
            $durasi = $data['durasi'];
            if($durasi == ''){
                return '';
            }else{
                return date("H:i:s", strtotime($durasi) );
            }
        });

        $datatables->edit('createdat_checkout', function ($data) {
            $createdat_checkout = $data['createdat_checkout'];
            if($createdat_checkout == ''){
                return '';
            }else{
                return date("d F Y H:i:s", strtotime($createdat_checkout) );
            }
        });

		$datatables->add('action', function ($data) {
            $i_spb = trim($data['i_spb']);
            $e_saran = trim($data['e_saran']);
            $e_foto = trim($data['e_foto']);
            $data = '';

            if($i_spb != ''){
                $data .= "<i class='fas fa-shopping-cart'></i>&nbsp;&nbsp;";
            }

            if($e_saran != ''){
                $data .= "<i class='fas fa-inbox'></i>&nbsp;&nbsp;";
            }

            if($e_foto != ''){
                $data .= "<i class='fas fa-image'></i>&nbsp;&nbsp;";
            }

			return $data;
        });

        $datatables->hide('i_spb');
        $datatables->hide('e_saran');
        $datatables->hide('e_foto');


        return $datatables->generate();
    }

}

/* End of file M_staff.php */
