<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


  public function wipe_cache($list){
    foreach($list as $controller => $method){
      $key = sha1($method);
      $this->cache->delete($key)      
    }
  }



}