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
                                    
}

?>