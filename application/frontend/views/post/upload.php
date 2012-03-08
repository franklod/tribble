<div class="g75 box">
<h2>Post a new image</h2>
<hr>
<p>You can only use jpeg or png files and image size is restricted to <b>800 x 600</b>  pixels.</p>
<p>This is simply to keep the storage needs as low as possible and to "encourage" designers to focus on the really important detail rather than posting whole layouts.</p>
<p>Remember, you can always post another image!</p>
<p>Be kind to youself, and others, and give proper title, tags and description to your work.</p>
<hr>
<? if(isset($errors)): ?>
<div class="alert-msg error">
  <?foreach($errors as $key => $message):?>
  <?=$message?>
  <?endforeach;?>
</div>
<? endif;?>
<? if(isset($success)): ?>
<div class="alert-msg success">
  <p><?=$success?></p>
</div>
<? endif;?>
<?=form_open_multipart('/upload',array('id'=>'upload'))?>
<?=form_fieldset()?>
<div class="e_wrap <?=(form_error('image_file') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Image','image_file')?>
<?=form_upload(array('name'=>'image_file','id'=>'image_file','value'=>set_value('image_file')))?>
<?=(form_error('image_file') == TRUE) ? form_error('image_file') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('post_title') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Title','post_title')?>
<?=form_input(array('name'=>'post_title','id'=>'post_title','placeholder'=>'Give your work a nice title','value'=>set_value('post_title')))?>
<?=(form_error('post_title') == TRUE) ? form_error('post_title') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('post_text') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Description','post_text')?>
<?=form_textarea(array('name'=>'post_text','id'=>'post_text','placeholder'=>'Write a short comment or description about your work','value'=>set_value('post_text')))?>
<?=(form_error('post_text') == TRUE) ? form_error('post_text') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('post_tags') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Tags','post_tags')?>
<?=form_input(array('name'=>'post_tags','id'=>'post_tags','placeholder'=>'Use comma separated expressions to tag your work','value'=>set_value('post_tags')))?>
<?=(form_error('post_tags') == TRUE) ? form_error('post_tags') : ''; ?>
</div>
<div class="e_wrap">
<?=form_submit(array('name'=>'createTribble','id'=>'createTribble','value'=>'Post image','class'=>'btn_success'))?>
</div>
<?=form_fieldset_close()?>
<?=form_close()?>
</div>