<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geo_analytic extends CI_Controller {

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
				'global_assets/js/main/maps_id.js',
				'assets/js/geo-analytic/index.js'
			)
		);
		$this->template->load('template',$this->folder.'/index');
	}

	public function data_nasional(){
		$i_company = $this->session->userdata('i_company');
		$data = $this->db->query("select a.id_maps, count(b.i_customer) as jumlah from tbl_area a
		left join tbl_customer b on( a.i_area = b.i_area and a.i_company = b.i_company)
		where a.id_maps <> ''
		and a.i_company = '$i_company'
		group by a.id_maps");

		$list = array();
		$key=1;
		$total = 0;
		foreach ($data->result() as $riw) {
			$list[$key]['id_maps'] = $riw->id_maps; 
			$list[$key]['jumlah'] = $riw->jumlah; 
			$key++;
			$total = $total + $riw->jumlah;
		}
		$list[0]['id_maps'] = 'id-3700'; 
		$list[0]['jumlah'] = $total; 
		header('Content-type: application/json');

		echo json_encode($list);
	}
}
