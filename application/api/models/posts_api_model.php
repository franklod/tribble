<?php
  
class Posts_API_model extends CI_Model {


    function getPostsByTag($tag,$page,$per_page){                   
      
      $this->db->select('
          tr_post.post_id AS post_id,
          tr_post.post_title AS post_title,
          tr_post.post_text AS post_text,
          tr_post.post_timestamp AS post_date,
          tr_image.image_path as post_image_path,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count,
          tr_user.user_id AS user_id,
          tr_user.user_realname AS user_name,          
          tr_user.user_email AS user_email            
      ');
      
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');
      $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');           
      $this->db->where(array('tr_post.post_is_deleted'=>0));    
      $this->db->like('tr_tag.tag_content', $tag, 'none');
      $this->db->or_like('tr_tag.tag_content', ','.$tag, 'before');
      $this->db->or_like('tr_tag.tag_content', ','.$tag.',', 'both');
      $this->db->or_like('tr_tag.tag_content', $tag.',', 'after');      
      $this->db->order_by('tr_post.post_timestamp','desc');
      
      $page = (int)$page;
      $per_page = (int)$per_page;
      
      if($page <= 1){
        $page = 1; 
      }

      $offset = ($page-1) * $per_page;
              
      if($offset > 0){
        $this->db->limit($per_page,$offset);
      } else {
        $this->db->limit($per_page);
      }
      
      if($query = $this->db->get()){
        $result = array('tag'=>$tag,'count'=>$query->num_rows(),'posts'=>$query->result());
        return $result;
      } else {
        return false;
      }
      
    }

    function getPostsByUser($user_id,$page,$per_page){                   
      
      $this->db->select('
          tr_post.post_id AS post_id,
          tr_post.post_title AS post_title,
          tr_post.post_text AS post_text,
          tr_post.post_timestamp AS post_date,
          tr_image.image_path as post_image_path,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count,
          (SELECT COUNT(1) FROM tr_post WHERE tr_post.post_user_id = tr_user.user_id AND tr_post.post_is_deleted = 0) as post_count,
          tr_user.user_id AS user_id,
          tr_user.user_realname AS user_name,          
          tr_user.user_email AS user_email,
          tr_user.user_bio AS user_bio
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','right outer');
      //  $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');           

      $this->db->where(array('tr_user.user_id' => $user_id));
      $this->db->where(array('tr_post.post_is_deleted' => 0));
      $this->db->order_by('tr_post.post_timestamp','desc');             
      
      $page = (int)$page;
      $per_page = (int)$per_page;
      
      if($page <= 1){
        $page = 1; 
      }

      $offset = ($page-1) * $per_page;                
              
      if($offset > 0){          
        $this->db->limit($per_page,$offset);
      } else {          
        $this->db->limit($per_page); 
      } 
      
      if($query = $this->db->get()){

        $posts = $query->result();
        $count = $query->num_rows();

        $this->db->select('
          user_id,
          user_realname AS user_name,
          user_email,
          user_bio,
        ');

        $this->db->from('user');
        $this->db->where(array('user_id'=>$user_id));
        $this->db->where(array('user_is_deleted'=>0));
        
        $query = $this->db->get();

        $user = $query->result();

        $result = array('user_id'=>$user[0]->user_id,'user_name'=>$user[0]->user_name,'user_email'=>$user[0]->user_email,'user_bio'=>$user[0]->user_bio,'count'=>$count,'posts'=>$posts);
        // $result = array('user_name'=>$qr[0]->user_name,'user_email'=>$qr[0]->user_email,'user_bio'=>$qr[0]->user_bio,'count'=>$qr[0]->post_count,'posts'=>$query->result());
        return $result;
      } else {
        return false;
      }
      
    }
  
      
    function getPostList($type,$page,$per_page){
            
      switch($type){
        case 'new':
          $sort_field = 'tr_post.post_timestamp desc';
          break;
        case 'buzzing':
          $sort_field = 'post_reply_count desc, tr_post.post_timestamp desc';
          break;
        case 'loved':
          $sort_field = 'post_like_count desc, tr_post.post_timestamp desc';
          break;
      }
          
      $this->db->select('
          tr_post.post_id AS post_id,
          tr_post.post_title AS post_title,
          tr_post.post_text AS post_text,
          tr_post.post_timestamp AS post_date,
          tr_image.image_path as post_image_path,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count,
          tr_user.user_id AS user_id,
          tr_user.user_realname AS user_name,          
          tr_user.user_email AS user_email                              
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');           

      $this->db->where('tr_post.post_is_deleted',0);            
                       
      $this->db->order_by($sort_field);
      
      $page = (int)$page;
      $per_page = (int)$per_page;
      
      if($page <= 1){
        $page = 1; 
      }

      $offset = ($page-1) * $per_page;                
              
      if($offset > 0){          
        $this->db->limit($per_page,$offset);
      } else {          
        $this->db->limit($per_page); 
      }                         
      
      if($query = $this->db->get())
      {
          if ($query->num_rows() > 0)
          {
              return $query->result();
          }
          else
          {
              return new CI_DB_mysql_result;
          }
      } else {
        return false;
      }                            
    }
    
    function _countPosts(){
      if($query = $this->db->get('tr_post')){
        $count = $query->num_rows();
        return $count;
      } else {
        return false;
      }
    }

    function getPostPalette($post_id){
      $this->db->select('HEX');
      $this->db->from('palette');
      $this->db->where(array('palette_post_id'=>$post_id));
      $this->db->order_by('HSL_H desc, HSL_L desc');
      $query = $this->db->get();
      return $query->result();
    }
    
    function getPostById($postId){
      
      $this->db->select('
        tr_post.post_id AS post_id,
        tr_post.post_title AS post_title,
        tr_post.post_text AS post_text,
        tr_post.post_timestamp AS post_date,
        tr_post.post_parent_id AS post_parent_id,
        COUNT(tr_like.like_post_id) AS post_like_count,
        tr_image.image_path as post_image_path,
        tr_tag.tag_content as post_tags,
        tr_user.user_id as user_id,
        tr_user.user_realname AS user_name,
        tr_user.user_email AS user_email,
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_like','tr_post.post_id = tr_like.like_post_id','left outer');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id');
      $this->db->where('tr_post.post_id',$postId);
      $this->db->group_by('
        tr_post.post_id,
        tr_post.post_title,
        tr_post.post_text,
        tr_post.post_timestamp,
        tr_image.image_path,
        tr_user.user_id,
        tr_user.user_realname
      ');
      $query = $this->db->get();            
      $result = $query->result();
      return $result;
    }

    function getPaletteByPostId($post_id){
  
      $this->db->select('HEX');      
      $this->db->from('tr_palette');
      $this->db->join('tr_post','tr_post.post_id = tr_palette.palette_post_id','inner');
      $this->db->where('tr_palette.palette_post_id',$post_id);
      $this->db->where('tr_reply.reply_is_deleted',0);      
      
      $query = $this->db->get();
      if($query->num_rows() == 0){
        return;
      } else {
        return $query->result();
      }                  
    }
    
    function getRepliesByPostId($post_id){
  
      $this->db->select('
        tr_post.post_id AS reply_post_id,
        tr_post.post_title AS reply_post_title,
        tr_post.post_text AS reply_post_text,
        tr_image.image_path AS post_image_path,
        tr_user_post.user_id AS reply_post_user_id,
        tr_user_post.user_realname AS reply_post_user_name,        
        tr_user_post.user_email AS reply_post_user_email,
        tr_comment.comment_id as reply_comment_id,        
        tr_comment.comment_text as reply_comment_text,                
        tr_user_comment.user_id AS reply_comment_user_id,
        tr_user_comment.user_realname AS reply_comment_user_name,
        tr_user_comment.user_email AS reply_comment_user_email,
        tr_reply.reply_timestamp as reply_date                        
      ');
      
      $this->db->from('tr_reply');
      $this->db->join('tr_comment','tr_reply.reply_comment_id = tr_comment.comment_id','LEFT OUTER');
      $this->db->join('tr_post','tr_reply.reply_rebound_id = tr_post.post_id','LEFT OUTER');
      $this->db->join('tr_user tr_user_comment','tr_comment.comment_user_id = tr_user_comment.user_id','LEFT OUTER');
      $this->db->join('tr_user tr_user_post','tr_post.post_user_id = tr_user_post.user_id','LEFT OUTER');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','LEFT OUTER');
      $this->db->where('tr_reply.reply_post_id',$post_id);
      $this->db->where('tr_reply.reply_is_deleted',0);      
      
      $query = $this->db->get();
      if($query->num_rows() == 0){
        return;
      } else {
        return $query->result();
      }                  
    }
    
    function searchPostsTitleAndDescription($string,$page,$per_page){
          
      $this->db->select('
          tr_post.post_id AS post_id,
          tr_post.post_title AS post_title,
          tr_post.post_text AS post_text,
          tr_post.post_timestamp AS post_date,
          tr_user.user_realname AS user_name,
          tr_user.user_id AS user_id,
          tr_user.user_email AS user_email,
          tr_image.image_path as post_image_path,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count         
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');
      
      $this->db->like(array('tr_post.post_title'=>$string));
      $this->db->or_like(array('tr_post.post_text'=>$string));
                                   
      $this->db->order_by('tr_post.post_timestamp desc');
      
      if((int)$per_page || (int)$page){
        if($page <= 1){
          $page = 0; 
        }
        
        $offset = $page * $per_page;        
                
        if($offset > 0){          
          $this->db->limit($offset,$per_page);
        } else {          
          $this->db->limit($per_page); 
        }                         
      } else {
        
      }
      
      if($query = $this->db->get()){
        $result = array('string'=>$string,'count'=>$query->num_rows(),'posts'=>$query->result());
        return $result; 
      } else {
        return false;
      }                            
    }
    
    function insert_comment($post_id,$user_id,$comment_text){
      
      $comment = array(
       'comment_text' => $comment_text,
       'comment_user_id' => $user_id
      );
      $this->db->trans_start();
      $this->db->insert('comment',$comment);
      $comment_id = $this->db->insert_id();            
      $this->db->insert('reply',array('reply_post_id'=>$post_id,'reply_comment_id'=>$comment_id));
      $this->db->trans_complete();
      
      if ($this->db->trans_status() === FALSE)
      {
        return false;
      } else {
        return true;
      } 
                                 
    }
    
    function delete_comment($post_id,$comment_id,$user_id)
    {
      
      $this->db->trans_begin();
      // flag comment as deleted
      $comment_update = array('comment_is_deleted'=>1);
      $this->db->where('comment_id',$comment_id);
      $this->db->where('comment_user_id',$user_id);
      $this->db->update('comment',$comment_update);
      // flag reply as deleted
      $reply_update = array('reply_is_deleted'=>1);
      $this->db->where('reply_comment_id',$comment_id);
      $this->db->where('reply_post_id',$post_id);
      $this->db->update('reply',$reply_update); 
      $this->db->trans_complete();
      
      if ($this->db->trans_status() === FALSE)
      {
        return false;
      } else {
        return true;
      }
      
                 
    }
    
    function checkIfPostExists($post_id){
      $query = $this->db->get_where('post',array('post_id'=>$post_id));
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      }
    }        
    
    function checkIfCommentExists($comment_id){
      $query = $this->db->get_where('comment',array('comment_id'=>$comment_id));
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      }
    }
    
          
      
    function insertPost($post_data,$tags,$image){

      $this->db->trans_start();

      // try to write the post text data
      if(!$this->db->insert('post', $post_data)){
         $result->error = 'Error while writing tribble data.';
         log_message('error','Error while writing post data.');
      } else {
        log_message('debug','Post data writen');
      }            
      
      // get the post id from the last insert
      $post_id = $this->db->insert_id();
      
      $tagdata['tag_content'] = $tags;
      $tagdata['tag_post_id'] = $post_id;
        
      // try to write the tag data
      if(!$this->db->insert('tag',$tagdata)){
        $result->error = 'Error while writing tag data.';
        log_message('error','Error while writing post tag data.');
      } else {
        log_message('debug', 'tag data writen');
      }

      // get the image data                   
      $imagedata['image_post_id'] = $post_id;
      $imagedata['image_path'] = $image['path'];
      
      // try to write the image data
      if(!$this->db->insert('image',$imagedata)){
        $result->error = 'Error while writing image data.';
        log_message('error','Error while writing post image data.');
      } else {
        log_message('debug','image data was writen');
      }

      // store the image palette
      if(!$colors = GetImagePalette($this->config->item('app_path').$imagedata['image_path'],$post_id)){
        log_message('error','Error while obtaining post palette data.');
      } else {
        log_message('debug','palette data was obtained');
      }

      foreach ($colors as $color)
      {     
        if(!$this->db->insert('palette',$color)){
          log_message('error','Error while post palette data: '.$color['HEX']);
        } else {
          log_message('debug','palette data writen.');
        }
      } 
      
      
      
      
      $likedata['like_post_id'] = $post_id;
      $likedata['like_user_id'] = $post_data['post_user_id'];
            
      if(!$this->db->insert('like',$likedata)){
        $result->error = "Error while writing like data";
        log_message('error','Error while post like data.');
      } else {
        log_message('debug','like data writen');  
      }
                 
      
      
      $this->db->trans_complete();
              
      if ($this->db->trans_status() === FALSE){
        return false;
      } else {
        return $post_id;
      }
    }


    function _transferPalette($ID,$HEX,$R,$G,$B,$H,$S,$V,$L,$A,$B){
      $data = array(
         'post_id' => $ID,
         'HEX' => $HEX,
         'RGB_R' => $R,
         'RGB_G' => $G,
         'RGB_B' => $B,
         'HSV_H' => $H,
         'HSV_S' => $S,
         'HSV_V' => $V,
         'LAB_L' => $L,
         'LAB_A' => $A,
         'LAB_B' => $B,
      );
      $this->db->insert('palette',$data);
    }

    function insertReply($reply_id,$parent_id){
      $data = array('reply_post_id'=>$parent_id,'reply_rebound_id'=>$reply_id);
      $this->db->insert('reply', $data);
      if($this->db->affected_rows() == 1){
        return true;
      } else{
        return false;
      }
    }


    function postIsReply($post_id){

    }

    function getPostReplies($post_id){

      $this->db->select('post_id, post_user_id');
      $this->db->from('tr_post');
      $this->db->where(array('post_parent_id'=>$post_id));

      if(!$query = $this->db->get())
        return false;

      return $query->result();
    }

    function deletePost($post_id){

      $this->db->where('reply_rebound_id', $post_id);
      $this->db->update('tr_reply', array('reply_is_deleted'=>1));

      $this->db->where('post_id', $post_id);
      $this->db->update('tr_post', array('post_is_deleted'=>1));

      $num_rows = $this->db->affected_rows();

      if($num_rows == 1){
        $this->db->where('post_parent_id', $post_id);
        $this->db->update('tr_post', array('post_parent_id'=>0));
	return true;
      } else {
        return false;
      }
    }

    function checkUserPostPermission($post_id,$user_id){
      $query = $this->db->get_where('post',array('post_id'=>$post_id));
      $result = $query->result();
      ($result[0]->post_user_id == $user_id) ? $response = true : $response = false;      
      return $response;
    }

    function getPostLikers($post_id){      

      $this->db->select('
        user_realname,
        user_id
      ');

      $this->db->from('tr_like');
      $this->db->join('tr_user','tr_like.like_user_id = tr_user.user_id','inner');
      $this->db->where(array('like_post_id'=>$post_id));
      $this->db->order_by('tr_user.user_realname','asc');
      if(!$query = $this->db->get())
        return false;

        return $query->result();
    }


    function colorSearch($HEX,$VARIATION = 40, $COVERAGE = 10){

    if(strlen($HEX) != 6){
      // exit('VAI MEXER NO CARALHO!');
    }

    $RGB = HEXToRGB($HEX);
    $HSL = RGBToHSL($RGB);
    $XYZ = RGBToXYZ($RGB);
    $LAB1 = XYZToLAB($XYZ);

    // CHECK IF HUE IS NEAR 0 - RED
    ($HSL['H'] >= 350 || $HSL['H'] <= 20) ? $RED = true : $RED = false;
    // CHECK IF HUE IS NEAR 0 - RED
    ($HSL['H'] >= 21 && $HSL['H'] <= 70) ? $YELLOW = true : $YELLOW = false;
    // CHECK IF HUE IS NEAR 120 - GREEN
    ($HSL['H'] >= 71 && $HSL['H'] < 165) ? $GREEN = true : $GREEN = false;
    // CHECK IF HUE IS NEAR 240 - BLUE
    ($HSL['H'] >= 1166 && $HSL['H'] < 350) ? $BLUE = true : $BLUE = false;

    $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
    $L_VARIATION = $VARIATION  / 1.2;
    $S_VARIATION = $VARIATION * 3.5;

    if($RED){
      $H_VARIATION =  (($VARIATION * 360) / 100) / 30;
      $L_VARIATION = $VARIATION  * 1.2;
      $S_VARIATION = $VARIATION / 1.1;
    } elseif ($YELLOW) {
      $H_VARIATION =  (($VARIATION * 360) / 100) / 80;
      $L_VARIATION = $VARIATION  / 1;
      $S_VARIATION = $VARIATION * 1;      
    } elseif ($GREEN) {
      $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
      $L_VARIATION = $VARIATION  / 1;
      $S_VARIATION = $VARIATION * 1;      
    } elseif ($BLUE) {
      $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
      $L_VARIATION = $VARIATION  / 1.2;
      $S_VARIATION = $VARIATION * 3.5; 
    }
    
    $DELTA_VARIATION = ( 80 / 100 ) * $VARIATION;
    
    $HB = $HSL['H'] - $H_VARIATION * 3.5;
    $HT = $HSL['H'] + $H_VARIATION * 3;

    ($HB < 0) ? $H_BOTTOM = 360 + $HB : $H_BOTTOM = $HB;
    ($HT > 360) ? @$H_TOP += $HT - 360 : $H_TOP = $HT;

    $DELTA = 0;

     if($H_BOTTOM > $H_TOP){
      $DELTA = 360 - $H_BOTTOM;
      $H_BOTTOM = 0;
      $H_TOP = $H_TOP + $DELTA;
     }

    $SB = $HSL['S'] - $S_VARIATION;
    $ST = $HSL['S'] + $S_VARIATION;

    ($SB < 0 ) ? $S_BOTTOM = 0 : $S_BOTTOM = $SB;
    ($ST > 100 ) ? $S_TOP = 100 : $S_TOP = $ST;

    $LB = $HSL['L'] - $L_VARIATION;
    $LT = $HSL['L'] + $L_VARIATION;

    ($LB < 0 ) ? $L_BOTTOM = 0 : $L_BOTTOM = $LB;
    ($LT > 100 ) ? $L_TOP = 100 : $L_TOP = $LT;

    // check if the search color is grey(ish)
    ($HSL['S'] < 3) ? $is_grey = true : $is_grey = false;
   

    $this->db->select('tr_palette.palette_post_id, HEX, PERCENT, HSL_H, HSL_S, HSL_L, LAB_L as L, LAB_A as A, LAB_B as B');
    $this->db->distinct();
    $this->db->from('palette');
    $this->db->join('post','tr_post.post_id = tr_palette.palette_post_id','inner');
    $this->db->where('tr_palette.HSL_H + '.$DELTA.' BETWEEN '.$H_BOTTOM.' AND '.$H_TOP);
    $this->db->where('tr_palette.HSL_S BETWEEN '.$S_BOTTOM.' AND '.$S_TOP);
    $this->db->where('tr_palette.HSL_L BETWEEN '.$L_BOTTOM.' AND '.$L_TOP);
    $this->db->where('tr_palette.PERCENT > '.$COVERAGE);
    $this->db->where(array('tr_post.post_is_deleted'=>0));
    $this->db->group_by('post_id');
    $query = $this->db->get();

    $COLORS = $query->result();

    if(count($COLORS) == 0){
      return 0;
    }

    $GREY_FILTERED_COLORS = array();

    if(!$is_grey){
      foreach ($COLORS as $COLOR) {
        if($COLOR->HSL_H != 0 && $COLOR->HSL_S != 0 && $COLOR->HSL_S > 15 && $COLOR->HSL_L > 15){
          // var_dump($COLOR);
          array_push($GREY_FILTERED_COLORS,$COLOR);
        }
      }
    } else {
      foreach ($COLORS as $COLOR) {
        if($COLOR->HSL_S == 0 && $COLOR->HSL_S < 3){
          // var_dump($COLOR);
          array_push($GREY_FILTERED_COLORS,$COLOR);
        }
      }
    }

      return array('post_count'=>count($GREY_FILTERED_COLORS),'posts'=>$GREY_FILTERED_COLORS,'HSL'=>$HSL);

    }


    function getListOfPosts($list,$page,$per_page){

      $post_count = count($list);
    
    
      $page = (int)$page;
      $per_page = (int)$per_page;
      
      // if($page <= 1){
      //   $page = 1; 
      // }

      // $offset = ($page-1) * $per_page;
              
      // if($offset > 0){
      //   $subjects = array_slice($list, $offset, $per_page);
      // } else {
      //   $subjects = array_slice($list, $offset, $per_page);
      // }
                       
      // var_dump($list);
      
      $posts = array();

      foreach($list as $post){


        $this->db->select('
          tr_post.post_id AS post_id,
          tr_post.post_title AS post_title,
          tr_post.post_text AS post_text,
          tr_post.post_timestamp AS post_date,
          tr_image.image_path as post_image_path,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as post_like_count,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as post_reply_count,
          tr_user.user_id AS user_id,
          tr_user.user_realname AS user_name,          
          tr_user.user_email AS user_email            
        ');
        $this->db->from('tr_post');
        $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
        $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');
        $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');
        $this->db->where(array('post_id'=>$post->palette_post_id));
        $this->db->where(array('post_is_deleted'=>0));
        $query = $this->db->get();
        $result = $query->result();
        
        if($query->num_rows() == 1){
        //   if(!array_key_exists($result[0]->post_id, $posts)){
        //     $posts[$result[0]->post_id] = $result[0];
        //   }
          array_push($posts, $result[0]);
        }


      }

      $response = array('post_count'=>$post_count,'posts'=>$posts);

      return $response;
      
    }

    function getPostEditableData($post_id){
      
      $this->db->select('
        post_id,
        post_title,
        post_text,
        tag_content as post_tags,
        image_path as post_image_path,
      ');
      $this->db->from('post');
      $this->db->join('tr_tag','tr_tag.tag_post_id = tr_post.post_id');
      $this->db->join('tr_image','tr_image.image_post_id = tr_post.post_id');
      $this->db->where(array('tr_post.post_id'=>$post_id));

      $query = $this->db->get();

      if($query->num_rows() != 1)
        return false;
      
      $result = $query->result();

      return $result[0];
    }

    function updatePostData($post_data,$tags){

      $this->db->trans_start();
      $this->db->where(array('post_id'=>$post_data['post_id']));
      $this->db->update('post', $post_data);
      $this->db->where(array('tag_post_id'=>$post_data['post_id']));
      $this->db->update('tag', $tags);
      $this->db->trans_complete();

      ($this->db->trans_status() === true) ? $response = true : $response = false;
      return $response;

      
    }
                                    
} 
  
?>
