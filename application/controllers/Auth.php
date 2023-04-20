<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    }
	public function index()
	{
        $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required|trim');
        
        if ($this->form_validation->run() == false){
            $this->load->view('auth/login');
        }else {
                $this->login();
        }
		
	}

    private function login(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();


        if($user){
            if($user['is_active'] == 1 ){

                if(password_verify($password, $user['password'])){
                    $data = [

                        'email' => $user['email']
                    ];
                    $this->session->set_userdata($data);
                    $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
                    welcome to the user page
         </div>');
                    redirect('user');
                }else{
                    $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                    Incorrect email or password !
         </div>');
         redirect('auth');
                }
            }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                Email has not been Verified, you must verify your email!!
         </div>');
         redirect('auth');
            }
        }else{
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
            you have not registered
         </div>');
         redirect('auth');
        }
    }

    public function register()
	{
        $this->form_validation->set_rules('nama', 'nama', 'required|trim');
        $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email|is_unique[user.email]',
        [
            'is_unique' => 'Email already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'password', 'required|trim|min_length[5]|matches[password2]',
    [
        'matches' => 'passwords are not the same!',
        'min_length' => 'password too short'
    ]);
        $this->form_validation->set_rules('password2', 'repeat password', 'required|trim|matches[password1]');

        if($this->form_validation->run() == false){
            $data['title'] = 'Sistem Registrasi';
            $this->load->view('auth/register');
        }else{
            $this->upload();
        }
        
	}

    public function upload(){
        
       
        $upload_foto = $_FILES['image']['name'];
        if ($upload_foto != null) {
            $config['upload_path'] = './assets/img/'; //tempat ubah file foto
            $config['allowed_types'] = 'jpg|png|jpeg'; //mengatur type foto
            $config['max_size'] = '5048'; //besar kecil ukrn file(5mb)
            $config['remove_space'] = TRUE;
            $config['overwrite'] = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $pict = $this->upload->data('file_name');
                $email = $this->input->post('email', true);
           $data = [
               'nama' => htmlspecialchars($this->input->post('nama', true)),
               'email' =>htmlspecialchars($email),
               'image' => $pict,
               'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
               'is_active' => 0,
           ];

           $token = base64_encode(random_bytes(32));
           $user_token = [
               'email' => $email,
               'token' => $token,
               'date_created' => time()
           ];
           $this->db->insert('user', $data);
           $this->db->insert('user_token', $user_token);

           $this->_sendEmail($token, 'verify');
           $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
           
Congratulations, your account has been successfully created, Please active your account in your email!
         </div>');
           redirect('auth');
                
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                the file you entered is wrong!
         </div>');
           redirect('auth/register');
            }
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
            You Must Include a photo
         </div>');
           redirect('auth/register');
        
    }
}

    public function _sendEmail($token, $type){
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'windifikriyansyah12@gmail.com',
            'smtp_pass' => 'hhbmulyszdvtbile',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('windifikriyansyah12@gmail.com', 'Windi Fikriyansyah');
        $this->email->to($this->input->post('email', $config));

        if($type == 'verify'){
            $this->email->subject('Account verification');
            $this->email->message('click this link to verify you account : <a href="'.base_url(). 'auth/verify?email='. $this->input->post('email') . '&token=' . urlencode($token) .'">Active</a>');
    
        } 
        else if($type == 'forget'){

            $this->email->subject('Reset Password');
            $this->email->message('click this link to Reset Password : <a href="'.base_url(). 'auth/resetpassword?email='. $this->input->post('email') . '&token=' . urlencode($token) .'">Reset Password</a>');
    
        }
        
        if($this->email->send()){
            return true;
        }else{
            echo $this->email->print_debugger();
            
        }
    }

    public function verify(){
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' =>$email])->row_array();

        if($user){

            $user_token = $this->db->get_where('user_token', ['token' =>$token])->row_array();
            if($user_token){

                $this->db->set('is_active', 1);
                $this->db->where('email', $email);
                $this->db->update('user');
                $this->db->delete('user_token', ['email' => $email]);
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
           '.$email.' Your activation was successful, please login.
         </div>');
           redirect('auth');

            }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                your activation failed! wrong tokens!
         </div>');
           redirect('auth');
            }
        }else{
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
           
your activation failed! wrong email!
         </div>');
           redirect('auth');
        }
    }


    public function forgetPassword(){

        $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email');
        if($this->form_validation->run() == false){
            $this->load->view('auth/forget_password');
        }else{

            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if($user){

                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
               'token' => $token,
               'date_created' => time()
           
                ];
                $this->db->insert('user_token', $user_token);
                $this->_sendEmail($token, 'forget');
                $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
                Please check your email for password reset
         </div>');
           redirect('auth/forgetPassword');


            }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                Your Email is Not Registered and has not been activated!
         </div>');
           redirect('auth/forgetPassword');
            }
        }
        
    }


    public function resetPassword(){

        
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if($user){

            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if($user_token){

                $this->session->set_userdata('reset_email', $email);

                $this->changePassword();
            }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                Password reset failed, Wrong Token!
               </div>');
                 redirect('auth');
            }

        }else{
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
            Password reset failed, Wrong Email!
         </div>');
           redirect('auth');
        }
    }

    public function changePassword(){
        if(!$this->session->userdata('reset_email')){
            redirect('auth');
        }
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[5]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'required|trim|min_length[5]|matches[password1]');

        if($this->form_validation->run() == false){
            $this->load->view('auth/changepassword');
        }else{

            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
            Password changed successfully, Please Login
         </div>');
           redirect('auth');
        }
        
    
}

public function logout(){
    $this->session->unset_userdata('email');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
    you have successfully logout!
   </div>');
     redirect('auth');

}
}
