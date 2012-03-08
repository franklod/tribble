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

  var $ttl;

  public function __construct()
  {
    parent::__construct();

    $this->ttl->one_day = $this->config->item('api_1_day_cache');
    $this->ttl->one_hour = $this->config->item('api_1_hour_cache');
    $this->ttl->thirty_minutes = $this->config->item('api_30_minutes_cache');
    $this->ttl->ten_minutes = $this->config->item('api_10_minutes_cache');

    //$this->output->enable_profiler(TRUE);
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

    log_message('error','controller');
    
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

      $this->event->add('like',lang('EVENT_LIKE_ADD'),$post_id,$user_id);

      // CALCULATE THE NUMBER OF POSSIBLE CACHE PAGES FOR THE POST LISTINGS
      $cache_pages = ceil($this->_countPosts() / 600);

      // log_message('error','cache pages: '.$cache_pages);

      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_id);

      // output the api response
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

      // // CALCULATE THE NUMBER OF POSSIBLE CACHE PAGES FOR THE POST LISTINGS
      $cache_pages = ceil( $this->_countPosts() / 600);

      // check if there is allready a 'like' event from this user on this post
      $event_id = $this->event->event_exists('like',$user_id,$post_id);

      if($event_id != false){
        log_message('error','delete event: '.$event_id);
        $this->event->delete($event_id);
      }

      
      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_id);
      $this->response(array('status' => true, 'message' => lang('S_DELETE_LIKE')));

    }
        
  }

  function _countPosts()
  {
    $this->load->model('Posts_API_model', 'mPosts');
    $count = $this->mPosts->_countPosts();

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