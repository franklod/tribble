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
    //$this->output->enable_profiler(TRUE);
    $this->load->model('User_API_Model', 'mUser');
  }

  public function liked_get()
  {
    $user_id = $this->get('user');
    $post_id = $this->get('post');

    if (!$user_id || !$post_id)
      $this->response(array('status' => false, 'message' =>
          'Insuficient data provided.'), 404);


    $user_like_status = $this->mUser->checkUserLiked($user_id, $post_id);

    if ($user_like_status)
    {
      $this->response(array('status' => true, 'like_status' => true));
    } else
    {
      $this->response(array('status' => true, 'like_status' => false));
    }
  }

  public function profile_get()
  {
    $user_id = $this->get('user');

    if (!$user_id)
      $this->response(array('status' => false, 'message' =>
          'Insuficient data provided.'), 404);

    $profile = $this->mUser->getUserProfile($user_id);

    if ($profile)
    {
      $this->response(array('status' => true, 'user' => $profile));
    } else
    {
      $this->response(array('status' => false, 'message' => 'Unknown user.'));
    }
  }
  
  public function profile_post(){
    
    $user_data = array(
      'user_email'=>$this->post('email'),
      'user_realname'=>$this->post('realname'),
      'user_bio'=>$this->post('bio'),
      'user_avatar'=>$this->post('avatar');
    );
    
    if($this->mUser->updateProfile($this->post('id'),$user_data)){
      $this->response(array('status'=>true,'message'=>'User profile successfuly updated.'));
    } else {
      $this->response(array('status'=>false,'message'=>'Couldn\'t update the user profile.'));
    }
    
  }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
