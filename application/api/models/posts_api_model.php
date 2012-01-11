<?php
  
class Posts_API_model extends CI_Model {
      
    function getPostList($type,$page,$per_page){
            
      switch($type){
        case 'new':
          $sort_field = 'tr_tribbles.tribble_timestamp desc';
          break;
        case 'buzzing':
          $sort_field = 'replies desc, tr_tribbles.tribble_timestamp desc';
          break;
        case 'loved':
          $sort_field = 'likes desc, tr_tribbles.tribble_timestamp desc';
          break;
      }
          
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,,
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
    
    function countPosts(){
      if($query = $this->db->get('tr_tribbles')){
        $count = $query->num_rows();
        return $count;
      } else {
        return false;
      }
    }
    
    function getPostById($postId){
      
      $this->db->select('
        tr_tribbles.tribble_id AS id,
        tr_tribbles.tribble_title AS title,
        tr_tribbles.tribble_text AS `text`,
        tr_tribbles.tribble_timestamp AS ts,
        COUNT(tr_likes.like_tribble_id) AS likes,
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
    
    function getRepliesByPostId($tribble_id){
  
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
      if($query->num_rows() == 0){
        return;
      } else {
        return $query->result();
      }                  
    }
    
    function searchPostsTitleAndDescription($string,$page,$per_page){
          
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_users.user_avatar AS avatar,,
          tr_images.image_path as image,
          COUNT(DISTINCT tr_likes.like_tribble_id) AS likes,
          COUNT(tr_replies.reply_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_tribble_id','LEFT OUTER');
      
      $this->db->like(array('tr_tribbles.tribble_title'=>$string));
      $this->db->or_like(array('tr_tribbles.tribble_text'=>$string));
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
                       
      $this->db->order_by('tr_tribbles.tribble_timestamp desc');
      
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
    
    function insertComment($post_id,$user_id,$comment_text){
      
      $comment = array(
       'comment_text' => $comment_text,
       'comment_user_id' => $user_id
      );
      $this->db->trans_start();
      $this->db->insert('comments',$comment);
      $comment_id = $this->db->insert_id();            
      $this->db->insert('replies',array('reply_tribble_id'=>$post_id,'reply_comment_id'=>$comment_id));
      $this->db->trans_complete();
      
      if ($this->db->trans_status() === FALSE)
      {
        return false;
      } else {
        return true;
      } 
                                 
    }
    
    function checkIfPostExists($post_id){
      $query = $this->db->get_where('tribbles',array('tribble_id'=>$post_id));
      if($query->num_rows() == 0){
        return false;
      } else {
        return true;
      }
    }        
                                    
} 
  
?>