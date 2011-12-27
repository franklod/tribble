<div id="main">
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
	<?=form_open('user/login',array('class'=>'g75'))?>
  <div class="e_wrap">
    <?=form_label('Email','email')?>
    <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>''))?>
  </div>
  <div class="e_wrap">
    <?=form_label('Password','password')?>
    <?=form_password(array('name'=>'password','id'=>'password','placeholder'=>'Your password','value'=>''))?>
  </div>
  <div class="e_wrap">
    <?=anchor('/user/signup','Signup')?>
    <?=form_submit(array('name'=>'doLogin','id'=>'doLogin','value'=>'Login','class'=>'btn_success'))?>
  </div>
  <?=form_close()?>
  
</div>