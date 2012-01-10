<?php if (!defined('BASEPATH'))
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
        
    }

    public function list_get()
    {

        // load the memcached driver
        $this->load->driver('cache');
        // load the posts model
        $this->load->model('Posts_API_model', 'MPosts');

        // get the uri parameters
        $type = $this->get('type');
        $page = $this->get('page');
        $limit = $this->get('limit');

        // create the cache key
        $cachekey = sha1('list/' . $type . $page . $limit);        

        // check if the key exists in cache
        if (!$this->cache->memcached->get($cachekey)) {

            // check if the list type is valid
            switch ($type) {
                case 'new':
                    break;
                case 'buzzing':
                    break;
                case 'loved':
                    break;
                default:
                    $this->response(array('status' => false, 'message' =>
                        'An invalid post list type was requested.'));
            }

            
            // get the data from the database
            if ($posts = $this->MPosts->getPostList($type, $page, $limit)) {
                $posts_count = $this->MPosts->countPosts();
                // we have a dataset from the database, let's save it to memcached
                $object = array('status' => true, 'count' => $posts_count,'posts' => $posts);
                @$this->cache->memcached->save($cachekey, $object, 10 * 60);
                // output the response
                $this->response($object);
            } else {
                // we got nothing to show, output error
                $this->response(array('status' => false, 'message' =>
                    'Fatal error: Could not get data either from cache or database.'), 404);
            }
        } else {
            // the object is cached, send it
            $cache = $this->cache->memcached->get($cachekey);
            $this->response($cache);
        }

    }

    public function detail_get()
    {
        $post_id = $this->get('id');
        
        // load the memcached driver
        $this->load->driver('cache');
        // load the posts model
        $this->load->model('Posts_API_model', 'MPosts');

        // hash the method name and params to get a cache key
        $cachekey = sha1('detail' . $post_id);

        // check if the key exists in cache
        if(@!$this->cache->memcached->get($cachekey)){
          // get the data from the db, cache and echo the json string
          if (!(bool)$post = $this->MPosts->getPostById($post_id)) {
            $this->response(array('status' => false, 'message' => 'Couldn\'t get the post data.'));            
          } else {
            $replies = $this->MPosts->getRepliesByPostId($post_id);
            $object = array('status' => true, 'post' => $post, 'replies' => array('count' => count($replies),'replies'=>$replies));
            $this->cache->memcached->save($cachekey,$object,10*60);
            $this->response($object);
          } 
        } else {
        // key exists. echo the json string
          $this->response($this->cache->memcached->get($cachekey));
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
