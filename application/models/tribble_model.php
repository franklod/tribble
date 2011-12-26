<?php

class Tribble_model extends CI_Model {

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
    
    function writeImage(){
      
    }
    
    function post(){
      $data = array(
         'tribble_parent' => 0 ,
         'tribble_text' => $this->input->post('trText'),
         'tribble_user_id' => $this->session->userdata('uid'),
         'tribble_views' => 'tribble_views+1';
      );
      
      if($this->db->insert('tribbles', $data)){
        return $postid;
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