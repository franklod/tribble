<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  // log_message('error', 'fuck1!');

class Event {

  public function __construct(){
    $this->CI =& get_instance();
    $this->CI->load->model('Event_api_model','mEvent');
  }


  public function add($event_type,$event_message,$event_post_id,$event_from_user_id) {
  	if($this->CI->mEvent->add($event_type,$event_message,$event_post_id,$event_from_user_id)){
  		return true;
  	} else {
  		return false;
  	}
  }

  public function event_exists($event_type,$user_id,$post_id){
    if($event_id = $this->CI->mEvent->checkForExistingEvent($event_type,$user_id,$post_id)){
      return $event_id;
    } else {
      return false;
    }
  }

  public function delete($event_id) {
  	if($this->CI->mEvent->delete($event_id)){
  		return true;
  	} else {
  		return false;
  	}
  }

  public function clear($user_id,$post_id){
    // log_message('error', 'user: '.$user_id.' post: '.$post_id);
    $this->CI->mEvent->clear($user_id,$post_id);
  }

}