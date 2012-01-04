<?php

class User_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
    }
    
  function checkUserLogin(){      
    $query = $this->db->get_where('users',array('user_email'=>$this->input->post('email',TRUE),'user_password'=>$this->encrypt->sha1($this->encrypt->sha1($this->input->post('password',TRUE)))));
    if($query->num_rows() == 1){       
      return $query->result(); 
    } else {
      return false;
    }       
  }
    
  function createNewUser(){
    
    $result = '';
    
    $this->user_email = $this->input->post('email',TRUE);
    $this->user_password = $this->encrypt->sha1($this->encrypt->sha1($this->input->post('password',TRUE)));
    $this->user_realname = $this->input->post('realname',TRUE);
    $this->user_bio = $this->input->post('bio',TRUE);
          
    // check if the email already exists
    $query = $this->db->get_where('users', array('user_email' => $this->input->post('email',TRUE)));
    if ($query->num_rows() > 0){
      // user exists: return error message
      $result->error = "We already have that email on our database?";
      return $result;
    } else {
      // user is new: write to db and return uid
      if(!$this->db->insert('users',$this)){
        // error while writing to db: return error message
        $result->error = "We're sorry but the database dwarf made a mistake. The dungeon master has been notified";          
        return $result;        
      } else {
        // everything went well. commit the transaction and return the uid hash
        $result->user_hash = do_hash($this->user_email);        
        return $result;
      }
    }            
  }
  
  function getUserData($uid){
    $query = $this->db->get_where('users',array('user_id' => $uid));
    $result = $query->result();
    return $result;
  }
  
}

?>