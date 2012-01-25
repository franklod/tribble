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
    {
      $this->response(array('request_status' => false, 'message' => 'No user_id was supplied'));
    } else
      if (!$this->mPosts->checkIfPostExists($post_id))
      {
        $this->response(array('request_status' => false, 'message' => 'Unknown post'));
      }
    if (!$post_id)
    {
      $this->response(array('request_status' => false, 'message' => 'No post_id was supplied'));
    } else
      if (!$this->mUser->checkIfUserExists($user_id))
      {
        $this->response(array('request_status' => false, 'message' => 'Unknown user'));
      }
    if (!$comment_text)
      $this->response(array('request_status' => false, 'message' => 'No comment_text was supplied'));

    $comment_insert = $this->mPosts->insert_comment($post_id, $user_id, $comment_text);
    if (!$comment_insert)
    {
      $this->response(array('request_status' => false, 'message' => 'Could not insert comment.'), 404);
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
      $this->response(array('request_status' => true, 'message' => 'Comment was inserted successfuly.'));
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
      $this->response(array('request_status' => false, 'message' => 'No comment id was supplied.'));
    if (!$this->mPosts->checkIfCommentExists($comment_id))
      $this->response(array('request_status' => false, 'message' => 'Unknown comment.'));
    if (!$post_id)
      $this->response(array('request_status' => false, 'message' => 'No post id was supplied.'));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('request_status' => false, 'message' => 'Unknown post.'));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => 'No user id was supplied.'));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('request_status' => false, 'message' => 'Unknown user.'));

    $comment_delete = $this->mPosts->delete_comment($post_id, $comment_id, $user_id);

    if ($comment_delete == false)
    {
      $this->response(array('request_status' => false, 'message' => 'Could not delete comment.'), 404);
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
      $this->response(array('request_status' => true, 'message' => 'Comment was deleted successfuly.'));
    }

  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */