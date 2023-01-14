<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_customer');
    }

    public $folder = 'customer';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/customer/index.js?v=1',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Customer');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_customer->serverside();
    }

    public function view()
    {
        $id = $this->uri->segment('3');
        $id = decrypt_url($id);
        if (!$id) {
            redirect('customer', 'refresh');
        }
        $cek_data = $this->M_customer->cek_data($id);

        if ($cek_data) {
            add_key(
                array(
                    "var i_customer = '$id';",
                )
            );
            add_js(
                array(
                    'global_assets/js/plugins/tables/datatables/datatables.min.js',
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/notifications/sweet_alert.min.js',
                    'assets/js/customer/view.js?v=2',
                )
            );
            $data = array(
                'data_customer' => $this->M_customer->data_customer($id)->row(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Customer View ' . $id);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('customer', 'refresh');
        }

    }

    public function view_serverside()
    {
        $id = $this->uri->segment('3');
        echo $this->M_customer->view_serverside($id);
    }

    public function getlocation()
    {
        $id = $this->input->post('i_customer');
        $data = $this->M_customer->data_customer($id)->result_array();

        echo json_encode($data);
    }

    public function change_address()
    {
        $i_customer = $this->input->post('i_customer');
        $data = $this->input->post('data');
        $address = $data[0];
        $latitude = $data[1];
        $longitude = $data[2];

        $pos1 = strpos($latitude, ",");
        $pos2 = strpos($longitude, ",");

        if ($latitude == '' || $longitude == '') {
            $status = 'failed';
        } else if ($pos1 !== false || $pos2 !== false) {
            $status = 'failed';
        } else {
            $this->M_customer->change_address($i_customer, $address, $latitude, $longitude);
            $status = 'success';
        }

        $data = array(
            'status' => $status,
            'url' => base_url('customer'),
        );
        $this->Logger->write(null, null, 'Mengganti Alamat Customer ' . $i_customer);
        echo json_encode($data);

    }

    public function change_status()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->input->post('i_company', true);
        $f_status = $this->input->post('val', true);

        $data = array(
            'f_active' => $f_status,
            'modifiedat' => current_datetime(),
        );

        $this->db->where('i_customer', $i_customer);
        $this->db->where('i_company', $i_company);
        $this->db->update("tbl_customer", $data);

        $this->Logger->write(null, null, 'Update Status Pelanggan : ' . $i_customer . ' Menjadi : ' . $f_status);

        $data = array(
            'status' => true,
        );
        echo json_encode($data);
    }

    public function create_new_location()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->session->userdata('i_company');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $keterangan = $this->input->post('keterangan');
        $username = $this->session->userdata('username');

        $data = [
            'i_customer' => $i_customer,
            'i_company' => $i_company,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'keterangan' => $keterangan,
            'username' => $username
        ];
        $this->M_customer->create_new_location($data);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function edit_location()
    {
        $i_customer = $this->input->post('i_customer');
        $i_company = $this->session->userdata('i_company');
        $id = $this->input->post('id');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $keterangan = $this->input->post('keterangan');
        $username = $this->session->userdata('username');

        $data = [
            'i_customer' => $i_customer,
            'i_company' => $i_company,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'keterangan' => $keterangan,
            'username' => $username,
            'd_update' => current_datetime()
        ];
        $this->M_customer->update_location($id, $data);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function view_all_location()
    {
        $id = $this->uri->segment('3');
        echo $this->M_customer->view_all_location($id);
    }

    public function delete_location()
    {
        $id = $this->input->post('id');
        $this->M_customer->delete_location($id);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

}
