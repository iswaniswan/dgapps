<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_product');
    }

    public $folder = 'product';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'global_assets/js/plugins/loaders/blockui.min.js',
                'assets/js/product/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Product');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_product->serverside();
    }
    public function change_status()
    {
        $i_product = $this->input->post('i_product', true);
        $i_company = $this->input->post('i_company', true);
        $f_status = $this->input->post('val', true);

        $data = array(
            'f_active' => $f_status,
            'modifiedat' => current_datetime(),
        );

        $this->db->where('i_product', $i_product);
        $this->db->where('i_company', $i_company);
        $this->db->update("tbl_product", $data);

        $this->Logger->write(null, null, 'Update Status Product : ' . $i_product . ' Menjadi : ' . $f_status);

        $data = array(
            'status' => true,
        );
        echo json_encode($data);
    }

}