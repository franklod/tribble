<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $data['title'] = 'Tribble';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';

        if(!$this->session->userdata('uid')) {
            redirect('/auth/login');
        } else {
        }

    }


    public function profile($user_id)
    {
            
    }

    public function signup()
    {
        $data['title'] = 'Tribble - Signup';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';

        $this->load->view('common/page_top.php', $data);
        $this->load->view('user/signup.php', $data);
        $this->load->view('common/page_end.php', $data);
    }

    public function dosignup()
    {
        $this->form_validation->set_error_delimiters('<p class="help">', '</p>');

        $data['title'] = 'Tribble - Signup';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';

        if($this->form_validation->run('signup') == false) {

            $this->load->view('common/page_top.php', $data);
            $this->load->view('user/signup.php', $data);
            $this->load->view('common/page_end.php', $data);

        } else {

            $this->load->model('User_model', 'uModel');
            if($result = $this->uModel->createNewUser()) {
                if(@$result->error) {
                    $data['error'] = $result->error;
                    $this->load->view('common/page_top.php', $data);
                    $this->load->view('user/signup.php', $data);
                    $this->load->view('common/page_end.php', $data);
                } else {
                    $user_hash = $result->user_hash;
                    $user_dir = "./data/" . $user_hash;

                    if(is_dir($user_dir)) {
                        $data['error'] = "Oops. There something happened while finishing your account setup.";
                        $this->load->view('common/page_top.php', $data);
                        $this->load->view('user/signup.php', $data);
                        $this->load->view('common/page_end.php', $data);
                    } else {
                        mkdir($user_dir, 0755);
                        $data['success'] = "You're good to go! Go ahead and login.";
                        $this->load->view('common/page_top.php', $data);
                        $this->load->view('user/signup.php', $data);
                        $this->load->view('common/page_end.php', $data);
                    }

                }
            }

        }
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
