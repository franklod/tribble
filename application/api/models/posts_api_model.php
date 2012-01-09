<?php

class Posts_API_model extends CI_Model {
      
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
          tr_users.user_avatar AS avatar,,
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
        return $query->result_array();
      } else {
        return false;
      }                            
    }                        
}

?>