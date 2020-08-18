<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Push extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_push');
    }

    public $folder = 'push';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/plugins/tables/datatables/datatables.min.js',
                'global_assets/js/plugins/notifications/sweet_alert.min.js',
                'global_assets/js/plugins/forms/styling/uniform.min.js',
                'global_assets/js/plugins/forms/selects/select2.min.js',
                'assets/js/push/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Push');
        $this->template->load('template', $this->folder . '/index');
    }

    public function serverside()
    {
        echo $this->M_push->serverside();
    }

    public function add()
    {
        $this->Logger->write(null, null, 'Membuka Menu Tambah Push');
        $this->template->load('template', $this->folder . '/add');
    }

    public function simpan()
    {
        $title = $this->input->post('title');
        $message = $this->input->post('message');
        $url = $this->input->post('url');

        $headings = array(
            "en" => "$title",
        );

        $content = array(
            "en" => "$message",
        );

        $fields = array(
            'app_id' => "bf781614-4802-46aa-962a-681061a247e4",
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'headings' => $headings,
            'url' => $url,
            // 'large_icon' => 'http://202.150.150.58/dgapps/assets/images/dokumentasi/1/admin-11578018117.jpg'
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic YjFmMzA2NWMtY2UwYy00YWZhLWIyZjMtY2Y1NTgxMGYxMTc1'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);

        $recipients = $response->recipients;

        $this->M_push->simpan($title, $message, $url, $recipients);

        $this->Logger->write(null, null, 'Tambah Push ' . $title);

        redirect('push', 'refresh');

    }

}
