<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function GetImagePalette($imagepath,$post_id,$range = 10){

  $command = 'convert '.$imagepath.' -colors '.$range.' +dither -format %c histogram:info: &';

  exec($command, $colors, $exit);

  $palette = array();

  foreach ($colors as $color) {

    preg_match('/( {2,})(\d+:) (\(.+\)) (#.{6}) (.+)/i', $color, $matches);

    if(isset($matches[2])){

      @$PIXELS = (int)substr($matches[2], 0,strlen($matches[2])-1);
      @$PERCENT = ($PIXELS/120000) * 100;
      @$HEX = $matches[4];
      @$CLEAN_RGBA = str_replace(' ', '', substr($matches[3], 1,strlen($matches[3])-2));

      // var_dump($CLEAN_RGBA);      
      $P_RGBA = explode(',', $CLEAN_RGBA);
      $R = $P_RGBA[0];
      $G = $P_RGBA[1];
      $B = $P_RGBA[2];
      if(isset($P_RGBA[3])){
        $A = $P_RGBA[3];
      } else {
        $A = null;
      }
      $RGBA = array('R'=>$R,'G'=>$G,'B'=>$B,'A'=>$A);
      $RGB = array('R'=>$R,'G'=>$G,'B'=>$B);
      $HSL = RGBToHSL($RGB);
      $XYZ = RGBToXYZ(array('R'=>$RGBA['R'],'G'=>$RGBA['G'],'B'=>$RGBA['A']));
      $LAB = XYZToLAB($XYZ);


      $COLOR_DATA = array(
        'palette_post_id' => $post_id,
        'PERCENT' => $PERCENT,    
        'HEX'     => $HEX,
        'RGBA_R'   => $RGBA['R'],
        'RGBA_G'   => $RGBA['G'],
        'RGBA_B'   => $RGBA['B'],
        'RGBA_A'   => $RGBA['A'],
        'HSL_H'   => $HSL['H'],
        'HSL_S'   => $HSL['S'],
        'HSL_L'   => $HSL['L'],
        'LAB_L'   => $LAB['L'],
        'LAB_A'   => $LAB['A'],
        'LAB_B'   => $LAB['B']
        );

        if($PERCENT > 0.2){
          array_push($palette, $COLOR_DATA);
        }
      }       
    }
    return $palette;
  }


  function RGBToHex($r, $g, $b) {
    //String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
    $hex = "#";
    $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

    return $hex;
  }

  function HexToRGB($hex) {
    if(strlen($hex) == 7) {
      $hexvalues = substr($hex, 1);
    } else {
      $hexvalues = $hex;
    }
    $r = hexdec(substr($hexvalues, 0,2));
    $g = hexdec(substr($hexvalues, 2,2));
    $b = hexdec(substr($hexvalues, 4,2));

    $rgb = array('R'=>$r,'G'=>$g,'B'=>$b);

    return $rgb;
  }

  function CieLab2Hue( $var_a, $var_b )          //Function returns CIE-H° value
  {
   $var_bias = 0;

   if ( $var_a >= 0 && $var_b == 0 ) 
   return 0;

   if ( $var_a <  0 && $var_b == 0 ) 
   return 180;

   if ( $var_a == 0 && $var_b >  0 ) 
   return 90;
   if ( $var_a == 0 && $var_b <  0 ) 
   return 270;

   if ( $var_a >  0 && $var_b >  0 ) 
   $var_bias = 0;
   
   if ( $var_a <  0 ) 
   $var_bias = 180;

   if ( $var_a >  0 && $var_b <  0 ) 
   $var_bias = 360;

   return ( rad2deg( atan( $var_b / $var_a ) ) + $var_bias );
 }

 function DELTA_E($LAB1,$LAB2)
 {
  //Color #1 CIE-L*ab values
  $L1 = $LAB1['L'];
  $A1 = $LAB1['A'];
  $B1 = $LAB1['B'];

  //Color #2 CIE-L*ab values 
  $L2 = $LAB2['L'];
  $A2 = $LAB2['A'];
  $B2 = $LAB2['B']; 

  $DeltaE = sqrt( (pow(( $L1 - $L2 ), 2)) + ( pow(( $A1 - $A2 ),2) ) + ( pow(( $B1 - $B2 ),2) ) );

  return $DeltaE;
}

function DELTA_E1994($LAB1,$LAB2){
  //Color #1 CIE-L*ab values
  $L1 = $LAB1['L'];
  $A1 = $LAB1['A'];
  $B1 = $LAB1['B'];

  //Color #2 CIE-L*ab values 
  $L2 = $LAB2['L'];
  $A2 = $LAB2['A'];
  $B2 = $LAB2['B'];         


  $WHT_L = 1;
  $WHT_C = 1;
  $WHT_H = 1;

  $xC1 = sqrt( pow($A1,2) +  pow($B1, 2) );
  $xC2 = sqrt( pow($A2,2) +  pow($B2, 2) );

  $xDL = $L2 - $L1;
  $xDC = $xC2 - $xC1;
  $xDE = sqrt( ( ($L1-$L2)*($L1 - $L2) ) + ( ($A1-$A2)*($A1-$A2) ) + ( ($B1-$B2) * ($B1-$B2) ) );

  if ( sqrt($xDE) > ( sqrt( abs($xDL) ) + sqrt( abs($xDC) ) ) ) 
  {
   $xDH = sqrt( ( $xDE * $xDE ) - ( $xDL * $xDL ) - ( $xDC * $xDC ) );
 }
 else {
   $xDH = 0;
 }

 $xSC = 1 + ( 0.045 * $xC1 );
 $xSH = 1 + ( 0.015 * $xC1 );

 $xDL = $xDL / $WHT_L;
 $xDC = $xDC / $WHT_C * $xSC;
 $xDH = $xDH / $WHT_H * $xSH;


 $Delta_E94 = sqrt( pow($xDL,2) + pow($xDC,2) + pow($xDH,2));
 return $Delta_E94;
}


function DELTA_E2000($LAB1,$LAB2){

  //Color #1 CIE-L*ab values
  $L1 = $LAB1['L'];
  $A1 = $LAB1['A'];
  $B1 = $LAB1['B'];

  //Color #2 CIE-L*ab values 
  $L2 = $LAB2['L'];
  $A2 = $LAB2['A'];
  $B2 = $LAB2['B'];         


  $WHT_L = 1;
  $WHT_C = 1;
  $WHT_H = 1;

  $xC1 = sqrt( $A1 * $A1 + $B1 * $B1 );
  $xC2 = sqrt( $A2 * $A2 + $B2 * $B2 );
  $xCX = ( $xC1 + $xC2 ) / 2;
  $xGX = 0.5 * ( 1 - sqrt( ( pow($xCX,7) ) / ( ( pow($xCX,7) ) + ( pow(25,7) ) ) ) );
  $xNN = ( 1 + $xGX ) * $A1;
  $xC1 = sqrt( $xNN * $xNN + $B1 * $B1 );
  $xH1 = CieLab2Hue( $xNN, $B1 );
  $xNN = ( 1 + $xGX ) * $A2;
  $xC2 = sqrt( $xNN * $xNN + $B2 * $B2 );
  $xH2 = CieLab2Hue( $xNN, $B2 );
  $xDL = $L2 - $L1;
  $xDC = $xC2 - $xC1;
  if ( ( $xC1 * $xC2 ) == 0 ) {
   $xDH = 0;
 }
 else {
   $xNN = round( $xH2 - $xH1, 12 );
   if ( abs( $xNN ) <= 180 ) {
    $xDH = $xH2 - $xH1;
  }
  else {
    if ( $xNN > 180 ) 
    {
      $xDH = $xH2 - $xH1 - 360;
    }
    else             
    {
      $xDH = $xH2 - $xH1 + 360;
    }
  }
}
$xDH = 2 * sqrt( $xC1 * $xC2 ) * sin( deg2rad( $xDH / 2 ) );
$xLX = ( $L1 + $L2 ) / 2;
$xCY = ( $xC1 + $xC2 ) / 2;

if ( ( $xC1 *  $xC2 ) == 0 ) {
 $xHX = $xH1 + $xH2;
}
else {
 $xNN = abs( round( $xH1 - $xH2, 12 ) );
 if ( $xNN >  180 ) {
  if ( ( $xH2 + $xH1 ) <  360 ) 
  {
    $xHX = $xH1 + $xH2 + 360;
  }
  else {
    $xHX = $xH1 + $xH2 - 360;
  }
}
else {
  $xHX = $xH1 + $xH2;
}

$xHX /= 2;
}

$xTX = 1 - 0.17 * cos( deg2rad( $xHX - 30 ) ) + 0.24 * cos( deg2rad( 2 * $xHX ) ) + 0.32 * cos( deg2rad( 3 * $xHX + 6 ) ) - 0.20 * cos( deg2rad( 4 * $xHX - 63 ) );
$xPH = 30 * exp( - ( ( $xHX  - 275 ) / 25 ) * ( ( $xHX  - 275 ) / 25 ) );
$xRC = 2 * sqrt( ( pow($xCY,7) ) / ( ( pow($xCY,7) ) + ( pow(25,7) ) ) );
$xSL = 1 + ( ( 0.015 * ( ( $xLX - 50 ) * ( $xLX - 50 ) ) ) / sqrt( 20 + ( ( $xLX - 50 ) * ( $xLX - 50 ) ) ) );
$xSC = 1 + 0.045 * $xCY;
$xSH = 1 + 0.015 * $xCY * $xTX;
$xRT = - sin( deg2rad( 2 * $xPH ) ) * $xRC;
$xDL = $xDL / ( $WHT_L * $xSL );
$xDC = $xDC / ( $WHT_C * $xSC );
$xDH = $xDH / ( $WHT_H * $xSH );
$Delta_E00 = sqrt( pow($xDL,2) + pow($xDC,2) + pow($xDH,2) + $xRT * $xDC * $xDH );

return $Delta_E00;
}



function XYZToLAB($XYZ) {

  $X = $XYZ['X'];
  $Y = $XYZ['Y'];
  $Z = $XYZ['Z'];

  $LAB = array();

  //ref_X =  95.047   Observer= 2°, Illuminant= D65
  //ref_Y = 100.000
  //ref_Z = 108.883

  $ref_X =  95.047;
  $ref_Y = 100.000;
  $ref_Z = 108.883;

  $var_X = $X / $ref_X;
  $var_Y = $Y / $ref_Y;          
  $var_Z = $Z / $ref_Z;          

  if ( $var_X > 0.008856 ) 
  {
    $var_X = pow($var_X,(1/3));
  }
  else
  {
    $var_X = ( 7.787 * $var_X ) + ( 16 / 116 );
  }
  if ( $var_Y > 0.008856 ) 
  {
    $var_Y = pow($var_Y,(1/3));
  }
  else
  {
    $var_Y = (7.787*$var_Y) + (16/116);
  }
  if ( $var_Z > 0.008856 )
  {
    $var_Z = pow($var_Z,(1/3));
  }
  else {
    $var_Z = (7.787*$var_Z) + (16/116);
  }                    

  $LAB['L'] = ( 116 * $var_Y ) - 16;
  $LAB['A'] = 500 * ( $var_X - $var_Y );
  $LAB['B'] = 200 * ( $var_Y - $var_Z );

  return $LAB;

}

function RGBToXYZ($RGB){

  $XYZ = array();

  $var_R = ( $RGB['R'] / 255 );        //R from 0 to 255
  $var_G = ( $RGB['G'] / 255 );        //G from 0 to 255
  $var_B = ( $RGB['B'] / 255 );       //B from 0 to 255

  if ( $var_R > 0.04045 ) 
  {
    $var_R = pow((($var_R + 0.055)/1.055),2.4);
  } 
  else 
  {
    $var_R = $var_R / 12.92;
  }

  if ( $var_G > 0.04045 ) 
  {
    $var_G = pow((($var_G + 0.055)/ 1.055),2.4);
  }
  else 
  {
    $var_G = $var_G / 12.92;
  }
  if ( $var_B > 0.04045 ) 
  {
    $var_B = pow((($var_B+0.055)/1.055),2.4);
  }
  else 
  {
    $var_B = $var_B / 12.92;
  }                   

  $var_R = $var_R * 100;
  $var_G = $var_G * 100;
  $var_B = $var_B * 100;

  //Observer. = 2°, Illuminant = D65
  $XYZ['X'] = ($var_R * 0.4124) + ($var_G * 0.3576) + ($var_B * 0.1805);
  $XYZ['Y'] = ($var_R * 0.2126) + ($var_G * 0.7152) + ($var_B * 0.0722);
  $XYZ['Z'] = ($var_R * 0.0193) + ($var_G * 0.1192) + ($var_B * 0.9505);

  return $XYZ;

}

function RGBToHSL($RGB){

  $var_r = ( $RGB['R'] / 255 );        //R from 0 to 255
  $var_g = ( $RGB['G'] / 255 );        //G from 0 to 255
  $var_b = ( $RGB['B'] / 255 );       //B from 0 to 255



  // $min = min($var_R,$var_G,$var_B);
  // $max = max($var_R,$var_G,$var_B);
  // $delta = $max - $min;

  $var_min = min($var_r,$var_g,$var_b);
    $var_max = max($var_r,$var_g,$var_b);
    $del_max = $var_max - $var_min;

    $l = ($var_max + $var_min) / 2;

    if ($del_max == 0)
    {
            $h = 0;
            $s = 0;
    }
    else
    {
            if ($l < 0.5)
            {
                    $s = $del_max / ($var_max + $var_min);
            }
            else
            {
                    $s = $del_max / (2 - $var_max - $var_min);
            };

            $del_r = ((($var_max - $var_r) / 6) + ($del_max / 2)) / $del_max;
            $del_g = ((($var_max - $var_g) / 6) + ($del_max / 2)) / $del_max;
            $del_b = ((($var_max - $var_b) / 6) + ($del_max / 2)) / $del_max;

            if ($var_r == $var_max)
            {
                    $h = $del_b - $del_g;
            }
            elseif ($var_g == $var_max)
            {
                    $h = (1 / 3) + $del_r - $del_b;
            }
            elseif ($var_b == $var_max)
            {
                    $h = (2 / 3) + $del_g - $del_r;
            };

            if ($h < 0)
            {
                    $h += 1;
            };

            if ($h > 1)
            {
                    $h -= 1;
            };
    };

  $HSL['H'] = $h * 360;
  $HSL['S'] = $s * 100;
  $HSL['L'] = $l * 100;
  return($HSL);

}

function HSLToRGB($HSL){

$H = $HSL['H'] / 360;
$S = $HSL['S'] / 100;
$L = $HSL['L'] / 100;
  
  if ( $S == 0 )                       //H$SL from 0 to 1
{
   $R = $L * 255;                     //$RGB results from 0 to 255
   $G = $L * 255;
   $B = $L * 255;
}
else
{
  if ( $L < 0.5 ) 
  {
    $var_2 = $L * ( 1 + $S );
  }
  else
  {
    $var_2 = ( $L + $S ) - ( $S * $L );
  }

   $var_1 = 2 * $L - $var_2;

   $R = 255 * Hue_2_RGB( $var_1, $var_2, $H + ( 1 / 3 ) ); 
   $G = 255 * Hue_2_RGB( $var_1, $var_2, $H );
   $B = 255 * Hue_2_RGB( $var_1, $var_2, $H - ( 1 / 3 ) );

   // var_dump($R);
   // var_dump($G);
   // var_dump($B);

   return array('R'=>round($R),'G'=>round($G),'B'=>round($B));
}

}

function Hue_2_RGB( $v1, $v2, $vH )             //Function Hue_2_RGB
{
   if ( $vH < 0 ) $vH += 1;
   if ( $vH > 1 ) $vH -= 1;
   if ( ( 6 * $vH ) < 1 ) return ( $v1 + ( $v2 - $v1 ) * 6 * $vH );
   if ( ( 2 * $vH ) < 1 ) return ( $v2 );
   if ( ( 3 * $vH ) < 2 ) return ( $v1 + ( $v2 - $v1 ) * ( ( 2 / 3 ) - $vH ) * 6 );
   return ( $v1 );
}



?>