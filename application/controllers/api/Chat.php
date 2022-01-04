<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Chat extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $id = $this->post('id');

            if ($id) {
                $data = $this->db
		    ->select('tb_chat.id,tb_chat.id_user,tb_user.name,tb_chat.topic,tb_chat.update_time')
                    ->from('tb_chat')
		    ->join('tb_user', 'tb_user.id_user = tb_chat.id_user')
                    ->get()
                    ->result_array();
                $this->response(
                    [
                        'status' => true,
                        'data' => $data,
                    ],
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else
            $this->response(
                [
                    'status' => false,
                    'message' =>
                        'No data  were found',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND
            );
    }
public function rechat_post()
    {
        $id = $this->post('id');

            if ($id) {
                $data = $this->db
                    ->where(
                        "id_chat" , $id
                    )
                    ->get('tb_reply_chat')
                    ->result_array();

                $this->response(
                    [
                        'status' => true,
                        'data' => $data,
                    ],
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else
            $this->response(
                [
                    'status' => false,
                    'message' =>
                        'No data  were found',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND
            );
    }

    public function send_post()
    {
        $data = [
            'id' => $this->post('id_chat'),
            'from_by' => $this->post('id_user'),
            'message' => $this->post('message'),
        ];
        if ($this->db->insert('tb_reply_chat', $data)) {
            $this->response(
                [
                    'status' => true,
                    'message' => 'Send Reply Success',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_CREATED
            );
        } else {
            //user tidak ada
            $this->response(
                [
                    'status' => false,
                    'message' => 'Send Reply Field',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND
            ); // NOT_FOUND (404) being the HTTP response code
        }
    }
}
