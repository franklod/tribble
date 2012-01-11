<?php

class Meta_API_model extends CI_Model {
      
    function getTags(){
      $this->db->select('tags_content');
      $this->db->from('tags');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return false;
      }                                              
    }
    
    function getColors(){
      $this->db->select('image_palette');
      $this->db->from('images');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return false;
      }                                              
    }        
                                    
}

?>