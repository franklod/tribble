<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tribbles extends CI_Controller {

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
  
  public function tribble(){
    
  }
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */