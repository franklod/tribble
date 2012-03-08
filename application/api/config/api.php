<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['api_site_name'] = 'Tribble API';
$config['api_site_url'] = 'http://api.triblle.local';
$config['api_1_day_cache'] = 24 * 60 * 60;
$config['api_1_hour_cache'] = 60 * 60;
$config['api_30_minutes_cache'] = 30 * 60;
$config['api_10_minutes_cache'] = 10 * 60;

$config['api_methods'] = array(
  'Posts' => array(
    'list_get' => array(
      'uri' => 'posts/list/'
    ),
    'detail_get' => array(
      'uri' => 'posts/detail/id/'
    ),
    'tag_get' => array(
      'uri' => 'posts/tag/'
    ),
    'user_get' => array(
      'uri' => 'posts/user/id/'
    ),
    'find_get' => array(
      'uri' => 'posts/find/txt/'
    ),
    'likes_get' => array(
      'uri' => 'posts/likes/'
    ),
    'color_get' => array(
      'uri' => 'posts/color/'
    )
  ),
  'Meta' => array(
    'tags_get' => array(
      'uri' => 'meta/tags/'
    ),
    'colors_get' => array(
      'uri' => 'meta/colors/'
    ),
    'users_get' => array(
      'uri' => 'meta/users/'
    )
  ),
  'Users' => array(
    'list_get' => array(
      'uri' => 'users/list/'
    ),
    'profile_get' => array(
      'uri' => 'users/profile/id/'
    ),
    'profile_put' => array(
      'uri' => 'users/profile/id/'
    )
  ),
  'Auth' => array(
    'session_put' => array(
      'uri' => 'auth/session/user/'
    )
  )
);

/* End of file api.php */
/* Location: ./application/api/config/api.php */