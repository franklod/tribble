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

  public function exists_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    $this->load->model('Users_API_model', 'mUsers');

    // get the uri parameters
    $post_id = $this->get('post');
    $user_id = $this->get('user');

    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => lang('INV_POST')));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => lang('INV_USER')));

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
    $this->load->model('Users_API_model', 'mUsers');
    $this->load->model('Likes_API_model', 'mLikes');
    
    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => lang('INV_POST')));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => lang('INV_USER')));
      
    $like_insert = $this->mLikes->add_like($post_id,$user_id);
    
    if ($like_insert == false)
    {
      $this->response(array('status' => false, 'message' => lang('F_ADD_LIKE')));
    } else
    {

      // CALCULATE THE NUMBER OF POSSIBLE CACHE PAGES FOR THE POST LISTINGS
      $cache_pages = ceil( $this->countPosts() / 600);

      // KILL THE LISTS CACHE
      for($i=1;$i<=$cache_pages;$i++){
        @$this->cache->memcached->delete(sha1('list/new'.$i));
        @$this->cache->memcached->delete(sha1('list/buzzing'.$i));
        @$this->cache->memcached->delete(sha1('list/loved'.$i));
      }

      // kill the post cache
      $this->cache->memcached->delete(sha1('detail/' . $post_id));
      $this->cache->memcached->delete(sha1('posts/likes/' . $post_id));
      $this->response(array('status' => true, 'message' => lang('S_ADD_LIKE')));
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
    $this->load->model('Users_API_model', 'mUsers');
    $this->load->model('Likes_API_model', 'mLikes');
    
    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => lang('INV_POST')));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => lang('INV_USER')));
      
    $like_delete = $this->mLikes->remove_like($post_id,$user_id);
    
    if ($like_delete == false)
    {
      $this->response(array('status' => false, 'message' => lang('F_DELETE_LIKE')));
    } else
    {

      // CALCULATE THE NUMBER OF POSSIBLE CACHE PAGES FOR THE POST LISTINGS
      $cache_pages = ceil( $this->countPosts() / 600);

      // KILL THE LISTS CACHE
      for($i=1;$i<=$cache_pages;$i++){
        @$this->cache->memcached->delete(sha1('list/new'.$i));
        @$this->cache->memcached->delete(sha1('list/buzzing'.$i));
        @$this->cache->memcached->delete(sha1('list/loved'.$i));
      }

      // kill the post cache
      $this->cache->memcached->delete(sha1('detail/' . $post_id));
      $this->cache->memcached->delete(sha1('posts/likes/' . $post_id));
      $this->response(array('status' => true, 'message' => lang('S_DELETE_LIKE')));
    }
        
  }

  public function countPosts()
  {
    $this->load->model('Posts_API_model', 'mPosts');
    $count = $this->mPosts->countPosts();
    
    if (!$count)
    {
      return false;
    } else
    {
      return $count;
    }
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */