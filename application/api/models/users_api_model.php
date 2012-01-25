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

    function getUserList(){
      $this->db->select('
        tr_user.user_realname AS user_name,
        tr_user.user_id,
        tr_user.user_avatar,
        COUNT(tr_post.post_id) AS post_count
      ');
      $this->db->from('user');
      $this->db->join('post','tr_user.user_id = tr_post.post_user_id','inner');
      $this->db->group_by('
        tr_user.user_realname,
        tr_user.user_id,
        tr_user.user_avatar
      ');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return false;
      }
    }
    
    function getUserProfile($user_id){      
      $this->db->select('user_id,user_email,user_realname as user_name,user_bio, user_avatar');
      $this->db->from('user');
      $this->db->where(array('user_id'=>$user_id));      
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
      $query = $this->db->get_where('user',array('user_id'=>$user_id,'user_password'=>$this->encrypt->sha1($this->encrypt->sha1($old_pass))));
      if($query->num_rows() == 1){
        return true;
      } else {
        return false;
      }
    }

    function updateUserPassword($new_pass,$user_id){      
      $object = array('user_password'=>$this->encrypt->sha1($this->encrypt->sha1($new_pass)));
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