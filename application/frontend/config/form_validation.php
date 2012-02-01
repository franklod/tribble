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
    'profile' => array(
      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email'
      ),
      array(
        'field' => 'realname',
        'label' => 'Real name',
        'rules' => 'required'
      )  
    ),
    'change_password' => array(
      array(
        'field' => 'old_password',
        'label' => 'Old password',
        'rules' => 'required'
      ),
      array(
        'field' => 'new_password',
        'label' => 'New password',
        'rules' => 'required|min_length[6]'
      ),
      array(
        'field' => 'retype_new_password',
        'label' => 'Confirm new password',
        'rules' => 'required|matches[new_password]'
      ),
    ),
    'upload_image' => array(
      array(
        'field' => 'image_file',
        'label' => 'Image',
        'rules' => 'file_required'
      ),
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
    ),
    'user_profile' => array(
      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required'
      ),
      array(
        'field' => 'realname',
        'label' => 'Name',
        'rules' => 'required'
      )
    )
  );
?>