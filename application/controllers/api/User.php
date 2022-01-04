<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_User');
    }

    public function index_post()
    {
        $email = $this->post('email');
        $user = $this->db->get_where('tb_user', ['email' => $email])->row_array();
        if ($user['id_role'] ===  '2') {
            $data = $this->Model_User->getAllMahasiswaAll();
        } elseif ($user['id_role'] ===  '3') {
            $data = $this->Model_User->getAllDosen();
        } elseif ($user['id_role'] ===  '1') {
            $data = $this->db->get('tb_user')->result_array();
        } 
	if ($data) {
                $this->response([
                    'status' => true,
                    'data' => $data
                ], \Restserver\Libraries\REST_Controller::HTTP_CREATED);
            }
	else {
            //user tidak ada
            $this->response([
                'status' => FALSE,
                'message' => 'No users were found'
            ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
    }
}
