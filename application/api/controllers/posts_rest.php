<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH.'/libraries/REST_Controller.php';
 
class Posts_rest extends REST_Controller {
  
  public function __construct()
  {
      parent::__construct();
      $this->load->driver('cache');
      $this->load->model('Tribbles_API_model','trModel');
      //$this->output->enable_profiler(TRUE);     
  }
  
  public function list_get(){
    
    $type = $this->get('type');
    $page = $this->get('page');
    $limit = $this->get('limit');
    
    if($type != 'new' && $type != 'buzzing' && $type != 'loved'){
       $this->response(array('status'=>false,'message'=>'Invalid post list type'));
    }
    
    // hash the method name and params to get a cache key 
    $cachekey = sha1('list/'.$type);        

    // check if the key exists in cache         
    if(@!$this->cache->memcached->get($cachekey)){
      // get the data from the db, cache and echo the json string      
      if($posts = $this->trModel->getPostList($type,$page,$limit)){
        @$this->cache->memcached->save($cachekey,$posts,10*60);      
        $this->response($posts);
        $this->response(array('status'=>'true','message'=>'tostas'));          
      } else {
        $this->response(array('status'=>false,'message'=>'Fatal error: Could not get data either from cache or database.'));        
      }                                              
    } else {
      // key exists. echo the json string
      $this->response($this->cache->memcached->get($cachekey));
    } 
    
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */