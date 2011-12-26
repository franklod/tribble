<div id="main">
	<?=form_open('user/create',array('class'=>'g75'))?>
  <div class="e_wrap">
    <?=form_label('Email','email')?>
    <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>''))?>
  </div>
  <div class="e_wrap">
    <?=form_label('Password','password')?>
    <?=form_password(array('name'=>'password','id'=>'password','placeholder'=>'Your password','value'=>''))?>
  </div>
  <div class="e_wrap">
    <?=form_label('Real name')?>
    <?=form_input(array('name'=>'realname','id'=>'realname','placeholder'=>'Your real name','value'=>''))?>
  </div>
  <div class="e_wrap">
    <?=form_label('Some words about you','bio')?>
    <?=form_textarea(array('name'=>'bio','id'=>'bio'))?>
  </div>
  <div class="e_wrap">
    <?=form_submit(array('name'=>'doRegister','id'=>'doRegister','value'=>'Register','class'=>'btn_sucess'))?>
  </div>
  <?=form_close()?>
</div>