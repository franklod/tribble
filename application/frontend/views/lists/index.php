<div class="g75">
  <div class="inner-wrapper">
  <ul class="tribbles" style="overflow: hidden;">
	<? foreach($posts as $post): ?>
    <li class="space"> 
      <div class="box">     
        <div class="tribble-img">
          <a href="<?=site_url("/view/".$post->post_id)?>" class="tribble-hover">
            <span class="title"><?= character_limiter($post->post_title,12)?></span>
            <span class="desc"><?=word_limiter($post->post_text,20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?></em>              
          </a>
          <img src="<?=getThumb($post->post_image_path)?>"  alt="<?=$post->post_title?>" />
        </div>
        <div class="tribble-tools">
          <p class="ico"><a href="" class="comments" title="There are <?=$post->post_reply_count?> replies to this post"><?=$post->post_reply_count?></a>Comments</p>
          <p class="ico"><a href="" class="likes" title="<?=$post->post_like_count?> users like this post"><?=$post->post_like_count?></a>likes</p>  
          <p class="ico"><a href="" class="rebound"><?=$post->post_like_count?></a>likes</p>     
        </div>        
      </div>
      <div class="tribble-user-info">
        <a href="/">
          <img name="avatar" src="<?= (!empty($post->user_avatar)) ? $post->avatar : '/assets/images/avatar.jpg' ?>" width="18" height="18" alt=""/><?=$post->user_name?>
        </a>
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
  <hr />
  <?=$paging?>  
  </div>
</div>
 
