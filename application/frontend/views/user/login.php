<div class="g75">
  <div class="inner-wrapper">
    <h2>Login</h2>
  <hr/>
  <? if(isset($error)): ?>
  <div class="alert-msg error">
    <p><?=$error?></p>
  </div>
  <? endif;?>
<<<<<<< HEAD
	<?=form_open($form_action,array('class'=>'g75','id'=>'login'))?>
=======
	<?=form_open($form_action)?>
>>>>>>> 3774a8faf6e0443dee8525e37b45a5b32efa0cdd
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
    <?=anchor('/signup','Signup',array('class'=>'defaultBtn float_right'))?>
    <?=form_submit(array('name'=>'doLogin','id'=>'doLogin','value'=>'Login','class'=>'defaultBtn btn_success float_right'))?>
  </div>
  <?=form_close()?>
 </div> 
</div>