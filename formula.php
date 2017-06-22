<?php
echo "L: ".$l = -0.3521;
echo "<br/>M: ".$m = 2.441;
echo "<br/>S: ".$s = 0.09182;
echo "<br/>Z: ".$z_score = -2;

echo "<br/>A: ".$a = (($s*$l)*$z_score)+$l;
echo "<br/>R: ".$x =1/$l;
echo "<br/>B: ".$b = pow($a,$x);
echo "<br/>W: ".$weight = $b*$m;

echo "<br/>".$birthDate = "2016-04-06";
echo "<br/>B_YEAR: ".$b_year = substr($birthDate,0,4);
echo "<br/>B_Month: ".$b_month = substr($birthDate,5,-3);
echo "<br/>B_Day: ".$b_day = substr($birthDate,8);
echo "<br/>C_YEAR: ".$c_year = date('Y');
echo "<br/>C_Month: ".$c_month = date('n');
echo "<br/>C_Day: ".$c_day = date('d');
echo "<br/>".$age_year = $c_year-$b_year;
echo "<br/>".$age_month = $c_month-$b_month;

if($b_month > $c_month) {
  $age_year = $age_year-1;
  $age_month = $c_month;
}

if($b_day < $c_day)
  $age_month = $age_month-1;

echo "<br/>AGE:".$age_year;
echo "<br/>AGE:".$age_month = ($age_year*12) + $age_month;



function compute_wasting_score($weight,$height,$sex) {
  //kilos
  //$weight = 12;
  //$height = 65;
  //$sex = 1;
  $z_score = 0;

  if($height >= 65) {
    $table = "wfhanthro";
    $measure = "height";
  }
  else {
    $table = "wflanthro";
    $measure = "length";
  }

  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
  echo $query = "SELECT * FROM $table WHERE sex ='$sex' AND $measure = '$height'";
  $result = pg_query($dbconn, $query);
  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
  //collect row
  $l_val = $ref['l'];
  $m_val = $ref['m'];
  $s_val = $ref['s'];


  //compute for z-score
  $a = pow($weight/$m_val,$l_val) - 1;
  $b = $s_val * $l_val;
  $z_score = $a/$b;

  if($z_score < -3) {
    $exp = 1/$l_val;
    $a = 1 + ($l_val*$s_val*-3);
    $sd3_neg = $m_val * pow($a,$exp);
    $sd23_neg = ($m_val * pow((1+($l_val*$s_val*-2)),$exp)) - $sd3_neg;
    $z_score = -3 - (($sd3_neg-$weight)/$sd23_neg);
  }

  else if($z_score > 3) {
    $exp = 1/$l_val;
    $a = 1+ ($l_val*$s_val*3);
    $sd3 = $m_val * pow($a,$exp);
    $sd23 = $sd3 - $m_val * pow((1+$l_val*$s_val*2),$exp);
    $z_score = 3 + (($weight - $sd3)/$sd23);
  }

  //SAM = -3 MAM = -2
  $z_score = round($z_score,2);

  return $z_score;
}
?>
