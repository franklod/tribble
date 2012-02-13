<div class="g75">
  <div class="inner-wrapper">
  <div class="user-banner box">
    <div class="avatar"><?=get_gravatar($email,64)?></div>
    <h2><?=$name?></h2>
    <p><?=$count?><?= ($count == 1) ? ' post' : ' posts' ?></p>
    <?if(!empty($bio)):?>
    <p><?=$bio?></p>
    <?endif;?>
  </div>  
  <hr />
  <?if($count > 0):?>
  <ul class="posts" style="overflow: hidden;">
  <? foreach($posts as $post): ?>
    <li class="space"> 
      <div class="box">     
        <div class="post-img">
          <a href="<?=site_url("/view/".$post->post_id.'-'.url_title($post->post_title))?>" class="post-hover">
            <span class="title"><?= character_limiter($post->post_title,12)?></span>
            <span class="desc"><?=word_limiter($post->post_text,20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?></em>              
          </a>
          <img src="<?=getThumb($post->post_image_path)?>"  alt="<?=$post->post_title?>" />
        </div>
        <div class="post-tools">
          <p class="ico"><a href="" class="comments"><?=$post->post_reply_count?></a>Comments</p>
          <p class="ico"><a href="" class="likes"><?=$post->post_like_count?></a>likes</p>  
          <p class="ico"><a href="" class="rebound">2</a>likes</p>     
        </div>        
      </div>
      <div class="post-user-info">
        <a href="<?=site_url('/user/'.$post->user_id.'-'.url_title($post->user_name))?>">
          <?=get_gravatar($post->user_email,18)?><?=$post->user_name?>
        </a>
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
  <?else:?>
  <h3><?=$name?> hasn't posted any work yet.</h3>
  <?endif?>
  <hr />
  <?=$paging?>  
  </div>
</div>