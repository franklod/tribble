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

class Trash extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();

  }

  public function throw_put(){
    $trash_path = $this->put('trash_path')
    $this->load->model('Trash_api_model','mTrash');
    $this->mTrash->putInTrash($trash_path);
  }

  public function empty_get(){
    $this->load->model('Trash_api_model','mTrash');
    foreach($this->mTrash->getTrash() as $gunk){    
      $full_path = $this->config->item('app_path').$gunk->trash_path;      
        if(file_exists($full_path))
         if(unlink($full_path))
            echo $full_path . " was deleted.\n";
    }
    $this->mTrash->emptyTrash();
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */