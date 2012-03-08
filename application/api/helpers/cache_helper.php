<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  // log_message('error', 'fuck1!');

  class Cacher {

    function clear_cache($list){

      foreach ($list as $api_call) {
        $key = sha1($api_call);
        $this->cache->memcached->delete($key);
        log_message('error', 'api: '.$api_call);
      }
    
    }
  }