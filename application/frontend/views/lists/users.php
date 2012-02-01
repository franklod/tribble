<div class="g75">
  <div class="inner-wrapper">
  <ul class="alphabet-links">
    <?foreach ($user_list as $initial => $user):?>
      <li><a href="#<?=$initial?>"><?=$initial?></a></li>
    <?endforeach;?>
  </ul>
  <ul class="tags list">
  <?foreach ($user_list as $initial => $users):?>
  <hr>
  <li style="font-size: 1.2em; color: #222;"><a class="initial" name="<?=$initial?>" id="<?=$initial?>"><strong><?=$initial?></strong></a></li>
	<? foreach($users as $user): ?>
    <li>
       <a href="<?=site_url('user/'.$user['user']->user_id.'-'.url_title($user['user']->user_name))?>">
        <?=get_gravatar($user['user']->user_email,18)?>
        <strong style="margin-left: 8px;"><?=$user['user']->user_name?></strong><span class="count"><?=$user['user']->post_count?></span></a>
    </li>  
  <? endforeach; ?>
  <? endforeach; ?>
  </ul>
  </div>
</div>