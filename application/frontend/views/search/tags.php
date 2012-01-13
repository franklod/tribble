<div class="g75">
  <div class="inner-wrapper">
  <h3>We found <?=$count?> posts tagged as &ldquo;<?=$tag?>&raquo;</h3>
  <hr />
  <ul class="tribbles" style="overflow: hidden;">
	<? foreach($posts as $post): ?>
    <li class="space"> 
      <div class="box">     
        <div class="tribble-img">
          <a href="<?=site_url("/view/".$post->id)?>" class="tribble-hover">
            <span class="title"><?= character_limiter($post->title,12)?></span>
            <span class="desc"><?=word_limiter($post->text,20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($post->ts));?></em>              
          </a>
          <img src="<?=getThumb($post->image)?>"  alt="<?=$post->title?>" />
        </div>
        <div class="tribble-tools">
          <p class="ico"><a href="" class="comments"><?=$post->replies?></a>Comments</p>
          <p class="ico"><a href="" class="likes"><?=$post->likes?></a>likes</p>  
          <p class="ico"><a href="" class="rebound"><?=$post->likes?></a>likes</p>     
        </div>        
      </div>
      <div class="tribble-user-info">
        <a href="/">
          <img name="avatar" src="<?= (!empty($post->avatar)) ? $post->avatar : '/assets/images/avatar.jpg' ?>" width="18" height="18" alt=""/><?=$post->username?>
        </a>
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
  <hr />
  <?=$paging?>  
  </div>
</div>