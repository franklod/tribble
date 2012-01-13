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
  
  <?=form_open_multipart('/comment/add')?>
  
  <?=form_hidden('user_id',$this->session->userdata('uid'))?>
  <?=form_hidden('post_id',$tribble->id)?>
  <?=form_fieldset('')?>
    <div class="e_wrap">
  <?=form_label('Comment','text')?>
  <?=form_textarea(array('name'=>'comment_text','id'=>'comment_text','placeholder'=>''))?>
  </div>
  <div class="e_wrap">
  <?=form_submit(array('name'=>'reply','id'=>'reply','value'=>'Comment','class'=>'btn_success'))?>
  </div>
  <?=form_fieldset_close()?>
  <?=form_close()?>
</div>
<!-- /replyform -->