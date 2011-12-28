<?php

class Tribbles_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getNewer(){
      $this->db->select('
          tr_images.image_path AS image,
          tr_images.image_palette AS palette,
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS user,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_id) AS comments
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_tags','tr_tribbles.tribble_id = tr_tags.tags_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_id','left outer');      
      $this->db->group_by('
        tr_images.image_path,
        tr_images.image_palette,
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_tribbles.tribble_timestamp,
        tr_users.user_realname
      ');
      $this->db->order_by("tr_tribbles.tribble_timestamp", "desc"); 
      $query = $this->db->get();
      $result = $query->result();
      return $result;                            
    }        
    
    function getBuzzing(){
      $this->db->select('
          tr_images.image_path AS image,
          tr_images.image_palette AS palette,
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS user,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_id) AS comments
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_tags','tr_tribbles.tribble_id = tr_tags.tags_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_id','left outer');      
      $this->db->group_by('
        tr_images.image_path,
        tr_images.image_palette,
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_tribbles.tribble_timestamp,
        tr_users.user_realname
      ');
      $this->db->order_by("comments", "desc"); 
      $query = $this->db->get();
      $result = $query->result();
      return $result;                
    }
    
    function getLoved(){      
      $this->db->select('
          tr_images.image_path AS image,
          tr_images.image_palette AS palette,
          tr_tribbles.tribble_id AS id,
          tr_tribbles.tribble_title AS title,
          tr_tribbles.tribble_text AS `text`,
          tr_tribbles.tribble_timestamp AS ts,
          tr_users.user_realname AS user,
          COUNT(tr_likes.like_id) AS likes,
          COUNT(tr_replies.reply_id) AS comments
      ');
      $this->db->from('tr_tribbles');
      $this->db->join('tr_images','tr_tribbles.tribble_id = tr_images.image_tribble_id','inner');
      $this->db->join('tr_tags','tr_tribbles.tribble_id = tr_tags.tags_tribble_id','inner');
      $this->db->join('tr_users','tr_tribbles.tribble_user_id = tr_users.user_id','inner');
      $this->db->join('tr_likes','tr_tribbles.tribble_id = tr_likes.like_tribble_id','inner');
      $this->db->join('tr_replies','tr_tribbles.tribble_id = tr_replies.reply_id','left outer');      
      $this->db->group_by('
        tr_images.image_path,
        tr_images.image_palette,
        tr_tribbles.tribble_id,
        tr_tribbles.tribble_title,
        tr_tribbles.tribble_text,
        tr_tribbles.tribble_timestamp,
        tr_users.user_realname
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
         'tribble_views' => 'tribble_views+1'
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
        return true;
      }
    }
              
    
    function getTribble($tribble){
      $query = $this->db->get_where('tribbles', array('tribble__id' => $$tribble));
      return $query->result();
    }
    
    function deletePost(){
      
    }
    
    function reply(){
      
    }
    
    function rebound(){
      
    }
    
    function incrementViews($tribble){
      $this->db->set('tribble_views','tribble_views+1', FALSE);      
      $this->db->where('tribble_id',$tribble);
      $this->db->update('tr_tribbles');
    }
            
}

?>