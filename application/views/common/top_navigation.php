<?
$pages->home->uri = '';
$pages->home->text = 'home';
$pages->buzzing->uri = 'tribble/buzzing';
$pages->buzzing->text = 'buzzing';
$pages->loved->uri = 'tribble/loved';
$pages->loved->text = 'loved';
$pages->upload->uri = 'tribble/upload';
$pages->upload->text = 'upload';
if($this->session->userdata('uid')){
  $pages->upload->uri = 'tribble/upload';
  $pages->upload->text = 'upload';
}

?>

<div id="topNavigation" class="blackMenu">
  <ul class="h_navigation">
  <? foreach($pages as $page): ?>
    <? if(current_url() == site_url()."/".$page->uri): ?>    
    <li class="active"><a href="<?=site_url()."/".$page->uri?>"><?=$page->text?></a></li>
    <? else: ?>
    <li><a href="<?=site_url()."/".$page->uri?>"><?=$page->text?></a></li>
    <? endif; ?>
  <? endforeach; ?>
  </ul>
</div>