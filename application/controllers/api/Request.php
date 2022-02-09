<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Request extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_User');
        $this->load->model('Model_FullCalendar');
    }

    public function index_post()
    {
        $id = $this->post('id');
        $roleId = $this->post('roleId');
        if ($roleId == "2") {
            $data['mRequest'] = $this->db->get_where('tb_event', ['id_dosen' => $id])->result_array();
            // var_dump($data); die;
            if ($data['mRequest']) {
                $this->response([
                    'status' => true,
                    'data' => $data['mRequest']
                ], \Restserver\Libraries\REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'field add data event'
                ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
            }
        } elseif ($roleId == "3") {
            $data['mRequest'] = $this->db->get_where('tb_event', ['id_user' => $id])->result_array();
            // var_dump($data); die;
            if ($data['mRequest']) {
                $this->response([
                    'status' => true,
                    'data' => $data['mRequest']
                ], \Restserver\Libraries\REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'field add data event'
                ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $data['mRequest'] = $this->db->get_where('tb_event', ['id_user' => $id])->result_array();
            $this->response([
                'status' => false,
                'message' => 'field add data'
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function make_post()
    {
        $data = array(
            'title'  => $this->post('title'),
            'start_event' => $this->post('start'),
            'end_event' => $this->post('end'),
            'id_user' => $this->post('mahasiswa'),
            'id_dosen' => $this->post('dosen'),
            'status' => "waiting",
        );

        // var_dump($data); die;
        if ($this->db->insert('tb_event', $data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'success add data'
            ], \Restserver\Libraries\REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'field add data'
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function index_put()
    {
        $id = $this->put('id');
        $data = $this->put('status');

        if ($id && $data === "accept") {
            $id = $this->uri->segment(4);
            $data = [
                'message' => 'Request kamu diterima',
                'status' => 'accept'
            ];
            $this->db->where('id', $id);
            $this->db->update('tb_event', $data);
            $this->response([
                'status' => true,
                'message' => 'success update data' . $data
            ], \Restserver\Libraries\REST_Controller::HTTP_CREATED);
        } else {
            $data = [
                'message' => 'Request kamu ditolak',
                'status' => 'reject'
            ];
            $this->db->where('id', $id);
            $this->db->update('tb_event', $data);
            $this->response([
                'status' => false,
                'message' => 'field add data' . $data
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }

    }
}
