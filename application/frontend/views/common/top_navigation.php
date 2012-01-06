<?
$pages->home->uri = '';
$pages->home->text = 'New';
$pages->buzzing->uri = 'tribble/buzzing';
$pages->buzzing->text = 'Buzzing';
$pages->loved->uri = 'tribble/loved';
$pages->loved->text = 'Loved';
if($this->session->userdata('uid')){
  $pages->upload->uri = 'tribble/upload';
  $pages->upload->text = 'Upload';
}

?>

<div id="topNavigation" class="blackMenu">
  <ul class="h_navigation">
    <? foreach($pages as $page): ?>
    <? if(current_url() == site_url().$page->uri || current_url() == site_url().'/'.$page->uri): ?>
    <li class="active"><a href="<?=site_url()."/".$page->uri?>">
      <?=$page->text?>
      </a></li>
    <? else: ?>
    <li><a href="<?=site_url()."/".$page->uri?>">
      <?=$page->text?>
      </a></li>
    <? endif; ?>
    <? endforeach; ?>
    <hr />
  </ul>
  <form id="search">
    <input name="searchInput" type="text" class="" id="searchInput" placeholder="Pesquisar" />
  </form>    
</div>
<div id="main">
