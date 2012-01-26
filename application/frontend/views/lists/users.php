<?
  $alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
?>
<div class="g75">
  <div class="inner-wrapper">
  <ul class="tags list">
	<? foreach($user_list as $user): ?>
    <li style="line-height: 56px;">
       <a href="<?=site_url('user/'.$user->user_id.'-'.url_title($user->user_name))?>">
        <?=get_gravatar($user->user_email,54)?>
        <strong style="margin-left: 8px;"><?=$user->user_name?></strong><span class="count"><?=$user->post_count?></span></a>
    </li>  
  <? endforeach; ?>
  </ul>
  </div>
</div>