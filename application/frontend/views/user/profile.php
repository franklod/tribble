<div class="g75">
  <?if($profile):?>
  	<p><?=get_gravatar($profile->user_email,80)?></p>
  	<p>Name: <?=$profile->user_name?></p>
    <p>Email: <?=$profile->user_email?></p>    
    <p>Biography: <?=$profile->user_bio?></p>    
  <?endif;?>
  	<p><a href="<?=site_url('user/edit')?>" class="defaultBtn btn_send">Edit your profile</a></p>
  	<p><a href="<?=site_url('user/password')?>" class="defaultBtn btn_send">Change your password</a></p>
    <p><a href="<?=site_url('user/delete')?>" class="defaultBtn btn_delete">Delete your account</a></p>
</div>