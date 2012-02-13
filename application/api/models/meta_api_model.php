<?php

class Meta_API_model extends CI_Model {
      
    function getTags(){
      $this->db->select('tag_content');
      $this->db->from('tag');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }
    
    function getColors(){
      $this->db->select('image_palette');
      $this->db->from('image');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }
    
    function getUsers()      {
      $this->db->select('
        tr_user.user_id,
        tr_user.user_realname as user_name,
        tr_user.user_email,
        COUNT(tr_post.post_id) AS post_count
      ');
      $this->db->from('tr_user');
      $this->db->join('tr_post','tr_user.user_id = tr_post.post_user_id','inner');
      $this->db->group_by('
        tr_user.user_id,
        tr_user.user_realname,
        tr_user.user_email
      ');
      $this->db->order_by('post_count','DESC');
      $this->db->limit(10);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      } 
    }
                                    
}

?>