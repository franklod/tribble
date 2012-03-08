<div class="g75 login">
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
</div>