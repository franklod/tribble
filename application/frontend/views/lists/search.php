<div class="g75">
  <div class="inner-wrapper">
  <h3>We found <?=$results?> posts containing <em><?=$search_text?>!</em></h3>
  <hr />
  <ul class="tribbles" style="overflow: hidden;">
	<? foreach($tribbles as $tribble): ?>
    <li class="space"> 
      <div class="box">     
        <div class="tribble-img">
          <a href="<?=site_url("/view/".$tribble->id)?>" class="tribble-hover">
            <span class="title"><?= character_limiter($tribble->title,12)?></span>
            <span class="desc"><?=word_limiter($tribble->text,20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?></em>              
          </a>
          <img src="<?=getThumb($tribble->image)?>"  alt="<?=$tribble->title?>" />
        </div>
        <div class="tribble-tools">
          <p class="ico"><a href="" class="comments"><?=$tribble->replies?></a>Comments</p>
          <p class="ico"><a href="" class="likes"><?=$tribble->likes?></a>likes</p>  
          <p class="ico"><a href="" class="rebound"><?=$tribble->likes?></a>likes</p>     
        </div>        
      </div>
      <div class="tribble-user-info">
        <a href="/">
          <img name="avatar" src="<?= (!empty($tribble->avatar)) ? $tribble->avatar : '/assets/images/avatar.jpg' ?>" width="18" height="18" alt=""/><?=$tribble->username?>
        </a>
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
  <hr />
  <?=$paging?>  
  </div>
</div>