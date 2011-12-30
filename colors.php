<?
if (!function_exists('getImageColorPalette')) {
    function getImageColorPalette($imageFile, $numColors = 8, $granularity = 5)
    {
        $granularity = max(1, abs((int)$granularity));
        $hexcolors = array();
        $rgbcolors = array();
        $size = @getimagesize($imageFile);
        if ($size === false) {
            user_error("Unable to get image size data");
            return false;
        }

        switch ($size['mime']) {
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

        if (!$img) {
            user_error("Unable to open image file");
            return false;
        }
        for ($x = 0; $x < $size[0]; $x += $granularity) {
            for ($y = 0; $y < $size[1]; $y += $granularity) {
                $thisColor = imagecolorat($img, $x, $y);
                $rgb = imagecolorsforindex($img, $thisColor);
                $red = round(round(($rgb['red'] / 0x33)) * 0x33);
                $green = round(round(($rgb['green'] / 0x33)) * 0x33);
                $blue = round(round(($rgb['blue'] / 0x33)) * 0x33);


                $thisHEX = sprintf('%02X%02X%02X', $rgb['red'], $rgb['green'], $rgb['blue']);


                $hexcolors[] = $thisHEX;
                $rgbcolors[] = $rgb['red'].','.$rgb['green'].','.$rgb['blue'];


            }
        }

        echo "<p>hex: " . count($hexcolors) . " - rgb: ". count($rgbcolors) ."</p>";
        
        $uniqueRGB = array_unique($rgbcolors);
        $uniqueHEX = array_unique($hexcolors);
        
        sort($uniqueRGB);
        sort($uniqueHEX);
        
        $uniqueRGB2 = $uniqueRGB;
        
        for($i=0;$i<1;$i++){
          for($i=0;$i<count($uniqueRGB);$i++){
          $rgb_1 = explode(',',$uniqueRGB[$i]);
          $rgb_2 = explode(',',$uniqueRGB2[$i]);                    
          $diff = pythdiff($rgb_1[0],$rgb_1[1],$rgb_1[2],$rgb_2[0],$rgb_2[1],$rgb_2[2]);          
            if($diff<300){
              //unset($uniqueHEX[$i]);
              unset($uniqueRGB[$i]);                        
            }                                         
          }
          $arDif = array_diff($uniqueRGB,$uniqueRGB2);
          
          print_r($arDif);
          
          foreach($arDif as $uElement){
            echo($uElement);
          }          
        }
                        
        echo "<p>hex: " . count($uniqueHEX) . " - rgb: ". count($uniqueRGB) ."</p>";

        $colors['hex'] = $uniqueHEX;
        $colors['rgb'] = $uniqueRGB;

        //arsort($colors);
        //return array_slice(array_keys($colors), 0, $numColors);
        return $colors;
    }


}

function pythdiff($R1,$G1,$B1,$R2,$G2,$B2){
    $RD = $R1 - $R2;
    $GD = $G1 - $G2;
    $BD = $B1 - $B2;
 
    return  round(sqrt( $RD * $RD + $GD * $GD + $BD * $BD )) ;
}

function RGB_TO_HSV($R, $G, $B) // RGB Values:Number 0-255

{ // HSV Results:Number 0-1
    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $V = $var_Max;

    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        $S = $del_Max / $var_Max;

        $del_R = ((($max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
        $del_G = ((($max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
        $del_B = ((($max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

        if ($var_R == $var_Max)
            $H = $del_B - $del_G;
        else
            if ($var_G == $var_Max)
                $H = (1 / 3) + $del_R - $del_B;
            else
                if ($var_B == $var_Max)
                    $H = (2 / 3) + $del_G - $del_R;

        if (H < 0)
            $H++;
        if (H > 1)
            $H--;
    }

    $HSL['H'] = $H;
    $HSL['S'] = $S;
    $HSL['V'] = $V;

    return $HSL;
}

function HSV_TO_RGB($H, $S, $V) // HSV Values:Number 0-1

{ // RGB Results:Number 0-255
    $RGB = array();

    if ($S == 0) {
        $R = $G = $B = $V * 255;
    } else {
        $var_H = $H * 6;
        $var_i = floor($var_H);
        $var_1 = $V * (1 - $S);
        $var_2 = $V * (1 - $S * ($var_H - $var_i));
        $var_3 = $V * (1 - $S * (1 - ($var_H - $var_i)));

        if ($var_i == 0) {
            $var_R = $V;
            $var_G = $var_3;
            $var_B = $var_1;
        } else
            if ($var_i == 1) {
                $var_R = $var_2;
                $var_G = $V;
                $var_B = $var_1;
            } else
                if ($var_i == 2) {
                    $var_R = $var_1;
                    $var_G = $V;
                    $var_B = $var_3;
                } else
                    if ($var_i == 3) {
                        $var_R = $var_1;
                        $var_G = $var_2;
                        $var_B = $V;
                    } else
                        if ($var_i == 4) {
                            $var_R = $var_3;
                            $var_G = $var_1;
                            $var_B = $V;
                        } else {
                            $var_R = $V;
                            $var_G = $var_1;
                            $var_B = $var_2;
                        }

                        $R = $var_R * 255;
        $G = $var_G * 255;
        $B = $var_B * 255;
    }

    $RGB['R'] = $R;
    $RGB['G'] = $G;
    $RGB['B'] = $B;

    return $RGB;
}

$y = getImageColorPalette('test.jpg', '', 5);

foreach ($y['hex'] as $color) {
   echo '<div style="background: #' . $color . '; height: 100px; width: 100px; line-height: 100px; display: inline-block;">#'.$color.' </div> ';
}

?>

