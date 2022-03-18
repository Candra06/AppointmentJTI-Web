<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Jadwal extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_User');
        $this->load->model('FullCalendar_Model');
    }

    public function index_post()
    {
        $id = $this->post('id');

        $data['mRequest'] = $this->db
            ->get_where('events', ['id_user' => $id])
            ->result_array();
        // var_dump($data); die;
        if ($data['mRequest']) {
            $this->response(
                [
                    'status' => true,
                    'data' => $data['mRequest'],
                ],
                \Restserver\Libraries\REST_Controller::HTTP_CREATED
            );
        } else {
            $this->response(
                [
                    'status' => false,
                    'message' => 'field add data',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST
            );
        }
    }
    public function make_post()
    {
        $data = [
            'title' => $this->post('title'),
            'start_event' => $this->post('start'),
            'end_event' => $this->post('end'),
            'id_user' => $this->post('user'),
        ];

        // var_dump($data); die;
        if ($data) {
            $this->FullCalendar_Model->insert_event($data);
            $this->response(
                [
                    'status' => true,
                    'message' => 'success add data',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_CREATED
            );
        } else {
            $this->response(
                [
                    'status' => false,
                    'message' => 'field add data',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST
            );
        }
    }
    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response(
                [
                    'status' => false,
                    'message' => 'provide id',
                ],
                \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST
            );
        } else {
            $this->Model_FullCalendar->delete_event($id);
            $this->response(
                [
                    'status' => true,
                    'message' => 'success delete data' . $id,
                ],
                REST_Controller::HTTP_NO_CONTENT
            );
        }
    }

    public function index_put()
    {
        $status = $this->put('status');
        $massage = $this->put('message');
        $id = $this->put('id');

        if (!$status || $massage) {
            $data = [
                'status' => $status,
                'message' => $massage,
            ];
            $this->db->where('id', $id);
            $this->db->update('tb_event', $data);
            $this->response(
                [
                    'status' => true,
                    'message' => 'success update data' . $data,
                ],
                \Restserver\Libraries\REST_Controller::HTTP_CREATED
            );
        } else {
            $data = [
                'status' => $status,
            ];
            $this->db->where('id', $id);
            $this->db->update('tb_event', $data);
            $this->response(
                [
                    'status' => false,
                    'message' => 'field add data' . $data,
                ],
                \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST
            );
        }
    }
}
