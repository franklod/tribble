<?php

$pages->auth->uri = 'auth/logout/' . uri_to_string(uri_string());
$pages->auth->text = 'logout';

?>
  <div id="header">
    <h1><a href="/">Tribble</a></h1>  
  <ul id="login">  
  <?if(!$this->session->userdata('uri')):?>
    <li class="sign"><a href="<?=site_url()."/user/signup"?>">Sign up</a></li>
    <li class="log"><a href="<?=site_url()."/auth/login"?>">Login</li>
  <?else:?>
    <li class="sign"><a href="<?=site_url()."/user/profile"?>">my profile</a></li>
    <li class="log"><a href="<?=site_url()."/auth/logout".uri_to_string(uri_string())?>">Logout</li>
  <?endif;?>  
  </ul>
  </div>
