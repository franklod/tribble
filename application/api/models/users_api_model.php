<?php

class Users_API_model extends CI_Model {
    
    function checkUserLiked($user_id,$post_id){
        $query = $this->db->get_where('like',array('like_user_id'=>$user_id,'like_post_id'=>$post_id));
        if($query->num_rows() == 0){
            return false;
        } else {
            return true;
        }
    }

    function createNewUser($user){
                    
      $query = $this->db->get_where('user', array('user_email' => $user['user_email']));

      if ($query->num_rows() > 0){
        return false;
      } else {
        $this->db->insert('user',$user);
          return  sha1($user['user_email']);
        } 
    }

    function getUserList(){
      $this->db->select('
        tr_user.user_realname AS user_name,
        tr_user.user_id,
        tr_user.user_email,
        (SELECT COUNT(1) FROM `tr_post` WHERE post_user_id = user_id AND post_is_deleted = 0) as post_count
      ');
      $this->db->from('user');
      $this->db->group_by('
        tr_user.user_realname,
        tr_user.user_id,
        tr_user.user_email
      ');
      $this->db->where(array('tr_user.user_is_deleted'=>0));
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return false;
      }
    }
    
    function getUserProfile($user_id){      
      $this->db->select('user_id,user_email,user_realname as user_name,user_bio');
      $this->db->from('user');
      $this->db->where(array('user_id'=>$user_id));      
      $this->db->where(array('user_is_deleted'=>0));
      $query = $this->db->get();
      if($query->num_rows() == 1){
        return $query->result();
      } else {
        return false;
      }
    }
    
    function updateProfile($user_id,$user_data){      
      $this->db->where(array('user_id'=>$user_id));
      $this->db->update('user',$user_data);
      return $this->db->affected_rows();
    }
    
    function checkIfUserExists($user_id){
      $query = $this->db->get_where('user',array('user_id'=>$user_id));
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      }
    }
    
    function getUserData($uid){
      $query = $this->db->get_where('user',array('user_id' => $uid));
      $result = $query->result();
      return $result;
    }

    function checkPasswordForUser($old_pass,$user_id){
      $query = $this->db->get_where('user',array('user_id'=>$user_id,'user_password'=>$old_pass));
      if($query->num_rows() == 1){
        return true;
      } else {
        return false;
      }
    }

    function updateUserPassword($new_pass,$user_id){      
      $object = array('user_password'=>$new_pass);
      $this->db->where(array('user_id'=>$user_id));
      $this->db->update('user',$object);
      if($this->db->affected_rows() == 1){
        return true;
      } else {
        return false;
      }      
    }
                            
}

?>