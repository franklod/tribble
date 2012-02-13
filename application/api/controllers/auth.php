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

  private function _double_hash($str){
    return $this->encrypt->sha1($this->encrypt->sha1($str));
  }

  public function login_post()
  {
    $email = $this->post('email');
    $password = $this->post('password');

    if (!$email)
      $this->response(array('resquest_status' => false, 'message' => lang('E_NO_EMAIL')));
    if (!$password)
      $this->response(array('resquest_status' => false, 'message' => lang('E_NO_PASSWORD')));
              
    // load the auth model
    $this->load->model('Auth_api_model', 'mAuth');
    
    $this->load->library('encrypt');
    
    $login = $this->mAuth->checkUserLogin($email,$this->_double_hash($password));
    if(!$login)
      $this->response(array('request_status' => false, 'message' => $this->lang->line('INV_LOGIN')));

    $this->response(array('request_status' => true, 'user' => $login));
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
      $this->cache->memcached->save($cachekey,$session_data,24*60*60);
      $this->response(array('request_status'=>true,'id'=>$cachekey));
    } else {
      $this->response(array('request_status'=>true,'id'=>$cachekey));
    } 
    
  }
  
  public function session_get(){    
    $id = $this->get('id');
    // load the memcached driver
    $this->load->driver('cache');
    if(!$this->cache->memcached->get($id)){
      $this->response(array('request_status'=>false,'message'=>$this->lang->line('E_SESSION_UNKNOW')));
    } else {
      $metadata = $this->cache->memcached->get_metadata($id);   
      $TTL = (int)floor(($metadata['expire'] - time()) / 60);
      if($TTL < 26)
        $this->cache->memcached->save($id,$metadata['data'],24*60*60);                    
      $this->response(array('request_status'=>true,'user'=>$this->cache->memcached->get($id)));
    }     
  }
  
  public function session_delete(){    
    $id = $this->delete('id');
    // load the memcached driver
    $this->load->driver('cache');
    if(!$this->cache->memcached->get($id)){      
      $this->response(array('request_status'=>false,'message'=>$this->lang->line('E_SESSION_UNKNOW')));
    } else {
      $this->cache->memcached->delete($id);
      $this->response(array('request_status'=>true,'message'=>$this->lang->line('S_SESSION_KILLED')));
    }   
  }

}
?>