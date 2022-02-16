<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Koordinat extends CI_Controller
{

    public function index()
    {
       //echo "string kesini";
        $data = $this->db->query("
            select i_customer, i_company,  e_customer_name, 
            replace(replace(e_customer_address, '(KOTA)', ''), '(KAB)', '') as e_customer_address
            , replace(e_area_name, 'Baby Joy', '') as e_area_name
            from tbl_customer_tmp 
            where (latitude = '0' or latitude = '') order by e_customer_name asc
        ")->result();

        foreach ($data as $row) {
            // echo $row->i_customer. ' | '. $row->e_customer_name.' | '. $row->e_customer_address. ' | '. $row->e_area_name. ' | '. $row->i_company. ' <br> ';

            $queryString = http_build_query([
              'access_key' => 'a61b5d72cf937ff5c77777c580f9007f',
              'query' => $row->e_customer_address." ".$row->e_area_name,
              'region' =>  $row->e_area_name,
              'output' => 'json',
              'limit' => 1,
            ]);

            $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $json = curl_exec($ch);
            curl_close($ch);
            $apiResult = json_decode($json, true);
            if (empty($apiResult) || !isset($apiResult['data'][0])) {
                //echo 'Kosong';
            } else {
                $latitude = $apiResult['data'][0]['latitude'];
                $longitude = $apiResult['data'][0]['longitude'];
                $this->db->query("
                    update tbl_customer_tmp set latitude='$latitude', longitude = '$longitude' where i_customer = '$row->i_customer' and i_company = '$row->i_company'
                ");
            }
            // var_dump($apiResult);
            // if ($apiResult) {
            //      $latitude = $apiResult['data'][0]['latitude'];
            //      $longitude = $apiResult['data'][0]['longitude'];
            //     $this->db->query("
            //         update tbl_customer_tmp set latitude='$latitude', longitude = '$longitude' where i_customer = '$row->i_customer' and i_company = '$row->i_company'
            //     ");
            // }

        }
 
    }

}
