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
        $start_event = $d['start_event'];
        $end_event   = $d['end_event'];
        $cek = $this->db->query("SELECT * FROM tb_event where  ( '$start_event' < end_event AND '$end_event' > start_event ) ")->result_array();
        // if(count($cek) > 0){
        //     $response = [
        //         'status' => false,
        //         'message' => "tanggal sudah terdaftar"
        //     ];
        // }else{
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
        // }
        // print_r($cek);
        
        $this->response($response, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
    public function check_in_range($start_date, $end_date, $date_from_user)
    {
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);

    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
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