<? $tribble = $tribble[0]; ?>
<? $tags = explode(',',$tribble->tags); ?>
<h1><?=$tribble->title?></h1>
<p><?=$tribble->text?></p>
<p><img src="<?=$tribble->image?>" /></p>
<p><?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?></p>
<p>likes: <?=$tribble->likes?></p>
<ul>
  <?foreach($tags as $tag):?>
  <li><a href="#"><?=$tag?></a></li>  
  <?endforeach?>  
</ul>
<ul>
  <?foreach(json_decode($tribble->palette) as $color):?>
    <li style="background: #<?=$color?>;">&nbsp;</li>
  <?endforeach;?>
</ul>