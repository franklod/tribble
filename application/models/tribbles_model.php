<?php

class Tribbles_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getNewer(){
                   
    }
    
    function getBuzzing(){
                   
    }
    
    function getLoved(){      
                   
    }
          
    function writeTribbleData(){
      $data = array(
         'tribble_parent' => 0 ,
         'tribble_text' => $this->input->post('trText'),
         'tribble_title' => $this->input->post('trTitle'),
         'tribble_user_id' => $this->session->userdata('uid'),
         'tribble_views' => 'tribble_views+1'
      );
      
      if($this->db->insert('tribbles', $data)){
        return $this->db->insert_id();
      } else {
        return false;
      }
      
    }
    
    function writeImageData($args){
      if($this->db->insert('images',$args)){
        return true;
      } else {
        return false;
      }          
    }
    
    function writeTags($args){
      if($this->db->insert('tags',$args)){
        return true;
      } else {
        return false;
      }          
    }
    
    function editPost(){
      
    }
    
    function deletePost(){
      
    }
    
    function reply(){
      
    }
    
    function rebound(){
      
    }
            
}

?>