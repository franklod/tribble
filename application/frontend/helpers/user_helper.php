<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( ! function_exists('set_user_data')){
  function set_user_data($session){
    $user->name = $session->user->user_name;
    $user->id = $session->user->user_id;
    $user->email = $session->user->user_email;
    return $user;
    var_dump($user);
  }
}

