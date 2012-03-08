<div class="g75">
  <div class="inner-wrapper">
  <ul class="posts" style="overflow: hidden;">
	<? foreach($posts as $post): ?>
    <li class="space"> 
      <div class="box">     
        <div class="post-img">
          <a href="<?=site_url("/view/".$post->post_id.'-'.url_title($post->post_title))?>" class="post-hover">
            <span class="title"><?= character_limiter($post->post_title,12)?></span>
            <span class="desc"><?=word_limiter(str_replace('-', '&#8209;', $post->post_text),20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?></em>             
          </a>
          <img src="<?=cdn_url(getThumb($post->post_image_path))?>"  alt="<?=$post->post_title?>" />
        </div>
        <div class="post-tools">
            <p class="ico"><span class="comments" title="There are <?=$post->post_reply_count?> replies to this post"><?=$post->post_reply_count?></span>Comments</p>
            <p class="ico"><span class="likes" title="<?=$post->post_like_count?> users like this post"><?=$post->post_like_count?></span>likes</p>  
          <!-- <p class="ico"><a href="" class="rebound"><?=$post->post_like_count?></a>likes</p>    -->
        </div>        
      </div>
      <div class="post-user-info">
        <a href="<?=site_url('user/'.$post->user_id.'-'.url_title($post->user_name))?>">
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
 
