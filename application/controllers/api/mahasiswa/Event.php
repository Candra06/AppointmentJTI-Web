<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Event extends REST_Controller
{
    
	public function __construct()
	{
		parent::__construct();
        $this->table = 'events';
	}
    public function index_get()
	{
		$data = $this->db->query("SELECT * FROM $this->table e JOIN tb_user tu ON e.id_user=tu.id_user ")->result_array();
        $response = [];
        if(count($data) > 0){
            $response = [
                'status' => true,
                'data' => $data,
                'message' => "Data Berhasil diunduh"
            ];
        }else{
            $response = [
                'status' => false,
                'data' => $data,
                'message' => "Data Kosong"
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
	}
    public function pengajuan_get($id)
	{
		$data = $this->db->query("SELECT e.*, tu.name FROM tb_event e JOIN tb_user tu ON e.id_dosen=tu.id_user where e.id_user=$id")->result_array();
        $response = [];
        if(count($data) > 0){
            $response = [
                'status' => true,
                'data' => $data,
                'message' => "Data Berhasil diunduh"
            ];
        }else{
            $response = [
                'status' => false,
                'data' => $data,
                'message' => "Data Kosong"
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
	}
    public function detail_pengajuan_get($id)
	{
		$data = $this->db->query("SELECT * FROM tb_event e JOIN tb_user tu ON e.id_dosen=tu.id_user where e.id=$id AND tu.id_role=2")->row_array();
        $response = [];
        if($data){
            $response = [
                'status' => true,
                'data' => $data,
                'message' => "Data Berhasil diunduh"
            ];
        }else{
            $response = [
                'status' => false,
                'data' => $data,
                'message' => "Data Kosong"
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
	}
    public function save_pengajuan_post(){
        $d = $_POST;
        $q = $this->db->insert("tb_event", 
        [
            'title' => $d['title'], 'message' => $d['message'], 'status' => 'waiting',
            'start_event' => $d['start_event'],'end_event' => $d['end_event'],
            'id_dosen' => $d['id_dosen'],'id_user' => $d['id_user'],

    ]);
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
    public function delete_get($id)
	{
            $data = $this->db->delete("tb_event", ['id' => $id]);
            $response = [];
            if($data){
                $response = [
                    'status' => true,
                    'message' => "Data Berhasil dihapus"
                ];
            }else{
                $response = [
                    'status' => false,
                    'data' => $data,
                    'message' => "Data Gagal dihapus"
                ];
            }
            $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
        
	}
    public function batal_get($id)
	{
            $data = $this->db->update("tb_event", ['status' => 'batal'], ['id' => $id]);
            $response = [];
            if($data){
                $response = [
                    'status' => true,
                    'message' => "Data Berhasil dibatalkan"
                ];
            }else{
                $response = [
                    'status' => false,
                    'data' => $data,
                    'message' => "Data Gagal dibatalkan"
                ];
            }
            $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
        
	}
}