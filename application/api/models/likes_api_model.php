<?php
  
class Likes_API_model extends CI_Model {
      
    function add_like($post_id,$user_id){      
      
      $data = array('like_post_id'=>$post_id,'like_user_id'=>$user_id);
      $query = $this->db->insert('like',$data);            
      if($query == true){
        return true;
      } else {
        return false;
      }
         
    }
    
    function remove_like($post_id,$user_id){      
      $data = array('like_post_id'=>$post_id,'like_user_id'=>$user_id);
      $query = $this->db->delete('like',$data);      
      if($query == true){
        return true;
      } else {
        return false;
      }
         
    }
                                    
} 
  
?>