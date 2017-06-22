<?php
  $i = 0;
  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

  function dateDifference($date_1,$date_2)
  {
      $datetime1 = date_create($date_1);
      $datetime2 = date_create($date_2);

      $interval = date_diff($datetime1, $datetime2);

   		$year = $interval->format('%y');
   		$month = $interval->format('%m');
   		$day = $interval->format('%d');

  		$year = $year*12;
  		if($day > 15) {
  			$month++;
  		}

  		$month = $month+$year;

  		return $month;
  }

  function compute_bmi_score($birthday,$w_date,$weight,$height,$sex) {
    global $dbconn;
    $birthday = new DateTime($birthday);
    $w_date = new DateTime($w_date);
    $diff = $birthday->diff($w_date);
    $age = $diff->format('%a');
    $decimal_age = round($age/30.4375,1);

  	$age_floor = floor($decimal_age);
    $age_ceil = floor($decimal_age)+1;
    $age_diff = round($decimal_age-$age_floor,2);

		//kilos
	  //$weight = 3.1;
	  //$height = 50.4;
	  //$sex = 2;

		//BMI
		$h_val = $height/100;
		$h_val = pow($h_val,2);
		$value = $weight/$h_val;
		$bmi_value = round($value,2);
	  $z_score = 0;

    if($age_diff > 0) {
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_floor'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
      $l_fl_val = $ref['l'];
  	  $m_fl_val = $ref['m'];
  	  $s_fl_val = $ref['s'];
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_ceil'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);
      $l_ce_val = $ref['l'];
  	  $m_ce_val = $ref['m'];
      $s_ce_val = $ref['s'];
      $l_val = $l_fl_val + ($age_diff*($l_ce_val-$l_fl_val));
      $m_val = $m_fl_val + ($age_diff*($m_ce_val-$m_fl_val));
      $s_val = $s_fl_val + ($age_diff*($s_ce_val-$s_fl_val));
    }

    else {
      $query = "SELECT * FROM bfawho WHERE sex ='$sex' AND age = '$age_ceil'";
  	  $result = pg_query($dbconn, $query);
  	  $ref = pg_fetch_array($result,NULL,PGSQL_BOTH);

  	  //collect row
  	  $l_val = $ref['l'];
  	  $m_val = $ref['m'];
  	  $s_val = $ref['s'];
    }

	  //compute for z-score
	  $a = pow($bmi_value/$m_val,$l_val) - 1;
	  $b = $s_val * $l_val;
	  $z_score = $a/$b;

	  if($z_score < -3) {
	    $exp = 1/$l_val;
	    $a = 1 + ($l_val*$s_val*-3);
	    $sd3_neg = $m_val * pow($a,$exp);
	    $sd23_neg = ($m_val * pow((1+($l_val*$s_val*-2)),$exp)) - $sd3_neg;
	    $z_score = -3 - (($sd3_neg-$bmi_value)/$sd23_neg);
	  }

	  else if($z_score > 3) {
	    echo "<br/>exp ".$exp = 1/$l_val;
	    echo "<br/>z ".$a = 1+ ($l_val*$s_val*3);
	    echo "<br/>z ".$sd3 = $m_val * pow($a,$exp);
	    echo "<br/>z ".$sd23 = $sd3 - $m_val * pow((1+$l_val*$s_val*2),$exp);
	    $z_score = 3 + (($bmi_value - $sd3)/$sd23);
	  }

	  //SAM = -3 MAM = -2
	  $z_score = round($z_score,2);

    return $z_score;
  }


  $normal = 0;
  $sam = 0;
  $mam = 0;

  $query = "SELECT list_hbf_patient.id as patient_pk, *
            FROM list_hbf_patient
            LEFT JOIN list_transform_application
            ON list_hbf_patient.fk_application_pk = list_transform_application.id
            WHERE community_id ilike '16___2%'
            AND (list_transform_application.tag = 5 or list_transform_application.tag = 6)
            AND measure = 'BMI'
            ORDER BY community_id";

/*  $query = "SELECT list_hbf_patient.id as patient_pk, *
            FROM list_hbf_patient
            LEFT JOIN list_transform_application
            ON list_hbf_patient.fk_application_pk = list_transform_application.id
            WHERE community_id ilike '16___1%'
            AND (list_transform_application.tag = 5 or list_transform_application.tag = 6)
            ORDER BY community_id";*/

  $participant = pg_query($dbconn, $query);

  while($patient = pg_fetch_array($participant,NULL,PGSQL_BOTH)) {
    $patient_pk = $patient['patient_pk'];
    echo "<br/>P Bday:".$birthday = $patient['birthday'];
    echo "<br/>P Wday:".$w_date = $patient['initial_weight_date'];
    $b_day = date_create($birthday);
  	$w_day = date_create($w_date);
  	$b_day = $b_day->format('Y-m-d');
  	$w_day = $w_day->format('Y-m-d');
    echo "<br/>W:".$weight = $patient['initial_weight'];
    echo "<br/>H:".$height = $patient['initial_height'];
    echo "<br/>S:".$sex = $patient['gender'];
    echo "<br/>XD:".$month_age = dateDifference($b_day,$w_day);
    $initial_condition = $patient['initial_condition'];

    echo "x:".$value = compute_bmi_score($birthday,$w_date,$weight,$height,$sex);

    if($value <= -3)
			$condition = "SAM";
		else if($value <= -2 && $value > -3)
			$condition = "MAM";
		else if($value == 99999)
			$condition = "Undefined";
		else
			$condition = "Normal";

    echo "<br/>initial condition:".$initial_condition;
    echo "<br/>current condition:".$condition;
    echo "<br/>";

    if($condition == "Normal") {
      $normal++;
      $tag = 3;
    }
    else if($condition == "SAM") {
      $sam++;
      if (6 > $month_age || 156 < $month_age)
        $tag = 1;
      else
        $tag = 2;
    }
    else if($condition == "MAM") {
      $mam++;
      if (6 > $month_age || 156 < $month_age)
        $tag = 1;
      else
        $tag = 2;
    }

    echo $query = "UPDATE list_hbf_patient
             SET
             /*initial_wasting_status = '$value',
             initial_condition = '$condition',
             current_condition = '$condition',*/
             tag = '$tag'
             WHERE id = '$patient_pk'";
    /*$query = "UPDATE list_hbf_patient
             SET
             month_age = '$month_age'
             WHERE id = '$patient_pk'";*/

    $result = pg_query($dbconn, $query);
  }

  echo "normal".$normal."sam".$sam."mam".$mam;

?>
