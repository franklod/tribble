<div class="g75">
  <?if($profile):?>
  	<p><?=$profile->user_avatar?></p>
  	<p>Name: <?=$profile->user_name?></p>
    <p>Email: <?=$profile->user_email?></p>    
    <p>Biography: <?=$profile->user_bio?></p>    
  <?endif;?>
  	<p><a href="<?=site_url('user/edit')?>" class="defaultBtn">Edit your profile</a></p>
  	<p><a href="<?=site_url('user/password')?>" class="defaultBtn">Change your password</a></p>
    <p><a href="<?=site_url('user/delete')?>" class="defaultBtn">Delete your account</a></p>
</div>