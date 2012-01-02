<?

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
//     return array_slice(array_keys($colors), 0, $numColors); 
return $colors;
  } 
}
$images = array('not.jpg','hat.jpg','test.jpg');
foreach($images as  $img){
$y = getImageColorPalette($img,'', 0);
echo "<div>";
echo '<img src="'.$img.'">';
echo "<h2>".count($y)." colors unique extracted</h2>";
foreach ($y as $hex => $count) {
   echo '<div style="background: #' . $hex . '; height: 100px; width: 100px; line-height: 100px; text-align: center; display: inline-block;">'.$count.'|'.$hex.'</div> ';   
}
echo "</div><br>";
}
?>

