<?php
  
class Posts_API_model extends CI_Model {


    function getPostsByTag($tag,$page,$per_page){                   
      
      $this->db->select('
          tr_post.post_id as id,
          tr_post.post_title as title,
          tr_post.post_text as text,
          tr_post.post_timestamp as ts,
          tr_image.image_path as image,
          tr_user.user_id userid,
          tr_user.user_realname username,
          tr_user.user_avatar avatar,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as likes,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as replies            
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');
      $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');           

      $this->db->like('tr_tag.tag_content', $tag.',', 'after');
      $this->db->or_like('tr_tag.tag_content', ','.$tag.',', 'both');
      $this->db->or_like('tr_tag.tag_content', ','.$tag, 'before');
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
  
      
    function getPostList($type,$page,$per_page){
            
      switch($type){
        case 'new':
          $sort_field = 'tr_post.post_timestamp desc';
          break;
        case 'buzzing':
          $sort_field = 'replies desc, tr_post.post_timestamp desc';
          break;
        case 'loved':
          $sort_field = 'likes desc, tr_post.post_timestamp desc';
          break;
      }
          
      $this->db->select('
          tr_post.post_id AS id,
          tr_post.post_title AS title,
          tr_post.post_text AS `text`,
          tr_post.post_timestamp AS ts,
          tr_user.user_realname AS username,
          tr_user.user_id AS userid,
          tr_user.user_avatar AS avatar,,
          tr_image.image_path as image,
          (SELECT COUNT(1) FROM tr_like WHERE tr_like.like_post_id = tr_post.post_id) as likes,
          (SELECT COUNT(1) FROM tr_reply WHERE tr_reply.reply_post_id = tr_post.post_id AND tr_reply.reply_is_deleted = 0) as replies            
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
      
      if($query = $this->db->get()){
        return $query->result();
      } else {
        return false;
      }                            
    }
    
    function countPosts(){
      if($query = $this->db->get('tr_post')){
        $count = $query->num_rows();
        return $count;
      } else {
        return false;
      }
    }
    
    function getPostById($postId){
      
      $this->db->select('
        tr_post.post_id AS id,
        tr_post.post_title AS title,
        tr_post.post_text AS `text`,
        tr_post.post_timestamp AS ts,
        COUNT(tr_like.like_post_id) AS likes,
        tr_image.image_path as image,
        tr_image.image_palette as palette,
        tr_tag.tag_content as tags,
        tr_user.user_id as userid,
        tr_user.user_realname AS username,
        tr_user.user_avatar AS avatar,
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
        tr_image.image_palette,
        tr_user.user_id,
        tr_user.user_realname
      ');
      $query = $this->db->get();            
      $result = $query->result();
      return $result;
    }
    
    function getRepliesByPostId($post_id){
  
      $this->db->select('
        tr_post.post_text AS reb_text,
        tr_post.post_title AS reb_title,
        tr_comment.comment_text,
        tr_comment.comment_id,
        tr_reply.reply_timestamp as ts,
        tr_user.user_realname AS com_username,
        tr_user.user_id AS com_userid,
        tr_user.user_avatar AS avatar,
        tr_user1.user_realname AS reb_username,
        tr_user1.user_id AS reb_userid,
        tr_user1.user_avatar AS reb_avatar,
        tr_image.image_path AS image,
        tr_post.post_id AS reb_id
      ');
      $this->db->from('tr_reply');
      $this->db->join('tr_comment','tr_reply.reply_comment_id = tr_comment.comment_id','LEFT OUTER');
      $this->db->join('tr_post','tr_reply.reply_rebound_id = tr_post.post_id','LEFT OUTER');
      $this->db->join('tr_user','tr_comment.comment_user_id = tr_user.user_id','LEFT OUTER');
      $this->db->join('tr_user tr_user1','tr_post.post_user_id = tr_user1.user_id','LEFT OUTER');
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
          tr_post.post_id AS id,
          tr_post.post_title AS title,
          tr_post.post_text AS `text`,
          tr_post.post_timestamp AS ts,
          tr_user.user_realname AS username,
          tr_user.user_id AS userid,
          tr_user.user_avatar AS avatar,,
          tr_image.image_path as image,
          COUNT(DISTINCT tr_like.like_post_id) AS likes,
          COUNT(tr_reply.reply_post_id) AS reply          
      ');
      $this->db->from('tr_post');
      $this->db->join('tr_image','tr_post.post_id = tr_image.image_post_id','inner');
      $this->db->join('tr_like','tr_post.post_id = tr_like.like_post_id','inner');
      $this->db->join('tr_user','tr_post.post_user_id = tr_user.user_id','inner');
      $this->db->join('tr_reply','tr_post.post_id = tr_reply.reply_post_id','LEFT OUTER');
      
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
                                    
} 
  
?>