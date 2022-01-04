<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');
        $user = $this->db->get_where('tb_user', ['email' => $email])->row_array();
        if ($user) {
            //usernya ada

            //cek password
            if ($user['password'] == $password) {
                //password benar
                $this->response([
                    'status' => true,
                    'data' => [
                        'id_user' => $user['id_user'],
                        'email' => $user['email'],
                        'id_role' => $user['id_role'],
			'image' => $user['image'],
                    ],
                    'message' => 'Login Success'
                ], \Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                // password salah
                $this->response([
                    'status' => FALSE,
                    'message' => 'Password not same'
                ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

            }
        } else {
            //user tidak ada
            $this->response([
                'status' => FALSE,
                'message' => 'No users were found'
            ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
    }
}
