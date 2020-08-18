<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    public function index()
    {

        // $data_customer = $this->db->query("select a.i_customer, a.i_company, a.e_customer_address||' '||b.e_area_name as e_customer_address, a.latitude, a.longitude, b.e_area_name
        // from tbl_customer a, tbl_area b
        //                 where a.i_area = b.i_area
        //                 and a.i_company = b.i_company
        //                 /*and a.latitude isnull
        //                 and a.longitude isnull
        //                 and b.f_active = 't'
        //                 and a.i_area <> '00'*/
        //                 and a.i_customer = '31A09'
        //                 limit 1");

        // foreach ($data_customer->result() as $row) {
        //     $i_customer = $row->i_customer;
        //     $i_company = $row->i_company;
        //     $e_customer_address = $row->e_customer_address;

        //     $url =
        //     "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($e_customer_address) . "&region=indonesia&key=AIzaSyC5Knm3yStpPRpfNkJmbVKSxvexZ0kVezI";

        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     $responseJson = curl_exec($ch);
        //     curl_close($ch);

        //     $response = json_decode($responseJson);

        //     if ($response->status == 'OK') {
        //         $latitude = $response->results[0]->geometry->location->lat;
        //         $longitude = $response->results[0]->geometry->location->lng;

        //         $data = array(
        //             'latitude' => $latitude,
        //             'longitude' => $longitude,
        //         );

        //         $this->db->where('i_customer', $i_customer);
        //         $this->db->where('i_company', $i_company);
        //         $this->db->update('tbl_customer', $data);

        //         // echo $response->results[0]->address_components[6]->long_name;
        //     }

        // }
        // echo 'berhasil';

        // $url = base_url() . 'welcome/face/hendra-1.jpg';

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
        // $responseJson = curl_exec($ch);
        // curl_close($ch);
        // echo $responseJson;
    }

    public function face()
    {
        $data = array(
            'foto' => $this->uri->segment('3'),
        );
        $this->load->view("welcome", $data);

    }

}