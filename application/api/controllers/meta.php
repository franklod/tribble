<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Posts
 * 
 * @package tribble
 * @author xxx xxx xxx
 * @copyright 2011
 * @version $Id$
 * @access public
 */

require APPPATH . '/libraries/REST_Controller.php';

class Meta extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    //$this->output->enable_profiler(TRUE);
    $cacheTTL = 15 * 60;

  }

  public function tags_get()
  {

    // load the memcached driver
    $this->load->driver('cache');
    // load the posts model
    $this->load->model('Meta_API_model', 'mMeta');

    $limit = $this->get('limit');

    if (!$limit)
      $limit = 12;

    // create the cache key
    $cachekey = sha1('tags/' . $limit);
    // create the final array
    $unique_tags = array();
    // check if the key exists in cache
    if (!$this->cache->memcached->get($cachekey))
    {

      // get the data from the database
      if ($all_tags = $this->mMeta->getTags())
      {
        // iterate over the tags from each post
        foreach ($all_tags as $tag_group)
        {
          // explode the post tags csv string
          $tags = explode(',', $tag_group->tags_content);
          // iterate over each individual tag
          foreach ($tags as $tag)
          {
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
        // sort the final tags array 
        arsort($unique_tags);
        // define the response object structure                
        $object = array('status' => true, 'tags' => array_slice($unique_tags,0,$limit));
        // we have a dataset from the database, let's save it to memcached
        @$this->cache->memcached->save($cachekey, $object, 10 * 60);
        // output the response
        $this->response($object);
      } else
      {
        // we got nothing to show, output error
        $this->response(array('status' => false, 'message' =>
            'Fatal error: Could not get data either from cache or database.'), 404);
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
      $limit = 12;

    // create the cache key
    $cachekey = sha1('colors/' . $limit);
    // create the final array
    $unique_colors = array();
    
    
    //$palettes = $this->mMeta->getColors();
//    foreach($palettes as $palette){
//      $colors = json_decode($palette->image_palette);
//      foreach($colors as $color){
//        var_dump($color);
//      }
//    }
    
    // check if the key exists in cache
    if (!$this->cache->memcached->get($cachekey))
    {
      // get the data from the database
      if ($palettes = $this->mMeta->getColors())
      {        
        // iterate over the tags from each post
        foreach ($palettes as $palette)
        {
          // explode the post tags csv string
          $colors = json_decode($palette->image_palette);
          // iterate over each individual tag
          foreach ($colors as $color)
          {
            $color = "#".$color;
            // check if it exists in the final array
            if (array_key_exists($color, $unique_colors))
            {
              // if its there, increment the counter
              $unique_colors[$color]++;
            } else
            {
              // or set it to 1
              $unique_colors[$color] = 1;
            }
          }
        }
        // sort the final tags array 
        arsort($unique_colors);
        // define the response object structure                
        $object = array('status' => true, 'colors' => array_slice($unique_colors,0,$limit));
        // we have a dataset from the database, let's save it to memcached
        @$this->cache->memcached->save($cachekey, $object, 10 * 60);
        // output the response
        $this->response($object);
      } else
      {
        // we got nothing to show, output error
        $this->response(array('status' => false, 'message' =>
            'Fatal error: Could not get data either from cache or database.'), 404);
      }
    } else
    {
      // the object is cached, send it
      $cache = $this->cache->memcached->get($cachekey);
      $this->response($cache);
    }
  }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
