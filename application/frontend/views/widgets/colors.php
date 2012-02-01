<?if(!empty($colors)):?>    
<h3>Most common colors</h3>
<hr />        
<ul class="color-scheme">
<?foreach($colors as $color => $count):?>    
  <li style="background-color: <?=$color?>;" title="<?=$color?>">&nbsp;</li>
<?endforeach;?>      
</ul>
<?endif;?> 