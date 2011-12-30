<h1><?=$tribble->title?></h1>
<p><?=$tribble->text?></p>
<p><img src="<?=$tribble->image?>" /></p>
<p><?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?></p>
<p>likes: <?=$tribble->likes?></p>
<ul>
  <? $tags = explode(',',$tribble->tags); ?>
  <?foreach($tags as $tag):?>
  <li><a href="#"><?=$tag?></a></li>  
  <?endforeach?>  
</ul>
<ul>
  <?foreach(json_decode($tribble->palette) as $color):?>
    <li style="background: <?=$color?>;">&nbsp;</li>
  <?endforeach;?>
</ul>
<?if(!$replies):?>
<h4>It seems no one has replied to this tribble</h4>
<p>Beat them to the punch!</p>
<?endif;?>