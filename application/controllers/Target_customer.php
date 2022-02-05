<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Target_customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_target_customer');
        $this->i_company = $this->session->userdata('i_company');
    }

    public $folder = 'target_customer';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/target_customer/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu User Management');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_target_customer->serverside();
    }
    
    public function add()
    {
        add_js(
            array(
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/forms/validation/validate.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'assets/js/target_customer/add.js',
            )
        );
        $data = array(
            'data_area' => $this->M_target_customer->data_area(),
            'data_role' => $this->M_target_customer->data_role(),

        );
        $this->Logger->write(null, null, 'Membuka Menu Tambah User Management');
        $this->template->load('template', $this->folder . '/add', $data);
    }

    /** Get Data Customer */
	public function get_customer()
	{
		$filter = [];
		$data = $this->M_target_customer->get_customer(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->i_customer,
				'text' => $row->i_customer. ' - ' . $row->e_customer_name,
			);
		}
		echo json_encode($filter);
	}

    public function simpan()
    {
        $v_spb_target = $this->input->post('v_spb_target', true);
        $v_nota_target = $this->input->post('v_nota_target', true);
        $i_customer = $this->input->post('i_customer', true);
        $i_periode = $this->input->post('i_periode', true);

        if($i_customer !='' && $i_periode!=''){

            $this->M_target_customer->simpan($i_customer, $i_periode, $v_spb_target, $v_nota_target);
            $this->Logger->write(null, null, 'Tambah Target ' . $i_customer.' Periode : '.$i_periode);
            
            $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">Success.</span> Kode Pelanggan : <span class="font-weight-semibold">' . $i_customer . '</span> Periode : <span class="font-weight-semibold">' . $i_periode . '</span>
            </div>');
            redirect('target_customer', 'refresh');
        }else{

            $this->session->Set_flashdata('message', '<div class="alert alert-danger alert-styled-left alert-arrow-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">Gagal Disimpan.</span>
            </div>');
            redirect('target_customer', 'refresh');
        }

    }

    public function edit()
    {
        add_js(
            array(
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/forms/validation/validate.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'assets/js/target_customer/add.js',
            )
        );

        $i_customer = decrypt_url($this->uri->segment(3));
        $i_periode = decrypt_url($this->uri->segment(4));
        $data = array(
            'data' => $this->M_target_customer->get_data($i_customer, $i_periode)->row(),
        );
        $this->Logger->write(null, null, 'Membuka Menu Ubah Target');
        $this->template->load('template', $this->folder . '/update', $data);
    }

    public function change_password()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $this->Logger->write(null, null, 'Ganti Password User ' . $username);
        $this->M_target_customer->change_password($username, $password);
    }

    public function view()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('target_customer', 'refresh');
        }
        $cek_data = $this->M_target_customer->cek_data($id);

        if ($cek_data) {
            add_js(
                array(
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/forms/validation/validate.min.js',
                    'global_assets/js/plugins/forms/styling/uniform.min.js',
                    'assets/js/target_customer/view.js',
                )
            );
            $data = array(
                'data_user' => $this->M_target_customer->data_user($id)->row(),
                'data_area' => $this->M_target_customer->data_area(),
                'data_role' => $this->M_target_customer->data_role(),

            );
            $this->Logger->write(null, null, 'Membuka Menu User Management View ' . $id);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('target_customer', 'refresh');
        }

    }

    public function update()
    {
        $i_role = $this->input->post('i_role', true);
        $i_area = $this->input->post('i_area', true);
        $f_active = $this->input->post('f_active', true);
        $address = $this->input->post('address', true);
        $username = $this->input->post('username', true);
        $i_staff = $this->input->post('i_staff', true);
        $e_name = $this->input->post('e_name', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);

        $this->Logger->write(null, null, 'Update User Management ' . $username);

        $this->M_target_customer->update($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email);

        $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		<span class="font-weight-semibold">Success.</span>
	</div>');
        redirect('target_customer', 'refresh');

    }
}
