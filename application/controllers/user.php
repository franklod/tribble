<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      $this->output->enable_profiler(TRUE);
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
		$this->load->view('auth/login.php',$data);
    $this->load->view('common/page_end.php',$data);
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
		$this->load->view('user/new.php',$data);
    $this->load->view('common/page_end.php',$data);
  }
  
  public function create()
  {
    $this->load->model('User_model','users'); 
    
    /*if($this->users->registerUser()){
      
      $data = array(
      'title' => 'Tribble - Login',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
      
      $this->load->view('common/page_start.php',$data);
      $this->load->view('common/top_navigation.php',$data);
  		$this->load->view('user/create/success.php',$data);
      $this->load->view('common/page_end.php',$data);  
    } else {
      
      $data = array(
      'title' => 'Tribble - Login',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );       
      
      $this->load->view('common/page_start.php',$data);
      $this->load->view('common/top_navigation.php',$data);
  		$this->load->view('user/create/error.php',$data);
      $this->load->view('common/page_end.php',$data);
    }    */    
    
  }  
  
  public function login()
	{
    
	}

  public function logout()
	{
    
    $this->session->destroy();
    
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('auth/login.php',$data);
    $this->load->view('common/page_end.php',$data);
	}  
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */