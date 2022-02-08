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

	public function index_get($id)
	{
		$data = $this->db->query("SELECT * FROM $this->table e JOIN tb_user tu ON e.id_user=tu.id_user where tu.id_user='$id' ")->result_array();
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
		$data = $this->db->query("SELECT * FROM tb_event e 
        JOIN tb_user tu ON e.id_user=tu.id_user where e.id_dosen='$id' ")->result_array();
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
    public function detail_data_get($id)
	{
		$data = $this->db->query("SELECT * FROM events e 
        JOIN tb_user tu ON e.id_user=tu.id_user where e.id='$id'")->row_array();
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
    public function detail_get($id)
	{
		$data = $this->db->query("SELECT * FROM tb_event e 
        JOIN tb_user tu ON e.id_user=tu.id_user where e.id='$id'")->row_array();
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
    public function delete_pengajuan_delete($id)
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
    public function delete_delete($id)
	{
            $data = $this->db->delete("events", ['id' => $id]);
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
    public function update_post($id)
	{
        $d = $_POST;
        $nama_Event = $d['nama_Event'];
        $data = [
            'nama_Event' => $nama_Event
        ];
        $q = $this->db->update($this->table, $data, ['id_Event' => $id]);
        $response = [];
        if($q){
            $response = [
                'status' => true,
                'message' => "Data Berhasil diupdate"
            ];
        }else{
            $response = [
                'status' => false,
                'message' => "Data Gagal diupdate"
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
        
	}
    public function save_post()
	{
        $d = $_POST;
        $nama_Event = $d['nama_Event'];
        $data = [
            'nama_Event' => $nama_Event
        ];
        $q = $this->db->insert($this->table, $data,);
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


    public function save_pengajuan_get($id){
        $d = $_POST;
        $q = $this->db->insert("tb_event", ['title' => $d['title'], 'message' => $d['message'], 'status' => 'waiting']);
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
    public function konfirmasi_pengajuan_post($id){
        $d = $_POST;
        $q = $this->db->update("tb_event", ['status' => $d['status'], 'message' => $d['message']], ['id' => $id]);
        $response = [];
        if($q){
            $response = [
                'status' => true,
                'message' => "Data Berhasil ".$d['status']
            ];
        }else{
            $response = [
                'status' => false,
                'message' => "Data Gagal ".$d['status']
            ];
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
