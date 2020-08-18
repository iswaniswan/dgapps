<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->library('custom');
        $this->load->model('M_Dashboard');
    }

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/visualization/echarts/echarts.min.js',
                'global_assets/js/plugins/extensions/jquery_ui/interactions.min.js',
                'global_assets/js/plugins/extensions/jquery_ui/widgets.min.js',
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/dashboard/index.js',
            )
        );
        $this->template->load('template', 'dashboard/index');
    }

    public function data_salesovertime($dfrom = null, $dto = null)
    {
        $username = $this->session->userdata('username');
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $dfrom = $this->input->post('dfrom');
        $dto = $this->input->post('dto');

        if ($dfrom == null && $dto == null) {
            $dfrom = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));
            $dto = date('Y-m-d');
        } else {
            $dfrom = date("Y-m-d", strtotime($this->input->post('dfrom')));
            $dto = date("Y-m-d", strtotime($this->input->post('dto')));
        }

        $query = $this->db->query("select to_char(d_spb,'dd-mm-yyyy') as d_spb, sum(v_spb_netto) as v_spb_netto from tbl_spb
		where f_spb_cancel = 'f' and i_company = '$i_company' and d_spb >= '$dfrom' and d_spb <= '$dto' and i_area in(select i_area from tbl_user_area where username = '$username'
		and i_company = '$i_company')
		group by d_spb order by d_spb asc");

        echo json_encode($query->result_array());
    }

    public function data_customeroverview()
    {
        $username = $this->session->userdata('username');
        // $area_downline = $this->custom->area_downline($username);
        $i_company = $this->session->userdata('i_company');

        $query = $this->db->query("select sum(x.register) as register, sum(x.visited) as visited, sum(x.producttive) as producttive from(
			select count(i_customer) as register, 0 as visited, 0 as producttive  from tbl_customer where i_company = '$i_company' and i_area in(select a.i_area from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.i_area)
			union all
			select 0 as register, count(x.i_customer) as visited, 0 as producttive from(
			select i_customer from tbl_customer_checkin where i_company = '$i_company' and username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
			group by i_customer
			) as x
			union all
			select 0 as register, 0 as visited, count(x.i_customer) as producttive from(
			select i_customer from tbl_spb where i_company = '$i_company' and username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
			 group by i_customer
			) as x
			) as x")->row();

        $data = array(
            'data' => [$query->register, $query->visited, $query->producttive],
        );
        echo json_encode($data);
    }

    public function data_call($dfrom = null, $dto = null, $area = null)
    {
        $username = $this->session->userdata('username');
        // $area_downline = $this->custom->area_downline($username);
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $dfrom = $this->input->post('dfrom');
        $dto = $this->input->post('dto');
        $area = $this->input->post('area');

        if ($dfrom == null && $dto == null && $area == null) {
            $dfrom = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));
            $dto = date('Y-m-d');
            $area = 'na';
        } else {
            $dfrom = date("Y-m-d", strtotime($this->input->post('dfrom')));
            $dto = date("Y-m-d", strtotime($this->input->post('dto')));
        }

        if ($area == 'na') {
            $query = $this->db->query("select z.i_company, 'National' as title, sum(z.call) as call, sum(z.effective) as effective from (
                select x.i_company, x.username, count(x.i_customer) as call, 0 as effective from (
                select i_company, username, i_customer from tbl_customer_checkin
                where i_company||username||i_customer||d_checkin in(
                select i_company||username||i_customer||d_rrkh from tbl_rrkh
                where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                )
                and d_checkin >= '$dfrom'
                and d_checkin <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                group by username, i_customer, i_company
                ) as x
                group by x.i_company, x.username
                union all
                select x.i_company, x.username, 0 as call, count(x.i_customer) as effective from (
                select i_company, username, i_customer from tbl_spb
                where i_company||username||i_customer||d_spb in(
                select i_company||username||i_customer||d_checkin from tbl_customer_checkin
                where i_company||username||i_customer||d_checkin in(
                select i_company||username||i_customer||d_rrkh from tbl_rrkh
                where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                )
                and d_checkin >= '$dfrom'
                and d_checkin <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                group by username, i_customer, i_company, d_checkin
                )
                and d_spb >= '$dfrom'
                and d_spb <= '$dto'
                group by i_company, username, i_customer
                ) as x
                group by x.i_company, x.username
                ) as z
                inner join tbl_user b on(z.username = b.username and z.i_company = b.i_company)
                left join tbl_area c on(b.i_area = c.i_area and b.i_company = c.i_company)
                where z.i_company = '$i_company'
                group by z.i_company");
        } elseif ($area == 'area') {
            $query = $this->db->query("select z.i_company, c.e_area_name as title, sum(z.call) as call, sum(z.effective) as effective from (
                select x.i_company, x.username, count(x.i_customer) as call, 0 as effective from (
                select i_company, username, i_customer from tbl_customer_checkin
                where i_company||username||i_customer||d_checkin in(
                select i_company||username||i_customer||d_rrkh from tbl_rrkh
                where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                )
                and d_checkin >= '$dfrom'
                and d_checkin <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                group by username, i_customer, i_company
                ) as x
                group by x.i_company, x.username
                union all
                select x.i_company, x.username, 0 as call, count(x.i_customer) as effective from (
                select i_company, username, i_customer from tbl_spb
                where i_company||username||i_customer||d_spb in(
                select i_company||username||i_customer||d_checkin from tbl_customer_checkin
                where i_company||username||i_customer||d_checkin in(
                select i_company||username||i_customer||d_rrkh from tbl_rrkh
                where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                )
                and d_checkin >= '$dfrom'
                and d_checkin <= '$dto'
                and i_area in(select i_area from tbl_user_area where username = '$username'
                and i_company = '$i_company')
                group by username, i_customer, i_company, d_checkin
                )
                and d_spb >= '$dfrom'
                and d_spb <= '$dto'
                group by i_company, username, i_customer
                ) as x
                group by x.i_company, x.username
                ) as z
                inner join tbl_user b on(z.username = b.username and z.i_company = b.i_company)
                left join tbl_area c on(b.i_area = c.i_area and b.i_company = c.i_company)
                where z.i_company = '$i_company'
                group by c.e_area_name, z.i_company");
        } elseif ($area == 'staff') {
            $query = $this->db->query("select z.i_company, z.username as title, sum(z.call) as call, sum(z.effective) as effective from (
				select x.i_company, x.username, count(x.i_customer) as call, 0 as effective from (
				select i_company, username, i_customer from tbl_customer_checkin
				where i_company||username||i_customer||d_checkin in(
				select i_company||username||i_customer||d_rrkh from tbl_rrkh
				where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
				and i_area in(select i_area from tbl_user_area where username = '$username'
		and i_company = '$i_company')
				)
				and d_checkin >= '$dfrom'
				and d_checkin <= '$dto'
				and i_area in(select i_area from tbl_user_area where username = '$username'
		and i_company = '$i_company')
				group by username, i_customer, i_company
				) as x
				group by x.i_company, x.username
				union all
				select x.i_company, x.username, 0 as call, count(x.i_customer) as effective from (
				select i_company, username, i_customer from tbl_spb
				where i_company||username||i_customer||d_spb in(
				select i_company||username||i_customer||d_checkin from tbl_customer_checkin
				where i_company||username||i_customer||d_checkin in(
				select i_company||username||i_customer||d_rrkh from tbl_rrkh
				where d_rrkh >= '$dfrom' and d_rrkh <= '$dto'
				and i_area in(select i_area from tbl_user_area where username = '$username'
		and i_company = '$i_company')
				)
				and d_checkin >= '$dfrom'
				and d_checkin <= '$dto'
				and i_area in(select i_area from tbl_user_area where username = '$username'
		and i_company = '$i_company')
				group by username, i_customer, i_company, d_checkin
				)
                and d_spb >= '$dfrom'
                and d_spb <= '$dto'
                group by i_company, username, i_customer
				) as x
				group by x.i_company, x.username
				) as z
				where z.i_company = '$i_company'
				group by z.username, z.i_company");
        }

        echo json_encode($query->result_array());
    }

    public function data_attendance($dfrom = null, $dto = null)
    {
        $username = $this->session->userdata('username');
        // $area_downline = $this->custom->area_downline($username);
        // $username_downline = $this->custom->username_downline($username);
        $i_company = $this->session->userdata('i_company');

        $dfrom = $this->input->post('dfrom');
        $dto = $this->input->post('dto');

        if ($dfrom == null && $dto == null) {
            $dfrom = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));
            $dto = date('Y-m-d');
        } else {
            $dfrom = date("Y-m-d", strtotime($this->input->post('dfrom')));
            $dto = date("Y-m-d", strtotime($this->input->post('dto')));
        }

        $query = $this->db->query("select count(x.username) as hadir, x.d_login, (select count(x.username) semua from(
			select username from tbl_user where
			i_company = '$i_company' and username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
			) as x) - count(x.username) as tidak_hadir from (
			select username, d_login from tbl_user_login where
			i_company = '$i_company' and d_login >= '$dfrom' and d_login <= '$dto' and username in(select a.username from tbl_user_area a, tbl_user b where a.i_area in(
                select a.i_area from tbl_user_area a where a.username = '$username' and a.i_company = '$i_company'
                )
                and a.i_company = '$i_company'
                and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
                group by a.username)
			) as x group by x.d_login
			order by x.d_login asc");

        $d_login = [];
        $hadir = [];
        $tidak_hadir = [];
        foreach ($query->result() as $row) {
            $hadir[] = $row->hadir;
            $d_login[] = date("d-m-Y", strtotime($row->d_login));
            $tidak_hadir[] = $row->tidak_hadir;
        }

        $data = array(
            [
                'd_login' => $d_login,
                'hadir' => $hadir,
                'tidak_hadir' => $tidak_hadir,
            ],

        );
        echo json_encode($data);

    }

    public function activitylist()
    {
        $dfrom = date("Y-m-d", strtotime($this->uri->segment('3')));
        $dto = date("Y-m-d", strtotime($this->uri->segment('4')));

        echo $this->M_Dashboard->activitylist($dfrom, $dto);

    }

    public function switch_language($language = "indonesia")
    {

        $this->session->set_userdata('language', $language);

        redirect(base_url(), 'refresh');

    }

}
