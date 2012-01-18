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

class Likes extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    //$this->output->enable_profiler(TRUE);
    $cacheTTL = 15 * 60;

  }

  public function is_liked_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    $this->load->model('User_API_model', 'mUsers');

    // get the uri parameters
    $post_id = $this->get('post');
    $user_id = $this->get('user');

    if (!$post_id)
      $this->response(array('status' => false, 'message' => 'No post id was supplied.'));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => 'Unknown post.'));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => 'No user id was supplied.'));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => 'Unknown user.'));

    $like_status = $this->mUsers->checkUserLiked($user_id, $post_id);

    if (!$like_status)
    {
      $this->response(array('request_status' => true, 'like' => false));
    } else
    {
      $this->response(array('request_status' => true, 'like' => true));
    }

  }
  
  public function like_put()
  {
    $post_id = $this->put('post_id');
    $user_id = $this->put('user_id');
    
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    $this->load->model('User_API_model', 'mUsers');
    $this->load->model('Likes_API_model', 'mLikes');
    
    if (!$post_id)
      $this->response(array('status' => false, 'message' => 'No post id was supplied.'));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => 'Unknown post.'));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => 'No user id was supplied.'));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => 'Unknown user.'));
      
    $like_insert = $this->mLikes->add_like($post_id,$user_id);
    
    if ($like_insert == false)
    {
      $this->response(array('status' => false, 'message' => 'Could not add a like to the post.'));
    } else
    {

      $cacheKeys = array(
        sha1('detail' . $post_id),
        sha1('list/new'),
        sha1('list/buzzing'),
        sha1('list/loved'));
      foreach ($cacheKeys as $key)
      {
        $this->cache->memcached->delete($key);
      }
      $this->response(array('status' => true, 'message' => 'A like was added to the post.'));
    }
    
        
  }
  
  public function like_delete()
  {
    $post_id = $this->delete('post_id');
    $user_id = $this->delete('user_id');
    
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    $this->load->model('User_API_model', 'mUsers');
    $this->load->model('Likes_API_model', 'mLikes');
    
    if (!$post_id)
      $this->response(array('status' => false, 'message' => 'No post id was supplied.'));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => 'Unknown post.'));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => 'No user id was supplied.'));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => 'Unknown user.'));
      
    $like_delete = $this->mLikes->remove_like($post_id,$user_id);
    
    if ($like_delete == false)
    {
      $this->response(array('status' => false, 'message' => 'Could not remove like from  post.'));
    } else
    {

      $cacheKeys = array(
        sha1('detail' . $post_id),
        sha1('list/new'),
        sha1('list/buzzing'),
        sha1('list/loved'));
      foreach ($cacheKeys as $key)
      {
        $this->cache->memcached->delete($key);
      }
      $this->response(array('status' => true, 'message' => 'Like was removed from post.'));
    }
        
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */