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

    public function index_get($id_role)
    {
        // $email = $this->post('email');
        // $id_user = $this->post('id_user');
        
        // $user = $this->db->get_where('tb_user', ['id_user' => $id_user])->row_array();
        if ($id_role ===  '2') {
            $data = $this->Model_User->getAllMahasiswaAll();
        } elseif ($id_role ===  '3') {
            $data = $this->Model_User->getAllDosen();
        } elseif ($id_role===  '1') {
            $data = $this->db->join("tb_prodi", 'tb_user.id_prodi=tb_prodi.id_prodi')->get('tb_user')->result_array();
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
        $data = $this->db->join("tb_prodi", 'tb_user.id_prodi=tb_prodi.id_prodi')->get_where($this->table, ['id_user' => $id])->row_array();
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
            'id_prodi' => $d['id_prodi'],
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
    public function save_post()
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
                'message' => "Email sudah terpakai"
            ];
        }else{
            $q = $this->db->insert($this->table, $data);
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
    public function update_foto_post($id_user){
        $d = $_POST;
        // $data = $this->db->get_where('tb_user', ['id_user' => $id_user])->row_array();
        $file_name = str_replace('.','',$id_user);
		$config['upload_path']          = FCPATH.'/assets/img/profile/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 1024; // 1MB

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('avatar')) {
			$data['error'] = $this->upload->display_errors();
            $response = [
                'status' => false,
                'message' => "foto Gagal disimpan",
                'error' => $this->upload->display_errors()
            ];
		} else {
			$uploaded_data = $this->upload->data();
            // print_r($uploaded_data['file_name']);
            $update = $this->db->update('tb_user', ['image' => $uploaded_data['file_name']], ['id_user' => $id_user]);
            if($update){
                $response = [
                    'status' => true,
                    'message' => "foto Berhasil disimpan"
                ];
            }else{
                $response = [
                    'status' => false,
                    'message' => "foto Gagal disimpan"
                ];
            }
		}
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
