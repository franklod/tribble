<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  if ( ! function_exists('double_hash')){
    function double_hash($str){
    return $this->encrypt->sha1($this->encrypt->sha1($srt));
  }
}