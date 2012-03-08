<?php

class Colors_API_model extends CI_Model {


  function colorSearch($HEX,$VARIATION = 40,$COVERAGE = 10){

    if(strlen($HEX) != 6){
      // exit('VAI MEXER NO CARALHO!');
    }

    $RGB = HEXToRGB($HEX);
    $HSL = RGBToHSL($RGB);
    $XYZ = RGBToXYZ($RGB);
    $LAB1 = XYZToLAB($XYZ);


    // CHECK IF HUE IS NEAR 0 - RED
    ($HSL['H'] >= 350 || $HSL['H'] <= 20) ? $RED = true : $RED = false;
    // CHECK IF HUE IS NEAR 0 - RED
    ($HSL['H'] >= 25 && $HSL['H'] <= 70) ? $YELLOW = true : $YELLOW = false;
    // CHECK IF HUE IS NEAR 120 - GREEN
    ($HSL['H'] >= 75 && $HSL['H'] < 165) ? $GREEN = true : $GREEN = false;
    // CHECK IF HUE IS NEAR 240 - BLUE
    ($HSL['H'] >= 170 && $HSL['H'] < 255) ? $BLUE = true : $BLUE = false;

    $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
    $L_VARIATION = $VARIATION  / 1.2;
    $S_VARIATION = $VARIATION * 3.5;

    if($RED){
      echo "RED<br>";
      $H_VARIATION =  (($VARIATION * 360) / 100) / 30;
      $L_VARIATION = $VARIATION  * 1.2;
      $S_VARIATION = $VARIATION / 1.1;
    } elseif ($YELLOW) {
      echo "YELLOW<br>";
      $H_VARIATION =  (($VARIATION * 360) / 100) / 80;
      $L_VARIATION = $VARIATION  / 1;
      $S_VARIATION = $VARIATION * 1;      
    } elseif ($GREEN) {
      echo "GREEN<br>";
      $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
      $L_VARIATION = $VARIATION  / 1;
      $S_VARIATION = $VARIATION * 1;      
    } elseif ($BLUE) {
      echo "BLUE<br>";
      $H_VARIATION =  (($VARIATION * 360) / 100) / 20;
      $L_VARIATION = $VARIATION  / 1.2;
      $S_VARIATION = $VARIATION * 3.5; 
    }
    
    $DELTA_VARIATION = ( 80 / 100 ) * $VARIATION;

    
    // var_dump($DELTA_VARIATION);

    $HB = $HSL['H'] - $H_VARIATION * 3.5;
    $HT = $HSL['H'] + $H_VARIATION * 3;

    ($HB < 0) ? $H_BOTTOM = 360 + $HB : $H_BOTTOM = $HB;
    ($HT > 360) ? @$H_TOP += $HT - 360 : $H_TOP = $HT;

    $DELTA = 0;

     if($H_BOTTOM > $H_TOP){
      $DELTA = 360 - $H_BOTTOM;
      $H_BOTTOM = 0;
      $H_TOP = $H_TOP + $DELTA;
     }

    $SB = $HSL['S'] - $S_VARIATION;
    $ST = $HSL['S'] + $S_VARIATION;

    ($SB < 0 ) ? $S_BOTTOM = 0 : $S_BOTTOM = $SB;
    ($ST > 100 ) ? $S_TOP = 100 : $S_TOP = $ST;

    $LB = $HSL['L'] - $L_VARIATION;
    $LT = $HSL['L'] + $L_VARIATION;

    ($LB < 0 ) ? $L_BOTTOM = 0 : $L_BOTTOM = $LB;
    ($LT > 100 ) ? $L_TOP = 100 : $L_TOP = $LT;

    // check if the search color is grey(ish)
    ($HSL['S'] < 3) ? $is_grey = true : $is_grey = false;

    // $is_grey = false;

    echo('HUE VARIATION: '. $H_VARIATION.'<BR>');
    echo('SATURATION VARIATION: '. $S_VARIATION.'<BR>');
    echo('LIGHTNESS VARIATION: '. $L_VARIATION.'<BR>');
    echo('DELTA VARIATION: '. $DELTA_VARIATION.'<BR><BR>');
    
    echo('TOP HUE: '. $H_TOP.'<BR>');
    echo('BOTTOM HUE: '. $H_BOTTOM.'<BR><BR>');
    

    echo('TOP SATURATION: '. $S_TOP.'<BR>');
    echo('BOTTOM SATURATION: '. $S_BOTTOM.'<BR><BR>');
    
    echo('TOP VALUE: '. $L_TOP.'<BR>');
    echo('BOTTOM VALUE: '. $L_BOTTOM.'<BR>');
    
  

    $this->db->select('tr_palette.palette_post_id, HEX, PERCENT, HSL_H, HSL_S, HSL_L, LAB_L as L, LAB_A as A, LAB_B as B');
    $this->db->distinct();
    $this->db->from('palette');
    $this->db->join('post','tr_post.post_id = tr_palette.palette_post_id','inner');
    $this->db->where('tr_palette.HSL_H + '.$DELTA.' BETWEEN '.$H_BOTTOM.' AND '.$H_TOP);
    $this->db->where('tr_palette.HSL_S BETWEEN '.$S_BOTTOM.' AND '.$S_TOP);
    $this->db->where('tr_palette.HSL_L BETWEEN '.$L_BOTTOM.' AND '.$L_TOP);
    $this->db->where(array('tr_post.post_is_deleted'=>0));
    $this->db->group_by('post_id');
    $query = $this->db->get();

    $COLORS = $query->result();

    // if(count($COLORS) == 0){
    //   return 0;
    // }

    // var_dump($COLORS);

    $GREY_FILTERED_COLORS = array();

    if(!$is_grey){
      foreach ($COLORS as $COLOR) {
        if($COLOR->HSL_H != 0 && $COLOR->HSL_S != 0 && $COLOR->HSL_S > 25 && $COLOR->HSL_L > 25){
          // var_dump($COLOR);
          array_push($GREY_FILTERED_COLORS,$COLOR);
        }
      }
    } else {
      foreach ($COLORS as $COLOR) {
        if($COLOR->HSL_S == 0 && $COLOR->HSL_S < 3){
          // var_dump($COLOR);
          array_push($GREY_FILTERED_COLORS,$COLOR);
        }
      }
    }

      return array('post_count'=>count($GREY_FILTERED_COLORS),'posts'=>$GREY_FILTERED_COLORS,'HSL'=>$HSL);

    }

  } 

  ?>