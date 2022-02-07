<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logger extends CI_Model {

    public function write($i_company = NULL, $username = NULL, $pesan)
    {
		if($i_company == NULL){
			$i_company = $this->session->userdata('i_company');
		}
		if($username == NULL){
			$username = $this->session->userdata('username');
		}
		
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'i_company' => $i_company,
			'username' => $username,
			'ip_address' => $ip_address,
			'waktu' => current_datetime(),
			'activity' => $pesan
		);

		$this->db->insert('tbl_log', $data);
    }

}

/* End of file Logger.php */
