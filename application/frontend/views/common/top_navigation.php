<?
$pages->home->uri = '';
$pages->home->text = 'New';
$pages->buzzing->uri = 'buzzing';
$pages->buzzing->text = 'Buzzing';
$pages->loved->uri = 'loved';
$pages->loved->text = 'Loved';
if($this->session->userdata('uid')){
  $pages->upload->uri = 'upload';
  $pages->upload->text = 'Upload';
}

?>

<div id="topNavigation" class="blackMenu">
  <ul class="h_navigation">
    <? foreach($pages as $page): ?>
    <? ($page->uri != '') ? $pattern = '/'.substr($page->uri,strpos($page->uri,'/')).'/i' : $pattern = '/'.$page->text.'/i'?>
    <? if($page->uri == uri_string() || @preg_match($pattern,uri_string())): ?>
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
  <?= form_open(site_url('/dosearch')) ?>  
    <input name="search" type="text" class="" id="search" placeholder="Pesquisar" />
  <?= form_close(); ?>    
</div>
<div id="main">
