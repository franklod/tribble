<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  // log_message('error', 'fuck1!');

class Cachehandler {

  public function __construct(){

    $this->CI =& get_instance();
    $this->CI->config->load('cacher');
  }

  public function clear_cache($list,$total_posts = 0){

    foreach ($list as $api_call) {
      $key = sha1($api_call);
      $this->CI->cache->memcached->delete($key);
      log_message('error', 'api: '.$api_call);
    }
    
  }

  public function purge_cache($method,$cache_pages,$post_id = null,$user_id = null) {

    log_message('error','Invoked method: '.$method);

    $caches = $this->CI->config->item('cacher');

    foreach ($caches[$method] as $cache) {
      if($cache['paged'] != 0){
        for($i=0;$i<=$cache_pages;$i++){          
          $key = $cache['method'].$i;          
          if(!$this->CI->cache->memcached->delete(sha1($key))){
            log_message('error', 'cache key: '.$key);
          }
        }
      } else {
        if($cache['post_id'] == 1){
          $key = $cache['method'].$post_id;
        } elseif($cache['user_id'] == 1) {
          $key = $cache['method'].$user_id;  
        } else {
          $key = $cache['method'];  
        }        
        if($this->CI->cache->memcached->delete(sha1($key))){
          log_message('error', 'cache key: '.$key);
        }
      }
    }
  }

}