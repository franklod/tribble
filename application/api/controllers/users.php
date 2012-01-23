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
    $this->load->model('Users_API_Model', 'mUser');
    $this->load->library('encrypt');
    $this->output->enable_profiler(TRUE);
  }

  public function liked_get()
  {
    $user_id = $this->get('user');
    $post_id = $this->get('post');

    if (!$user_id || !$post_id)
      $this->response(array('request_status' => false, 'message' =>
          'Insuficient data provided.'), 404);

    $user_like_status = $this->mUser->checkUserLiked($user_id, $post_id);

    if ($user_like_status)
    {
      $this->response(array('request_status' => true, 'like_status' => true));
    } else
    {
      $this->response(array('request_status' => true, 'like_status' => false));
    }
  }

  public function profile_get()
  {
    $user_id = $this->get('id');

    if (!$user_id)
      $this->response(array('request_status' => false, 'message' =>
          'Insuficient data provided.'), 404);

    $profile = $this->mUser->getUserProfile($user_id);

    if ($profile)
    {
      $this->response(array('request_status' => true, 'user' => $profile[0]));
    } else
    {
      $this->response(array('request_status' => false, 'message' => 'Unknown user.'));
    }
  }

  public function profile_put()
  {

    $user_data = array(
      'user_email' => $this->put('email'),
      'user_realname' => $this->put('realname'),
      'user_bio' => $this->put('bio'),
      'user_avatar' => $this->put('avatar')
    );

    if ($this->mUser->updateProfile($this->put('id'), $user_data))
    {
      $this->response(array('request_status' => true, 'message' =>
          'User profile successfuly updated.'));
    } else
    {
      $this->response(array('request_status' => false, 'message' => 'Couldn\'t update the user profile.'));
    }

  }

  public function signup_put()
  {    
      $this->load->model('User_model', 'uModel');
      if ($result = $this->uModel->createNewUser())
      {
        if (@$result->error)
        {
          $data['error'] = $result->error;
          $this->load->view('common/page_top.php', $data);
          $this->load->view('user/signup.php', $data);
          $this->load->view('common/page_end.php', $data);
        } else
        {
          $user_hash = $result->user_hash;
          $user_dir = "./data/" . $user_hash;

          if (is_dir($user_dir))
          {
            $data['error'] = "Oops. There something happened while finishing your account setup.";
            $this->load->view('common/page_top.php', $data);
            $this->load->view('user/signup.php', $data);
            $this->load->view('common/page_end.php', $data);
          } else
          {
            mkdir($user_dir, 0755);
            $data['success'] = "You're good to go! Go ahead and login.";
            $this->load->view('common/page_top.php', $data);
            $this->load->view('user/signup.php', $data);
            $this->load->view('common/page_end.php', $data);
          }

        }
      }

    
  }

  public function password_post()
  {
    $user_id = $this->post('user_id');
    $new_pass = $this->post('new_password');
    $old_pass = $this->post('old_password');
    
    if(!$new_pass)
      $this->response(array('request_status'=>false,'message'=>'The new password was not supplied.'));

    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>'The user_id was not supplied.'));

    if(!$old_pass)
      $this->response(array('request_status'=>false,'message'=>'The old password was not supplied.')); 
    
    if($old_pass == $new_pass)
      $this->response(array('request_status'=>false,'message'=>'The passwords are identical. No change was made.')); 

    $change_pass = $this->mUser->updateUserPassword($new_pass,$user_id);

    if(!$change_pass)
      $this->response(array('request_status'=>false,'message'=>'We\'re sorry but we couldn\'t change your password. Please try again later.'));

    $this->response(array('request_status'=>true,'message'=>'Your password was changed.'));

  }

  public function checkOldPassword_get()
  {
    $user_id = $this->get('user_id');
    $old_pass = $this->get('old_password');

    if(!$old_pass)
      $this->response(array('request_status'=>false,'message'=>'The old password was not supplied.'));
    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>'The user_id was not supplied.'));
    
    $check_old_pass = $this->mUser->checkPasswordForUser($old_pass,$user_id);

    if(!$check_old_pass)
      $this->response(array('request_status'=>false,'message'=>'The old password was wrong.'));
    
    $this->response(array('request_status'=>true,'message'=>'Old password checks out.'));    
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */