<html>
<?php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";
	include "../_reportFunctions.php";
  include "../Thrive/v2.0/_ptrFunctions.php";
/*
  $dl_query = get_double_lesson_weeks('1416');
  while ($dl = pg_fetch_array($dl_query,NULL,PGSQL_BOTH)) {
      $week_number = $dl['week_number'];
      $value = strtolower(chr(64 + $week_number));
      $dl_arr[] = $value;
  }

  $i = 0;
  foreach($dl_arr as $key => $instance)
  {
    $set = "bcelzpanm";
    echo $instance;
    if(strpos($set, $instance) == true)
      $i++;
  }
  echo $i;

?>*/

//kilos
echo "w:".$weight = 5.5;
echo "<br/>";
echo "h:".$height = 64;
echo "<br/>";
echo "s:".$sex = 2;
echo "<br/>";
$z_score = 0;

if($height < 45 || $height > 120)
	$z_score = 99999;
else {
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
	$query = "SELECT * FROM $table WHERE sex ='$sex' AND $measure = '$height'";
	$result = pg_query($dbconn, $query);
	$ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
	//collect row
	echo "<br/>l_val:".$l_val = $ref['l'];
	echo "<br/>m_val:".$m_val = $ref['m'];
	echo "<br/>s_val:".$s_val = $ref['s'];

	//compute for z-score
	echo "<br/><br/>a:".$a = pow($weight/$m_val,$l_val) - 1;
	echo "<br/>b:".$b = $s_val * $l_val;
	echo "<br/>z:".$z_score = $a/$b;

	if($z_score < -3) {
		echo "<br/><br/>-3";
		echo "<br/>exp:".$exp = 1/$l_val;
		echo "<br/>a:".$a = 1 + ($l_val*$s_val*-3);
		echo "<br/>sd3_neg:".$sd3_neg = $m_val * pow($a,$exp);
		echo "<br/>sd23_neg:".$sd23_neg = ($m_val * pow((1+($l_val*$s_val*-2)),$exp)) - $sd3_neg;
		echo "<br/>z:".$z_score = -3 - (($sd3_neg-$weight)/$sd23_neg);
	}

	else if($z_score > 3) {
		echo "<br/><br/>+3";
		echo "<br/>exp:".$exp = 1/$l_val;
		echo "<br/>a:".$a = 1+ ($l_val*$s_val*3);
		echo "<br/>sd3:".$sd3 = $m_val * pow($a,$exp);
		echo "<br/>sd23p:".$sd23 = $sd3 - $m_val * pow((1+$l_val*$s_val*2),$exp);
		echo "<br/>z:".$z_score = 3 + (($weight - $sd3)/$sd23);
	}

	//SAM = -3 MAM = -2
	$z_score = round($z_score,2);
}

echo "<br/><br/>".$z_score; ?>
</html>
