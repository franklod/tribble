<?php

class Auth_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function checkUserLogin()
    {
        $query = $this->db->get_where('user', array('user_email' => $this->input->post('email', true), 'user_password' => $this->encrypt->sha1($this->encrypt->sha1($this->input->post('password', true)))));
        if($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

}

?>