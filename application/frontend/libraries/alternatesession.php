<?php

class AlternateSession {

  var $use_own_sessions = TRUE;

  public function __construct($params = array()){
    $this->CI =& get_instance();
  }
  
  public function session_exists()
  {
    if($this->CI->config->item('use_own_sessions')){

      if ($session = $this->CI->rest->get('auth/session/', array('id'=>$this->CI->session->userdata('sid'))))
      {
        if ($session->request_status == true)
        {
          return $session->user;
        } else
        {
          $this->CI->session->sess_destroy();
          return false;
        }
      }

    } else{
      echo "BOF";
    }
  }

}

?>