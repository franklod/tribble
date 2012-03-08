<?if(!empty($users)):?>
<h3>Most active designers</h3>
<hr />
<ul class="tags list users">
  <?foreach($users as $user):?>
    <li>      
      <a href="<?=site_url('user/'.$user->user_id.'-'.url_title($user->user_name))?>">
        <?= get_gravatar($user->user_email,18) ?>
        <strong style="margin-left: 8px;"><?=$user->user_name?></strong><span class="count"><?=$user->post_count?></span>
        <span class="percentage-bar" style="width:%"></span>
      </a>
    </li>
  <?endforeach;?>
</ul>
<?endif;?>