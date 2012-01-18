<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH . '/libraries/REST_Controller.php';

class Docs extends CI_Controller
{

  var $data = array();

  public function __construct()
  {
    parent::__construct();
  }
  
  public function index(){
    $data['api_site_name'] = $this->config->item('api_site_name');
    $data['api_site_url'] = $this->config->item('api_site_url');    
    $this->load->view('documentation',$data);
  }    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */