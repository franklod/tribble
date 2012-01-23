<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        // Load the rest client spark
        $this->load->spark('restclient/2.0.0');
        // Run some setup
        $this->rest->initialize(array('server' => 'http://api.tribble.local/'));
        // load the pagination library
        $this->load->library('pagination');

        // $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        $data['title'] = 'Tribble';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';

        if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
        {
          if ($session->request_status == true)
          {
            $data['user']->name = $session->user->user_name;
            $data['user']->id = $session->user->user_id;
          } else
          {
            redirect(site_url('/'));
            $this->session->sess_destroy();
          }
        }

    }

    public function profile()
    {
     
        if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
        {
          if ($session->request_status == true)
          {
            $data['user']->name = $session->user->user_name;
            $data['user']->id = $session->user->user_id;
          } else
          {
            redirect(site_url('/'));
            $this->session->sess_destroy();
          }
        }
        
        $data['title'] = 'Tribble - Signup';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';        

        // GET THE USER PROFILE DATA FROM THE API
        $user_data = $this->rest->get('users/profile/'.$session->user->user_id);
        
        // CHECK IF WE GOT THE DATA 
        if(!$user_data->request_status)
            show_error("Couldn't get your profile info.",404);
        
        // PREPARE TO SHOW THE EDIT PROFILE FORM
        $data['profile'] = $user_data->user;
        
        $this->load->view('common/page_top.php', $data);
        $this->load->view('user/profile.php', $data);
        $this->load->view('common/page_end.php', $data);
            
    }

    public function edit()
    {
     
        if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
        {
          if ($session->request_status == true)
          {
            $data['user']->name = $session->user->user_name;
            $data['user']->id = $session->user->user_id;
          } else
          {
            redirect(site_url('/'));
            $this->session->sess_destroy();
          }
        }
        
        $data['title'] = 'Tribble - Signup';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble';        

        // GET THE USER PROFILE DATA FROM THE API
        $user_data = $this->rest->get('users/profile/'.$session->user->user_id);
        
        // CHECK IF WE GOT THE DATA 
        if(!$user_data->request_status)
            // show_error("Couldn't get your profile info.",404);
            var_dump($session);
            exit();
        
        // PREPARE TO SHOW THE EDIT PROFILE FORM
        $data['profile'] = $user_data->user;
        
        $this->load->view('common/page_top.php', $data);
        $this->load->view('user/edit.php', $data);
        $this->load->view('common/page_end.php', $data);
            
    }

    public function password()
    {

        $data['title'] = 'Tribble - Signup';
        $data['meta_description'] = 'A design content sharing and discussion tool.';
        $data['meta_keywords'] = 'Tribble'; 

     
        if ($session = $this->rest->get('auth/session/', array('id' => $this->session->userdata('sid'))));
        {
          if ($session->request_status == true)
          {
            $data['user']->name = $session->user->user_name;
            $data['user']->id = $session->user->user_id;

            // GET THE USER PROFILE DATA FROM THE API
            $user_data = $this->rest->get('users/profile/'.$session->user->user_id);
            
            // CHECK IF WE GOT THE DATA 
            if(!$user_data->request_status)
                show_error("Couldn't get your profile info.",404);
            
            // PREPARE TO SHOW THE EDIT PROFILE FORM
            $data['profile'] = $user_data->user;

            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

            if($this->form_validation->run('change_password') == false){        
                    
                //form has errors : show page and errors
                $this->load->view('common/page_top.php', $data);
                $this->load->view('user/password.php', $data);
                $this->load->view('common/page_end.php', $data);

            } else 
            {
                
                $request_object = array(
                    'old_password'=>$this->input->post('old_password'),
                    'new_password'=>$this->input->post('new_password'),
                    'user_id'=>$session->user->user_id
                );

                $old_pass_check = $this->rest->get('users/check/password',$request_object);

                if(!$old_pass_check->request_status){

                    $data['error'] = 'The old password was not correct.';

                    //form has errors : show page and errors
                    $this->load->view('common/page_top.php', $data);
                    $this->load->view('user/password.php', $data);
                    $this->load->view('common/page_end.php', $data);
                }

                $update_pass = $this->rest->post('users/update/password',$request_object);

                if(!$update_pass->request_status){
                    $data['error'] = $update_pass->message;

                    //form has errors : show page and errors
                    $this->load->view('common/page_top.php', $data);
                    $this->load->view('user/password.php', $data);
                    $this->load->view('common/page_end.php', $data);
                } else {
                    
                    $data['success'] = $update_pass->message;

                    //form has errors : show page and errors
                    $this->load->view('common/page_top.php', $data);
                    $this->load->view('user/password.php', $data);
                    $this->load->view('common/page_end.php', $data);
                }

            }

          } else
          {
            redirect(site_url('/'));
            $this->session->sess_destroy();
          }
        }
            
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
