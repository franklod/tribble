<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notifications extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load the rest client spark
        $this->load->spark('restclient/2.0.0');
        // Run some setup
        $this->rest->initialize(array('server' => api_url()));
    }

    public function get()        
    {                        
        
        $session = $this->alternatesession->session_exists();

        if($session)
        {
          $data['user'] = $session;
        } else
        {            
            $this->session->sess_destroy();
            redirect(site_url('/'));            
        }

        $notification = $this->rest->get('events/notifications',array('user_id'=>$session->user_id));
        echo json_encode($notification);

    }


}

?>