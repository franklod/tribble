<?if(!empty($colors)):?>    
<h3>Most common colors</h3>
<hr />        
<ul class="color-scheme">
<?foreach($colors as $color):?>    
  <li style="background-color: <?=$color->HEX?>;" title="<?=$color->HEX?>">
    <a href="<?=site_url('color/'.substr($color->HEX, 1))?>" title="<?=$color->HEX?>"></a>
  </li>
<?endforeach;?>      
</ul>
<?endif;?> 