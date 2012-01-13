<div class="g75">
  <div class="inner-wrapper">
    <div class="tribble-container">
    <div class="tribble-user-info"> <a href="<?=site_url('user/'.$tribble->userid)?>" title="<?=$tribble->username?>"><img src="<?= (!empty($tribble->avatar)) ? $tribble->avatar : '/assets/images/avatar.jpg' ?>" alt="Logobig" width="54" height="54"/></a>
      <div class="tribble-title">
        <h2>
          <?=$tribble->title?>
        </h2>
      </div>
      <h4>
        <a href="<?=site_url('user/'.$tribble->userid)?>" title="<?=$tribble->username?>"><?=$tribble->username?></a>
      </h4>
      <div class="tribble-date">
        <?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?>
        <?if($tribble->userid == $this->session->userdata('uid')):?>
        | <a href="/">delete</a>
        <?endif;?>
      </div>
    </div>
    <div class="tribble-img-container box clear"> <img src="<?=$tribble->image?>" width="400" height="300"/> </div>
    <div class="tribble-img-data">
      <div class="tribble-desc">
        <p>
          <?=$tribble->text?>
        </p>
      </div>
      <div class="tribble-tools">
        <p class="ico">
          <?if(!isset($like_status)):?>
            <a title="<?=$tribble->likes?> users like this post" href="#" class="likes"><?=$tribble->likes?></a>          
          <?else:?>
            <?if($like_status == true):?>
            <a title="Unlike this post" href="<?=site_url('like/remove/'.$tribble->id)?>" class="likes liked"><?=$tribble->likes?></a>
            <?else:?>
            <a type="Like this post" href="<?=site_url('like/add/'.$tribble->id)?>" class="likes"><?=$tribble->likes?></a>
            <?endif;?>
          <?endif;?>
        </p>
      </div>
      <h3>Tags</h3>
      <ul class="tags">
        <? $tags = explode(',',$tribble->tags) ?>
        <? foreach($tags as $tag):?>
        <li><a href="/">
          <?=$tag?>
          </a></li>
        <? endforeach; ?>
      </ul>
      <h3>Color Scheme</h3>
      <ul class="color-scheme">
        <? $colors = json_decode($tribble->palette) ?>
        <? foreach($colors as $key => $color):?>
        <li style="background: #<?=$color?>"><a href="/" title="<?=$color?>">
          <?=$color?>
          </a></li>
        <? endforeach; ?>
      </ul>
    </div>
    <div class="comments-list">
      <?if(!$replies):?>
      <h4><?=$replies_count?> responses</h4>
      <hr />
      <?else:?>
      <h4><?=$replies_count?> responses</h4>
      <hr />
      <ul id="comments">
        <?foreach($replies as $reply):?>
        <? if($reply->comment_text):?>
        <li class="response">
          <h4><a href="/user/<?=$reply->com_userid?>"><img src="<?= (!empty($tribble->avatar)) ? $tribble->avatar : '/assets/images/avatar.jpg' ?>"  width="42" height="42"/><?=$reply->com_username?></a> </h4>
          <div class="comment-body">
            <p><?=nl2br($reply->comment_text)?></p>
          </div>
          <!--
<div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p
          </div>
-->
          <p class="comment-date">
            <?=when(mysql_to_unix($reply->ts))?>
            <?if($reply->com_userid == $this->session->userdata('uid')):?>
             | <a href="<?=site_url('comment/delete/'.$reply->comment_id.'/'.$tribble->id.'/'.$reply->com_userid)?>" title="Delete this comment">delete</a>
            <?endif;?>
          </p>
        </li>
        <hr />
        <? else: ?>
          <li class="rebound">
            <h4><a href="/"><img src="http://tribble.local/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/dispic2_thumb.jpg" width="42" height="42"/></a> </h4>
            <div class="rebound-image"><a href="<?=site_url('/tribble/view/'.$reply->reb_id)?>"><img src="<?=$reply->image?>" width="74" height="49" class="box"/></a></div>
            <div class="rebound-title">
              <h2><?=$reply->reb_title?></h2>
              <h4><a href="/"><?=$reply->reb_username?></a></h4>
            </div>
            <div class="comment-body">
              <p><?=$reply->reb_text?></p>
            </div>
            <!--
<div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p>
          </div>
-->
          <p class="comment-date"><?=when(mysql_to_unix($reply->ts))?></p>
          </li>
          <hr />
        <? endif; ?>
        <?endforeach;?>                      
      </ul>
      <?endif;?>
    </div>
    <?if($this->session->userdata('uid')):?>    
    <? $this->load->view('tribble/replyform.php'); ?>
  <?endif;?>
  </div>
  </div>  
</div>
