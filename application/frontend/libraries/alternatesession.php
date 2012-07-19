<?php

class AlternateSession {

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

      // Check if there is an SSO session.
      if( $_SERVER[ "HTTP_SHIB_SESSION_ID" ] ) {

        $corp_id = $_SERVER["HTTP_SSOMAIL"];

	// Check if the corporate ID from Shibboleth belong to any of our users.
        $user_data = $this->CI->rest->post( 'auth/sso_login/', array( 'corp_id' => $corp_id ) );

        if( $user_data->request_status == true ) {

          // Create a session for our user.
          $session_data = array(
            'user_id'     => $user_data->user[0]->user_id,
            'user_name'   => $user_data->user[0]->user_name,
            'user_email'  => $user_data->user[0]->user_email,
            'user_avatar' => false );

          $session_id = $this->CI->rest->put( 'auth/session', $session_data );

          if( $session_id->request_status == true ) {

            // Load the session just created.
            $session = $this->CI->rest->get('auth/session/', array( 'id' => $session_id->id ) );

            if( $session->request_status == true ) {
              // Set the session ID and return the authenticated user data.
              $this->CI->session->set_userdata( array( 'sid' => $session_id->id ) );
              return $session->user;
            }
          }
        }
      }
      return false;

    }
  }

}

?>
