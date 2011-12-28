<?php
  $config = array(
    'signup' => array(
      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email'
      ),
      array(
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required|min_length[6]'
      ),
      array(
        'field' => 'passwordchk',
        'label' => 'Password confirmation',
        'rules' => 'required|matches[password]'
      ),
      array(
        'field' => 'realname',
        'label' => 'Real name',
        'rules' => 'required'
      )  
    ),
    'upload' => array(
      array(
        'field' => 'imagefile',
        'label' => 'Image file',
        'rules' => 'required'
      ),
      array(
        'field' => 'trTitle',
        'label' => 'Title',
        'rules' => 'required|min_length[6]'
      ),
      array(
        'field' => 'trText',
        'label' => 'Description',
        'rules' => 'required'
      ),
      array(
        'field' => 'trTags',
        'label' => 'Tags',
        'rules' => 'required'
      ) 
    ),
    'login' => array(
      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required'
      ),
      array(
        'field' => 'password',
        'label' => 'Password',
        'rules' => 'required'
      ) 
    )
  );
?>