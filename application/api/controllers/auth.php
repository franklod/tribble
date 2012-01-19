<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
  
require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function login_post()
  {
    $email = $this->post('email');
    $password = $this->post('password');

    if (!$email)
      $this->response(array('resquest_status' => false, 'message' => 'No email was suplied.'));
    if (!$password)
      $this->response(array('resquest_status' => false, 'message' => 'No password was suplied.'));
              
    // load the auth model
    $this->load->model('Auth_api_model', 'mAuth');
    
    $this->load->library('encrypt');
    
    $login = $this->mAuth->checkUserLogin($email,$password);
    if(!$login)
      $this->response(array('resquest_status' => false, 'message' => 'Invalid user/password combination.'));

    $this->response(array('resquest_status' => true, 'user' => $login));
  }

  public function logout($lb = null)
  {
    $this->session->sess_destroy();
    $redirectUrl = site_url() . "/" . string_to_uri($lb);
    redirect($redirectUrl);
  }
  
  public function session_put(){
    
    $session_data = array(
      'user_id'=>$this->put('user_id'),
      'user_name'=>$this->put('user_name'),
      'user_email'=>$this->put('user_email'),
      'user_avatar'=>$this->put('user_avatar')
    );
    // load the memcached driver
    $this->load->driver('cache');
    $cachekey = sha1($session_data['user_email']);
    if(!$this->cache->memcached->get($cachekey)){
      $this->cache->memcached->save($cachekey,$session_data,30*60);
      $this->response(array('request_status'=>true,'id'=>$cachekey));
    } else {
      $this->response(array('request_status'=>true,'id'=>$cachekey));
    } 
    
  }
  
  public function session_get(){    
    $session = $this->get('id');
    // load the memcached driver
    $this->load->driver('cache');
    if(!$this->cache->memcached->get($session)){
      $this->response(array('request_status'=>false,'message'=>'There\'s no such session.'));
    } else {
      $this->response(array('request_status'=>true,'id'=>$this->cache->memcached->get($session)));
    }     
  }
  
  public function session_delete(){    
    $session_data = $this->delete('id');
    // load the memcached driver
    $this->load->driver('cache');
    $cachekey = $session_data;
    if(!$this->cache->memcached->get($cachekey)){      
      $this->response(array('request_status'=>false,'message'=>'Session does not exist.'));
    } else {
      $this->response(array('request_status'=>true,'message'=>'Session was destroyed'));
    }   
  }

}
?>