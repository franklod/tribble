<div id="main">
<h1>Create a new Tribble</h1>
<p>Use the form to create a new tribble and remember to give you work a proper title, description and a couple of tags.</p>
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
<?=form_fieldset('',array('class'=>'box g70'))?>
<p class="note"><strong>Note:</strong> image size is restricted to 400 x 300 pixels.</p>
<?=form_open_multipart('/tribble/upload')?>
<div class="e_wrap">
<?=form_label('Image','image')?>
<?=form_upload(array('name'=>'image','id'=>'image'))?>
</div>
<div class="e_wrap">
<?=form_label('Title','title')?>
<?=form_input(array('name'=>'title','id'=>'title','placeholder'=>'Give your work a nice title'))?>
</div>
<div class="e_wrap">
<?=form_label('Description','text')?>
<?=form_textarea(array('name'=>'text','id'=>'text','placeholder'=>'Write a short comment or description about your work'))?>
</div>
<div class="e_wrap">
<?=form_label('Tags','tags')?>
<?=form_input(array('name'=>'tags','id'=>'tags','placeholder'=>'Use comma separated expressions to tag your work'))?>
</div>
<div class="e_wrap txtRight">
<?=form_submit(array('name'=>'createTribble','id'=>'createTribble','value'=>'Create Tribble','class'=>'btn_success'))?>
</div>
<?=form_fieldset_close()?>
<?=form_close()?>
</div>