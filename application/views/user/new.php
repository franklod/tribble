<?


 
?>

<div id="main">
	<?=form_open(base_url().'user/create')?>
  <?=form_label('Email','email')?>
  <?=form_input(array('name'=>'email','id'=>'email','placeholder'=>'Your email','value'=>''))?>
  <?=form_label('Password','password')?>
  <?=form_password(array('name'=>'password','id'=>'password','placeholder'=>'Your password','value'=>''))?>
  <?=form_label('Real name')?>
  <?=form_input(array('name'=>'realname','id'=>'realname','placeholder'=>'Your real name','value'=>''))?>
  <?=form_label('Some words about you','bio')?>
  <?=form_textarea(array('name'=>'bio','id'=>'bio'))?>
  <?=form_submit(array('name'=>'doRegister','id'=>'doRegister','value'=>'Register'))?>
  <?=form_close()?>
</div>