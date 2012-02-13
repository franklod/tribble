<div class="g75">
  <div class="inner-wrapper">
  <ul class="alphabet-links">
    <?foreach ($tag_list as $initial => $tag):?>
      <li><a href="#<?=$initial?>"><?=$initial?></a></li>
    <?endforeach;?>
  </ul>
  <ul class="tags list">
  <?foreach ($tag_list as $initial => $tags):?>
  <hr>
  <li style="font-size: 1.2em; color: #222;">
    <a class="initial" name="<?=$initial?>" id="<?=$initial?>"><strong><?=$initial?></strong></a>    
  </li>

  <? foreach($tags as $tag): ?>
    <li>
      <a href="<?=site_url('tag/'.convert_accented_characters($tag['item']))?>">
        <strong style="margin-left: 8px;"><?=$tag['item']?></strong><span class="count"><?=$tag['count']?></span>
        <span class="percentage-bar" style="width: <?=$tag['percent']?>%;"></span>
      </a>
    </li>  
  <? endforeach; ?>
  <? endforeach; ?>
  </ul>
  </div>
</div>