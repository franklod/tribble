<?php

class Tribbles_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getNewer(){
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_images.image_path as image,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies_ref.ref_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies_ref','tr_tribbles.tribble_id = tr_replies_ref.ref_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
      $this->db->order_by("tr_tribbles.tribble_timestamp", "desc"); 
      $query = $this->db->get();
      $result = $query->result();
      return $result;                            
    }        
    
    function getBuzzing(){
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_images.image_path as image,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies_ref.ref_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies_ref','tr_tribbles.tribble_id = tr_replies_ref.ref_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
      $this->db->order_by("replies", "desc"); 
      $query = $this->db->get();
      $result = $query->result();
      return $result;                
    }
    
    function getLoved(){      
      $this->db->select('
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS username,
          tr_users.user_id AS userid,
          tr_images.image_path as image,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies_ref.ref_tribble_id) AS replies          
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_replies_ref','tr_tribbles.tribble_id = tr_replies_ref.ref_tribble_id','LEFT OUTER');
            
      $this->db->group_by('
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_users.user_realname,
        tr_users.user_id,
        tr_images.image_path
      ');
      $this->db->order_by("likes", "desc"); 
      $query = $this->db->get();
      $result = $query->result();
      return $result;;                    
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
    
    function getTribble($tribble_id){      
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
        tr_users.user_realname username
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_tags','tr_tribbles.tribble_id = tr_tags.tags_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id');
      $this->db->where('tr_tribbles.tribble_id',$tribble_id);
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
    
    function getReplies($tribble_id,$currentPage=0){
      /*
      SELECT 
        tr_tribbles.tribble_text,
        tr_tribbles.tribble_title,
        tr_comments.comment_text,
        tr_replies_ref.ref_timestamp,
        tr_users.user_realname AS com_username,
        tr_users.user_id AS com_userid,
        tr_users1.user_realname AS reb_username,
        tr_users1.user_id AS reb_userid,
        tr_images.image_path AS image
      FROM
        tr_replies_ref
        LEFT OUTER JOIN tr_comments ON (tr_replies_ref.ref_comment_id = tr_comments.comment_id)
        LEFT OUTER JOIN tr_tribbles ON (tr_replies_ref.ref_rebound_id = tr_tribbles.tribble_id)
        LEFT OUTER JOIN tr_users ON (tr_comments.comment_user_id = tr_users.user_id)
        LEFT OUTER JOIN tr_users tr_users1 ON (tr_tribbles.tribble_user_id = tr_users1.user_id)
        LEFT OUTER JOIN tr_images ON (tr_tribbles.tribble_user_id = tr_images.image_tribble_id)
      WHERE
        tr_replies_ref.ref_tribble_id = 54
      */
      
      $this->db->select('
        tr_tribbles.tribble_text AS reb_text,
        tr_tribbles.tribble_title AS reb_title,
        tr_comments.comment_text,
        tr_replies_ref.ref_timestamp as ts,
        tr_users.user_realname AS com_username,
        tr_users.user_id AS com_userid,
        tr_users1.user_realname AS reb_username,
        tr_users1.user_id AS reb_userid,
        tr_images.image_path AS image,
        tr_tribbles.tribble_id AS reb_id
      ');
      $this->db->from('tr_replies_ref');
      $this->db->join('tr_comments','tr_replies_ref.ref_comment_id = tr_comments.comment_id','LEFT OUTER');
      $this->db->join('tr_tribbles','tr_replies_ref.ref_rebound_id = tr_tribbles.tribble_id','LEFT OUTER');
      $this->db->join('tr_users','tr_comments.comment_user_id = tr_users.user_id','LEFT OUTER');
      $this->db->join('tr_users tr_users1','tr_tribbles.tribble_user_id = tr_users1.user_id','LEFT OUTER');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','LEFT OUTER');
      $this->db->where(array('tr_replies_ref.ref_tribble_id'=>$tribble_id));
      
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
      if(!$this->db->insert('replies_ref',array('ref_tribble_id'=>$tribble_id,'ref_comment_id'=>$comment_id))){
        return false;        
      } else {
        return true;
      }                           
    }
    
            
}

?>