<div class="g80">
  <ul class="tribbles">
	<? foreach($tribbles as $tribble): ?>
    <li class="box space">      
      <div class="tribble-img">
        <a href="<?=site_url("/tribbles/view/".$tribble->id)?>" class="tribble-hover">
          <span class="title"><?=$tribble->title?></span>
          <span class="desc"><?=$tribble->text?></span>
          <em><?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?></em>              
        </a>
        <img src="<?=getThumb($tribble->image)?>" width="200" height="150" alt="<?=$tribble->title?>" />
      </div>
      <div class="tribble-tools">
        <p>comments: <?=$tribble->comments?></p>
        <p>likes: <?=$tribble->likes?></p>        
      </div>
      <h5><?=$tribble->user?></h5>    
    </li>  
  <? endforeach; ?>
  </ul>
</div>