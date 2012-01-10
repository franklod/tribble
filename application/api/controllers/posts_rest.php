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
 
class Posts extends REST_Controller {
   
  public function list_get(){
    
    // get the uri parameters
    $type = $this->get('type');
    $page = $this->get('page');
    $limit = $this->get('limit');
    
    // create the cache key 
    $cachekey = sha1('list/'.$type.$page.$limit);
    
    // load the memcached driver    
    $this->load->driver('cache');                

    // check if the key exists in cache         
    if(!$this->cache->memcached->get($cachekey)){
      
      // check if the list type is valid    
      switch($type){
        case 'new': 
          break;
        case: 'buzzing':
          break;
        case 'loved':
          break;
        default:
          $this->response(array('status'=>false,'message'=>'Invalid post list type'));            
      }
      
      // load the posts model
      $this->load->model('Posts_API_model','trModel');
      // get the data from the database     
      if($posts = $this->trModel->getPostList($type,$page,$limit)){
        // we have a dataset from the database, let's save it to memcached
        $this->cache->memcached->save($cachekey,$posts,10*60);
        // output the response              
        $this->response(array('status'=>true,'data'=>$cache),200);          
      } else {
        // we got nothing to show, output error 
        $this->response(array('status'=>false,'message'=>'Fatal error: Could not get data either from cache or database.'),404);        
      }                                              
    //} else {
      // key exists. echo the json string
      $cache = @$this->cache->memcached->get($cachekey); 
      $this->response(array('status'=>true,'data'=>$cache),200);
    //} 
    
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */