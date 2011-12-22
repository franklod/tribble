<?
$pages = array(
  'Home' => '',
  'Newer' => 'tribbles/newer',
  'Buzzing' => 'tribbles/buzzing',
  'Loved' => 'tribbles/loved'
);
?>

<div id="topNavigation">
  <ul>
  <? foreach($pages as $name => $uri): ?>
    <li><a href="<?=base_url().$uri?>"><?=$name?></a></li>
  <? endforeach; ?>
  asjhd kasjhd
  </ul>
</div>