<?if(!empty($colors)):?>    
<h3>Most common colors</h3>
<hr />        
<ul class="color-scheme">
<?foreach($colors as $color => $count):?>    
  <li style="background-color: <?=$color?>;" title="<?=$color?>">
    <a href="<?=site_url('color/'.substr($color, 1))?>" title="<?=$color?>"></a>
  </li>
<?endforeach;?>      
</ul>
<?endif;?> 