<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tribbles
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */
class Tribbles extends CI_Controller {
  
  public function __construct()
  {
      parent::__construct();
      $this->output->enable_profiler(TRUE);
  }

	public function index()
	{
    $data = array(
      'title' => 'Tribble',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);
	}
  
  public function newer()
	{
    $data = array(
      'title' => 'Tribble - Newer',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);
	}
  
  public function buzzing()
	{
    $data = array(
      'title' => 'Tribble - Buzzing',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);
	}
  
  public function loved()
	{
    $data = array(
      'title' => 'Tribble - Loved',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('home/index.php',$data);
    $this->load->view('common/page_end.php',$data);
	}
  
  public function create(){
    
  
    // check if the form was posted          
    if(isset($_POST['createTribble'])){
      
      // get the uid from the session data and hash it to be used as the user upload folder name       
      $uid = do_hash($this->session->userdata('uid'));
      
      // load the tribble model
      $this->load->model('Tribbles_model','trModel');
      
      // try to write the tribble data into the db
      if($tribbleId = $this->trModel->writeTribbleData()){
        
        $tags = array('tags_tribble_id'=>$tribbleId,'tags_content'=>$this->input->post('trTags'));
        
        if($this->trModel->writeTags($tags)){
          
        } else {
          
        }
        
        // set the upload configuration
        $ulConfig['upload_path'] = './data/'.$uid.'/';
    		$ulConfig['allowed_types'] = 'jpg|png';
    		$ulConfig['max_width']  = '400';
    		$ulConfig['max_height']  = '300';
        
        // load the file uploading lib and initialize
    		$this->load->library('upload', $ulConfig);
        $this->upload->initialize($ulConfig);
        
        // check if upload was successful and react        
        if (!$this->upload->do_upload()){                  
    			$error = array('error' => $this->upload->display_errors());
    		} else {
    			$data = array('upload_data' => $this->upload->data());       
          // set the data to write in db;
          $imageData = array('image_tribble_id'=>$tribbleId,'image_path'=>$ulConfig['upload_path'].$data['upload_data']['file_name'],'image_palette'=>json_encode(getImageColorPalette($data['upload_data']['full_path'])));
          
          if($this->trModel->writeImageData($imageData)){
            echo "image data written";
          } else {
            echo "image data not written";  
          }
  		  }
                 
      } else {
        
      }
      
    }
    
    $data = array(
      'title' => 'Tribble - Loved',
      'description' => 'A design content sharing and discussion tool.',
      'keywords' => 'Tribble'    
    );
    
    $this->load->view('common/page_start.php',$data);
    $this->load->view('common/top_navigation.php',$data);
		$this->load->view('tribbles/create.php',$data);
    $this->load->view('common/page_end.php',$data);      
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */