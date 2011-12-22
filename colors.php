<?php

phpinfo();

function colorPalette($imageFile, $numColors, $granularity = 5) 
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

$pic = "med.jpg";

$palette = colorPalette($pic, 8, 10);

sort($palette, SORT_NUMERIC);

echo "<img src=\"".$pic."\" />"; 
 
foreach($palette as $color) 
{
  $css = "display: inline-block; height: 100px; width: 100px; background: #".$color.";";
  echo "<span style=\"$css\"></span>"; 
} 

?>