<div class="g75">
  <div class="inner-wrapper">
    <div class="tribble-container">
    <div class="tribble-user-info"> <a href="<?=site_url('user/'.$post->user_id)?>" title="<?=$post->user_name?>"><img src="<?= (!empty($post->user_avatar)) ? $post->user_avatar : '/assets/images/avatar.jpg' ?>" alt="Logobig" width="54" height="54"/></a>
      <div class="tribble-title">
        <h2>
          <?=$post->post_title?>
        </h2>
      </div>
      <h4>
        <a href="<?=site_url('user/'.$post->user_id)?>" title="<?=$post->user_name?>"><?=$post->user_name?></a>
      </h4>
      <div class="tribble-date">
        <?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?>
        <?if(isset($user)):?>
        <?if($post->user_id == $user->id):?>
        | <a href="/post/delete/<?=$post->post_id?>" style="position: absolute; top: 26px; font-size: .9em;" class="defaultBtn btn_delete">delete</a>
        <?endif;?>
        <?endif;?>
      </div>
    </div>
    <div class="tribble-img-container box clear"> <img src="<?=$post->post_image_path?>" width="400" height="300"/> </div>
    <div class="tribble-img-data">
      <div class="tribble-desc">
        <p>
          <?=$post->post_text?>
        </p>
      </div>
      <div class="tribble-tools">
        <p class="ico">
          <?if(!isset($like_status)):?>
            <a title="<?=$post->post_like_count?> users like this post" href="#" class="likes"><?=$post->post_like_count?></a>          
          <?else:?>
            <?if($like_status == true):?>
            <a title="Unlike this post" href="<?=site_url('like/remove/'.$post->post_id)?>" class="likes liked"><?=$post->post_like_count?></a>
            <?else:?>
            <a type="Like this post" href="<?=site_url('like/add/'.$post->post_id)?>" class="likes"><?=$post->post_like_count?></a>
            <?endif;?>
          <?endif;?>
        </p>
      </div>
      <h3>Tags</h3>
      <ul class="tags">
        <? $tags = explode(',',$post->post_tags) ?>
        <? foreach($tags as $tag):?>
        <li><a href="/">
          <?=$tag?>
          </a></li>
        <? endforeach; ?>
      </ul>
      <h3>Color Scheme</h3>
      <ul class="color-scheme">
        <? $colors = json_decode($post->post_image_palette) ?>
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
        <? if($reply->reply_comment_text):?>
        <li class="response">
          <h4><a href="/user/<?=$reply->reply_comment_user_id?>"><img src="<?= (!empty($reply->reply_comment_user_avatar)) ? $reply->reply_comment_user_avatar : '/assets/images/avatar.jpg' ?>"  width="42" height="42"/><?=$reply->reply_comment_user_name?></a> </h4>
          <div class="comment-body">
            <p><?=nl2br($reply->reply_comment_text)?></p>
          </div>
          <!--
<div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p
          </div>
-->
          <p class="comment-date">
            <?=when(mysql_to_unix($reply->reply_date))?>
            <?if($reply->reply_comment_user_id == $this->session->userdata('uid')):?>
             | <a href="<?=site_url('comment/delete/'.$reply->reply_comment_id.'/'.$post->post_id.'/'.$reply->reply_comment_user_id)?>" title="Delete this comment">delete</a>
            <?endif;?>
          </p>
        </li>
        <hr />
        <? else: ?>
          <li class="rebound">
            <h4><a href="/"><img src="<?=$reply->reply_post_user_avatar?>" width="42" height="42"/></a> </h4>
            <div class="rebound-image"><a href="<?=site_url('/tribble/view/'.$reply->reply_post_id)?>"><img src="<?=$reply->post_image_path?>" width="74" height="49" class="box"/></a></div>
            <div class="rebound-title">
              <h2><?=$reply->reply_post_title?></h2>
              <h4><a href="/user/<?=$reply->reply_post_user_id?>"><?=$reply->reply_post_user_name?></a></h4>
            </div>
            <div class="comment-body">
              <p><?=$reply->reply_post_title?></p>
            </div>
            <!--
<div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p>
          </div>
-->
          <p class="comment-date"><?=when(mysql_to_unix($reply->reply_date))?></p>
          </li>
          <hr />
        <? endif; ?>
        <?endforeach;?>                      
      </ul>
      <?endif;?>
    </div>
    <?if(@$user->id):?>    
    <? $this->load->view('post/replyform.php'); ?>
  <?endif;?>
  </div>
  </div>  
</div>
