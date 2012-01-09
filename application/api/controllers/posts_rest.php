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
   
  public function list_get(){
    
    // get the uri parameters
    $type = $this->get('type');
    $page = $this->get('page');
    $limit = $this->get('limit');
    
    // create the cache key 
    $cachekey = sha1('list/'.$type);
    
    // load the memcached driver    
    $this->load->driver('cache');                

    // check if the key exists in cache         
    //if(!$this->cache->memcached->get($cachekey)){
      
      // check if the list type is valid
      if($type != 'new' && $type != 'buzzing' && $type != 'loved'){
         $this->response(array('status'=>false,'message'=>'Invalid post list type'));
      }
      
      $this->load->model('Posts_API_model','trModel');
     
      if($posts = $this->trModel->getPostList($type,$page,$limit)){
        
        //$this->cache->memcached->save($cachekey,$posts,10*60);              
        $this->response($posts);          
      } else {
        $this->response(array('status'=>false,'message'=>'Fatal error: Could not get data either from cache or database.'));        
      }                                              
    //} else {
      // key exists. echo the json string
      //$cache = @$this->cache->memcached->get($cachekey);
      // var_dump($cache); 
       //$this->response(array('status'=>true,'data'=>$cache));
    //} 
    
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */