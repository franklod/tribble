<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_user_data($session){
  $data['user']->name = $session->user->user_name;
  $data['user']->id = $session->user->user_id;
  $data['user']->email = $session->user->user_email;
  return $data
}


