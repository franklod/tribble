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

  var $cache_ttl;

  public function __construct()
  {
    parent::__construct();
    $this->cache_ttl = $this->config->item('short_cache');
    //$this->output->enable_profiler(TRUE);

  }

  public function index()
  {
    redirect('http://tribble.local');
  }

  public function total_get()
  {
    $this->load->model('Posts_API_model', 'mPosts');
    $posts_count = $this->mPosts->countPosts();

    if (!$posts_count)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_POST_COUNT')));
    } else
    {
      $this->response(array('request_status' => true, 'post_count' => $posts_count));
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
          $this->response(array('status' => false, 'message' =>lang('INV_POST_LIST_TYPE')));
      }

      // get the data from the database
      if ($posts = $this->mPosts->getPostList($type, $page, $limit))
      {
        // we have a dataset from the database, let's save it to memcached
        $object = array(
          'request_status' => true,
          'result_page' => $page,
          'post_count' => $limit,
          'posts' => $posts);
        @$this->cache->memcached->save($cachekey, $object, $this->cache_ttl);
        // output the response
        $this->response($object);
      } else
      {
        // we got nothing to show, log and output error
        log_message(1,'API call error: posts/list/' .'/'. $type .'/'. $page .'/'. $limit);
        $this->response(array('status' => false, 'message' => lang('E_DATA_READ'), 404));
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
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // hash the method name and params to get a cache key
    $cachekey = sha1('detail/' . $post_id);

    // check if the key exists in cache
    if (@!$this->cache->memcached->get($cachekey))
    {
      // get the data from the db, cache and echo the json string
      if (!(bool)$post = $this->mPosts->getPostById($post_id))
      {
        log_message(1,'API call error: posts/detail/'. $post_id);
        $this->response(array('status' => false, 'message' => lang('E_DATA_READ'), 404));
      } else
      {
        $replies = $this->mPosts->getRepliesByPostId($post_id);
        $object = array(
          'request_status' => true,
          'post' => $post,
          'post_replies' => array('count' => count($replies), 'replies' => $replies));
        $this->cache->memcached->save($cachekey, $object, $this->cache_ttl);
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
    $tag = urldecode($this->get('tag'));
    $page = $this->get('page');
    $limit = $this->get('limit');

    if (!$tag)
      $this->response(array('status' => false, 'message' => lang('E_NO_SEARCH_TAG')));

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // hash the method name and params to get a cache key
    $cachekey = sha1('tagged/' . $tag . $page . $limit);

    if (@!$this->cache->memcached->get($cachekey))
    {
      $posts = $this->mPosts->getPostsByTag($tag, $page, $limit);
      if ($posts)
      {
        $object = array(
          'request_status' => true,
          'result_page' => $page,
          'tag' => $posts['tag'],
          'post_count' => $posts['count'],
          'posts' => $posts['posts']
        );
        
        $this->cache->memcached->save($cachekey, $object, $this->cache_ttl);
        $this->response($object);
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
    }

  }

  public function user_get(){         

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    $user_id = $this->get('id');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    $cachekey = sha1('user/'.$user_id.$page.$limit);
      

    if(!$this->cache->memcached->get($cachekey))
    {        
      $posts = $this->mPosts->getPostsByUser($user_id,$page,$limit);

      if(!$posts)
        $this->response(array('request_status'=>false,'message'=>lang('F_DATA_READ')));
      
      $object = array(
        'request_status' => true,
        'result_page' => $page,
        'user_id' => $posts['user_id'],
        'user_name' => $posts['user_name'],
        'user_email' => $posts['user_email'],
        'user_bio' => $posts['user_bio'],
        'post_count' => $posts['count'],        
        'posts' => $posts['posts']
      );
            
      $this->cache->memcached->save($cachekey,$object, $this->cache_ttl);
      $this->response($object);
    } else {
      $this->response($this->cache->memcached->get($cachekey));                    
    }
      

  }

  public function find_get()
  {

    $string = $this->get('txt');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if (!$string)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_SEARCH_TEXT')));

    if (strlen($string) < 3)
      $this->response(array('request_status' => false, 'message' => lang('INV_SEARCH_TEXT')));

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
        $this->cache->memcached->save($cachekey, array('request_status' => true, 'search' => $posts), $this->cache_ttl);
        $this->response(array('request_status' => true, 'search' => $posts));
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
    }

  }

  public function post_put()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('Users_API_model', 'mUsers');

    $image_data = $this->put('image_data');
    $post_title = $this->put('post_title');
    $post_text = $this->put('post_text');
    $post_tags = $this->put('post_tags');
    $user_id = $this->put('user_id');

    if (!$image_data)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_IMAGE')));
    if (!$post_title)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TITLE')));
    if (!$post_text)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TEXT')));
    if (!$post_tags)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TAGS')));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('request_status' => false, 'message' => lang('INV_USER')));

    $post_data = array(
      'post_title' => $post_title,
      'post_text' => $post_text,
      'post_user_id' => $user_id);

    $insert_post = $this->mPosts->insertPost($post_data, $post_tags, $image_data);        

    if ($insert_post == false)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_POST_CREATE')), 404);
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

      @$this->cache->memcached->delete(sha1('users/list'));
      @$this->cache->memcached->delete(sha1('tags/0'));
      @$this->cache->memcached->delete(sha1('tags'));
      @$this->cache->memcached->delete(sha1('colors'));

      $this->response(array('request_status' => true, 'post_id' => $insert_post));      
    }
  }

  public function reply_put()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('Users_API_model', 'mUsers');

    $image_data = $this->put('image_data');
    $post_title = $this->put('post_title');
    $post_text = $this->put('post_text');
    $post_tags = $this->put('post_tags');
    $user_id = $this->put('user_id');
    $post_parent_id = $this->put('post_parent_id');

    if (!$image_data)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_IMAGE')));
    if (!$post_title)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TITLE')));
    if (!$post_text)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TEXT')));
    if (!$post_tags)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TAGS')));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$post_parent_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_PARENT_POST_ID')));
    if (!$this->mUsers->checkIfUserExists($user_id))
      $this->response(array('request_status' => false, 'message' => lang('INV_USER')));

    $post_data = array(
      'post_parent_id' => $post_parent_id,
      'post_title' => $post_title,
      'post_text' => $post_text,
      'post_user_id' => $user_id);

    $insert_post = $this->mPosts->insertPost($post_data, $post_tags, $image_data); 
           
    $insert_reply = $this->mPosts->insertReply($insert_post,$post_parent_id);

    if ($insert_reply == false)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_REPLY_CREATE')), 404);
    }

    if ($insert_post == false)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_POST_CREATE')), 404);
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

      @$this->cache->memcached->delete(sha1('detail/'.$post_parent_id));
      @$this->cache->memcached->delete(sha1('detail/'.$insert_post));
      @$this->cache->memcached->delete(sha1('users/list'));
      @$this->cache->memcached->delete(sha1('meta/tags'));
      @$this->cache->memcached->delete(sha1('meta/colors'));

      $this->response(array('request_status' => true, 'post_id' => $post_parent_id));      
    }
  }

  public function post_delete()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    // load the user model
    $this->load->model('Users_API_model', 'mUsers');

    $post_id = $this->delete('post_id');
    $user_id = $this->delete('user_id');

    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));
    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));
    
    $can_user_delete = $this->mPosts->checkPostOwnership($post_id,$user_id);

    if(!$can_user_delete)
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));

    $delete_post = $this->mPosts->deletePost($post_id);
    if(!$delete_post)
      $this->response(array('request_status'=>false,'message'=>lang('F_DELETE_POST')));

    // CALCULATE THE NUMBER OF POSSIBLE CACHE PAGES FOR THE POST LISTINGS
    $cache_pages = ceil( $this->countPosts() / 600);

    // KILL THE LISTS CACHE
    for($i=1;$i<=$cache_pages;$i++){
      @$this->cache->memcached->delete(sha1('list/new'.$i));
      @$this->cache->memcached->delete(sha1('buzzing/new'.$i));
      @$this->cache->memcached->delete(sha1('loved/new'.$i));
    }

    @$this->cache->memcached->delete(sha1('meta/tags'.$i));
    @$this->cache->memcached->delete(sha1('meta/tags/0'.$i));
    @$this->cache->memcached->delete(sha1('meta/users'.$i));
    @$this->cache->memcached->delete(sha1('meta/users/0'.$i));

    $this->response(array('request_status'=>true,'message'=>lang('S_DELETE_POST')));
  }
    

  public function edit_get(){

    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');
    
    // verify if we have all the data
    $post_id = $this->get('post_id');
    $user_id = $this->get('user_id');

    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));
    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));

    // // check if this user is allowed to edit this post
    if(!$this->mPosts->checkPostOwnership($post_id,$user_id))
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));

    // get the editable post data (title, text, tags)
    $data = $this->mPosts->getPostForEdit($post_id);

    $data_object = array(
      'post_title' => $data->post->post_title,
      'post_text' => $data->post->post_text,
      'post_tags' => $data->tags->post_tags
    );

    $this->response(array('request_status'=>true,$data_object));
  }    
  
  public function edit_put(){

    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    if (!$this->put('post_title'))
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TITLE')));
    if (!$this->put('post_text'))
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TEXT')));
    if (!$this->put('post_tags'))
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TAGS')));
    if(!$this->put('user_id'))
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));
    if(!$this->put('post_id'))
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));

    // // check if this user is allowed to edit this post
    if(!$this->mPosts->checkPostOwnership($post_id,$this->put('user_id')))
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));

    // load the memcached driver
    $this->load->driver('cache');

  }

  function likes_get() {

    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    $post_id = $this->get('post_id');

    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));

    // load the memcached driver
    $this->load->driver('cache');
    $cachekey = sha1('posts/likes/'.$post_id);

    if(!$this->cache->memcached->get($cachekey)){

      $likes = $this->mPosts->getPostLikers($post_id);


      if(!$likes)
        $this->response(array('request_status'=>false,'message'=>'Boo hoo, it seems no one likes you.'));

    
      $this->cache->memcached->save($cachekey,$likes, $this->cache_ttl);
      $this->response(array('request_status'=>true,'likes'=>$likes));
      
    } else {
      $this->response(array('request_status'=>true,'likes'=>$this->cache->memcached->get($cachekey)));
    }



  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
