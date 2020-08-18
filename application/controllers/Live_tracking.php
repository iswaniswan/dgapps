<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Live_tracking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_session();
    }

    public $folder = 'live_tracking';

    public function index()
    {
        add_js(
            array(
                'assets/js/live_tracking/index.js',
            )
        );
        $this->template->load('template', $this->folder . '/index');
    }

    public function data_sales()
    {
        $username_sekarang = $this->session->userdata('username');

        $i_company = $this->session->userdata('i_company');
        $data_user = $this->db->query("select a.username from tbl_user_area a, tbl_user b where a.i_area in(
            select a.i_area from tbl_user_area a where a.username = '$username_sekarang' and a.i_company = '$i_company'
            )
            and a.i_company = '$i_company'
            and a.username = b.username and a.i_company = b.i_company and b.i_role >= '3'
            group by a.username");

        $data = array();

        foreach ($data_user->result() as $row) {

            $username = $row->username;
            $cek = $this->db->query("select latitude, longitude, createdat from tbl_user_location where i_company = '$i_company'
            and username = '$username'
            order by createdat desc
            limit 1");

            if ($cek->num_rows() > 0) {
                $data[] = array(
                    'username' => $username,
                    'latitude' => $cek->row()->latitude,
                    'longitude' => $cek->row()->longitude,
                    'createdat' => date("d F Y H:i:s", strtotime($cek->row()->createdat)),
                );
            }

        }

        $data = array(
            'data' => $data,
        );
        echo json_encode($data);
    }

}

/* End of file Live_trackinig.php */