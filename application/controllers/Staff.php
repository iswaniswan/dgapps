<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_staff');
    }

    public $folder = 'staff';

    public function index()
    {

        $cari=$this->input->post("cari");
        // $i_company = $this->session->userdata('i_company');
        // if ($i_company == '6') {
        //     var_dump($this->M_staff->list_user($cari));
        //     die();
        // }
        $data = array(
            'list_user' => $this->M_staff->list_user($cari),
        );
        $this->Logger->write(null, null, 'Membuka Menu Staff');
        $this->template->load('template', $this->folder . '/index', $data);
    }

    public function view()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('staff', 'refresh');
        }

        $data_staff = $this->M_staff->data_staff($id);

        if ($data_staff) {
            add_key(
                array(
                    "var username = '$id';",
                )
            );
            add_js(
                array(
                    'global_assets/js/plugins/tables/datatables/datatables.min.js',
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/extensions/jquery_ui/interactions.min.js',
                    'global_assets/js/plugins/extensions/jquery_ui/widgets.min.js',
                    'global_assets/js/plugins/ui/fullcalendar/core/main.min.js',
                    'global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js',
                    'assets/js/staff/view.js?=v1',
                )
            );
            $cari=$this->input->post("cari");
            $data = array(
                'list_user' => $this->M_staff->list_user($cari),
                'data_staff' => $data_staff->row(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Staff View ' . $id);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('staff', 'refresh');
        }

    }

    public function view_serverside()
    {
        $id = $this->uri->segment('3');
        echo $this->M_staff->view_serverside($id);
    }

    public function maps($date = null)
    {
        $id = $this->input->post('username');
        $i_company = $this->session->userdata('i_company');
        $date = $this->input->post('date');
        if ($date == null) {
            $date = date('Y-m-d');
        } else {
            $date = date("Y-m-d", strtotime($this->input->post('date')));
        }

        $data_customer = $this->db->query("
		select a.i_company, b.e_customer_name, a.latitude_checkin, a.longitude_checkin, a.d_checkin, a.createdat_checkin, a.createdat_checkout
		from tbl_customer_checkin a, tbl_customer b
		where
		a.i_company = b.i_company
		and a.i_customer = b.i_customer
		and a.i_company = '$i_company'
		and a.username = '$id'
		and a.d_checkin = '$date'
		order by createdat_checkin asc")->result_array();

        $data = array(
            'data' => $data_customer,
        );
        echo json_encode($data);
    }

    public function tracking($date = null)
    {
        $id = $this->input->post('username');
        $i_company = $this->session->userdata('i_company');
        $date = $this->input->post('date');
        if ($date == null) {
            $date = date('Y-m-d');
        } else {
            $date = date("Y-m-d", strtotime($this->input->post('date')));
        }

        $data_tracking = $this->db->query("
		select  a.latitude, a.longitude , b.e_name, to_char(a.createdat,'dd-mm-yyyy HH24:MI:SS') as createdat from tbl_user_location a,
tbl_user b
where
a.username = b.username
and a.i_company = b.i_company
and a.i_company = '$i_company'
and a.username = '$id'
and to_char(a.createdat,'yyyy-mm-dd')='$date'
order by a.createdat asc");

        $data = array(
            'data' => $data_tracking->result_array(),
        );
        echo json_encode($data);
    }

    public function journey_plan()
    {
        $id = $this->input->post('username');
        $i_company = $this->session->userdata('i_company');

        $dfrom = date("Y-m-d", strtotime($this->input->post('start')));
        $dto = date("Y-m-d", strtotime($this->input->post('end')));

  //       $data_rrkh = $this->db->query("
		// select b.e_customer_name as title, a.d_rrkh as start, a.d_rrkh as end, '#00BCD4' as color  from tbl_rrkh a, tbl_customer b
		// where a.i_company = b.i_company
		// and a.i_customer = b.i_customer
		// and a.i_area = b.i_area
		// and a.d_rrkh >= '$dfrom'
		// and a.d_rrkh <= '$dto'
		// and a.username = '$id'
		// and a.i_company = '$i_company'")->result_array();


        $data_rrkh = $this->db->query("
            select b.e_customer_name as title, a.d_rrkh as start, a.d_rrkh as end, 
            case 
                when d.d_spb is not null then '#4CAF50'
                when c.d_checkin is not null then '#2196F3'
                else '#00BCD4' 
            end as color  
            from tbl_rrkh a
            inner join tbl_customer b on (a.i_company = b.i_company and a.i_customer = b.i_customer and a.i_area = b.i_area)
            left join ( 
                select i_company, i_customer, i_area , d_checkin from tbl_customer_checkin where d_checkin between '$dfrom' and '$dto'  and i_company = '$i_company' and username = '$id'
                group by i_company, i_customer, i_area , d_checkin
            ) as c on (a.i_company = c.i_company and a.i_customer = c.i_customer and a.d_rrkh = c.d_checkin)
            left join (
                select i_company, i_customer, i_area , d_spb from tbl_spb where d_spb between '$dfrom' and '$dto'  and i_company = '$i_company' and username = '$id'
                group by i_company, i_customer, i_area , d_spb
            ) as  d on (a.i_company = d.i_company and a.i_customer = d.i_customer and a.d_rrkh = d.d_spb)
            where 
            a.d_rrkh >= '$dfrom'
            and a.d_rrkh <= '$dto'
            and a.username = '$id'
            and a.i_company = '$i_company'
        ")->result_array(); 
        echo json_encode($data_rrkh);
    }

}
