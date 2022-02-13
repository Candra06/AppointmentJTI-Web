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

	public function index_get($id)
	{
		// $id = $this->post('id');

		if ($id) {
			$data = $this->db
				->select('tb_chat.id,tb_chat.id_user,tb_user.name,tb_chat.topic,tb_chat.update_time')
				->from('tb_chat')
				->join('tb_user', 'tb_user.id_user = tb_chat.id_user')
				->where('tb_chat.id_user',$id)
				->or_where('tb_chat.id_dosen',$id)
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
	public function save_post()
	{
        $d = $_POST;
        $data = [
            'id_user' => $d['id_user'],
			'id_dosen' => $d['id_dosen'],
			'topic' => $d['topic'],
        ];
        $q = $this->db->insert('tb_chat', $data,);
        $response = [];
        if($q){
            $response = [
                'status' => true,
                'message' => "Data Berhasil disimpan"
            ];
        }else{
            $response = [
                'status' => false,
                'message' => "Data Gagal disimpan"
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
        
	}
	public function detail_chat_post()
	{
		$id = $this->post('id');
		// $id_user = $this->post('id_user');

		if ($id) {
			$data = $this->db
				->where([
					"id_chat" => $id,
				]
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
			'id_chat' => $this->post('id_chat'),
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
