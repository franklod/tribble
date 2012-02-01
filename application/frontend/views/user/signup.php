<div class="g75 login">
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
  <h2>Create your Tribble account</h2>
	<?=form_open('signup',array('class'=>'g75'))?>
  <div class="e_wrap <?=(form_error('realname') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Real name')?>
    <?=form_input(array('name'=>'realname','id'=>'realname','placeholder'=>'Your real name','value'=>set_value('realname')))?>
    <?=(form_error('realname') == TRUE) ? form_error('realname') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Email','email')?>
    <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>set_value('email')))?>
    <?=(form_error('email') == TRUE) ? form_error('email') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('password') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Password','password')?>
    <?=form_password(array('name'=>'password','id'=>'password','placeholder'=>'Your password','value'=>set_value('password')))?>
    <?=(form_error('password') == TRUE) ? form_error('password') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('passwordchk') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Password Confirmation','passwordchk')?>
    <?=form_password(array('name'=>'passwordchk','id'=>'passwordchk','placeholder'=>'Confirm your password','value'=>set_value('passwordchk')))?>
    <?=(form_error('passwordchk') == TRUE) ? form_error('passwordchk') : ''; ?>
  </div>  
  <div class="e_wrap <?=(form_error('bio') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Some words about you','bio')?>
    <?=form_textarea(array('name'=>'bio','id'=>'bio','value'=>set_value('bio')))?>
    <?=(form_error('bio') == TRUE) ? form_error('bio') : ''; ?>
  </div>
  <div class="e_wrap">
    <?=form_submit(array('name'=>'doSignup','id'=>'doSignup','value'=>'Signup','class'=>'btn_success'))?>
  </div>
  <?=form_close()?>
</div>