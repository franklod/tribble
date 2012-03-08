<?php

class Event_api_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function add($event_type,$event_message,$event_post_id,$event_from_user_id)
    {
        
        // get the id of the post owner
        $this->db->select('post_user_id');
        $this->db->from('post');
        $this->db->where(array('post_id'=>$event_post_id));
        $q = $this->db->get();
        $res = $q->result();
        $post_owner = $res[0]->post_user_id;

        // get the name of the user that triggered the event
        $this->db->select('user_realname');
        $this->db->from('user');
        $this->db->where(array('user_id'=>$event_from_user_id));
        $q = $this->db->get();
        $res = $q->result();
        $event_from_name = $res[0]->user_realname;

        // if($post_owner == $event_from_user_id){
        //     return false;
        // }

    	$data = array(
            'event_type' => $event_type,
    		'event_message' => $event_message,
    		'event_post_id' => $event_post_id,
    		'event_to_user_id' => $post_owner,
    		'event_from_user_id' => $event_from_user_id,
    		'event_from_user_name' => $event_from_name
    	);

        if($this->db->insert('event',$data)){
        	return true;
        } else {
        	return false;
        }
    }

    function delete($event_id){
        if($this->db->delete('event', array('event_id' => $event_id))){
            return true;
        } else {
            return false;
        }
    }

    function getUserNotifications($user_id){
        $this->db->order_by('event_timestamp','desc');
    	$q = $this->db->get_where('event',array('event_to_user_id'=>$user_id));
        $count = $q->num_rows();
        $data['count'] = $count;
        $data['notifications'] = $q->result();
    	return $data;
    }

    function checkForExistingEvent($event_type,$user_id,$post_id){

        $this->db->select('event_id');
        $this->db->from('event');
        $this->db->where(array(
          'event_type' => $event_type,
          'event_from_user_id' => $user_id,
          'event_post_id' => $post_id
          )
        );
        $q = $this->db->get();

        if($q->num_rows() > 0){
          $res = $q->result();
          return $res[0]->event_id;
        } else {
          return false;
        }
    }

    function clear($user_id,$post_id){
        $this->db->where('event_to_user_id', $user_id);
        $this->db->where('event_post_id', $post_id);
        if($this->db->delete('event')) {
            return true;
        } else {
            return false;
        }
    }

}

?>