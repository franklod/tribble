<?php

class Meta_API_model extends CI_Model {
      
    function getTags(){
      $this->db->select('tag_content');
      $this->db->from('tag');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }
    
    function getColors(){
      $this->db->select('image_palette,image_post_id');
      $this->db->from('image');
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }


    function getColorsExtended($ID){

      $this->db->select('
        color_hex as HEX,
        color_R as R,
        color_G as G,
        color_B as B,
        color_H * 1000 as H,
        color_S * 1000 as S,
        color_V * 1000 as V,
      ');

      $this->db->from('palette');
      $this->db->where(array('post_id'=>$ID));
      
      
      // 
      
      $this->db->order_by('color_H','desc');
      $this->db->order_by('color_V','asc');

      
      $query = $this->db->get();

      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }    

    function updateHSV($id,$hsv_palette){

      $data = array(
         'image_palette_hsv' => $hsv_palette,
      );
      $this->db->where('image_id', $id);
      $this->db->update('image', $data); 
    }

    function transferPalette($ID,$HEX,$R,$G,$B,$H,$S,$V){
      $data = array(
         'post_id' => $ID,
         'color_hex' => $HEX,
         'color_R' => $R,
         'color_G' => $G,
         'color_B' => $B,
         'color_H' => $H,
         'color_S' => $S,
         'color_V' => $V,
      );
      $this->db->insert('palette',$data);
    }
    
    function getUsers()      {
      $this->db->select('
        tr_user.user_id,
        tr_user.user_realname as user_name,
        tr_user.user_email,
        COUNT(tr_post.post_id) AS post_count
      ');
      $this->db->from('tr_user');
      $this->db->join('tr_post','tr_user.user_id = tr_post.post_user_id','inner');
      $this->db->group_by('
        tr_user.user_id,
        tr_user.user_realname,
        tr_user.user_email
      ');
      $this->db->order_by('post_count','DESC');
      $this->db->limit(10);
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      } 
    }
                                    
}

?>