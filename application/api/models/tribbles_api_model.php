<?php

class Tribbles_API_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function countPosts(){
      if($query = $this->db->get('tr_tribbles')){
        $count = $query->num_rows();
        return $count;
      } else {
        return false;
      }
    }
    
    function getPostList($type,$page,$per_page){
            
      switch($type){
        case 'new':
          $sort_field = 'tr_tribbles.tribble_timestamp desc';
          break;
        case 'buzzing':
          $sort_field = 'replies, tr_tribbles.tribble_timestamp desc';
          break;
        case 'loved':
          $sort_field = 'likes, tr_tribbles.tribble_timestamp desc';
          break;
      }
          
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_tribble_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
                       
      $this->db->order_by($sort_field);
      
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
        return $query->result();
      } else {
        return false;
      }                            
    }
    
    function getMostRecent($page,$per_page){
          
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
                       
      $this->db->order_by("tr_tribbles.tribble_timestamp", "desc");
      
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
       
      $query = $this->db->get();
      $result = $query->result();
      return $result;                            
    }        
    
    function getMostCommented($page = null,$per_page){
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');          
      $this->db->order_by("replies", "desc");
      
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
      }
       
      $query = $this->db->get();
      $result = $query->result();
      return $result;                
    }
    
    function getMostLiked($page = null,$per_page){      
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
      $this->db->order_by("likes", "desc");
      
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
      }
       
      $query = $this->db->get();
      $result = $query->result();
      return $result;;                    
    }
    
    function searchPostsText($searchString,$page = null,$per_page){
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
      
      $this->db->like('tr_tribbles.tribble_title',$searchString);
      $this->db->or_like('tr_tribbles.tribble_text',$searchString);
                       
      $this->db->order_by("tr_tribbles.tribble_timestamp", "desc");
      
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
      }
       
      $query = $this->db->get();
      $result = $query->result();
      return $result;                            
    }
    
    function createNewTribble($args){
      
      $result = '';
      
      $uid = $this->session->userdata('uid');
      
      $data = array(
         'tribble_text' => $this->input->post('text'),
         'tribble_title' => $this->input->post('title'),
         'tribble_user_id' => $uid,
      );
      
      $this->db->trans_begin();
      if(!$this->db->insert('tribbles', $data)){
         $result->error = 'Error while writing tribble data.';
      }
      
      log_message('debug','tribble data writen');
      
      $tribbleid = $this->db->insert_id();
      
      $tagdata['tags_content'] = $this->input->post('tags');
      $tagdata['tags_tribble_id'] = $tribbleid;
        
      if(!$this->db->insert('tags',$tagdata)){
        $result->error = 'Error while writing tag data.';
      }
      
      log_message('debug', 'tag data writen');
      
      $imagedata['image_tribble_id'] = $tribbleid;
      $imagedata['image_path'] = $args['image_path'];
      $imagedata['image_palette'] = $args['image_palette'];
      
      if(!$this->db->insert('images',$imagedata)){
        $result->error = 'Error while writing image data.';  
      }
      
      log_message('debug','image data writen');
      
      
      $likedata['like_tribble_id'] = $tribbleid;
      $likedata['like_user_id'] = $uid;
            
      if(!$this->db->insert('likes',$likedata)){
        $result->error = "Error while writing like data";
      }
      
      log_message('debug','like data writen');
              
      if ($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        return $result;
      } else {
        $this->db->trans_commit();
        return $tribbleid;
      }
    }
              
    function reply($tribble_id){
      //$this->
    }
    
    function getPostById($postId = null){      
      $this->db->select('
        tr_tribbles.tribble_id AS id,
        tr_tribbles.tribble_title AS title,
        tr_tribbles.tribble_text AS `text`,
        tr_tribbles.tribble_timestamp AS ts,
        COUNT(tr_likes.like_id) AS likes,
        tr_images.image_path as image,
        tr_images.image_palette as palette,
        tr_tags.tags_content as tags,
        tr_users.user_id as userid,
        tr_users.user_realname AS username,
        tr_users.user_avatar AS avatar,
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_tags','tr_tribbles.tribble_id = tr_tags.tags_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id');
      $this->db->where('tr_tribbles.tribble_id',$postId);
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_tribbles.tribble_timestamp,
        tr_images.image_path,
        tr_images.image_palette,
        tr_users.user_id,
        tr_users.user_realname
      ');
      $query = $this->db->get();            
      $result = $query->result();
      return $result;
    }
    
    function getRepliesByPostId($tribble_id,$currentPage=0){      
      $this->db->select('
        tr_tribbles.tribble_text AS reb_text,
        tr_tribbles.tribble_title AS reb_title,
        tr_comments.comment_text,
        tr_replies.reply_timestamp as ts,
        tr_users.user_realname AS com_username,
        tr_users.user_id AS com_userid,
        tr_users.user_avatar AS avatar,
        tr_users1.user_realname AS reb_username,
        tr_users1.user_id AS reb_userid,
        tr_users1.user_avatar AS reb_avatar,
        tr_images.image_path AS image,
        tr_tribbles.tribble_id AS reb_id
      ');
      $this->db->from('tr_replies');
      $this->db->join('tr_comments','tr_replies.reply_comment_id = tr_comments.comment_id','LEFT OUTER');
      $this->db->join('tr_tribbles','tr_replies.reply_rebound_id = tr_tribbles.tribble_id','LEFT OUTER');
      $this->db->join('tr_users','tr_comments.comment_user_id = tr_users.user_id','LEFT OUTER');
      $this->db->join('tr_users tr_users1','tr_tribbles.tribble_user_id = tr_users1.user_id','LEFT OUTER');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','LEFT OUTER');
      $this->db->where(array('tr_replies.reply_tribble_id'=>$tribble_id));
      
      $query = $this->db->get();
      $result = $query->result();
      return $result;                  
    }
    
    function addComment($tribble_id,$uid){
      
      $comment = array(
       'comment_text' => $this->input->post('text'),
       'comment_user_id' => $uid
      );
      
      if(!$this->db->insert('comments',$comment)){
        return false;
      } else { 
        $comment_id = $this->db->insert_id();
      }            
      if(!$this->db->insert('replies_ref',array('reply_tribble_id'=>$tribble_id,'reply_comment_id'=>$comment_id))){
        return false;        
      } else {
        return true;
      }                           
    }
    
    function checkUserId($id){      
      $query= $this->db->get_where('users',array('user_id'=>$id));    
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      } 
    }
    
            
}

?>