<?php

class Auth_api_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function checkUserLogin($email,$password)
    {
        $this->db->select('
          user_id,
          user_realname as user_name,
          user_email
        ');        
        $this->db->from('user');
        $this->db->where(array('user_email'=>$email,'user_password'=>$password));        
        $query = $this->db->get();
        
        if($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function checkUserCorpId($corp_id)
    {
        $this->db->select('
          user_id,
          user_realname as user_name,
          user_email
        ');
        $this->db->from('user');
        $this->db->where( array( 'user_corp_id' => $corp_id ) );
        $query = $this->db->get();

        if($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>
