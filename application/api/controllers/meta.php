<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Meta extends REST_Controller
{

  var $ttl;

  public function __construct()
  {
    parent::__construct();

    $this->ttl->one_day = $this->config->item('api_1_day_cache');
    $this->ttl->one_hour = $this->config->item('api_1_hour_cache');
    $this->ttl->thirty_minutes = $this->config->item('api_30_minutes_cache');
    $this->ttl->ten_minutes = $this->config->item('api_10_minutes_cache');

    // $this->output->enable_profiler(TRUE);
  }

  public function tags_get()
  {


    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Meta_API_model', 'mMeta');

    $limit = (int)$this->get('limit');

    // create the cache key
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri'].$limit);
    
    // create the final array
    $unique_tags = array();
    // check if the key exists in cache
    if (!$this->cache->memcached->get($cachekey))
    {

      $all_tags = $this->mMeta->getTags();
      // get the data from the database
      if ($all_tags !== FALSE)
      {
        if($all_tags !== 0){
          // iterate over the tags from each post
          foreach ($all_tags as $tag_group)
          {
            // explode the post tags csv string
            $tags = explode(',', $tag_group->tag_content);
            // iterate over each individual tag
            foreach ($tags as $tag)
            {
              $tag = trim($tag);
              // check if it exists in the final array
              if (array_key_exists($tag, $unique_tags))
              {
                // if its there, increment the counter
                $unique_tags[$tag]++;
              } else
              {
                // or set it to 1
                $unique_tags[$tag] = 1;
              }
            }
          }        
          // if the limit is 0 give 'em all the tags
          if ($limit == 0){
            uksort($unique_tags, 'strcasecmp');
            // define the response object structure
            $object = array('request_status' => true, 'tags' => $unique_tags);
          } else {
            arsort($unique_tags);
            // define the response object structure                
            $object = array('request_status' => true, 'tags' => array_slice($unique_tags,0,$limit)); 
          }        
          // we have a dataset from the database, let's save it to memcached
          @$this->cache->memcached->save($cachekey, $object, $this->ttl->thirty_minutes);
          // output the response
          $this->response($object);
        } else {
          $this->response(array('request_status'=>true,'count'=>$all_tags,'tags'=>$unique_tags));
        }
      } else
      {
        // we got nothing to show, output error
        $this->response(array('request_status' => false, 'message' => lang('F_DATA_READ')), 404);
      }
    } else
    {
      // the object is cached, send it
      $cache = $this->cache->memcached->get($cachekey);
      $this->response($cache);
    }
  }
  
  public function colors_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Meta_API_model', 'mMeta');

    $limit = $this->get('limit');

    if (!$limit)
      $limit = 70;

    // create the cache key
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri']);

    // create the final array
    $unique_colors = array();
    
    // check if the key exists in cache
    if (!$this->cache->memcached->get($cachekey))
    {
      // get the data from the database
      if ($palettes = $this->mMeta->getColors())
      {              
        // define the response object structure                
        $object = array('status' => true, 'colors' => array_slice($palettes,0,$limit));
        // we have a dataset from the database, let's save it to memcached
        @$this->cache->memcached->save($cachekey, $object, $this->ttl->thirty_minutes);
        // output the response
        $this->response($object);
      } else
      {
        // we got nothing to show, output error
        // $this->response(array('status' => false, 'message' => lang('F_DATA_READ')), 404);
      }
    } else
    {
      // the object is cached, send it
      $cache = $this->cache->memcached->get($cachekey);
      $this->response($cache);
    }
  }


  public function testcolors_get(){
    $id = $this->get('id');
    $this->load->model('Meta_API_model', 'mMeta');
    $colors = $this->mMeta->getColorsExtended($id);

    // var_dump($colors);

    foreach($colors as $color){
      echo '<div style="background-color: '.$color->HEX.'">HEX: '.$color->HEX.' | H: '.$color->H.' | S: '.$color->S.' | V: '.$color->V.' | R: '.$color->R.' | G: '.$color->G.' | B: '.$color->B.'</div>';
    }

  }

  public function searchcolor_get(){
    $id = '#'.$this->get('hex');
    $v = $this->get('v');
    $p = $this->get('p');
    $this->load->model('Meta_API_model', 'mMeta');
    $colors = $this->mMeta->colorSearch($id,$v);

    // var_dump($colors);

    foreach($colors as $color){
      echo "<h3>".$color->post_id."</h3>";
      echo '<div style="background-color: '.$color->color_hex.'">H: '.$color->color_H.' | S: '.$color->color_S.' | V: '.$color->color_V.' | R: '.$color->color_R.' | G: '.$color->color_G.' | B: '.$color->color_B.'</div>';
    }

  }


  public function updatepalette_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Meta_API_model', 'mMeta');

    $limit = $this->get('limit');

    if (!$limit)
    $limit = 28;

    // create the final array
        
    // get the data from the database
    if ($images = $this->mMeta->getImagePaths())
    {        
      foreach($images as $image){
        $full_path = $this->config->item('app_path').$image->image_path;
        // var_dump($full_path);
        $this->mMeta->transferPalette(GetImagePalette($full_path,$image->post_id));        
      }
    }
  }

  public function users_get()
  {
    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Meta_API_model', 'mMeta');

    // create the cache key
    $api_methods = $this->config->item('api_methods');
    $cachekey = sha1($api_methods[__CLASS__][__FUNCTION__]['uri']);
    
    
    if(!$this->cache->memcached->get($cachekey))
    {
      $users = $this->mMeta->getUsers();
      if($users)
      {
        $object = array(
          'request_status' => true,
          'users' => $users
        );
        $this->cache->memcached->save($cachekey,$object, $this->ttl->ten_minutes);
        $this->response($object);
      } else 
      {
         $object = array(
          'request_status' => false,
          'message' => 'Could not get the user list'
        );
         
      }
    } else {
      $this->response($this->cache->memcached->get($cachekey ));
    }
        
  }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */