<?
  

// var_dump($likers);

if(isset($likers)){

  $num = count($likers);
  $liker_list = '';

  if($num == 1)
    $liker_list = $likers[0]->user_realname . ' likes this.';

  if($num == 2)
    $liker_list .= $likers[0]->user_realname . ' and ' . $likers[1]->user_realname . ' like this.';

  if($num > 2){
    
    foreach($likers as $liker){
      $liker_list .= $liker->user_realname . ", ";
    }
    $liker_list .= 'like this.';
    $liker_list = substr_replace($liker_list, '', strrpos($liker_list, ','),1);
    $liker_list = substr_replace($liker_list, ' and', strrpos($liker_list, ','),1);
  } 
} else {
    $liker_list = 'Boowhoo, it seems no one likes this!';
  }

  

  
?>

<div class="g75">
  <div class="inner-wrapper">
    <div class="post-container">
    <div class="post-user-info">
      <?if(isset($parent)):?>
        <p style="font-size: 1.2em; margin-bottom: 0.8em; font-weight: 500;">This post was a reply to <strong><a href="<?=site_url('view/'.$parent->post_id)?>"><?=$parent->post_title?></a></strong></p>
      <?endif;?>   
      <a href="<?=site_url('user/'.$post->user_id)?>" title="<?=$post->user_name?>">
        <?=get_gravatar($post->user_email,54)?>
      </a>
      <div class="post-title">
        <h2>
          <?=$post->post_title?>               
        </h2>        
      </div>
      <h4>
        <a href="<?=site_url('user/'.$post->user_id)?>" title="<?=$post->user_name?>"><?=$post->user_name?></a>
      </h4>
      <div class="post-date">
        <?=strftime('%B %d, %Y',mysql_to_unix($post->post_date));?>        
      </div>      
    </div>
    <div class="post-img-container box clear">
      <img src="<?=cdn_url($post->post_image_path)?>" /> </div>
    <div class="post-img-data">
      <div class="post-desc">
        <p>
          <?=auto_link(nl2br($post->post_text))?>
        </p>
      </div>
      <hr>
      <div class="post-tools">
      <?if(isset($user)):?>
        <?if($post->user_id == $user->user_id):?>
          <a title="Delete this post" class="defaultBtn btn_delete" href="/post/delete/<?=$post->post_id.'-'.url_title($post->post_title)?>">Delete</a>
        <?endif;?>
        <a class="defaultBtn btn_send" title="Create a new post as a reply" href="<?=site_url('reply/'.$post->post_id.'-'.url_title($post->post_title))?>">Reply</a>
        <?endif;?>
        <p class="ico">
          <?if(!isset($like_status)):?>
            <a href="#" title="<?=$liker_list?>" class="likes"><?=$post->post_like_count?></a>          
          <?else:?>
            <?if($like_status == true):?>
            <a href="<?=site_url('like/remove/'.$post->post_id.'-'.url_title($post->post_title))?>" title="<?=$liker_list?>" class="likes liked"><?=$post->post_like_count?></a>
            <?else:?>
            <a href="<?=site_url('like/add/'.$post->post_id.'-'.url_title($post->post_title))?>" title="<?=$liker_list?>" class="likes"><?=$post->post_like_count?></a>
            <?endif;?>
          <?endif;?>
        </p>
      </div>
      <hr>
      <h3>Tags</h3>
      <ul class="tags">
        <? $tags = explode(',',$post->post_tags) ?>
        <? foreach($tags as $tag):?>
        <li><a href="<?=site_url('tag/'.$tag)?>">
          <?=$tag?>
          </a></li>
        <? endforeach; ?>
      </ul>
      <hr>
      <h3>Color Scheme</h3>
      <ul class="color-scheme">
        <? $colors = $post->post_image_palette ?>
        <? foreach($colors as $key => $color):?>
        <li style="background-color: <?=$color?>" title="<?=$color?>"><a href="<?=site_url('color/'.substr($color, 1))?>" title="<?=$color?>">
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
      <h4><?=$replies_count?> <?=($replies_count == 1) ? 'response' : 'responses'?></h4>
      <hr />
      <ul id="comments">
        <?foreach($replies as $reply):?>
        <? if($reply->reply_comment_text):?>
        <li class="response">
          <h4><a href="/user/<?=$reply->reply_comment_user_id?>"><?=get_gravatar($reply->reply_comment_user_email,42)?><?=$reply->reply_comment_user_name?></a> </h4>
          <div class="comment-body">
            <p><?=auto_link(nl2br($reply->reply_comment_text), 'url', TRUE)?></p>
          </div>
          <!--
<div class="post-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p
          </div>
-->
          <p class="comment-date">
            <?=when(mysql_to_unix($reply->reply_date))?>
            <?if(isset($user)):?>
            <?if($reply->reply_comment_user_id == $user->user_id):?>
             | <a href="<?=site_url('comment/delete/'.$reply->reply_comment_id.'/'.$post->post_id.'/'.$reply->reply_comment_user_id)?>" title="Delete this comment">delete</a>
            <?endif;?>
            <?endif;?>
          </p>
        </li>
        <hr />
        <? else: ?>
          <li class="rebound">
            <h4><a href="/"><?=get_gravatar($reply->reply_post_user_email,42)?></a> </h4>
            <div class="rebound-image"><a href="<?=site_url('view/'.$reply->reply_post_id.'-'.url_title($reply->reply_post_title))?>"><img src="<?=$reply->post_image_path?>" width="74" height="49" class="box"/></a></div>
            <div class="rebound-title">
              <h2><?=$reply->reply_post_title?></h2>
              <h4><a href="/user/<?=$reply->reply_post_user_id?>"><?=$reply->reply_post_user_name?></a></h4>
            </div>
            <div class="comment-body">
              <p><?=$reply->reply_post_title?></p>
            </div>
            <!--
<div class="post-tools">
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
      <?if(!isset($user)):?>
      <h4 class="login-to-comment"><a href="<?=site_url('login/view/'.$post->post_id.'-'.url_title($post->post_title))?>">Log in</a> to comment on this post.</h4>
      <?endif;?>
    </div>
    <?if(@$user->user_id):?>    
    <? $this->load->view('post/replyform.php'); ?>
    <?endif;?>
  </div>
  </div>  
</div>
