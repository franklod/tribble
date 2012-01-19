<div class="g75">
  <div class="inner-wrapper">
  <ul class="tags list">
	<? foreach($tag_list as $item => $item_count): ?>
    <li> 
       <a href="<?=site_url('tag/'.$item)?>"><strong><?=$item?></strong><span class="count"><?=$item_count?></span></a>
    </li>  
  <? endforeach; ?>
  </ul>
  </div>
</div>
 
