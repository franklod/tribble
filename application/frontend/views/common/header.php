<div id="headerContainer">
    <div id="header">
      <h1><a href="<?=site_url()?>">Tribble</a></h1>
      <div id="login">
        <ul>
          <?if(!isset($user)):?>
          <li><a href="<?=site_url('/signup/')?>">Sign Up</a></li>
          <li><a href="<?=site_url('/login'.uri_string())?>" class="defaultBtn btn_send">Log in</a></li>
          <?else:?>
          <li><a href="<?=site_url('/user/profile')?>"><?=$user->user_name?></a></li>         
          <li id="notifications" style="display: none;" title="you have some stuff to look at, dude(ette)!">
            <a href="#">1</a>
            <ul id="messages" style="display: none;">
            </ul>
          </li>
          <li><a href="<?=site_url('/logout'.uri_string())?>" class="defaultBtn btn_send">Logout</a></li>
          <?endif;?>
        </ul>
      </div>
    </div>
</div>