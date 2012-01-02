<!-- replyform -->
<div class="comment-form">
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

<?=form_open_multipart('/tribble/reply/'.$tribble->id)?>
<?=form_fieldset('',array('class'=>'box g70'))?>
<div class="e_wrap">
<?=form_label('Image','image')?>
<?=form_upload(array('name'=>'image','id'=>'image'))?>
<p class="note"><strong>Note:</strong> image size is restricted to 400 x 300 pixels.</p>
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
<div class="e_wrap">
<?=form_submit(array('name'=>'reply','id'=>'reply','value'=>'Reply','class'=>'btn_success'))?>
</div>
<?=form_fieldset_close()?>
<?=form_close()?></div>
<!-- /replyform -->