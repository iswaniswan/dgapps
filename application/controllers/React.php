<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class React extends REST_Controller {

    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        $this->load->library('custom');

    }
    public function index_get()
    {
        echo "hai";
    }

    public function login_post(){
        $this->form_validation->set_rules('i_company', 'Username', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[0]');
        
		if ($this->form_validation->run() == FALSE) {
            $i_company      = $this->post('i_company');
            $username       = $this->post('username');
            $password       = $this->custom->password($this->post('password', TRUE));
            if($this->custom->cek_company($i_company)){

                $where = array (
                    'username'      => $username, 
                    'e_password'    => $password, 
                    'i_company'     => $i_company,
                    'f_active'      => 'true'
                );
                $data_user = $this->db->get_where('tbl_user', $where);

                if($data_user->num_rows() > 0){
                    
                    $data_user = $data_user->row();
                    $user = array(
                        'username'      => $data_user->username, 
                        'e_name'        => $data_user->e_name,
                        'i_area'        => $data_user->i_area,
                        'i_company'     => $data_user->i_company,
                    );

                    $this->response([
                        'status' => True,
                        'data' => $user,
                    ], REST_Controller::HTTP_OK);
                    
                }else{
                    $this->response([
                        'status' => FALSE,
                    ], REST_Controller::HTTP_OK);
                }
            }else{
                $this->response([
                    'status' => FALSE,
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => FALSE,
            ], REST_Controller::HTTP_OK);
        }
    }

}