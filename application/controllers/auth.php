<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model', 'aModel');
    }

    public function login($lb = NULL)        
    {
        
        
        
        $data['title'] = 'Tribble - Login';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';
        $data['form_action'] = current_url();
                 

        $this->form_validation->set_error_delimiters('<p class="help">', '</p>');

        if($this->form_validation->run('login') == false) {
            $this->load->view('common/page_start.php', $data);
            $this->load->view('common/top_navigation.php', $data);
            $this->load->view('common/header.php', $data);
            $this->load->view('user/login.php', $data);
            $this->load->view('common/page_end.php', $data);
        } else {            
            if($result = $this->aModel->checkUserLogin()) {
                $sessionData = array('uid' => $result[0]->user_id, 'uname' => $result[0]->user_realname, 'unique' => $result[0]->user_email);
                $this->session->set_userdata($sessionData);
                $redirectUrl = site_url()."/".string_to_uri($lb);
                redirect($redirectUrl);
            } else {
                $data['error'] = "Oops. It seems you have the wrong email, password or both. Try again sucker!";
                $this->load->view('common/page_start.php', $data);
                $this->load->view('common/top_navigation.php', $data);
                $this->load->view('common/header.php', $data);
                $this->load->view('user/login.php', $data);
                $this->load->view('common/page_end.php', $data);
            }
        }
    }
    
    public function logout($lb = NULL)
	{    
    $this->session->sess_destroy();
    $redirectUrl = site_url()."/".string_to_uri($lb);    
    redirect($redirectUrl);
	}


}

?>