<?php

class User_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function checkUserLogin()
    {      
      $query = $this->db->get_where('users',array('user_email'=>$this->input->post('email',TRUE),'user_password'=>$this->encrypt->sha1($this->encrypt->sha1($this->input->post('password',TRUE)))));
      if($query->num_rows() == 1){
        $res = $query->result(); 
        return $res[0];
      } else {
        return false;
      }       
    }

    function checkIfUserExists(){
      $query = $this->db->get_where('users', array('user_email' => $this->input->post('email',TRUE)));
      if ($query->num_rows() > 0){
        return true;
      } else {
        return false;
      }
    }

    function registerUser()
    {         
        $this->user_email = $this->input->post('email',TRUE);
        $this->user_password = $this->encrypt->sha1($this->encrypt->sha1($this->input->post('password',TRUE)));
        $this->user_realname = $this->input->post('realname',TRUE);
        $this->user_bio = $this->input->post('bio',TRUE);    
      
        if($this->db->insert('users',$this)){
          return true;
        } else {
          return false;
        }
              
    }
    

}

?>