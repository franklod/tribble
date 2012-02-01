<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();    
    $this->load->model('Users_API_Model', 'mUsers');
    $this->load->library('encrypt');
    // $this->output->enable_profiler(TRUE);
  }

  private function _double_hash($str){
    return $this->encrypt->sha1($this->encrypt->sha1($str));
  }

  
  public function list_get(){
    $cachekey = sha1('users/list');
    // load the memcached driver
    $this->load->driver('cache');

    if(!$this->cache->memcached->get($cachekey)){
      $user_list = $this->mUsers->getUserList();
      if(!$user_list)
        $this->response(array('request_status'=>false,'message'=>lang('F_DATA_READ')));

      $this->cache->memcached->save($cachekey,$user_list,60*60);
      $this->response(array('request_status'=>true,'user_list'=>$user_list));
    } else {
      $this->response(array('request_status'=>true,'user_list'=>$this->cache->memcached->get($cachekey)));
    }
      
  }

  public function profile_get()
  {
    $user_id = $this->get('id');

    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USERID')), 404);

    // load the memcached driver
    $this->load->driver('cache');
    // define the cache key
    $cachekey = sha1('profile/id/'.$user_id);

    if(!$this->cache->memcached->get($cachekey)){
      $profile = $this->mUsers->getUserProfile($user_id);
      $this->cache->memcached->save($cachekey, $profile[0], 10 * 60);
      $this->response(array('request_status' => true, 'user' => $profile[0]));
    } else {
      $this->response(array('request_status' => true, 'user' => $this->cache->memcached->get($cachekey)));
    }
  }

  public function updateprofile_put()
  {

    $user_data = array(
      'user_email' => $this->put('user_email'),
      'user_realname' => $this->put('user_realname'),
      'user_bio' => $this->put('user_bio'),
      'user_avatar' => $this->put('user_avatar')
    );

    // do the database update
    $update = $this->mUsers->updateProfile($this->put('user_id'), $user_data);
    // if update fails
    if ($update === false)
      $this->response(array('request_status' => false, 'message' => lang('F_USER_PROFILE_UPDATE')));
    // if no change was made
    if ($update == 0)
      $this->response(array('request_status' => false, 'message' => lang('NC_USER_PROFILE')));

    // load the memcached driver
    $this->load->driver('cache');
    // define the cache key
    $cachekey = sha1('profile/id/'.$this->put('user_id'));
    // check if the user's profile is cached and delete the object if present
    if($this->cache->memcached->get($cachekey))
      $this->cache->memcached->delete($cachekey);

    // EVERYTHING WEN'T WELL.
    $this->response(array('request_status' => true, 'message' => lang('S_USER_PROFILE_UPDATE')));      
  }

  public function signup_put()
  {    

      $user_realname = $this->put('user_realname');
      $user_email = $this->put('user_email');
      $user_bio = $this->put('user_bio');
      $user_password = $this->put('user_password');

      if(!$user_realname)
        $this->response(array('request_status'=>false,'message'=>lang('NO_USER_NAME')));
      if(!$user_email)
        $this->response(array('request_status'=>false,'message'=>lang('NO_USER_EMAIL')));
      if(!$user_password)
        $this->response(array('request_status'=>false,'message'=>lang('NO_USER_PASSWORD')));

      $user = array(
        'user_realname' => $user_realname,
        'user_email' => $user_email,
        'user_password' => $this->_double_hash($user_password),
        'user_bio' => $user_bio
      ); 

      $user_insert = $this->mUsers->createNewUser($user);

      if(!$user_insert)
        $this->response(array('request_status'=>false,'message'=>lang('INV_DUPLICATE_USER')));
      
      $user_dir = $this->config->item('app_path').'/data/'.$user_insert;

      if(is_dir($user_dir))
        $this->response(array('request_status'=>false,'message'=>lang('INV_DUPLICATE_USER_DIR')));
      
      if(!mkdir($user_dir,0755))
        $this->response(array('request_status'=>false,'message'=>lang('F_ADD_USER')));
      
      $this->response(array('request_status'=>true,'message'=>lang('S_ADD_USER')));
      
  }

  public function password_post()
  {
    $user_id = $this->post('user_id');
    $new_pass = $this->post('new_password');
    $old_pass = $this->post('old_password');
    
    if(!$new_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_NEW_PASSWORD')));

    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USERID')));

    if(!$old_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_OLD_PASSWORD'))); 
    
    if($old_pass == $new_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_SAME_PASS')));
      
    if(!$this->checkOldPassword($old_pass,$user_id))
      $this->response(array('request_status'=>false,'message'=>lang('INV_OLD_PASSWORD'))); 

    $change_pass = $this->mUsers->updateUserPassword($new_pass,$user_id);

    if(!$change_pass)
      $this->response(array('request_status'=>false,'message'=>lang('F_PASSWORD_CHANGE')));

    $this->response(array('request_status'=>true,'message'=>lang('S_PASSWORD_CHANGE')));

  }

  public function password_get()
  {
    $user_id = $this->get('user_id');
    $new_pass = $this->get('new_password');
    $old_pass = $this->get('old_password');
    
    if(!$new_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_NEW_PASSWORD')));

    if(!$new_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_NEW_PASSWORD')));

    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USERID')));

    if(!$old_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_OLD_PASSWORD'))); 
    
    if($old_pass == $new_pass)
      $this->response(array('request_status'=>false,'message'=>lang('NC_SAME_PASS')));
      
    if(!$this->checkOldPassword($old_pass,$user_id))
      $this->response(array('request_status'=>false,'message'=>lang('INV_OLD_PASSWORD'))); 
       

    $change_pass = $this->mUsers->updateUserPassword($new_pass,$user_id);

    if(!$change_pass)
      $this->response(array('request_status'=>false,'message'=>lang('F_PASSWORD_CHANGE')));

    $this->response(array('request_status'=>true,'message'=>lanf('S_PASSWORD_CHANGE')));

  }

  protected function checkOldPassword($old_pass,$user_id)
  {
    
    $check_old_pass = $this->mUsers->checkPasswordForUser($old_pass,$user_id);

    if(!$check_old_pass)
      return false;
    
    return true;
  }

  public function checkOldPassword_get()
  {
    $user_id = $this->get('user_id');
    $old_pass = $this->get('old_password');

    if(!$old_pass)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_OLD_PASSWORD')));
    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>'E_NO_USERID'));
    
    $check_old_pass = $this->mUsers->checkPasswordForUser($old_pass,$user_id);

    if(!$check_old_pass)
      $this->response(array('request_status'=>false,'message'=>lang('INV_OLD_PASSWORD')));
    
    $this->response(array('request_status'=>true,'message'=>'S_OLD_PASSWORD_VALIDATION'));    
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */