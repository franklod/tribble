<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

class Posts extends REST_Controller
{

  var $ttl;

  public function __construct()
  {
    parent::__construct();

    $this->ttl->one_day = $this->config->item('api_1_day_cache');
    $this->ttl->one_hour = $this->config->item('api_1_hour_cache');
    $this->ttl->thirty_minutes = $this->config->item('api_30_minutes_cache');
    $this->ttl->ten_minutes = $this->config->item('api_10_minutes_cache');

    // load the memcached driver
    $this->load->driver('cache');

    // load the posts model
    $this->load->model('Posts_API_model', 'mPosts');

    // load the user model
    $this->load->model('Users_API_model', 'mUsers');

    // $this->output->enable_profiler(TRUE);

  }

  public function _countPosts()
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

  public function total_get()
  {
    $this->load->model('Posts_API_model', 'mPosts');
    $posts_count = $this->mPosts->_countPosts();

    if (!$posts_count)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_POST_COUNT')));
    } else
    {
      $this->response(array('request_status' => true, 'post_count' => $posts_count));
    }
  }
  
  public function list_get()
  {

    
    

    // get the uri parameters
    $type = $this->get('type');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if(empty($page)){ $page = 1; }

    // log_message('error','page: '.$page);

    // create the cache key
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$type.'/'.$page);

    // $cachekey = sha1('posts/list/' . $type . $page . $limit);

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
        @$this->cache->memcached->save($cachekey, $object, $this->ttl->thirty_minutes);
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
    @$user_id = $this->get('user');

    if (!$post_id)
      $this->response(array('status' => false, 'message' => lang('E_NO_POST_ID')));

    // hash the method name and params to get a cache key
    // $cachekey = sha1('posts/detail/id/' . $post_id);
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$post_id);

    if(isset($user_id)){
      log_message('error','user: '.$user_id);
      $this->event->clear($user_id,$post_id);
    }

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

        $palette = $this->mPosts->getPostPalette($post_id);
        $replies = $this->mPosts->getRepliesByPostId($post_id);
        $object = array(
          'request_status' => true,
          'post' => $post,
          'palette' => $palette,
          'post_replies' => array('count' => count($replies), 'replies' => $replies));
        $this->cache->memcached->save($cachekey, $object, $this->ttl->thirty_minutes);
        $this->response($object);
      }
    } else
    {
      // key exists. echo the json string
      $this->response($this->cache->memcached->get($cachekey));
    }
  }

  public function tag_get()
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


    // get the api key generation string
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$tag.'/'.$page);

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
        
        $this->cache->memcached->save($cachekey, $object, $this->ttl->thirty_minutes);
        $this->response($object);
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
    }

  }

  public function user_get(){         

    $user_id = $this->get('id');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if(!$user_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    // $cachekey = sha1('posts/user/id/'.$user_id.$page.$limit);
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$user_id.'/'.$page);
      

    if(!$this->cache->memcached->get($cachekey))
    {        
      $posts = $this->mPosts->getPostsByUser($user_id,$page,$limit);

      if(!$posts)
        $this->response(array('request_status'=>false,'message'=>lang('F_DATA_READ')));
      
      $object = array(
        'request_status' => true,
        'result_page' => $page,
        'user_name' => $posts['user_name'],
        'user_email' => $posts['user_email'],
        'user_bio' => $posts['user_bio'],
        'post_count' => $posts['count'],        
        'posts' => $posts['posts']
      );
            
      $this->cache->memcached->save($cachekey,$object, $this->ttl->thirty_minutes);
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

    // hash the method name and params to get a cache key
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$string.'/'.$page);

    if (@!$this->cache->memcached->get($cachekey))
    {
      $posts = $this->mPosts->searchPostsTitleAndDescription($string, $page, $limit);
      if ($posts)
      {
        $this->cache->memcached->save($cachekey, array('request_status' => true, 'search' => $posts), $this->ttl->thirty_minutes);
        $this->response(array('request_status' => true, 'search' => $posts));
      }
    } else
    {
      $this->response($this->cache->memcached->get($cachekey));
    }

  }

  public function post_put()
  {    
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
      'post_user_id' => $user_id
    );

    $insert_post = $this->mPosts->insertPost($post_data, $post_tags, $image_data);

    if ($insert_post == false)
    {
      $this->response(array('request_status' => false, 'message' => lang('F_POST_CREATE')), 404);
      log_message('error','Error from api_posts_model->insertPost');
    } else
    {
      
      // lets wipe the relevant cache items
      // calculate how many cache pages we have
      $cache_pages = ceil( $this->_countPosts() / 600);

      // purge relevant cache objects
      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$insert_post,$user_id);

      $this->response(array('request_status' => true, 'post_id' => $insert_post));
    }
  }

  public function reply_put()
  {
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
      

      $this->event->add('reply',lang('EVENT_REPLY_ADD'),$insert_post,$user_id);

      // lets wipe the relevant cache items
      // calculate how many cache pages we have
      $cache_pages = ceil( $this->_countPosts() / 600);

      /* 
        setup the caches array - we're gonna use this 
        to tell the cache_clean functions which key to delete 
      */

      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$insert_post,$user_id);
      $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_parent_id,$user_id);

      $this->response(array('request_status' => true, 'post_id' => $post_parent_id));      
    }
  }

  public function delete_delete()
  {
    $post_id = $this->delete('post_id');
    $user_id = $this->delete('user_id');


    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));
    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_USER_ID')));
    
    $can_user_delete = $this->mPosts->checkUserPostPermission($post_id,$user_id);

    if(!$can_user_delete)
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));

    $post_replies = $this->mPosts->getPostReplies($post_id);

    $delete_post = $this->mPosts->deletePost($post_id);

    if(!$delete_post)
      $this->response(array('request_status'=>false,'message'=>lang('F_DELETE_POST')));

    // lets wipe the relevant cache items
    // calculate how many cache pages we have
    $cache_pages = ceil( $this->_countPosts() / 600);

    // kill 'em all!
    $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$post_id,$user_id);
    foreach( $post_replies as $reply ) {
        $this->cachehandler->purge_cache(__FUNCTION__,$cache_pages,$reply->post_id,$reply->post_user_id);
    }
    $this->response(array('request_status'=>true,'message'=>lang('S_DELETE_POST')));
  }
    

  function likes_get() {

    $post_id = $this->get('post_id');

    if(!$post_id)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_POST_ID')));

    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$post_id);

    if(!$this->cache->memcached->get($cachekey)){

      $likes = $this->mPosts->getPostLikers($post_id);

      if(!$likes)
        $this->response(array('request_status'=>false,'message'=>'Boo hoo, it seems no one likes you.'));

      $this->cache->memcached->save($cachekey,$likes, $this->ttl->ten_minutes);
      $this->response(array('request_status'=>true,'likes'=>$likes));
      
    } else {
      $this->response(array('request_status'=>true,'likes'=>$this->cache->memcached->get($cachekey)));
    }
  }


  public function color_get(){
    
    $hex = $this->get('hex');
    $var = $this->get('v');
    $per = $this->get('p');

    // $variation = $this->get('variation');
    $page = $this->get('page');
    $limit = $this->get('limit');

    if (!$page)
      $page = 1;

    if (!$limit)
      $limit = 600;

    if(!$hex)
      $this->response(array('request_status'=>false,'message'=>lang('E_NO_COLOR')));

    if(!$var)
      $var = 30;

    if(!$per)
      $per = 2;

    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri']);

    if(!$this->cache->memcached->get($cachekey)){
      
      $this->load->model('Posts_API_model', 'mPosts');
      
      $posts_by_color = $this->mPosts->colorSearch($hex,$var,$per);


      if($posts_by_color == 0)
        $this->response(array('request_status'=>false,'message'=>'Could not find posts to match that color'));

      $search_result =$this->mPosts->getListOfPosts($posts_by_color['posts'],$page,$limit);

      $object = array(
        'request_status' => true,
        'hex'=>'#'.$hex,
        'post_count'=>$search_result['post_count'],
        'posts'=>$search_result['posts']
      );

      // $this->cache->memcached->save($cachekey,$object,$this->ttl->ten_minutes);
      $this->response($object);
    } else {
        $this->response($this->cache->memcached->get($cachekey));
    }
  }    

  public function edit_get(){

    $post_id = $this->get('post_id');
    $user_id = $this->get('user_id');

    if (!$post_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));

    // check if user has permission to edit
    $can_user_edit = $this->mPosts->checkUserPostPermission($post_id,$user_id);

    if(!$can_user_edit)
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));
    
    $post_data = $this->mPosts->getPostEditableData($post_id);

    if(!$post_data)
      $this->response(array('request_status'=>false,'message'=>lang('F_DB_QUERY')));

    $rObject = array(
      'request_status' => true,
      'post' => $post_data
    );

    $this->response($rObject);
     
  }

  public function edit_post(){
    
    $post_id = $this->post('post_id');    
    $post_title = $this->post('post_title');
    $post_text = $this->post('post_text');
    $post_tags = $this->post('post_tags');
    $user_id = $this->post('user_id');

    // check if we have all the required data
    if (!$post_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_ID')));
    if (!$user_id)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_USER_ID')));
    if (!$post_title)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TITLE')));
    if (!$post_text)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TEXT')));
    if (!$post_tags)
      $this->response(array('request_status' => false, 'message' => lang('E_NO_POST_TAGS')));

    // check if user has permission to edit
    $can_user_edit = $this->mPosts->checkUserPostPermission($post_id,$user_id);

    if(!$can_user_edit)
      $this->response(array('request_status'=>false,'message'=>lang('INV_POST_PERMISSIONS')));

    $update_result = $this->mPosts->updatePostData(array('post_id'=>$post_id,'post_title'=>$post_title,'post_text'=>$post_text),array('tag_content'=>$post_tags));

    if(!$update_result)
      $this->response(array('request_status'=>false,'message'=>lang('F_EDIT_POST')));

    // lets wipe the relevant cache items
      // calculate how many cache pages we have
      $cache_pages = ceil( $this->_countPosts() / 600);

      /* 
        setup the caches array - we're gonna use this 
        to tell the cache_clean functions which key to delete 
      */

      $caches = array();
      
      

      for($i=1;$i<=$cache_pages;$i++){
        array_push($caches, 'posts/list/new/'.$i);
        array_push($caches, 'posts/list/buzzing/'.$i);
        array_push($caches, 'posts/list/loved/'.$i);
      }

      array_push($caches, 'posts/detail/id/'.$post_id);
      array_push($caches, 'users/list/');
      array_push($caches, 'meta/colors/');
      array_push($caches, 'meta/tags/');
      array_push($caches, 'meta/colors/0');
      array_push($caches, 'meta/tags/0');

      // kill 'em all!
      $this->cachehandler->clear_cache($caches);
    
    $this->response(array('request_status'=>true,'message'=>lang('S_EDIT_POST')));
  }
  

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
