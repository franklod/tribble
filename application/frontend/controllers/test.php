<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test extends CI_Controller
{

    public function __construct()
  {
      parent::__construct();
      
      // Load the rest client spark
      $this->load->spark('restclient/2.0.0');    
      // Load the library
      $this->load->library('rest');    
      // Run some setup
      $this->rest->initialize(array('server' => 'http://tribble.local/api.php/'));
      // load the pagination library
      $this->load->library('pagination');
              
      //$this->output->enable_profiler(TRUE);      
  }
  
  public function stuff($type){    
    print_r($this->rest->get('posts_rest/list/new/'.$type));    
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
