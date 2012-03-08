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
    $trash_path = $this->put('trash_path');
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

  public function scrap_get(){

    $post_id = $this->get('post_id');

    $this->db->delete('post',array('post_id'=>$post_id));
    $this->db->delete('image',array('image_post_id'=>$post_id));
    $this->db->delete('like',array('like_post_id'=>$post_id));
    $this->db->delete('tag',array('tag_post_id'=>$post_id));
    $this->db->delete('palette',array('palette_post_id'=>$post_id));

  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */