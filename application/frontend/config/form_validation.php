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
    'upload_image' => array(
      array(
        'field' => 'post_title',
        'label' => 'Title',
        'rules' => 'required'
      ),
      array(
        'field' => 'post_text',
        'label' => 'Description',
        'rules' => 'required'
      ),
      array(
        'field' => 'post_tags',
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