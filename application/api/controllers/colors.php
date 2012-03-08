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

class Colors extends REST_Controller
{

  public function xyz_get(){

    $RGB1['R'] = $this->get('R1');
    $RGB1['G'] = $this->get('G1');
    $RGB1['B'] = $this->get('B1');

    $XYZ1 = RGBToXYZ($RGB1);
    $LAB1 = XYZToLAB($XYZ1);

    // var_dump($LAB1);

    $RGB2['R'] = $this->get('R2');
    $RGB2['G'] = $this->get('G2');
    $RGB2['B'] = $this->get('B2');

    $XYZ2 = RGBToXYZ($RGB2);
    $LAB2 = XYZToLAB($XYZ2);

    // var_dump($LAB2);

    echo '<div style="width: 100px; height: 100px; float: left; margin-right: 2px; background-color: rgb('.$RGB1['R'].','.$RGB1['G'].','.$RGB1['B'].')"></div>';
    echo '<div style="width: 100px; height: 100px; float: left; margin-right: 2px; background-color: rgb('.$RGB2['R'].','.$RGB2['G'].','.$RGB2['B'].')"></div>';

    echo '<p style="clear: both;">Color distance: '. DELTA_E2000($LAB1,$LAB2).'</p>';

}

public function im_get(){

  $dir = "/home/pedro/work/tribble/data/b5e0eaebec229148d61d1881b27d1865e1bb5003/";
  $url = "data/b5e0eaebec229148d61d1881b27d1865e1bb5003/";
  $imagename = $this->get('f');

  $command = 'convert '.$dir.$imagename.' -colors 10 -format %c histogram:info: &';

  // var_dump($command);

  exec($command, $colors, $exit);

  // var_dump($out);

  $histogram = array();
  $hue_matrix = array();
  $interval = 0.05;

  if($exit == 0){
    foreach ($colors as $color) {

        preg_match('/( {2,})(\d+:) (\(.+\)) (#.{6}) (.+)/i', $color, $matches);

        if(isset($matches[2])){

            @$PIXELS = (int)substr($matches[2], 0,strlen($matches[2])-1);
            @$PERCENT = ($PIXELS/120000) * 100;
            @$HEX = $matches[4];
            @$CLEAN_RGBA = str_replace(' ', '', substr($matches[3], 1,strlen($matches[3])-2));

            // var_dump($matches[5]);

            // var_dump($CLEAN_RGBA);
            @list($R,$G,$B,$A) = explode(',', $CLEAN_RGBA);
            @$RGBA = array('R'=>$R,'G'=>$G,'B'=>$B,'A'=>$A);
            $HSV = RGBToHSV($RGBA['R'],$RGBA['G'],$RGBA['B']);
            $XYZ = RGBToXYZ(array('R'=>$RGBA['R'],'G'=>$RGBA['G'],'B'=>$RGBA['B']));
            $LAB = XYZToLAB($XYZ);
            
            $COLOR_DATA = array(
                'PERCENTAGE'  => $PERCENT,
                'PIXEL_COUNT' => $PIXELS,
                'HEX'         => $HEX,
                'RGBA'        => $RGBA,
                'HSV'         => $HSV,
                'LAB'         => $LAB                
                );

                if($PERCENT > 0.68){
                    @array_push($histogram, $COLOR_DATA);
                }
            }       
        }

        

        foreach($histogram as $HC){
            $hue = 0;
            while($hue < 1){
                if(($HC['HSV']['H']) >= $hue && ($HC['HSV']['H']) < $hue + $interval){
                    // echo 'Hue: '.$hue.' - color: '.$COLOR_DATA['HSV']['H'].'<br>';
                    $hue_matrix[(string)$hue][] = $HC;
                }
                $hue += $interval;
            }
        }

        // var_dump($hue_matrix);


        ksort($hue_matrix);

        foreach ($hue_matrix as $hue_interval => $colors) {
            echo "<h3>Hue: ".$hue_interval." to ".($hue_interval + $interval)." - ".count($colors)." colors</h3>";

            krsort($colors);

            foreach ($colors as $color) {

                if($color['HSV']['V']< 0.5 && $color['HSV']['S'] <= 1){
                    $text = "#fff";
                } else {
                    $text = "#000";
                }

                echo '<div style="font-family: sans-serif; padding: 10px; color: '.$text.'; background-color: '.$color['HEX'].'">';
                echo 'PERGENTAGE: '.$color['PERCENTAGE'].'% - H: '.$color['HSV']['H']. ' S: '.$color['HSV']['S']. ' V: '.$color['HSV']['V'];
                echo '</div>';
                // var_dump($color['HEX']);
            }
        }

        // arsort($histogram);

        echo "<style>pre, h3, hr {clear: both;} p {font-size: 12px; text-align: center; line-height: 36px; margin: 0; }</style>";

        echo '<img src="http://10.134.132.97:8082/'.$url.$imagename.'">';

        echo "<hr>";

        echo "<h3>Palette colors: ".count($histogram)."</h3>";

        ksort($histogram);

        foreach($histogram as $color){

            if($color['HSV']['V']<0.8 && $color['HSV']['S'] <= 1){
                $text = "#fff";
            } else {
                $text = "#000";
            }

            $display =  '<div style="float: left; color: '.$text.';  margin: 1px;">';
            $display .= '<div style="width: 150px; height: 150px; background-color: '.$color['HEX'].'">';
            $display .= '<p>'.round($color['PERCENTAGE'],5).'%</p>';
            $display .= '<p>H: '.$color['HSV']['H'].'</p>';
            $display .= '<p>S: '.$color['HSV']['S'].'</p>';
            $display .= '<p>V: '.$color['HSV']['V'].'</p>';        
            $display .= '</div></div>';

            echo $display;
        }

    }
}

    function color_get(){

        // $this->output->enable_profiler(TRUE);
        
        $hex = $this->get('hex');
        $variance = $this->get('var');
        $min_coverage = $this->get('cov');

        $this->load->model('Colors_API_model','mColors');

        $colors = $this->mColors->colorSearch($hex,$variance,$min_coverage);

        // var_dump($colors);

        echo '<div style="background-color: '.$hex.'; border-radius: 4px; margin: 2px; line-height: 40px; width: 164px; height: 164px;">H: '.$colors['HSL']['H'].'<br>S: '.$colors['HSL']['S'].'<br>L: '.$colors['HSL']['L'].' </div>';
        // echo '<div style="background-color: '.$hex.';">ASDA</div>';
        foreach ($colors['posts'] as $color) {
            // var_dump($color);
            echo '<div style="border-radius: 4px; margin: 2px; width: 80px; height: 80px; float: left; background-color: '.$color->HEX.';">POST: '.$color->post_id.'<br>H: '.$color->HSL_H.' S: '.$color->HSL_S.' L: '.$color->HSL_L.'</div>';
            // echo '<div style="border-radius: 4px; margin: 2px; width: 80px; height: 80px; float: left; background-color: '.$color->HEX.';">H</div>';
        }

        // $GR = 5;

        // for($i=0;$i<=360;$i+=$GR){
        //     $H = $i;
        //     $coiso = '<div style="width: 400px; float: left; margin: 2px;"><h2>'.$H.'</h2>';
        //     echo $coiso;
        //     // echo 'H:'.$H.'<br>';
        //     for($s=100;$s>=0;$s-=$GR){
        //         $S = $s;
        //         // echo 'S:'.$S.'<br>';;
        //         for($l=100;$l>=0;$l-=$GR){
        //             $L = $l;
        //             // echo 'L:'.$L.'<br>';;
        //             $HSL = array('H'=>$H,'S'=>$S,'L'=>$L);
        //             $RGB = HSLToRGB($HSL);
        //             // var_dump($RGB);
        //             $R = $RGB['R'];
        //             $G = $RGB['G'];
        //             $B = $RGB['B'];
        //             $swatch = '<div style="line-height: 20px; text-align: center; width: 40px; height: 40px; float: left; background-color: rgb('.@$R.','.@$G.','.@$B.')">S:'.$S.'<br>L:'.$L.'</div>';
        //             echo $swatch;
        //             // var_dump($HSL);
        //         }
        //     }
        //     echo '</div>';
        // }

    }

    function grid_get(){

        $GR = 5;

        for($i=0;$i<=360;$i+=$GR){
            $H = $i;
            $coiso = '<div style="width: 400px; float: left; margin: 2px;"><h2>'.$H.'</h2>';
            echo $coiso;
            // echo 'H:'.$H.'<br>';
            for($s=100;$s>=0;$s-=$GR){
                $S = $s;
                // echo 'S:'.$S.'<br>';;
                for($l=100;$l>=0;$l-=$GR){
                    $L = $l;
                    // echo 'L:'.$L.'<br>';;
                    $HSL = array('H'=>$H,'S'=>$S,'L'=>$L);
                    $RGB = HSLToRGB($HSL);
                    // var_dump($RGB);
                    $R = $RGB['R'];
                    $G = $RGB['G'];
                    $B = $RGB['B'];
                    $swatch = '<div style="line-height: 20px; text-align: center; width: 40px; height: 40px; float: left; background-color: rgb('.@$R.','.@$G.','.@$B.')">S:'.$S.'<br>L:'.$L.'</div>';
                    echo $swatch;
                    // var_dump($HSL);
                }
            }
            echo '</div>';
        }

    }

}