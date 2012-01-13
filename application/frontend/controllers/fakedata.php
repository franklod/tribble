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

  public function users($num)
  {

    $emails = $this->mFakedata->users($num);

    if ($result = $this->mFakedata->users($num))
    {
      if (@$result->error)
      {
        $data['error'] = $result->error;

      } else
      {

        foreach ($result->users as $user)
        {

          $user_hash = $user;
          $user_dir = "./data/" . $user_hash;

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

  }
  
  
  public function posts($num){
    var_dump($this->mFakedata->posts($num));
  }
  

}
?>