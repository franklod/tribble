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

class Replies extends REST_Controller
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

  private function _countPosts()
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

  public function comment_put()
  {
    $user_id = $this->put('user_id');
    $post_id = $this->put('post_id');
    $comment_text = $this->put('comment_text');

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('Users_API_model', 'mUser');

    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));

    if (!$this->mUser->checkIfUserExists($user_id))
        $this->response(array('request_status' => false, 'message' => lang('INV_USER')));

    if (!$post_id)  
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_ID')));

    if (!$this->mPosts->checkIfPostExists($post_id))      
      $this->response(array('request_status' => false, 'message' => lang('INV_POST')));

    if (!$comment_text)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_COMMENT_TEXT')));

    $comment_insert = $this->mPosts->insert_comment($post_id, $user_id, $comment_text);

    if (!$comment_insert)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_ADD_COMMENT')), 404);
    } else
    {

      $this->event->add('comment',lang('EVENT_COMMENT_ADD'),$post_id,$user_id);
      // lets wipe the relevant cache items
      // calculate how many cache pages we have
      $cache_pages = ceil( $this->_countPosts() / 600);

      // kill 'em all!
      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_id);
      $this->response(array('request_status' => true, 'message' => lang('S_ADD_COMMENT')));
    }

  }

  public function comment_delete()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('Users_API_model', 'mUsers');

    $comment_id = $this->delete('comment_id');
    $post_id = $this->delete('post_id');
    $user_id = $this->delete('user_id');

    if (!$comment_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_COMMENT_ID')));
    if (!$this->mPosts->checkIfCommentExists($comment_id))
      $this->response(array('request_status' => false, 'message' => lang('INV_COMMENT')));
    if (!$post_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('request_status' => false, 'message' => lang('INV_POST')));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('request_status' => false, 'message' => lang('INV_USER')));

    $comment_delete = $this->mPosts->delete_comment($post_id, $comment_id, $user_id);

    if ($comment_delete == false)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_DELETE_COMMENT')), 404);
    } else
    {


      // check if there is allready a 'like' event from this user on this post
      $event_id = $this->event->event_exists('comment',$user_id,$post_id);

      if($event_id != false){
        log_message('error','delete event: '.$event_id);
        $this->event->delete($event_id);
      }

      // lets wipe the relevant cache items
      // calculate how many cache pages we have
      $cache_pages = ceil( $this->_countPosts() / 600);

      // kill 'em all!
      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_id);      
      $this->response(array('request_status' => true, 'message' => lang('S_DELETE_COMMENT')));
    }

  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */