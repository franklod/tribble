<div id="headerContainer">
    <div id="header">
      <h1><a href="<?=site_url()?>">Tribble</a></h1>
      <div id="login">
        <ul>
          <?if(!$this->session->userdata('uid')):?>
          <li><a href="<?=site_url('/user/signup/')?>">Sign Up</a></li>
          <li><a href="<?=site_url('/auth/login/'.uri_to_string(uri_string()))?>" class="defaultBtn btn_send">Log in</a></li>
          <?else:?>
          <li><a href="<?=site_url('/user/profile/')?>"><?=$user->user_realname?></a></li>
          <li><a href="<?=site_url('/auth/logout/'.uri_to_string(uri_string()))?>" class="defaultBtn btn_send">Logout</a></li>
          <?endif;?>
        </ul>
      </div>
    </div>
</div>
