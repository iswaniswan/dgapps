<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        cek_login();
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[0]');

        if ($this->form_validation->run() == false) {
            $this->load->view('login');
        } else {
            $this->load->library('custom');

            $company = $this->input->post('company', true);
            $username = $this->input->post('username', true);
            $password = $this->custom->password($this->input->post('password', true));

            $user = $this->db->get_where('tbl_user', ['username' => $username, 'e_password' => $password, 'i_company' => $company, 'f_active' => 't', 'i_role <> ' => '5'])->row_array();

            if ($user) {

                $data = array(
                    'i_company' => $user['i_company'],
                    'username' => $user['username'],
                    'i_role' => $user['i_role']
                );

                $this->session->set_userdata($data);
                $i_company = $this->session->userdata('i_company');
                $username = $this->session->userdata('username');
                $this->Logger->write($i_company, $username, 'Login');
                redirect('dashboard', 'refresh');

            } else {
                redirect('auth', 'refresh');

            }
        }
    }

    public function logout()
    {

        $i_company = $this->session->userdata('i_company');
        $username = $this->session->userdata('username');
        $this->Logger->write($i_company, $username, 'Logout');

        $this->session->sess_destroy();

        redirect('auth', 'refresh');
    }
}
