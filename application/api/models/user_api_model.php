<?php

class User_API_model extends CI_Model {
    
    function checkUserLiked($user_id,$post_id){
        $query = $this->db->get_where('likes',array('like_user_id'=>$user_id,'like_tribble_id'=>$post_id));
        if($query->num_rows() == 0){
            return false;
        } else {
            return true;
        }
    }
    
    function getUserProfile($user_id){      
      $this->db->select('user_id as id,user_email as email,user_realname as realname,user_bio as bio, user_avatar as avatar');
      $this->db->from('users');
      $this->db->where(array('user_id'=>$user_id));      
      $query = $this->db->get();
      if($query->num_rows() == 1){
        return $query->result();
      } else {
        return false;
      }
    }
    
    function updateProfile($user_id,$user_data){
      $this->db->update($user_data);
      $this->db->where(array('user_id'=>$user_id));
      $query = $this->db->get();
      if($query->affected_rows() == 1){
        return true;        
      } else {
        return false;
      }
    }
    
    function checkIfUserExists($user_id){
      $query = $this->db->get_where('users',array('user_id'=>$user_id));
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      }
    } 
                            
}

?>