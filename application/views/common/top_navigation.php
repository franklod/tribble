<?
$pages->home->uri = '';
$pages->home->text = 'home';
$pages->buzzing->uri = 'tribble/buzzing';
$pages->buzzing->text = 'buzzing';
$pages->loved->uri = 'tribble/loved';
$pages->loved->text = 'loved';
if($this->session->userdata('uid')){
  $pages->upload->uri = 'tribble/upload';
  $pages->upload->text = 'upload';
}

?>
<div id="navContainer">
<div id="topNavigation" class="blackMenu">
<ul class="h_navigation">
<? foreach($pages as $page): ?>   
  <li <?= (current_url() == site_url()."/".$page->uri) ? 'class="active"' : FALSE?>"><a href="<?=site_url()."/".$page->uri?>"><?=$page->text?></a></li>
<? endforeach; ?>
</ul>
  <form id="search">
  <input name="txtSearch" type="text" class="" id="txtSearch" placeholder="Pesquisar" />
  <input name="btnSearch" type="button" class="btn" id="btnSearch" value="Pesquisar"> 
  </form>
</div>
</div>
