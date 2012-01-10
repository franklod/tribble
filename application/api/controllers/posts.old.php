<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH.'/libraries/REST_Controller.php';
 
class old extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      $this->load->driver('cache');
      $this->load->model('Tribbles_API_model','trModel');
      //$this->output->enable_profiler(TRUE);     
  }
  
  public function flush(){
    var_dump(@$this->cache->memcached->cache_info());
    $this->cache->memcached->clean();
  }
  
  public function searchPostsText($searchString){
    // hash the method name and params to get a cache key 
    $cachekey = sha1('searchPostsText/'.$searchString);        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->searchPostsText($searchString,$page,$per_page);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                     
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    }      
  } 



  public function countPosts($page = null, $per_page = 12)
	{
    // hash the method name and params to get a cache key 
    $cachekey = sha1('countPosts');        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->countPosts();                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);      
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));      
    }
                                         
	}
  
	public function getMostRecent($page = 1,$per_page = 500)
	{
	 
    // hash the method name and params to get a cache key 
    $cachekey = sha1('getMostRecent/');        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->getMostRecent($page,$per_page);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                     
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    }
                                         
	}    
   
  public function getMostCommented($page = 1,$per_page = 500)
	{
    // hash the method name and params to get a cache key 
    $cachekey = sha1('getMostCommented/');        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->getMostCommented($page,$per_page);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                      
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    }
                                         
	}  
  
  public function getMostLiked($page = 1,$per_page = 500)
	{
    // hash the method name and params to get a cache key 
    $cachekey = sha1('getMostLiked/');        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->getMostLiked($page,$per_page);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                      
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    } 
	}
  
  public function getPostById($postId = null){
    // hash the method name and params to get a cache key 
    $cachekey = sha1('getPostById/'.$postId);        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->getPostById($postId);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                      
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    }        
  }
  
  public function getRepliesByPostId($postId = null){
    // hash the method name and params to get a cache key 
    $cachekey = sha1('getRepliesByPostId/'.$postId);        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      $posts = $this->trModel->getRepliesByPostId($postId);                  
      $this->cache->memcached->save($cachekey,$posts,10*60);      
      echo json_encode($posts);                      
    } else {
      // key exists. echo the json string
      echo json_encode($this->cache->memcached->get($cachekey));
    }  
  }
  
  function reply($tribble){
    if(!$this->session->userdata('unique')){
      redirect('auth/login');
    } else {
      redirect('/tribbles/view/'.$tribble);
    }
  }
  
  function createNewTribble($user_email){
    
        // get the uid from the session data and hash it to be used as the user upload folder name       
        $user_hash = do_hash($user_email);        
          
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
            $response['status'] = false;
            $response['message'] = $result->error;
            echo json_encode($response);  
          } else {
            $response['status'] = true;
            $response['message'] = 'Posting successful';
            echo json_encode($response); 
          }
          
          $this->cache->memcached->delete(sha1('getMostRecent/'));
                     
       }            
  }
  
  public function createNewPost($userId = null){
    
    $res = null;
    
    if(empty($userId) || !$this->trModel->checkUserId($userId)){
      $res->status = 'error';
      $res->message = 'no valid user was supplied';
      echo json_encode($res);
    } else {
      echo "fuck yeah!";      
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