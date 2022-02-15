<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_sales_order');
    }

    public $folder = 'sales_order';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/sales_order/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Sales Order');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_sales_order->serverside();
    }

    public function view()
    {
        $i_spb = $this->uri->segment('3');
        $i_spb = decrypt_url($i_spb);
        $i_area = $this->uri->segment('4');
        $i_area = decrypt_url($i_area);
        if (!$i_spb || !$i_area) {
            redirect('sales-order', 'refresh');
        }
        $cek_data = $this->M_sales_order->cek_data($i_spb, $i_area)->row_array();

        if ($cek_data) {
            add_js(
                array(
                    'global_assets/js/plugins/notifications/sweet_alert.min.js',
                    'global_assets/js/plugins/forms/selects/select2.min.js',
                    'global_assets/js/plugins/forms/validation/validate.min.js',
                    'global_assets/js/plugins/forms/styling/uniform.min.js',
                    'assets/js/sales_order/view.js',
                )
            );

            $data = array(
                'header' => $this->M_sales_order->cek_data($i_spb, $i_area)->row(),
                'detail' => $this->M_sales_order->detail_spb($i_spb, $i_area)->result(),
            );
            $this->Logger->write(null, null, 'Membuka Menu Sales Order View ' . $i_spb . ' Area ' . $i_area);
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('sales-order', 'refresh');
        }

    }

    public function cancel_so()
    {
        $i_spb = $this->input->post('i_spb');
        $i_area = $this->input->post('i_area');

        $cek_data = $this->db->query("select i_spb from tbl_spb where i_spb = '$i_spb' and i_area = '$i_area' and f_status_transfer = 'f' and f_spb_cancel = 'f' ")->row_array();

        if ($cek_data) {
            $this->M_sales_order->cancel_so($i_spb, $i_area);
            $this->Logger->write(null, null, 'Batal Sales Order ' . $i_spb . ' Area ' . $i_area);
        }

    }
}
