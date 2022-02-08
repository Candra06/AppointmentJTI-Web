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
        $this->table = 'tb_user';
    }

    public function index_get($id_user)
    {
        // $email = $this->post('email');
        // $id_user = $this->post('id_user');
        
        $user = $this->db->get_where('tb_user', ['id_user' => $id_user])->row_array();
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
    public function detail_get($id){
        $data = $this->db->get_where($this->table, ['id_user' => $id])->row_array();
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
    public function delete_get($id)
	{
            $data = $this->db->delete($this->table, ['id_user' => $id]);
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
        $data = [
            'name' => $d['name'],
            'nip/nim' => $d['no_induk'],
            'email' => $d['email'],
            'password' => $d['password'],
            'id_role' => $d['id_role'],
            'id_prodi' => $d['id_prodi'],
            // 'image' => $d['image'],
        ];
        $q = $this->db->update($this->table, $data, ['id_user' => $id]);
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
    public function save_post($id)
	{
        $d = $_POST;
        $data = [
            'name' => $d['name'],
            'nip/nim' => $d['no_induk'],
            'email' => $d['email'],
            'password' => $d['password'],
            'id_role' => $d['id_role'],
            'id_prodi' => $d['id_prodi'],
        ];
        $cek = $this->db->get_where($this->table, ['email' => $d['email']])->result_array();
        if(count($cek) > 0){
            $response = [
                'status' => false,
                'message' => "Data Berhasil disimpan"
            ];
        }else{
            $q = $this->db->update($this->table, $data, ['id_user' => $id]);
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
        }
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
	}
}
