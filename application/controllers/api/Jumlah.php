<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Jumlah extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $this->response(
            [
                'status' => true,
                'data' => [
                    'mahasiswa' => $this->db
                        ->like('id_role', '2')
                        ->get('tb_user')->num_rows(),
                    'dosen' => $this->db
                        ->like('id_role', '3')
                        ->get('tb_user')->num_rows(),
                    // 'Request' => $this->db->count_all('tb_user')->row_array(),
                    'jadwal' => $this->db->count_all('tb_event'),
                ],
                'message' => 'Success get data',
            ],
            \Restserver\Libraries\REST_Controller::HTTP_OK
        );
    }
}
