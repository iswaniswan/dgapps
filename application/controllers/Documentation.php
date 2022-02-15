<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documentation extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_documentation');
    }

    public $folder = 'documentation';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/media/fancybox.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'assets/js/documentation/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Documentation');
        $this->template->load('template', $this->folder . '/index');
    }

    public function data_documentation()
    {
        echo $this->M_documentation->data_documentation();
    }

    public function data_checkin()
    {
        echo $this->M_documentation->data_checkin();
    }

    public function serverside()
    {
        echo $this->M_documentation->serverside();
    }

    public function view()
    {
        $i_customer = decrypt_url($this->uri->segment('3'));
        $i_saran_type = decrypt_url($this->uri->segment('4'));
        $d_saran = decrypt_url($this->uri->segment('5'));
        if (!$i_customer || !$i_saran_type || !$d_saran) {
            redirect('documentation', 'refresh');
        }
        $cek_data = $this->M_documentation->cek_data($i_customer, $i_saran_type, $d_saran)->row_array();

        if ($cek_data) {
            $data = array(
                'data_saran' => $this->M_documentation->cek_data($i_customer, $i_saran_type, $d_saran)->row(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Documentation View ' . $i_customer . ' Type Saran ' . $i_saran_type . ' Tanggal Saran ' . $d_saran);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('documentation', 'refresh');
        }

    }

    public function save()
    {
        $this->form_validation->set_rules('response', 'response', 'trim|required|min_length[0]');

        if ($this->form_validation->run() == false) {
            redirect('documentation', 'refresh');
        } else {
            $response = $this->input->post('response', true);
            $i_customer = $this->input->post('i_customer', true);
            $i_saran_type = $this->input->post('i_saran_type', true);
            $d_saran = $this->input->post('d_saran', true);
            $this->M_documentation->update($i_customer, $i_saran_type, $d_saran, $response);
            $this->Logger->write(null, null, 'Edit Documentation ' . $i_customer . ' Type Saran ' . $i_saran_type . ' Tanggal Saran ' . $d_saran);
            redirect('documentation', 'refresh');
        }
    }

}