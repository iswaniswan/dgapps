<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_management extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_user_management');
    }

    public $folder = 'user_management';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/user_management/index.js?v=1',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu User Management');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_user_management->serverside();
    }

    public function change_password()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $this->Logger->write(null, null, 'Ganti Password User ' . $username);
        $this->M_user_management->change_password($username, $password);
    }

    public function view()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('user-management', 'refresh');
        }
        $cek_data = $this->M_user_management->cek_data($id);

        if ($cek_data) {
            add_js(
                array(
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/forms/validation/validate.min.js',
                    'global_assets/js/plugins/forms/styling/uniform.min.js',
                    'assets/js/user_management/view.js',
                )
            );
            $data = array(
                'data_user' => $this->M_user_management->data_user($id)->row(),
                'data_area' => $this->M_user_management->data_area(),
                'data_role' => $this->M_user_management->data_role(),
                'data_upline' => $this->M_user_management->data_upline(),
                'data_user_area' => $this->M_user_management->get_array_user_area($id)
            );
            $this->Logger->write(null, null, 'Membuka Menu User Management View ' . $id);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('user-management', 'refresh');
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
        $username_upline = $this->input->post('username_upline', true);
        $coverage_area = $this->input->post('coverage_area', true);

        $this->Logger->write(null, null, 'Update User Management ' . $username);

        $this->M_user_management->update($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email, $username_upline);

        /* update ke tbl_user_area */
        if (@$coverage_area) {
            $this->M_user_management->update_user_area($username, $coverage_area);
        }

        $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		<span class="font-weight-semibold">Success.</span>
	</div>');
        redirect('user-management', 'refresh');

    }

    public function add()
    {
        add_js(
            array(
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/forms/validation/validate.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'assets/js/user_management/add.js',
            )
        );
        $data = array(
            'data_area' => $this->M_user_management->data_area(),
            'data_role' => $this->M_user_management->data_role(),
            'data_upline' => $this->M_user_management->data_upline(),
        );
        $this->Logger->write(null, null, 'Membuka Menu Tambah User Management');
        $this->template->load('template', $this->folder . '/add', $data);
    }

    public function simpan()
    {
        $i_role = $this->input->post('i_role', true);
        $i_area = $this->input->post('i_area', true);
        $f_active = $this->input->post('f_active', true);
        $address = $this->input->post('address', true);
        $i_staff = $this->input->post('i_staff', true);
        $e_name = $this->input->post('e_name', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);
        $e_password = $this->input->post('e_password', true);
        $username_upline = $this->input->post('username_upline', true);
        $coverage_area = $this->input->post('coverage_area', true);

        $username_parts = array_filter(explode(" ", strtolower($e_name))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = replace((!empty($username_parts[0])) ? substr($username_parts[0], 0, 8) : ""); //cut first name to 8 letters
        $part2 = replace((!empty($username_parts[1])) ? substr($username_parts[1], 0, 5) : ""); //cut second name to 5

        $part2 = preg_replace('~[aiueo]~', '', $part2);

        if ($part2) {
            $username = $part1 . '.' . $part2;
        } else {
            $username = $part1;
        }
        $i_company = $this->session->userdata('i_company');
        $cek_user = $this->db->get_where('tbl_user', ['username' => $username, 'i_company' => $i_company]);

        if ($cek_user->num_rows() > 0) {
            $username = $username . $cek_user->num_rows();
        }

        $this->M_user_management->simpan($i_role, $i_area, $f_active, $address, $username, $i_staff, $e_name, $phone, $email, $e_password, $username_upline);

        /* insert ke tbl_user_area */
        if ($f_active == 't') {
            foreach ($coverage_area as $area) {
                $_area = [
                    'username' => $username,
                    'i_area' => $area,
                    'i_company' => $i_company
                ];
                $this->M_user_management->insert_user_area($_area);
            }
        }

        $this->Logger->write(null, null, 'Tambah User ' . $username);

        $this->session->Set_flashdata('message', '<div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
		<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
		<span class="font-weight-semibold">Success.</span> Username : <span class="font-weight-semibold">' . $username . '</span> Password : <span class="font-weight-semibold">' . $e_password . '</span>
	</div>');
        redirect('user-management', 'refresh');

    }
}
