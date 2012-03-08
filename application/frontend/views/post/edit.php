<div class="g75 box">
<h2>Edit your post</h2>
<hr>
<img src="<?=cdn_url($post->post_image_path)?>" /> 
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
<?=form_open_multipart(site_url('edit/'.$post->post_id.'-'.url_title($post->post_title)),array('id'=>'upload'))?>
<?=form_fieldset()?>
<?=form_hidden('post_id',$post->post_id)?>
<div class="e_wrap <?=(form_error('post_title') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Title','post_title')?>
<?=form_input(array('name'=>'post_title','id'=>'post_title','placeholder'=>'Give your work a nice title','value'=>$post->post_title))?>
<?=(form_error('post_title') == TRUE) ? form_error('post_title') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('post_text') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Description','post_text')?>
<?=form_textarea(array('name'=>'post_text','id'=>'post_text','placeholder'=>'Write a short comment or description about your work','value'=>$post->post_text))?>
<?=(form_error('post_text') == TRUE) ? form_error('post_text') : ''; ?>
</div>
<div class="e_wrap <?=(form_error('post_tags') == TRUE) ? 'error' : ''; ?>">
<?=form_label('Tags','post_tags')?>
<?=form_input(array('name'=>'post_tags','id'=>'post_tags','placeholder'=>'Use comma separated expressions to tag your work','value'=>set_value('post_tags')))?>
<?=(form_error('post_tags') == TRUE) ? form_error('post_tags') : ''; ?>
</div>
<div class="e_wrap">
<?=form_submit(array('name'=>'createTribble','id'=>'createTribble','value'=>'Edit post','class'=>'btn_success'))?>
</div>
<?=form_fieldset_close()?>
<?=form_close()?>
</div>
