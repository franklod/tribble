<?if(!empty($colors)):?>    
<h3>Most common colors</h3>
<hr />        
<ul class="color-scheme">
<?foreach($colors as $color => $count):?>    
  <li style="background-color: <?=$color?>;"><a title="<?=$color?>" href="<?=site_url('/colors/'.$color)?>"><?=$count?></a></li>
<?endforeach;?>      
</ul>
<?endif;?> 