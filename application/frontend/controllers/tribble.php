<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tribbles
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */
 
class Tribble extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      
      // Load the rest client spark
      $this->load->spark('restclient/2.0.0');    
      // Load the library
      $this->load->library('rest');    
      // Run some setup
      $this->rest->initialize(array('server' => 'http://tribble.local/api.php/'));
      // load the pagination library
      $this->load->library('pagination'); 
      //$this->output->enable_profiler(TRUE);      
  }

	
  
  public function newer($page = null)
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';
    
    if($uid = $this->session->userdata('uid')){
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];            
    }            
    
    // Pull in an array of tribbles
    $tribble_list = json_decode($this->rest->get('posts/list/recent/'.$page)); 

    $data['tribbles'] = $tribble_list;
    
    $config['base_url'] = site_url('new/page');
    $config['display_pages'] = false;
    $config['total_rows'] = $this->rest->get('posts/count');
    $config['per_page'] = 12;
    $config['next_link'] = 'older';
    $config['prev_link'] = 'newer';
    $config['last_link'] = false;
    $config['first_link'] = false;
    $config['use_page_numbers'] = true; 

    $this->pagination->initialize($config); 
    
    $data['paging'] = $this->pagination->create_links();
    
    $this->load->view('common/page_top.php', $data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);        
	}    
   
  public function buzzing($page = 0,$posts_per_page = 12)
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';
    
    if($uid = $this->session->userdata('uid')){
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];            
    }            
    
    // Pull in an array of tribbles
    $tribble_list = json_decode($this->rest->get('posts/list/commented/'.$page)); 

    $data['tribbles'] = $tribble_list;
    
    $config['base_url'] = site_url('buzzing/page');
    $config['display_pages'] = false;
    $config['total_rows'] = $this->rest->get('posts/count');
    $config['per_page'] = 12;
    $config['next_link'] = 'older';
    $config['prev_link'] = 'newer';
    $config['last_link'] = false;
    $config['first_link'] = false;
    $config['use_page_numbers'] = true; 

    $this->pagination->initialize($config); 
    
    $data['paging'] = $this->pagination->create_links();
    
    $this->load->view('common/page_top.php', $data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);        
	}  
  
  public function loved($page = 0,$posts_per_page = 12)
	{
    $data['title'] = 'Tribble - Home';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';    

    if($uid = $this->session->userdata('uid')){
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];
    }

    // Pull in an array of tweets
    $tribble_list = json_decode($this->rest->get('tribbles/getMostLiked/0/12'));
    $data['tribbles'] = $tribble_list;
    
    $config['base_url'] = 'http:tribbe.local/index.php/';
    $config['display_pages'] = FALSE;
    $config['total_rows'] = 200;
    $config['per_page'] = $posts_per_page;
    $config['next_link'] = 'older';
    $config['previous_link'] = 'newer';
    $config['last_link'] = false;
    $config['first_link'] = false; 

    $this->pagination->initialize($config);
    $data['paging'] = $this->pagination->create_links();
    
    $this->load->view('common/page_top.php', $data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data); 
	}
  
  public function view($tribble){
    
    if($uid = $this->session->userdata('uid')){
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];
    }    
    
    $this->load->model('Tribbles_model','trModel');
    $tribbleData = $this->trModel->getTribble($tribble);
    $replyData = $this->trModel->getReplies($tribble);
        
    $data['tribble'] = $tribbleData[0];
    
    $data['replies'] = $replyData;
    
    //echo "<pre>";
    //print_r($replyData);
    //echo "</pre>";
    
    $data['title'] = 'Tribble - ' . $data['tribble']->title;
    $data['meta_description'] = $data['tribble']->title;
    $data['meta_keywords'] = $data['tribble']->tags;
    
    $this->load->view('common/page_top.php', $data);
		$this->load->view('tribble/view.php',$data);    
    $this->load->view('common/page_end.php',$data);         
  }
  
  function reply($tribble){
    if(!$this->session->userdata('unique')){
      redirect('auth/login');
    } else {
      redirect('/tribbles/view/'.$tribble);
    }
  }
  
  function upload(){
    $data['title'] = 'Tribble - Upload';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';
    
    if($uid = $this->session->userdata('uid')){
      // user is logged in: get and show user profile link
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];
      
      $this->load->view('common/page_top.php', $data);
  		$this->load->view('tribble/upload.php',$data);
      $this->load->view('common/page_end.php',$data); 
      
    } else {
      // user is not logged in: redirect to login form
      redirect('/auth/login/'.str_replace('/','-',uri_string()));
    } 
          
  }    
  
  public function doupload(){
    
    $data['title'] = 'Tribble - Upload';
    $data['meta_description'] = 'A design content sharing and discussion tool.';
    $data['meta_keywords'] = 'Tribble';
    
    $this->form_validation->set_error_delimiters('<p class="help">', '</p>');

    if($uid = $this->session->userdata('uid')){
      // user is logged in: get and show user profile link
      $this->load->model('User_model','uModel');
      $user = $this->uModel->getUserData($uid);
      $data['user'] = $user[0];
      
      // check form submission and validate
      if($this->form_validation->run('upload_image') == false){
              echo "form validation failed";
        // form has errors: show page and errors
        $this->load->view('common/page_top.php', $data);
    		$this->load->view('tribble/upload.php',$data);
        $this->load->view('common/page_end.php',$data);          
      } else {
        // form validation passed: proceed to upload and save image file and  tribble data
        
        // get the uid from the session data and hash it to be used as the user upload folder name       
        $user_hash = do_hash($this->session->userdata('unique'));
        
        // load the tribble model
        $this->load->model('Tribbles_model','trModel');           
          
        // set the upload configuration
        $ulConfig['upload_path'] = './data/'.$user_hash.'/';
    		$ulConfig['allowed_types'] = 'jpg|png';
    		$ulConfig['max_width']  = '400';
    		$ulConfig['max_height']  = '300';
        
        // load the file uploading lib and initialize
    		$this->load->library('upload', $ulConfig);
        $this->upload->initialize($ulConfig);
        
        // check if upload was successful and react        
        if (!$this->upload->do_upload('image_file')){                  
    			$error = array('error' => $this->upload->display_errors());
    		} else {
    			$data = array('upload_data' => $this->upload->data());       
          // set the data to write in db;
          $imgdata = array('image_path'=>substr($ulConfig['upload_path'].$data['upload_data']['file_name'],1),'image_palette'=>json_encode(getImageColorPalette($data['upload_data']['full_path'])));
          
          $config['image_library'] = 'gd2'; 
          $config['source_image'] = $ulConfig['upload_path'].$data['upload_data']['file_name'];	
          $config['create_thumb'] = TRUE; 
          $config['maintain_ratio'] = TRUE; 
          $config['width'] = 200; 
          $config['height'] = 150; 
          
          $this->load->library('image_lib', $config); 
          $this->image_lib->resize();
          
          if(!$result = $this->trModel->createNewTribble($imgdata)){
            $data['error'] = $result->error;
            $this->load->view('common/page_top.php', $data);
        		$this->load->view('tribble/upload.php',$data);
            $this->load->view('common/page_end.php',$data);  
          } else {
            redirect('/tribble/view/'.$result);
          }        
  		  }                
      }      
    } else {
      // user is not logged in: redirect to login form
      redirect('/auth/login/'.str_replace('/','-',uri_string()));
    }                                                      
  }
  
  public function like($tribble_id){
    $this->load->model('Tribbles_model','trModel');
    //$this->trModel->li
  }
  
  public function comment($tribbleid){
    $this->load->model('Tribbles_model','trModel');
    $comment = $this->trModel->addComment($tribbleid,$this->session->userdata('uid'));
    redirect('/tribble/view/'.$tribbleid);          
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */