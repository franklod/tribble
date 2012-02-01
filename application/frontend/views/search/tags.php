<div class="g75">
  <div class="inner-wrapper">
  <h3>We found <?=$count?> posts tagged as &ldquo;<?=$tag?>&rdquo;</h3>
  <hr />
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
  <hr />
  <?=$paging?>  
  </div>
</div>