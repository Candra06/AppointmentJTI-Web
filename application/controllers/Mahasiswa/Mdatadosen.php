<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdatadosen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Session Login
        if (!$this->session->userdata('email')) {
            redirect('Auth/access_blocked');
        } else {
            $role = $this->session->userdata('id_role');
            if ($role != '3') {
                redirect('Auth/access_blocked');
            }
        }
        $this->load->model('Model_User');
    }

    public function index()
    {
        //title
        $data['title'] = 'Data Dosen';

        //ambil data session login
        $data['user'] = $this->db->get_where('tb_user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('tb_role', ['id_role' => $this->session->userdata('id_role')])->row_array();

        $data['dosen_mif'] = $this->Model_User->getAllDosenMif();
        $data['dosen_tif'] = $this->Model_User->getAllDosenTif();
        $data['dosen_tkk'] = $this->Model_User->getAllDosenTkk();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/mahasiswa_sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('mahasiswa/m_data_dosen', $data);
        $this->load->view('templates/footer');
    }
}
