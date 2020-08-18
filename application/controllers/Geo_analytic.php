<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Geo_analytic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('M_customer');
    }

    public $folder = 'geo_analytic';

    public function index()
    {
        add_js(
            array(
                'global_assets/js/main/highmaps.js',
                'global_assets/js/main/data.js',
                'global_assets/js/main/drilldown.js',
                'global_assets/js/main/maps_id.js',
                'assets/js/geo-analytic/index.js',
            )
        );
        $this->Logger->write(null, null, 'Membuka Menu Geo Analytic');
        $this->template->load('template', $this->folder . '/index');
    }

    public function data_nasional()
    {
        $i_company = $this->session->userdata('i_company');
        $data = $this->db->query("select a.id_maps, a.e_area_name, count(b.i_customer) as jumlah from tbl_area a
		left join tbl_customer b on( a.i_area = b.i_area and a.i_company = b.i_company and b.f_active = 't')
		where a.id_maps <> ''
        and a.i_company = '$i_company'
        and a.f_active = 't'
		group by a.id_maps, a.e_area_name");

        $list = array();
        $key = 1;
        $total = 0;
        $list[0]['hc-key'] = 'id-3700';
        $list[0]['name'] = 'Nasional';
        $list[0]['value'] = $total;
        foreach ($data->result() as $riw) {
            $list[$key]['hc-key'] = $riw->id_maps;
            $list[$key]['name'] = $riw->e_area_name;
            $list[$key]['value'] = $riw->jumlah;
            $key++;
            $total = $total + $riw->jumlah;
        }
        $data = array(
            'features' => $list,
        );
        header('Content-type: application/json');

        echo json_encode($data);
    }

    public function data_city()
    {
        $i_company = $this->session->userdata('i_company');
        $id_maps = $this->input->post('id_maps');
        $data = $this->db->query("select a.i_city, a.e_city_name, count(b.i_customer) as jumlah from tbl_city a
		left join tbl_customer b on( a.i_city = b.i_city and a.i_company = b.i_company)
		where a.id_maps <> ''
		and a.i_company = '$i_company'
		and a.id_maps = '$id_maps'
		group by a.i_city, a.e_city_name
		order by a.e_city_name");

        $list = array();
        $key = 0;
        $total = 0;
        // $list[0]['hc-key'] = 1;
        // $list[0]['name'] = 'Nasional';
        // $list[0]['value'] = $total;
        foreach ($data->result() as $riw) {
            $list[$key]['hc-key'] = $riw->i_city;
            $list[$key]['name'] = $riw->e_city_name;
            $list[$key]['value'] = $riw->jumlah;
            $key++;
        }
        $data = array(
            'features' => $list,
        );

        header('Content-type: application/json');

        echo json_encode($data);
    }

    public function view($id)
    {

        $id_maps = $id;
        $i_company = $this->session->userdata('i_company');
        $cek_data = $this->db->query("select * from tbl_area where id_maps = '$id_maps' and i_company = '$i_company'");

        if ($cek_data->num_rows() > 0) {
            add_key(
                array(
                    "var id_maps = '$id_maps';",
                )
            );

            add_js(
                array(
                    'global_assets/js/main/highmaps.js',
                    'global_assets/js/main/data.js',
                    'global_assets/js/main/drilldown.js',
                    'assets/js/geo-analytic/view.js',
                )
            );
            $data = array(
                'data_area' => $cek_data->row(),
            );
            $this->template->load('template', $this->folder . '/view', $data);
        } else {
            redirect('geo-analytic', 'refresh');

        }

    }

    public function maps($id)
    {

        $i_city = $id;
        $i_company = $this->session->userdata('i_company');
        $cek_data = $this->db->query("select * from tbl_city where i_city = '$i_city' and i_company = '$i_company'");

        if ($cek_data->num_rows() > 0) {
            add_key(
                array(
                    "var i_city = '$i_city';",
                )
            );

            add_js(
                array(
                    'assets/js/geo-analytic/maps.js',
                )
            );
            $data = array(
                'data_city' => $cek_data->row(),
            );
            $this->template->load('template', $this->folder . '/maps', $data);
        } else {
            redirect('geo-analytic', 'refresh');

        }

    }

    public function getcity()
    {
        $id = $this->input->post('i_city');
        $i_company = $this->session->userdata('i_company');
        $cek_data = $this->db->query("select latitude, longitude from tbl_city where i_city = '$id' and i_company = '$i_company'")->result_array();
        $data_customer = $this->db->query("select a.i_customer, a.e_customer_name, a.e_customer_address, a.latitude,a.longitude,
        'info-i_maps.png' as type, count(c.username) as visit, case when sum(d.v_spb_netto) > 0 then sum(d.v_spb_netto) else 0 end as total_order from tbl_area b,  tbl_customer a
        left join tbl_customer_checkin c on(a.i_company = c.i_company and a.i_customer = c.i_customer and a.i_area = c.i_area)
        left join tbl_spb d on(a.i_company = d.i_company and a.i_customer = d.i_customer and a.i_area = d.i_area)
        where a.i_city = '$id'
        and a.i_company = '$i_company'
        and a.latitude != ''
        and a.i_area = b.i_area
        and b.f_active = 't'
        group by a.i_customer, a.e_customer_name, a.e_customer_address, a.latitude,a.longitude
        order by a.e_customer_name asc ")->result_array();
        $data = array(
            $cek_data,
            'data' => $data_customer,
        );
        echo json_encode($data);
    }

    public function ambil()
    {
        $data = $this->http_request("https://code.highcharts.com/mapdata/countries/id/id-all.geo.json");

        $jml = count($data['features']);

        for ($i = 1; $i < $jml; $i++) {
            $id_maps = $data['features'][$i]['properties']['hc-key'];
            $link = base_url() . "global_assets/js/plugins/maps/$id_maps.json";
            echo $link . "<br>";
            $data_kota = $this->http_request($link);
            foreach ($data_kota['features'] as $key) {
                $i_city = $key['properties']['id'];
                $e_city_name = $key['properties']['name'];

                $data_insert = array(
                    'i_city' => $i_city,
                    'e_city_name' => $e_city_name,
                    'i_company' => 1,
                    'id_maps' => $id_maps,
                );

                $this->db->insert('tbl_city', $data_insert);
            }

        }
    }

    public function http_request($url)
    {
        // persiapkan curl
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        // set user agent
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // tutup curl
        curl_close($ch);

        // mengembalikan hasil curl
        return json_decode($output, true);
    }

    public function http_maps($url)
    {
        $url = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($url) . "&region=indonesia&key=AIzaSyC5Knm3yStpPRpfNkJmbVKSxvexZ0kVezI";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson);

        return $response;
    }

    public function kota()
    {

        $data = $this->db->query("select a.e_customer_address||' '||b.e_area_name as alamat, a.i_customer, a.i_company, a.i_area from tbl_customer a, tbl_area b
		where a.i_company = b.i_company
		and a.i_area = b.i_area
		and a.latitude = ''");

        foreach ($data->result() as $row) {
            $alamat = $row->alamat;
            $i_customer = $row->i_customer;
            $i_company = $row->i_company;
            $i_area = $row->i_area;

            $datalang = $this->http_maps($alamat);

            $latitude = $datalang->results[0]->geometry->location->lat;
            $longitude = $datalang->results[0]->geometry->location->lng;

            $data_insert = array(
                'latitude' => $latitude,
                'longitude' => $longitude,
            );

            $this->db->where('i_customer', $i_customer);
            $this->db->where('i_company', $i_company);
            $this->db->where('i_area', $i_area);
            $this->db->update('tbl_customer', $data_insert);
        }

        echo "berhasil";

    }

}