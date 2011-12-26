<div id="main">
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
    <?=anchor('/user/register','Register')?>
    <?=form_submit(array('name'=>'doLogin','id'=>'doLogin','value'=>'Login','class'=>'btn_sucess'))?>
  </div>
  <?=form_close()?>
  
</div>