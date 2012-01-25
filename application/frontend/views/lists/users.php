<?
  $alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
?>
<div class="g75">
  <div class="inner-wrapper">
  <ul class="tags list">
	<? foreach($user_list as $user): ?>
    <li>
       <a href="<?=site_url('user/'.$user->user_id)?>"><strong><?=$user->user_name?></strong><span class="count"><?=$user->post_count?></span></a>
    </li>  
  <? endforeach; ?>
  </ul>
  </div>
</div>