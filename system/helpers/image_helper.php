<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Image Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Pedro Correia@SAPO.pt
 */

// ------------------------------------------------------------------------

/**
 * getImageColorPalette
 *
 * Lets you extract the n most common colors in an image and returns an array with the hex values.
 * Takes jpeg and png image files.
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if ( ! function_exists('getImageColorPalette'))
{
  function getImageColorPalette($imageFile, $numColors = 8, $granularity = 5) 
  { 
     $granularity = max(1, abs((int)$granularity)); 
     $colors = array(); 
     $size = @getimagesize($imageFile); 
     if($size === false) 
     { 
        user_error("Unable to get image size data"); 
        return false; 
     }
     
     switch($size['mime']){
        case 'image/png':
          $img = @imagecreatefrompng($imageFile);
          break;
        case 'image/jpeg':
          $img = @imagecreatefromjpeg($imageFile);
          break;
        default: 
          user_error('Unsupported image type');
          return false;       
     }   
      
     if(!$img) 
     { 
        user_error("Unable to open image file"); 
        return false; 
     } 
     for($x = 0; $x < $size[0]; $x += $granularity) 
     { 
        for($y = 0; $y < $size[1]; $y += $granularity) 
        { 
           $thisColor = imagecolorat($img, $x, $y); 
           $rgb = imagecolorsforindex($img, $thisColor); 
           $red = round(round(($rgb['red'] / 0x33)) * 0x33); 
           $green = round(round(($rgb['green'] / 0x33)) * 0x33); 
           $blue = round(round(($rgb['blue'] / 0x33)) * 0x33); 
           $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue); 
           if(array_key_exists($thisRGB, $colors)) 
           { 
              $colors[$thisRGB]++; 
           } 
           else 
           { 
              $colors[$thisRGB] = 1; 
           } 
        } 
     } 
     arsort($colors); 
     return array_slice(array_keys($colors), 0, $numColors); 
  } 
}

function getThumb($img){
  $thumb = preg_replace('/(\.jpg|\.png)/','_thumb$1',$img);
  return $thumb;
}

/* End of file image_helper.php */
/* Location: ./system/helpers/image_helper.php */