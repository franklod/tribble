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

  public function __construct()
  {
    parent::__construct();
    //$this->output->enable_profiler(TRUE);
    $cacheTTL = 15 * 60;

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
      $this->response(array('status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
        $this->response(array('status' => false, 'message' => lang('INV_POST'));
    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mUser->checkIfUserExists($user_id))
        $this->response(array('status' => false, 'message' => lang('INV_USER')));
    if (!$comment_text)
      $this->response(array('status' => false, 'message' => lang('E_NO_COMMENT_TEXT'));

    $comment_insert = $this->mPosts->insert_comment($post_id, $user_id, $comment_text);
    if (!$comment_insert)
      $this->response(array('status' => false, 'message' => lang('F_ADD_COMMENT')), 404);

      $cacheKeys = array(
        sha1('detail' . $post_id),
        sha1('list/new'),
        sha1('list/buzzing'),
        sha1('list/loved'));
      foreach ($cacheKeys as $key)
      {
        $this->cache->memcached->delete($key);
      }

      $this->response(array('status' => true, 'message' => lang('S_ADD_COMMENT')));

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
      $this->response(array('status' => false, 'message' => lang('E_NO_COMMENT_ID')));
    if (!$this->mPosts->checkIfCommentExists($comment_id))
      $this->response(array('status' => false, 'message' => lang('INV_COMMENT')));
    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => lang('INV_POST')));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => lang('INV_USER')));

    $comment_delete = $this->mPosts->delete_comment($post_id, $comment_id, $user_id);

    if ($comment_delete == false)
    {
      $this->response(array('status' => false, 'message' => lang('F_DELETE_COMMENT')), 404);
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
      $this->response(array('status' => true, 'message' => lang('S_DELETE_COMMENT')));
    }

  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */