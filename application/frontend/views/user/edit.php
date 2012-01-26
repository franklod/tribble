<div class="g75 box">
  <h2>Edit you user profile</h2>
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
	<?=form_open('user/profile/edit',array('class'=>'g75'))?>    
  <div class="e_wrap <?=(form_error('realname') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Real name')?>
    <?=form_input(array('name'=>'realname','id'=>'realname','placeholder'=>'Your real name','value'=>$profile->user_name))?>
    <?=(form_error('realname') == TRUE) ? form_error('realname') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('user_email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Email','email')?>
    <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>$profile->user_email))?>
    <?=(form_error('email') == TRUE) ? form_error('email') : ''; ?>
  </div>  
  <div class="e_wrap <?=(form_error('bio') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Some words about you','bio')?>
    <?=form_textarea(array('name'=>'bio','id'=>'bio','value'=>$profile->user_bio))?>
    <?=(form_error('bio') == TRUE) ? form_error('bio') : ''; ?>
  </div>
  <div class="e_wrap">
    <a href="<?=site_url('/user/profile')?>" class="defaultBtn btn_info">Cancel</a>
    <?=form_submit(array('name'=>'save_profile','id'=>'save_profile','value'=>'Save changes','class'=>'btn_success'))?>
  </div>
  <?=form_close()?>
</div>