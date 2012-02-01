<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Fakedata extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Fakedata_model', 'mFakedata');
  }

  public function users($num = 100)
  {

    $emails = $this->mFakedata->users($num);

    if ($result = $this->mFakedata->users($num))
    {
      if (@$result->error)
      {
        $data['error'] = $result->error;
        echo $data['error'] . "<br>";

      }
      

        foreach ($result->users as $user)
        {

          $user_hash = $user;
          $user_dir = $this->config->item('app_path') . '/data/' . $user_hash;

          echo $user_dir . "<br>";

          if (is_dir($user_dir))
          {
            echo $user_dir . ' already exists.<br>';
          } else
          {
            mkdir($user_dir, 0755);
            echo $user_dir . ' was created.<br>';
          }

        }

      }
    

  }
  
  
  public function posts($num){
    var_dump($this->mFakedata->posts($num));
  }

  public function update_colors($limit = 20,$offset = 0)
  {
    $this->mFakedata->update_colors($limit,$offset);
  }
  

}
?>