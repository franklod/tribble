<div class="g75 login">
  <? if(isset($error)): ?>
  <div class="alert-msg error">
    <p><?=$error?></p>
  </div>
  <? endif;?>
	<?=form_open($form_action,array('class'=>'g75'))?>
  <div class="e_wrap <?=(form_error('email') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Email','email')?>
    <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>''))?>
    <?=(form_error('email') == TRUE) ? form_error('email') : ''; ?>
  </div>
  <div class="e_wrap <?=(form_error('password') == TRUE) ? 'error' : ''; ?>">
    <?=form_label('Password','password')?>
    <?=form_password(array('name'=>'password','id'=>'password','placeholder'=>'Your password','value'=>''))?>
    <?=(form_error('password') == TRUE) ? form_error('password') : ''; ?>
  </div>
  <div class="e_wrap">
    <?=anchor('/user/signup','Signup',array('class'=>'defaultBtn btn_info '))?>
    <?=form_submit(array('name'=>'doLogin','id'=>'doLogin','value'=>'Login','class'=>'btn_success'))?>
  </div>
  <?=form_close()?>
  
</div>