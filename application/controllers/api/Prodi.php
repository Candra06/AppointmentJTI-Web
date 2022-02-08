<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Prodi extends REST_Controller
{
    
	public function __construct()
	{
		parent::__construct();
        $this->table = 'tb_prodi';
	}

	public function index_get()
	{
		$data = $this->db->get($this->table)->result_array();
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
    public function detail_get($id)
	{
		$data = $this->db->get_where($this->table, ['id_prodi' => $id])->row_array();
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
    public function delete_delete($id)
	{
            $data = $this->db->delete($this->table, ['id_prodi' => $id]);
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
        $nama_prodi = $d['nama_prodi'];
        $data = [
            'nama_prodi' => $nama_prodi
        ];
        $q = $this->db->update($this->table, $data, ['id_prodi' => $id]);
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
        $nama_prodi = $d['nama_prodi'];
        $data = [
            'nama_prodi' => $nama_prodi
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
}
