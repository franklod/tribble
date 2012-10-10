<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  // log_message('error', 'fuck1!');

class Cachehandler {

  public function __construct(){

    $this->CI =& get_instance();
    $this->CI->config->load('cacher');
  }

}