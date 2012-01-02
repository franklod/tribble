<div class="g75">
  <ul class="tribbles">
	<? foreach($tribbles as $tribble): ?>
    <li class="space"> 
    <div class="box">     
      <div class="tribble-img">
        <a href="<?=site_url("/tribble/view/".$tribble->id)?>" class="tribble-hover">
          <span class="title"><?=$tribble->title?></span>
          <span class="desc"><?=$tribble->text?></span>
          <em><?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?></em>              
        </a>
        <img src="<?=getThumb($tribble->image)?>" width="195" height="146" alt="<?=$tribble->title?>" />
      </div>
      <div class="tribble-tools">
 
        <p class="ico"><span class="comments"><?=$tribble->comments?></span>Comments</p>
        <p class="ico"><span class="likes"><?=$tribble->likes?></span>likes</p>     
      </div>
      <p class="tribble-user-info"><?=$tribble->user?></p>  
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
</div>