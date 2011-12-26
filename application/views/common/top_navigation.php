<?
$pages = array(
  'Home' => '',
  'Newer' => 'tribbles/newer',
  'Buzzing' => 'tribbles/buzzing',
  'Loved' => 'tribbles/loved',
  'Login' => 'user'
);
?>

<div id="topNavigation" class="blackMenu">
  <ul class="h_navigation">
  <? foreach($pages as $name => $uri): ?>
    <? if(current_url() == base_url().index_page()."/".$uri): ?>    
    <li class="active"><a href="<?=base_url().index_page()."/".$uri?>"><?=$name?></a></li>
    <? else: ?>
    <li><a href="<?=base_url().index_page()."/".$uri?>"><?=$name?></a></li>
    <? endif; ?>
  <? endforeach; ?>
  </ul>
</div>