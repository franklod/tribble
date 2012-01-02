<div class="g75">
  <div class="tribble-container" >
    <div class="tribble-user-info">
      <p><span><img src="" width="42" height="42" /></span><a href="<?=site_url('user/view/'.$tribble->userid)?>"><?=$tribble->username?></a></p>
    </div>
    <div class="tribble-img-container box"> <img src="<?=$tribble->image?>" /></div>
    <div class="tribble-img-data">
      <h2>
        <?=$tribble->title?>
      </h2>
      <div class="tribble-date">
        <?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?>
      </div>
      <div class="tribble-desc">
        <?=$tribble->text?>
      </div>
      <div class="tribble-tools">
        <p class="ico"><a href="" class="likes">
          <?=$tribble->likes?></a>likes
        </p>
      </div>
      <h3>Tags</h3>
      <ul class="tags">
        <? $tags = explode(',',$tribble->tags); ?>
        <?foreach($tags as $tag):?>
        <li><a href="#">
          <?=$tag?>
          </a></li>
        <?endforeach?>
      </ul>
      <h3>Color Scheme</h3>
      <ul class="color-scheme">
        <?foreach(json_decode($tribble->palette) as $color):?>
        <li style="background: <?=$color?>;">&nbsp;</li>
        <?endforeach;?>
      </ul>
    </div>
    <div class="comments-list">
    <h3>It seems no one has replied to this tribble</h3>    
    <?if($replies):?>
    <ul>
      <li>
      </li>
    </ul>
    <?endif;?>    
    </div>
  </div>
</div>