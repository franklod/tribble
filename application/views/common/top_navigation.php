<?
//$pages = array(
//  'Home' => '',
//  'Most comments' => 'tribbles/buzzing',
//  'Most likes' => 'tribbles/loved',
//  'Most views' => 'tribbles/viewed',
//  'Upload' => 'tribbles/upload',
//  'Login' => 'user/login',
//  'logout' => 'user/logout'
//);


$pages->home->uri = '';
$pages->home->text = 'home';
$pages->buzzing->uri = 'tribbles/buzzing';
$pages->buzzing->text = 'buzzing';
$pages->loved->uri = 'tribbles/loved';
$pages->loved->text = 'loved';
$pages->upload->uri = 'tribbles/upload';
$pages->upload->text = 'upload';
if($this->session->userdata('uid')){
  $pages->auth->uri = 'user/logout';
  $pages->auth->text = 'logout';  
} else {
  $pages->auth->uri = 'user/login';
  $pages->auth->text = 'login';
}

?>

<pre>
<? print_r($pages) ?>
</pre>

<div id="topNavigation" class="blackMenu">
  <ul class="h_navigation">
  <? foreach($pages as $page): ?>
    <? if(current_url() == base_url().index_page()."/".$page->uri): ?>    
    <li class="active"><a href="<?=base_url().index_page()."/".$page->uri?>"><?=$page->text?></a></li>
    <? else: ?>
    <li><a href="<?=base_url().index_page()."/".$page->uri?>"><?=$page->text?></a></li>
    <? endif; ?>
  <? endforeach; ?>
  </ul>
</div>