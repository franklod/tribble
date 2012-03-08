<div class="g75">
  <div class="inner-wrapper">
  <div class="colors-nav">
    <ul class="color-links group">
      <li class="color">
        <a href="<?=site_url('color/660000')?>" style="background-color: #660000" title="#660000">#660000</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/990000')?>" style="background-color: #990000" title="#990000">#990000</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/cc0000')?>" style="background-color: #cc0000" title="#cc0000">#cc0000</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/cc3333')?>" style="background-color: #cc3333" title="#cc3333">#cc3333</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ea4c88')?>" style="background-color: #ea4c88" title="#ea4c88">#ea4c88</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/993399')?>" style="background-color: #993399" title="#993399">#993399</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/663399')?>" style="background-color: #663399" title="#663399">#663399</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/333399')?>" style="background-color: #333399" title="#333399">#333399</a>
      </li>
      <li class="color current">
        <a href="<?=site_url('color/0066cc')?>" style="background-color: #0066cc" title="#0066cc">#0066cc</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/0099cc')?>" style="background-color: #0099cc" title="#0099cc">#0099cc</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/66cccc')?>" style="background-color: #66cccc" title="#66cccc">#66cccc</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/77cc33')?>" style="background-color: #77cc33" title="#77cc33">#77cc33</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/669900')?>" style="background-color: #669900" title="#669900">#669900</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/336600')?>" style="background-color: #336600" title="#336600">#336600</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/666600')?>" style="background-color: #666600" title="#666600">#666600</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/999900')?>" style="background-color: #999900" title="#999900">#999900</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/cccc33')?>" style="background-color: #cccc33" title="#cccc33">#cccc33</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ffff00')?>" style="background-color: #ffff00" title="#ffff00">#ffff00</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ffcc33')?>" style="background-color: #ffcc33" title="#ffcc33">#ffcc33</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ff9900')?>" style="background-color: #ff9900" title="#ff9900">#ff9900</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ff6600')?>" style="background-color: #ff6600" title="#ff6600">#ff6600</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/cc6633')?>" style="background-color: #cc6633" title="#cc6633">#cc6633</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/996633')?>" style="background-color: #996633" title="#996633">#996633</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/663300')?>" style="background-color: #663300" title="#663300">#663300</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/000000')?>" style="background-color: #000000" title="#000000">#000000</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/999999')?>" style="background-color: #999999" title="#999999">#999999</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/cccccc')?>" style="background-color: #cccccc" title="#cccccc">#cccccc</a>
      </li>
      <li class="color">
        <a href="<?=site_url('color/ffffff')?>" style="background-color: #ffffff" title="#ffffff">#ffffff</a>
      </li>
    </ul>
  </div>
  <hr /> 
  <h3>We found <?=$count?> posts containing colors similar to <span style="padding: 4px; background-color: <?=$color?>"><?=$color?></span></h3>
  <hr />  
  <ul class="posts" style="overflow: hidden;">
	<? foreach($posts as $post): ?>
    <li class="space"> 
      <div class="box">     
        <div class="post-img">
          <a href="<?=site_url("/view/".$post->post_id.'-'.url_title($post->post_title))?>" class="post-hover">
            <span class="title"><?= character_limiter($post->post_title,12)?></span>
            <span class="desc"><?=word_limiter($post->post_text,20)?></span>
            <em><?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?></em>              
          </a>
          <img src="<?=cdn_url(getThumb($post->post_image_path))?>"  alt="<?=$post->post_title?>" />
        </div>
        <div class="post-tools">
          <p class="ico"><a href="" class="comments"><?=$post->post_reply_count?></a>Comments</p>
          <p class="ico"><a href="" class="likes"><?=$post->post_like_count?></a>likes</p>  
          <p class="ico"><a href="" class="rebound">2</a>likes</p>     
        </div>        
      </div>
      <div class="post-user-info">
        <a href="<?=site_url('/user/'.$post->user_id.'-'.url_title($post->user_name))?>">
          <?=get_gravatar($post->user_email,18)?><?=$post->user_name?>
        </a>
      </div>  
    </li>  
  <? endforeach; ?>
  </ul>
  <hr />
  <?=$paging?>  
  </div>
</div>