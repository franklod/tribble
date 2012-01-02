<div class="g75">
  <div class="tribble-container">
    <div class="tribble-user-info"> <a href="/" title="<?=$tribble->username?>"><img src="http://tribble.local/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/dispic2_thumb.jpg" alt="Logobig" width="54" height="54"/></a>
      <div class="tribble-title">
        <h2>
          <?=$tribble->title?>
        </h2>
      </div>
      <h4><a href="/">
        <?=$tribble->username?>
        </a></h4>
      <div class="tribble-date">
        <?=strftime('%B %d, %Y',mysql_to_unix($tribble->ts));?>
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
        <p class="ico"> <a href="<?=site_url('tribble/like/'.$tribble->id)?>" class="likes">
          <?=$tribble->likes?>
          </a> </p>
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
        <li style="background: <?=$color?>"><a href="/">
          <?=$color?>
          </a></li>
        <? endforeach; ?>
      </ul>
    </div>
    <div class="comments-list">
      <h3>COMENTARIOS</h3>
      <?if(!$replies):?>
      <p>no comments yet</p>
      <?else:?>
      <ul id="comments">
        <li class="response">
          <h4><a href="/"><img src="http://tribble.local/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/dispic2_thumb.jpg"  width="42" height="42"/>Username</a> </h4>
          <div class="comment-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<a href="/"> Ut enim ad minim veniam, </a>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
          </div>
          <div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a>comments</p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p>
          </div>
          <p class="comment-date">10 days ago</p>
        </li>
        <hr />
<li class="rebound">
            <h4><a href="/"><img src="http://tribble.local/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/dispic2_thumb.jpg" width="42" height="42"/></a> </h4>
            <div class="rebound-image"><a href="/"><img src="http://tribble.local/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/dispic2_thumb.jpg" width="74" height="49" class="box"/></a></div>
            <div class="rebound-title">
              <h2>twitter app iOS</h2>
              <h4><a href="/">Username</a></h4>
            </div>
            <div class="comment-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<a href="/"> Ut enim ad minim veniam, </a>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            </div>
            <div class="tribble-tools">
            <p class="ico"><a href="/" class="comments">15</a></p>
            <p class="ico"><a href="/" class="likes">20</a>likes</p>
          </div>
                      <p class="comment-date">10 days ago</p>

          </li>
      </ul>
      <?endif;?>
    </div>
  </div>
</div>
