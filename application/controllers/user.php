<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      // $this->output->enable_profiler(TRUE);
  }

	public function index()
	{
    $data = array(
      'title' => 'Tribble - Login',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('user/login/login.php',$data);
    $this->load->view('common/page_end.php',$data);
	}
  
  public function login(){
    $this->load->model('User_model','uModel');
    if($user = $this->uModel->checkUserLogin()){
      $userSessionData = array('uid'=>$user->user_id,'uname'=>$user->user_realname);
      $this->session->set_userdata($array);
      redirect('/');
    } else {
      $this->load->view('common/page_start.php',$data);
      $this->load->view('common/top_navigation.php',$data);
  		$this->load->view('user/login/login.php',$data);
      $this->load->view('common/page_end.php',$data);
    }
  }
  
  public function edit()
	{
    
	}
  
  public function register()
  {
    $data = array(
      'title' => 'Tribble - Login',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('user/register/register.php',$data);
    $this->load->view('common/page_end.php',$data);
  }
  
  public function create()
  {
    $this->load->model('User_model','uModel');
    
    if($this->uModel->checkIfUserExists()){
      $data = array(
      'title' => 'Tribble - Login',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble',
      'error' => 'That username is taken'    
    );       
      
      $this->load->view('common/page_start.php',$data);
      $this->load->view('common/top_navigation.php',$data);
  		$this->load->view('user/login/login.php',$data);
      $this->load->view('common/page_end.php',$data);
    } else { 
    
      if($this->uModel->registerUser()){
        
        $data = array(
        'title' => 'Tribble - Login',
        'description' => 'A design content sharing and discussion tool.',
        'keywords' => 'Tribble'    
      );
        
        $user_dir = do_hash($this->input->post('email'));        
        if(!mkdir("./data/".$user_dir)){
          $this->load->view('common/page_start.php',$data);
          $this->load->view('common/top_navigation.php',$data);
      		$this->load->view('user/register/success.php',$data);
          $this->load->view('common/page_end.php',$data);  
        } else {
          $data = array(
            'title' => 'Tribble - Login',
            'description' => 'A design content sharing and discussion tool.',
            'keywords' => 'Tribble',
            'error' => 'There was an error while creating your images folder'    
          );       
            
          $this->load->view('common/page_start.php',$data);
          $this->load->view('common/top_navigation.php',$data);
      		$this->load->view('user/register/error.php',$data);
          $this->load->view('common/page_end.php',$data);
        }
        
          
      } else {
        
        $data = array(
        'title' => 'Tribble - Login',
        'description' => 'A design content sharing and discussion tool.',
        'keywords' => 'Tribble'    
      );       
        
        $this->load->view('common/page_start.php',$data);
        $this->load->view('common/top_navigation.php',$data);
    		$this->load->view('user/register/error.php',$data);
        $this->load->view('common/page_end.php',$data);
      }
    }       
    
  }  

  public function logout()
	{    
    $this->session->sess_destroy();    
    redirect('/');
	}  
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */