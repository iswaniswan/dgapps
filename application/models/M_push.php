<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class M_push extends CI_Model {

    function serverside(){
        $i_company = $this->session->userdata('i_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select b.e_name, a.title, a.message, a.url, a.recipients, a.createdat from tbl_push a, tbl_user b where a.i_company = b.i_company
        and a.username = b.username order by a.createdat desc");
        $datatables->edit('createdat', function ($data) {
            $createdat = $data['createdat'];
            if($createdat == ''){
                return '';
            }else{
                return date("d F Y H:i:s", strtotime($createdat) );
            }
        });
        
        return $datatables->generate();
    }

    function simpan($title, $message, $url, $recipients){
        $i_company = $this->session->userdata('i_company');
		$username = $this->session->userdata('username');
		
		$data = array(
			'i_company' => $i_company,
			'username' => $username,
			'title' => $title,
			'message' => $message,
			'url' => $url,
			'recipients' => $recipients,
			'createdat' => current_datetime()
		);

		$this->db->insert('tbl_push', $data);
    }


}

/* End of file M_user_management.php */
