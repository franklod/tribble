<?php

class Meta_API_model extends CI_Model {
      
    function getTags(){
      $this->db->select('tag_content');
      $this->db->from('tag');
      $this->db->join('tr_post','tr_tag.tag_post_id = tr_post.post_id','inner');
      $this->db->where(array('tr_post.post_is_deleted'=>0));
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }


    function getColors(){
      $this->db->select('HEX');
      $this->db->distinct();
      $this->db->from('palette');
      $this->db->join('tr_post','tr_palette.palette_post_id = tr_post.post_id','inner');
      $this->db->where(array('tr_post.post_is_deleted'=>0));
      $this->db->where('tr_palette.HSL_L > 20');
      $this->db->where('tr_palette.HSL_S > 60');
      $this->db->order_by('HSL_H','ASC');
      // $this->db->order_by('HSL_L','DESC');   
      $query = $this->db->get();
      if($query->num_rows() > 0){
        return $query->result();
      } else {
        return $query->num_rows();
      }                                              
    }
    
    function getImagePaths(){
      $this->db->select('image_path,image_post_id as post_id');
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

    function transferPalette($palette){
      foreach ($palette as $color) {
        $this->db->insert('palette',$color);
      }      
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
      $this->db->where(array('tr_post.post_is_deleted'=>0));
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