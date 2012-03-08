<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
  
require APPPATH . '/libraries/REST_Controller.php';

class Events extends REST_Controller
{

  var $ttl;

  public function __construct()
  {
    parent::__construct();

    $this->ttl->one_day = $this->config->item('api_1_day_cache');
    $this->ttl->one_hour = $this->config->item('api_1_hour_cache');
    $this->ttl->thirty_minutes = $this->config->item('api_30_minutes_cache');
    $this->ttl->ten_minutes = $this->config->item('api_10_minutes_cache');;
  }

  public function notifications_get(){
    $user_id = $this->get('user_id');
    $this->load->model('Event_api_model','mEvent');
    /*if(*/$notifications = $this->mEvent->getUserNotifications($user_id);/*){*/
      $this->response(array('request_status'=>true,$notifications));
    /*}*/
  }

}
?>