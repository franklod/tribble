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

class Posts extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    //$this->output->enable_profiler(TRUE);
    $cacheTTL = 15 * 60;

  }
  
  public function total_get(){
    $this->load->model('Posts_API_model', 'mPosts');
    $posts_count = $this->mPosts->countPosts();
    if(!$posts_count){
      $this->response(array('status'=>false,'message'=>'Could not get the post count.'));
    } else {
      $this->response(array('status'=>true,'total_posts'=>$posts_count));
    }
  }

  public function list_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // get the uri parameters
    $type = $this->get('type');
    $page = $this->get('page');
    $limit = $this->get('limit');

    // create the cache key
    $cachekey = sha1('list/' . $type . $page . $limit);

    // if we dont get page and limit vars set the defaults for 600 posts (50 * 12)
    if (!$page)
      $page = 1;
    if (!$limit)
      $limit = 600;

    // check if the key exists in cache
    if (!$this->cache->memcached->get($cachekey))
    {

      // check if the list type is valid
      switch ($type)
      {
        case 'new':
          break;
        case 'buzzing':
          break;
        case 'loved':
          break;
        default:
          $this->response(array('status' => false, 'message' => 'An invalid post list type was requested.'));
      }

      // get the data from the database
      if ($posts = $this->mPosts->getPostList($type, $page, $limit))
      {
        $posts_count = $this->mPosts->countPosts();
        // we have a dataset from the database, let's save it to memcached
        $object = array(
          'page' => $page,
          'status' => true,
          'total_posts' => $posts_count,
          'count' => $limit,
          'posts' => $posts);
        @$this->cache->memcached->save($cachekey, $object, 10 * 60);
        // output the response
        $this->response($object);
      } else
      {
        // we got nothing to show, output error
         $this->response(array('status' => false, 'message' => 'Fatal error: Could not get data either from cache or database.'), 404);
      }
    } else
    {
      // the object is cached, send it
      $cache = $this->cache->memcached->get($cachekey);
      $this->response($cache);
    }

  }

  public function detail_get()
  {
    $post_id = $this->get('id');

    if (!$post_id)
      $this->response(array('status' => false, 'message' => 'No post id was supplied'));

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // hash the method name and params to get a cache key
    $cachekey = sha1('detail' . $post_id);

    // check if the key exists in cache
    if (@!$this->cache->memcached->get($cachekey))
    {
      // get the data from the db, cache and echo the json string
      if (!(bool)$post = $this->mPosts->getPostById($post_id))
      {
        $this->response(array('status' => false, 'message' => 'Couldn\'t get the post data.'));
      } else
      {
        $replies = $this->mPosts->getRepliesByPostId($post_id);
        $object = array(
          'status' => true,
          'post' => $post,
          'replies' => array('count' => count($replies), 'replies' => $replies));
        $this->cache->memcached->save($cachekey, $object, 10 * 60);
        $this->response($object);
      }
    } else
    {
      // key exists. echo the json string
      $this->response($this->cache->memcached->get($cachekey));
    }
  }

  public function tagged_get()
  {
    $tag = $this->get('tag');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if (!$tag)
      $this->response(array('status' => false, 'message' => 'No search text was supplied'));

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // hash the method name and params to get a cache key
    $cachekey = sha1('tagged' . $tag . $page . $limit);

    if (@!$this->cache->memcached->get($cachekey))
    {
      $posts = $this->mPosts->getPostsByTag($tag, $page, $limit);
      if ($posts)
      {
        $this->cache->memcached->save($cachekey, array('status' => true, 'search' => $posts), 10 * 60);
        $this->response(array('status' => true, 'search' => $posts));
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
    }

  }

  public function find_get()
  {

    $string = $this->get('txt');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if (!$string)
      $this->response(array('status' => false, 'message' => 'No search text was supplied'));

    if (strlen($string) < 3)
      $this->response(array('status' => false, 'message' => 'Search text must be longer than 3 characters'));

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // hash the method name and params to get a cache key
    $cachekey = sha1('search' . $string . $page . $limit);

    if (@!$this->cache->memcached->get($cachekey))
    {
      $posts = $this->mPosts->searchPostsTitleAndDescription($string, $page, $limit);
      if ($posts)
      {
        $this->cache->memcached->save($cachekey, array('status' => true, 'search' => $posts), 10 * 60);
        $this->response(array('status' => true, 'search' => $posts));
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
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
    $this->load->model('User_API_model', 'mUser');

    if (!$user_id)
    {
      $this->response(array('status' => false, 'message' => 'No user_id was supplied'));
    } else
      if (!$this->mPosts->checkIfPostExists($post_id))
      {
        $this->response(array('status' => false, 'message' => 'Unknown post'));
      }
    if (!$post_id)
    {
      $this->response(array('status' => false, 'message' => 'No post_id was supplied'));
    } else
      if (!$this->mUser->checkIfUserExists($user_id))
      {
        $this->response(array('status' => false, 'message' => 'Unknown user'));
      }
    if (!$comment_text)
      $this->response(array('status' => false, 'message' => 'No comment_text was supplied'));

    $comment_insert = $this->mPosts->insert_comment($post_id, $user_id, $comment_text);
    if (!$comment_insert)
    {
      $this->response(array('status' => false, 'message' => 'Could not insert comment.'), 404);
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
      $this->response(array('status' => true, 'message' => 'Comment was inserted successfuly.'));
    }

  }

  public function comment_delete()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('User_API_model', 'mUsers');

    $comment_id = $this->delete('comment_id');
    $post_id = $this->delete('post_id');
    $user_id = $this->delete('user_id');

    if (!$comment_id)
      $this->response(array('status' => false, 'message' => 'No comment id was supplied.'));
    if (!$this->mPosts->checkIfCommentExists($comment_id))
      $this->response(array('status' => false, 'message' => 'Unknown comment.'));
    if (!$post_id)
      $this->response(array('status' => false, 'message' => 'No post id was supplied.'));
    if (!$this->mPosts->checkIfPostExists($post_id))
      $this->response(array('status' => false, 'message' => 'Unknown post.'));
    if (!$user_id)
      $this->response(array('status' => false, 'message' => 'No user id was supplied.'));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('status' => false, 'message' => 'Unknown post.'));

    $comment_delete = $this->mPosts->delete_comment($post_id, $comment_id, $user_id);

    if ($comment_delete == false)
    {
      $this->response(array('status' => false, 'message' => 'Could not delete comment.'), 404);
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
      $this->response(array('status' => true, 'message' => 'Comment was deleted successfuly.'));
    }

  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
