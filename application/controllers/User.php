<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {



    public function __construct()
    {
        parent::__construct();
        
    }

    public function index(){
        if($this->session->userdata('email')==''){
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
          Anda Belum Login
         </div>');
           redirect('auth');
        }
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        
        $this->load->view('user/index', $data);
    }
    
}