<div id="headerContainer">
    <div id="header">
      <h1><a href="<?=site_url()?>">Tribble</a></h1>
      <div id="login">
        <ul>
          <?if(!isset($user)):?>
          <li><a href="<?=site_url('/user/signup/')?>">Sign Up</a></li>
          <li><a href="<?=site_url('/login'.uri_string())?>" class="defaultBtn btn_send">Log in</a></li>
          <?else:?>
          <li><a href="<?=site_url('/user/profile/'.url_title($user->user_name))?>"><?=$user->user_name?></a></li>
          <li><a href="<?=site_url('/logout'.uri_string())?>" class="defaultBtn btn_send">Logout</a></li>
          <?endif;?>
        </ul>
      </div>
    </div>
</div>