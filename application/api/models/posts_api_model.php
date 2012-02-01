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

    function getPostsByUser($user_id,$page,$per_page){                   
      
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
      //  $this->db->join('tr_tag','tr_post.post_id = tr_tag.tag_post_id','inner');           

      $this->db->where(array('tr_user.user_id' => $user_id));
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

        $qr = $query->result();

        $result = array('user_name'=>$qr[0]->user_name,'user_email'=>$qr[0]->user_email,'count'=>$query->num_rows(),'posts'=>$query->result());
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
        tr_post.post_id AS post_id,
        tr_post.post_title AS post_title,
        tr_post.post_text AS post_text,
        tr_post.post_timestamp AS post_date,
        tr_post.post_parent_id AS post_parent_id,
        COUNT(tr_like.like_post_id) AS post_like_count,
        tr_image.image_path as post_image_path,
        tr_image.image_palette as post_image_palette,
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
    
          
      
    function insertPost($post_data,$tags,$image){


      $this->db->trans_start();
      if(!$this->db->insert('post', $post_data)){
         $result->error = 'Error while writing tribble data.';
      }
      
      // log_message('debug','Post data writen');
      
      $post_id = $this->db->insert_id();
      
      $tagdata['tag_content'] = $tags;
      $tagdata['tag_post_id'] = $post_id;
        
      if(!$this->db->insert('tag',$tagdata)){
        $result->error = 'Error while writing tag data.';
      }
      
      // log_message('debug', 'tag data writen');
      
      $imagedata['image_post_id'] = $post_id;
      $imagedata['image_path'] = $image['path'];
      $imagedata['image_palette'] = $image['palette'];
      $imagedata['image_color_ranges'] = $image['ranges'];
      
      if(!$this->db->insert('image',$imagedata)){
        $result->error = 'Error while writing image data.';  
      }
      
      // log_message('debug','image data writen');
      
      
      $likedata['like_post_id'] = $post_id;
      $likedata['like_user_id'] = $post_data['post_user_id'];
            
      if(!$this->db->insert('like',$likedata)){
        $result->error = "Error while writing like data";
      } 
                 
      // log_message('debug','like data writen');
      
      $this->db->trans_complete();
              
      if ($this->db->trans_status() === FALSE){
        return false;
      } else {
        return $post_id;
      }
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



    function deletePost($post_id){
      $this->db->where('post_id', $post_id);
      $this->db->update('post', array('post_is_deleted'=>1));
      ($this->db->affected_rows() == 1) ? $response = true : $response = false;
      return $response;
    }

    function checkUserPostPermission($post_id,$user_id){
      $query = $this->db->get_where('post',array('post_id'=>$post_id));
      $result = $query->result();
      ($result[0]->post_user_id == $user_id) ? $response = true : $response = false;      
      return $response;
    }
                                    
} 
  
?>