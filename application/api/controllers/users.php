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

  public function profile_put()
  {

    $user_data = array(
      'user_email' => $this->post('email'),
      'user_realname' => $this->post('realname'),
      'user_bio' => $this->post('bio'),
      'user_avatar' => $this->post('avatar'); );

    if ($this->mUser->updateProfile($this->post('id'), $user_data))
    {
      $this->response(array('status' => true, 'message' =>
          'User profile successfuly updated.'));
    } else
    {
      $this->response(array('status' => false, 'message' => 'Couldn\'t update the user profile.'));
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
