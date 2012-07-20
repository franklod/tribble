<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */

if( ! function_exists('gravatar_helper')){
  function get_gravatar( $email, $s = 80, $d = '', $r = 'g', $img = true, $atts = array() ) {
    $CI =& get_instance();
    if( $CI->config->item('secure_site') ) {
        $url = 'https://secure.gravatar.com/avatar/';
    } else {
        $url = 'http://www.gravatar.com/avatar/';
    }
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
      $url = '<img class="avatar" style="vertical-align: middle;" src="' . $url . '"';
      foreach ( $atts as $key => $val )
        $url .= ' ' . $key . '="' . $val . '"';
      $url .= ' />';
    }
    return $url;
  }
}

?>
