<div class="g75">
<h2>Create a new Tribble</h2>
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
<?=form_open_multipart('/tribble/doupload')?>
<div class="e_wrap <?=(form_error('image_file') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Image','image_file')?>
<?=form_upload(array('name'=>'image_file','id'=>'image_file','value'=>set_value('image_file')))?>
<?=(form_error('image_file') == TRUE) ? form_error('image_file') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('title') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Title','title')?>
<?=form_input(array('name'=>'title','id'=>'title','placeholder'=>'Give your work a nice title','value'=>set_value('title')))?>
<?=(form_error('title') == TRUE) ? form_error('title') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('text') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Description','text')?>
<?=form_textarea(array('name'=>'text','id'=>'text','placeholder'=>'Write a short comment or description about your work','value'=>set_value('text')))?>
<?=(form_error('text') == TRUE) ? form_error('text') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('tags') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Tags','tags')?>
<?=form_input(array('name'=>'tags','id'=>'tags','placeholder'=>'Use comma separated expressions to tag your work','value'=>set_value('tags')))?>
<?=(form_error('tags') == TRUE) ? form_error('tags') : ''; ?>
</div>
<div class="e_wrap">
<?=form_submit(array('name'=>'createTribble','id'=>'createTribble','value'=>'Create Tribble','class'=>'btn_success'))?>
</div>
<?=form_fieldset_close()?>
<?=form_close()?>
</div>