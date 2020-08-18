<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_Dashboard extends CI_Model {

    function activitylist($dfrom, $dto){
        $this->load->library('custom');
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select x.e_name, x.e_customer_name, x.createdat_checkin, x.createdat_checkout, x.durasi, x.i_spb, x.e_saran, x.e_foto from(
            select c.e_name, b.e_customer_name, a.createdat as createdat_checkin, a.createdat as createdat_checkout, NULL as durasi, a.i_spb, NULL as e_saran, NULL as e_foto from tbl_spb a, tbl_customer b, tbl_user c
            where a.i_company = b.i_company
            and a.i_customer = b.i_customer
            and a.i_company = c.i_company
            and a.username = c.username
            and a.i_area = b.i_area
            and a.username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
            and a.i_company = '$i_company'
            and a.d_spb >= '$dfrom' and a.d_spb <= '$dto'
            and a.i_spb||a.i_customer||a.i_area not in(
            select c.i_spb||c.i_customer||c.i_area from tbl_user b, tbl_customer f, tbl_customer_checkin a
            left join tbl_spb c on(a.i_company = c.i_company and a.i_customer = c.i_customer and a.username = c.username and c.createdat > a.createdat_checkin and c.createdat < a.createdat_checkout)
            left join tbl_customer_saran d on(a.i_company = d.i_company and a.i_customer = d.i_customer and a.username = d.username and d.createdat > a.createdat_checkin and d.createdat < a.createdat_checkout)
            left join tbl_customer_dokumentasi e on(a.i_company = e.i_company and a.i_customer = e.i_customer and a.username = e.username and e.createdat > a.createdat_checkin and e.createdat < a.createdat_checkout)
            where a.username = b.username
            and a.i_company = b.i_company
            and a.i_company = f.i_company 
            and a.i_customer = f.i_customer
            and a.username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
            and a.i_company = '$i_company'
            and not c.i_spb isnull
            and a.d_checkin >= '$dfrom' and a.d_checkin <= '$dto'
            )
            union all
            
            
            select b.e_name, f.e_customer_name, a.createdat_checkin, a.createdat_checkout, (a.createdat_checkout - a.createdat_checkin) as durasi,
            (select i_spb from tbl_spb where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1),
            (select e_saran from tbl_customer_saran where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1),
            (select e_foto from tbl_customer_dokumentasi where i_company = a.i_company and username = a.username and createdat > a.createdat_checkin and createdat < a.createdat_checkout limit 1)  
            from tbl_user b, tbl_customer f, tbl_customer_checkin a
            where a.username = b.username
            and a.i_company = b.i_company
            and a.i_company = f.i_company 
            and a.i_customer = f.i_customer
            and a.username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
            and a.i_company = '$i_company'
            and a.d_checkin >= '$dfrom' and a.d_checkin <= '$dto'
            
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

/* End of file M_user_management.php */
