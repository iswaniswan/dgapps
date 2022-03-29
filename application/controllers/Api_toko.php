<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_toko extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('upload');
    }

    public function index_get()
    {
        //echo "KEsini";
    }

    public function login_post() {
        $this->load->library('custom');

        $username = $this->post('username');
        $password = $this->custom->password($this->post('password'));

        $check_login = $this->db->query("
            select username, e_password from tbl_user_toko where username = '$username' and f_active = true
        ", FALSE);

        if ($check_login->num_rows() > 0) {
            if ($check_login->row()->e_password == $password) {
                 $this->response([
                    'status' => true,
                    'message' => 'Berhasil Login ',
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Password Salah ',
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Username '.$username. ' Belum Terdaftar',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function main_header_post() {
        $this->load->library('custom');

        $username = $this->post('username');
        $periode = date('Y');

        $data = $this->db->query("
             select a.username, a.e_name , b.i_customer, coalesce(c.v_nota_target,0) as v_nota_target, c.i_periode from tbl_user_toko a
             inner join tbl_user_toko_item b on (a.username = b.username)
             inner join tbl_customer_target c on (b.i_customer = c.i_customer and b.id_company = c.id_company)
             where a.username = '$username' and c.i_periode = '$periode'
        ", FALSE);

        $total = 0;
        $e_name = '';
        $list_customer = array();
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $total += $row->v_nota_target;
                $e_name = $row->e_name;
                array_push($list_customer, "'".$row->i_customer."'");
            }
            $arrayTxt = implode(',', $list_customer);
            $data2 = $this->db->query("
                select v_nota_netto, v_sisa, v_spb 
                from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                $$
                 select sum(v_nota_netto) as v_nota_netto , sum(v_sisa) as v_sisa , sum(v_spb) as v_spb from (
                      select coalesce(sum(v_nota_netto), 0) as v_nota_netto , coalesce(sum(v_sisa), 0) as v_sisa, 0 as v_spb from tm_nota 
                      where f_nota_cancel = false and to_char(d_nota , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                      union all 
                      select 0 as v_nota_netto , 0 as v_sisa, coalesce(sum(v_spb), 0) as v_spb from tm_spb 
                      where f_spb_cancel = false and to_char(d_spb , 'yyyy') = '$periode' and i_customer in ($arrayTxt)
                ) as x 
                $$
                ) AS nilai (
                    v_nota_netto numeric, v_sisa numeric, v_spb numeric
                )
            ", FALSE)->row();

            $item = $this->db->query("
                select i_nota , e_color , d_nota, d_jatuh_tempo, v_nota_netto , v_sisa , v_bayar , e_remark
                from dblink('host=192.168.0.93 user=dedy password=g#>m[J2P^^ dbname=bcl port=5432',
                $$
                with cte as (
                     select i_customer , i_nota, i_sj , d_nota, d_jatuh_tempo , 
                     current_date - d_jatuh_tempo as selisih,
                     v_nota_netto , v_sisa , v_nota_netto - v_sisa as v_bayar from tm_nota
                     where f_nota_cancel = false and to_char(d_nota , 'yyyy') = '$periode' and i_customer in ($arrayTxt) /*('02564', '02221', '02579', '02265')*/
                     order by  d_jatuh_tempo asc,v_sisa desc
                )
                select coalesce(a.i_nota,'') || ' / ' || a.i_sj || ' [' || a.i_customer || ']' as i_nota, a.d_nota, a.d_jatuh_tempo , 
                     case 
                          when v_sisa > 0 and selisih between 1 and 7 then '#279b37'
                          when v_sisa > 0 and selisih between 8 and 15 then '#ffdd00'
                          when v_sisa > 0 and selisih > 15 then '#e4002b'
                          else '#000000'
                     end as e_color,a.v_nota_netto , a.v_sisa ,a.v_bayar , coalesce(b.e_remark1, '') || ' - ' || coalesce(b.e_remark2, '') as e_remark from cte a
                left join (
                     select distinct on (a.i_nota) a.i_nota , 
                     coalesce(c.e_pelunasan_remark, '') as e_remark1, coalesce(a.e_remark,'') as e_remark2 from tm_pelunasan_item a
                     inner join tm_pelunasan b on (a.i_pelunasan = b.i_pelunasan and a.i_area = b.i_area)
                     left join tr_pelunasan_remark c on (a.i_pelunasan_remark = c.i_pelunasan_remark)
                     where a.i_nota in (select i_nota from cte) and b.f_pelunasan_cancel = false 
                     order by a.i_nota , b.d_entry desc
                ) as b on a.i_nota = b.i_nota
                $$
                ) AS nilai (
                     i_nota varchar(100), d_nota date, d_jatuh_tempo date, e_color varchar(20), v_nota_netto numeric, v_sisa numeric, v_bayar numeric, e_remark varchar(200)
                )    
                ", FALSE);

            $list = array();
            $key = 0;

            foreach ($item->result() as $riw) {
                $list[$key]['i_nota'] = $riw->i_nota;
                $list[$key]['d_nota'] = $riw->d_nota;
                $list[$key]['d_jatuh_tempo'] = $riw->d_jatuh_tempo;
                $list[$key]['v_nota_netto'] = $riw->v_nota_netto;
                $list[$key]['v_sisa'] = $riw->v_sisa;
                $list[$key]['v_bayar'] = $riw->v_bayar;
                $list[$key]['e_color'] = $riw->e_color;
                $list[$key]['e_remark_nota'] = $riw->e_remark;
                $key++;
            }

            $databanner = $this->db->query("
	            select e_path, e_remark from tbl_banner
				where f_active = true and current_date between d_start and d_end order by d_end asc, createdat asc
	        ", FALSE);

            $listbanner = array();
	        $keybanner = 0;
	        if ($databanner->num_rows() > 0) {
	            foreach ($databanner->result() as $riw) {
	                $listbanner[$keybanner]['e_path'] = $riw->e_path;
	                $listbanner[$keybanner]['e_remark'] = $riw->e_remark;
	                $keybanner++;
	            }
	        } else {
	        	$listbanner[$keybanner]['e_path'] = 'https://apps.dialoguegroup.net/dgapps/assets/images/banner/default.png';
	        	$listbanner[$keybanner]['e_remark'] = ' ';
	        }

            $this->response([
                'status' => true,  
                'v_nota_target' => number_format($total,0),  
                'v_nota_netto' => number_format($data2->v_nota_netto,0),  
                'v_sisa' => number_format($data2->v_sisa,0),  
                'v_spb' => number_format($data2->v_spb,0), 
                'e_name' => $e_name,                
                'data' => $list,  
                'banner' => $listbanner,  
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Belum Ada Toko Terdaftar Untuk Username '.$username,
            ], REST_Controller::HTTP_OK);
        }
    }


    public function version_post() {

        $username = $this->post('username');

        $data = $this->db->query("
             select a.f_active, b.version_code, b.version_name, b.version_update from tbl_user_toko a
             cross join tbl_version_android b
             where username = '$username'
        ", FALSE);

        if ($data->num_rows() > 0) {
            $row = $data->row();
            if ($row->f_active == 't') {
                 $this->response([
                    'status' => true,
                    'version_code' => $row->version_code,
                    'version_name' => $row->version_name,
                    'version_update' => $row->version_update,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Username Sudah Tidak Aktif',
                ], REST_Controller::HTTP_OK);
            }
        } 
    }

    public function bank_post() {
     

        $username = $this->post('username');

        $data = $this->db->query("
            select e_image_path, e_bank_name, i_norek, e_norek_name from tbl_bank 
			where f_active = true and id_company in (
			     select distinct b.id_company from tbl_user_toko a 
			     inner join tbl_user_toko_item b on (a.username = b.username) 
			     where a.username ilike '$username' limit 1
			) order by createdat asc;
        ", FALSE);

      	$list = array();
        $key = 0;
        if ($data->num_rows() > 0) {
    
            foreach ($data->result() as $riw) {
                $list[$key]['e_image_path'] = $riw->e_image_path;
                $list[$key]['e_bank_name'] = $riw->e_bank_name;
                $list[$key]['i_norek'] = $riw->i_norek;
                $list[$key]['e_norek_name'] = $riw->e_norek_name;
                $key++;
            }

            $this->response([
                'status' => true,                  
                'data' => $list,  
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,                  
                'data' => $list,  
            ], REST_Controller::HTTP_OK);
        }
    }

}