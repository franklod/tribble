<div id="main">
<?=form_open_multipart('/tribbles/create')?>
<div class="e_wrap">
<?=form_label('image','userfile')?>
<?=form_upload(array('name'=>'userfile','id'=>'userfile'))?>
</div>
<div class="e_wrap">
<?=form_label('Title','trTitle')?>
<?=form_input(array('name'=>'trTitle','id'=>'trTitle','placeholder'=>'Give your work a nice title'))?>
</div>
<div class="e_wrap">
<?=form_label('Title','trText')?>
<?=form_textarea(array('name'=>'trText','id'=>'trText','placeholder'=>'Write a short comment or description about your work'))?>
</div>
<div class="e_wrap">
<?=form_label('Tags','trTags')?>
<?=form_input(array('name'=>'trTags','id'=>'trTags','placeholder'=>'Use comma separated expressions to tag your work'))?>
</div>
<div class="e_wrap">
<?=form_submit(array('name'=>'createTribble','id'=>'createTribble','value'=>'Tribble','class'=>'btn_sucess'))?>
</div>
<?=form_close()?>
</div>