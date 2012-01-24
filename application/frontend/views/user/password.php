<div class="g75 box">
  <h2>Change your password</h2>
  <hr>
  <? if(isset($error)): ?>
  <div class="alert-msg error">
    <p><?=$error?></p>
  </div>
  <? endif;?>
  <? if(isset($success)): ?>
  <div class="alert-msg success">
    <p><?=$success?></p>
  </div>
  <? endif;?>  
	<?=form_open('user/password/edit',array('class'=>'g75'))?>
  <div class="e_wrap <?=(form_error('email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Old password','old_password')?>
    <?=form_password(array('name'=>'old_password','id'=>'old_password'))?>
    <?=(form_error('old_password') == TRUE) ? form_error('old_password') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('New password','new_password')?>
    <?=form_password(array('name'=>'new_password','id'=>'new_password'))?>
    <?=(form_error('new_password') == TRUE) ? form_error('new_password') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Confirm new password','retype_new_password')?>
    <?=form_password(array('name'=>'retype_new_password','id'=>'retype_new_password'))?>
    <?=(form_error('old_password') == TRUE) ? form_error('retype_new_password') : ''; ?>
  </div>    
  <div class="e_wrap">
    <a href="<?=site_url('/user/profile')?>" class="defaultBtn btn_info">Cancel</a>
    <?=form_submit(array('name'=>'save_profile','id'=>'save_profile','value'=>'Save changes','class'=>'btn_success'))?>
  </div>
  <?=form_close()?>
</div>