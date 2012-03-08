<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * ColorUtilities
 * 
 * @access public
 */
class ColorUtilities()
{


  /**
   * RGBToHex()
   * 
   * @param array $RGB
   * @return string
   */
  public function RGBToHex($RGB)
  {
    $R = $RGB['R'];
    $G = $RGB['G'];
    $B = $RGB['B'];
    $hex = "#";
    $hex .= str_pad(dechex($R), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($G), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($B), 2, "0", STR_PAD_LEFT);
    return $hex;
  }


  /**
   * HexToRGB()
   * 
   * @param string $HEX
   * @return array
   */
  public function HexToRGB($HEX)
  {
  
    (strpos($HEX,'#') != FALSE) : $hexvalues = substr($HEX, 1) ? $hexvalues = $HEX;  

    $r = hexdec(substr($hexvalues, 0, 2));
    $g = hexdec(substr($hexvalues, 2, 2));
    $b = hexdec(substr($hexvalues, 4, 2));
    $rgb = array(
      'r' => $r,
      'g' => $g,
      'b' => $b);
    return $rgb;
  }


  /**
   * CieLab2Hue()
   * 
   * @param float $var_a
   * @param float $var_b
   * @return float
   */
  public function CieLab2Hue($var_a, $var_b)
  {
    $var_bias = 0;

    if ($var_a >= 0 && $var_b == 0)
      return 0;

    if ($var_a < 0 && $var_b == 0)
      return 180;

    if ($var_a == 0 && $var_b > 0)
      return 90;
    if ($var_a == 0 && $var_b < 0)
      return 270;

    if ($var_a > 0 && $var_b > 0)
      $var_bias = 0;

    if ($var_a < 0)
      $var_bias = 180;

    if ($var_a > 0 && $var_b < 0)
      $var_bias = 360;

    return (rad2deg(atan($var_b / $var_a)) + $var_bias);
  }


  /**
   * DELTA_E()
   * 
   * @param array $LAB1
   * @param array $LAB2
   * @return float
   */
  public function DELTA_E($LAB1, $LAB2)
  {
    //Color #1 CIE-L*ab values
    $L1 = $LAB1['L'];
    $A1 = $LAB1['A'];
    $B1 = $LAB1['B'];

    //Color #2 CIE-L*ab values
    $L2 = $LAB2['L'];
    $A2 = $LAB2['A'];
    $B2 = $LAB2['B'];

    $DeltaE = sqrt((pow(($L1 - $L2), 2)) + (pow(($A1 - $A2), 2)) + (pow(($B1 - $B2), 2)));

    return $DeltaE;
  }


  /**
   * DELTA_E1994()
   * 
   * @param array $LAB1
   * @param array $LAB2
   * @return float
   */
  public function DELTA_E1994($LAB1, $LAB2)
  {
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

    $xC1 = sqrt(pow($A1, 2) + pow($B1, 2));
    $xC2 = sqrt(pow($A2, 2) + pow($B2, 2));

    $xDL = $L2 - $L1;
    $xDC = $xC2 - $xC1;
    $xDE = sqrt((($L1 - $L2) * ($L1 - $L2)) + (($A1 - $A2) * ($A1 - $A2)) + (($B1 - $B2) * ($B1 - $B2)));

    if (sqrt($xDE) > (sqrt(abs($xDL)) + sqrt(abs($xDC))))
    {
      $xDH = sqrt(($xDE * $xDE) - ($xDL * $xDL) - ($xDC * $xDC));
    } else
    {
      $xDH = 0;
    }

    $xSC = 1 + (0.045 * $xC1);
    $xSH = 1 + (0.015 * $xC1);

    $xDL = $xDL / $WHT_L;
    $xDC = $xDC / $WHT_C * $xSC;
    $xDH = $xDH / $WHT_H * $xSH;

    $Delta_E94 = sqrt(pow($xDL, 2) + pow($xDC, 2) + pow($xDH, 2));
    return $Delta_E94;
  }


  /**
   * DELTA_E2000()
   * 
   * @param array $LAB1
   * @param array $LAB2
   * @return float
   */
  public function DELTA_E2000($LAB1, $LAB2)
  {
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

    $xC1 = sqrt($A1 * $A1 + $B1 * $B1);
    $xC2 = sqrt($A2 * $A2 + $B2 * $B2);
    $xCX = ($xC1 + $xC2) / 2;
    $xGX = 0.5 * (1 - sqrt((pow($xCX, 7)) / ((pow($xCX, 7)) + (pow(25, 7)))));
    $xNN = (1 + $xGX) * $A1;
    $xC1 = sqrt($xNN * $xNN + $B1 * $B1);
    $xH1 = CieLab2Hue($xNN, $B1);
    $xNN = (1 + $xGX) * $A2;
    $xC2 = sqrt($xNN * $xNN + $B2 * $B2);
    $xH2 = CieLab2Hue($xNN, $B2);
    $xDL = $L2 - $L1;
    $xDC = $xC2 - $xC1;
    if (($xC1 * $xC2) == 0)
    {
      $xDH = 0;
    } else
    {
      $xNN = round($xH2 - $xH1, 12);
      if (abs($xNN) <= 180)
      {
        $xDH = $xH2 - $xH1;
      } else
      {
        if ($xNN > 180)
        {
          $xDH = $xH2 - $xH1 - 360;
        } else
        {
          $xDH = $xH2 - $xH1 + 360;
        }
      }
    }
    $xDH = 2 * sqrt($xC1 * $xC2) * sin(deg2rad($xDH / 2));
    $xLX = ($L1 + $L2) / 2;
    $xCY = ($xC1 + $xC2) / 2;

    if (($xC1 * $xC2) == 0)
    {
      $xHX = $xH1 + $xH2;
    } else
    {
      $xNN = abs(round($xH1 - $xH2, 12));
      if ($xNN > 180)
      {
        if (($xH2 + $xH1) < 360)
        {
          $xHX = $xH1 + $xH2 + 360;
        } else
        {
          $xHX = $xH1 + $xH2 - 360;
        }
      } else
      {
        $xHX = $xH1 + $xH2;
      }

      $xHX /= 2;
    }

    $xTX = 1 - 0.17 * cos(deg2rad($xHX - 30)) + 0.24 * cos(deg2rad(2 * $xHX)) + 0.32 * cos(deg2rad(3 * $xHX + 6)) - 0.20 * cos(deg2rad(4 * $xHX - 63));
    $xPH = 30 * exp(-(($xHX - 275) / 25) * (($xHX - 275) / 25));
    $xRC = 2 * sqrt((pow($xCY, 7)) / ((pow($xCY, 7)) + (pow(25, 7))));
    $xSL = 1 + ((0.015 * (($xLX - 50) * ($xLX - 50))) / sqrt(20 + (($xLX - 50) * ($xLX - 50))));
    $xSC = 1 + 0.045 * $xCY;
    $xSH = 1 + 0.015 * $xCY * $xTX;
    $xRT = -sin(deg2rad(2 * $xPH)) * $xRC;
    $xDL = $xDL / ($WHT_L * $xSL);
    $xDC = $xDC / ($WHT_C * $xSC);
    $xDH = $xDH / ($WHT_H * $xSH);
    $Delta_E00 = sqrt(pow($xDL, 2) + pow($xDC, 2) + pow($xDH, 2) + $xRT * $xDC * $xDH);

    return $Delta_E00;
  }


  /**
   * XYZToLAB()
   * 
   * @param mixed $XYZ
   * @return
   */
  public function XYZToLAB($XYZ)
  {

    $X = $XYZ['X'];
    $Y = $XYZ['Y'];
    $Z = $XYZ['Z'];

    $LAB = array();

    //ref_X =  95.047   Observer= 2°, Illuminant= D65
    //ref_Y = 100.000
    //ref_Z = 108.883

    $ref_X = 95.047;
    $ref_Y = 100.000;
    $ref_Z = 108.883;

    $var_X = $X / $ref_X;
    $var_Y = $Y / $ref_Y;
    $var_Z = $Z / $ref_Z;

    if ($var_X > 0.008856)
    {
      $var_X = pow($var_X, (1 / 3));
    } else
    {
      $var_X = (7.787 * $var_X) + (16 / 116);
    }
    if ($var_Y > 0.008856)
    {
      $var_Y = pow($var_Y, (1 / 3));
    } else
    {
      $var_Y = (7.787 * $var_Y) + (16 / 116);
    }
    if ($var_Z > 0.008856)
    {
      $var_Z = pow($var_Z, (1 / 3));
    } else
    {
      $var_Z = (7.787 * $var_Z) + (16 / 116);
    }

    $LAB['L'] = (116 * $var_Y) - 16;
    $LAB['A'] = 500 * ($var_X - $var_Y);
    $LAB['B'] = 200 * ($var_Y - $var_Z);

    return $LAB;

  }


  /**
   * RGBToXYZ()
   * 
   * @param array $RGB
   * @return array
   */
  public function RGBToXYZ($RGB)
  {

    $XYZ = array();

    $var_R = ($RGB['R'] / 255); //R from 0 to 255
    $var_G = ($RGB['G'] / 255); //G from 0 to 255
    $var_B = ($RGB['B'] / 255); //B from 0 to 255

    if ($var_R > 0.04045)
    {
      $var_R = pow((($var_R + 0.055) / 1.055), 2.4);
    } else
    {
      $var_R = $var_R / 12.92;
    }

    if ($var_G > 0.04045)
    {
      $var_G = pow((($var_G + 0.055) / 1.055), 2.4);
    } else
    {
      $var_G = $var_G / 12.92;
    }
    if ($var_B > 0.04045)
    {
      $var_B = pow((($var_B + 0.055) / 1.055), 2.4);
    } else
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

  /**
   * RGBToHSV()
   * 
   * @param array $RGB
   * @return array
   */
  public function RGBToHSV($RGB) // RGB Values:Number 0-255
  { // HSV Results:Number 0-1

    $R = $RGB['R'];
    $G = $RGB['G'];
    $B = $RGB['B'];

    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $V = $var_Max;

    if ($del_Max == 0)
    {
      $H = 0;
      $S = 0;
    } else
    {
      $S = $del_Max / $var_Max;

      $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
      $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
      $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

      if ($var_R == $var_Max)
        $H = $del_B - $del_G;
      else
        if ($var_G == $var_Max)
          $H = (1 / 3) + $del_R - $del_B;
        else
          if ($var_B == $var_Max)
            $H = (2 / 3) + $del_G - $del_R;

      if ($H < 0)
        $H++;
      if ($H > 1)
        $H--;
    }

    $HSL['H'] = $H;
    $HSL['S'] = $S;
    $HSL['V'] = $V;

    return $HSL;
  }

 
  /**
   * HSVToRGB()
   * 
   * @param array $HSV
   * @return array
   */
  public function HSVToRGB($HSV) // HSV Values:Number 0-1
  { // RGB Results:Number 0-255
  
    $H = $RGB['H'];
    $S = $RGB['S'];
    $V = $RGB['V'];
    
    $RGB = array();

    if ($S == 0)
    {
      $R = $G = $B = $V * 255;
    } else
    {
      $var_H = $H * 6;
      $var_i = floor($var_H);
      $var_1 = $V * (1 - $S);
      $var_2 = $V * (1 - $S * ($var_H - $var_i));
      $var_3 = $V * (1 - $S * (1 - ($var_H - $var_i)));

      if ($var_i == 0)
      {
        $var_R = $V;
        $var_G = $var_3;
        $var_B = $var_1;
      } else
        if ($var_i == 1)
        {
          $var_R = $var_2;
          $var_G = $V;
          $var_B = $var_1;
        } else
          if ($var_i == 2)
          {
            $var_R = $var_1;
            $var_G = $V;
            $var_B = $var_3;
          } else
            if ($var_i == 3)
            {
              $var_R = $var_1;
              $var_G = $var_2;
              $var_B = $V;
            } else
              if ($var_i == 4)
              {
                $var_R = $var_3;
                $var_G = $var_1;
                $var_B = $V;
              } else
              {
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
}
