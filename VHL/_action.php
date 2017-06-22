<?php
include_once "_tnsFunctions.php";
include_once "../_parentFunctions.php";


if(isset($_GET['command']))
	$command = $_GET['command'];
else
	$command = "";

if($command == "check_duplicate_patient") {
	$application_pk = $_GET['application_pk'];
  $participant_pk = $_GET['participant_pk'];
	$child_lname = $_GET['child_lname'];
	$child_fname = $_GET['child_fname'];

	checkHBF_duplicate($application_pk,$participant_pk,$child_lname,$child_fname);
}

else if($command == "check_patient_age") {
	$dob = $_GET['dob'];
	$current_age = computeHBF_age("",$dob);
	if($current_age < 24) {
		echo "";
	}
	else {
		echo "Nada";
	}
}

else if($command == "delete_patient") {
	$patient_pk = $_GET['patient_pk'];
	$patient = getHBF_patient_details($patient_pk);
	$patient_name = $patient['first_name']." ".$patient['last_name'];
	deleteHBF_patient($patient_pk);
	echo "$patient_name has been successfully deleted, please refresh page.";
}

else if($command == "delete_bib_participant") {
	$bib_participant_pk = $_GET['a'];
	$bib_participant_name = $_GET['b'];
	delete_bib_participant($bib_participant_pk);
	echo "$bib_participant_name has been successfully deleted, please refresh page.";
}

else if($command == "add_patient") {
  $week_entry = $_GET['week_entry'];
	$participant_pk = $_GET['participant_pk'];
	$child_fname = $_GET['child_fname'];
	$child_lname = $_GET['child_lname'];
	$bday = $_GET['bday'];
	$sex = $_GET['sex'];
	$weight_date = $_GET['weight_date'];
	$weight_value = $_GET['weight_value'];
	$height_value = $_GET['height_value'];
	$guardian_lname = $_GET['guardian_lname'];
	$guardian_fname = $_GET['guardian_fname'];
	$watsi_id = $_GET['watsi_id'];
	$recumbent = $_GET['recumbent'];
	$breast_feed = $_GET['breast_feed'];
	$contact_number = $_GET['contact_number'];
	$username = $_GET['username'];
	$application_pk = $_GET['application_pk'];
	$b_day = date_create($bday);
	$w_day = date_create($weight_date);
	$b_day = $b_day->format('Y-m-d');
	$w_day = $w_day->format('Y-m-d');

	if($w_day != "") {
		$month_age = dateDifference($b_day,$w_day);
	}

	if($month_age > 60) {
		$measure = "BMI";
		$value = compute_bmi_score($bday,$weight_date,$weight_value,$height_value,$sex);
		if($value <= -2 && $value > -3)
			$condition = "MAM";
		else if($value <= -3)
			$condition = "SAM";
		else if($value == 99999)
			$condition = "Undefined";
		else
			$condition = "Normal";
	}
	else {
		$measure = "WHZ";
		$value = compute_wasting_score($weight_value,$height_value,$sex);
		if($value <= -2 && $value > -3)
			$condition = "MAM";
		else if($value <= -3)
			$condition = "SAM";
		else if($value == 99999)
			$condition = "Undefined";
		else
			$condition = "Normal";
	}

	if((5 <= $month_age && 156 >= $month_age) && ("MAM" == $condition || "SAM" == $condition)) {
		//qualified
		$tag = 1;
	}
	else if(("MAM" == $condition || "SAM" == $condition) && ( 6 > $month_age || 156 < $month_age)) {
		//disqualified by age
		$tag = 2;
	}
	else if("Undefined" == $condition) {
		//undefined
		$tag = 4;
	}
	else if("MAM" != $condition || "SAM" != $condition) {
		//disqualified
		$tag = 3;
	}
	else {
		$tag = 4;
	}

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "INSERT INTO list_hbf_patient
	 (week_entry,fk_participant_pk,first_name,
		 last_name,birthday,gender,initial_weight_date,
		 initial_weight,initial_height,fk_application_pk,guardian_lname,guardian_fname,watsi_id,month_age,
		 updated_by,initial_wasting_status,initial_condition,current_condition,tag,updated_date,recumbent,breast_feed,contact_number,measure)
	 VALUES
	 ('$week_entry','$participant_pk','$child_fname','$child_lname','$bday'
	 	,'$sex'
	 	,'$weight_date'
	 	,'$weight_value'
	 	,'$height_value'
	  ,'$application_pk'
	  ,'$guardian_lname'
	  ,'$guardian_fname'
		,'$watsi_id'
		,'$month_age'
	 	,'$username'
	 	,'$value','$condition','$condition','$tag',TIMESTAMP '$timestamp','$recumbent','$breast_feed','$contact_number','$measure')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}

	$query = "SELECT *
					FROM list_hbf_patient
					WHERE fk_application_pk = '$application_pk'
					AND fk_participant_pk = '$participant_pk'
					AND first_name = '$child_fname'
					AND last_name = '$child_lname'
					AND birthday = '$bday'";

	$result = pg_query($dbconn, $query);
	$instance = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$patient_pk = $instance['id'];

	if($week_entry > 2) {
		$col = "week_".$week_entry;
		$query = "INSERT INTO log_hbf_weekly (
								fk_patient_pk,
								fk_application_pk,
								week_entry,
								weight,
								height,
								status,
								updated_by,
								updated_date)
			  VALUES
		 	 ('$patient_pk'
		 	 	,'$application_pk'
		 	 	,'$week_entry'
		 	 	,'$weight_value'
		 	 	,'$height_value'
		 	  ,'$condition'
		 	  ,'$username'
				,TIMESTAMP '$timestamp')";
		$result = pg_query($dbconn, $query);
	}

	echo "SUCCESS: Patient Added, please refresh page.";
}

else if($command == "update_h2h" || $command == "update_mcn") {
	$week_entry = $_GET['week_entry'];
  $patient_pk = $_GET['patient_pk'];
	$username = $_GET['username'];
	$weekly = getHBF_patient_weekly_details($patient_pk,$week_entry);
	$patient = getHBF_patient_details($patient_pk);
	$application_pk = $patient['fk_application_pk'];
	$log_pk = $weekly['id'];
	$value = ($command == "update_h2h" ? $weekly['h2h'] : $weekly['mcn']);
 	$replace_with = ($value == "t" ? "f" : "t");
	$column = ($command == "update_h2h" ? "h2h" : "mcn");
	if($log_pk != "")
		update_HBF_h2hmcn($column,$replace_with,$log_pk,$username);
	else
		insert_HBF_weekly_log_h2hmcn($patient_pk,$application_pk,$week_entry,$username,$column,$replace_with);
}

else if($command == "update_patient") {
  $week_entry = $_GET['week_entry'];
  $patient_pk = $_GET['patient_pk'];
	$participant_pk = $_GET['participant_pk'];
	$child_fname = $_GET['child_fname'];
	$child_lname = $_GET['child_lname'];
	$bday = $_GET['bday'];
	$sex = $_GET['sex'];
	$weight_date = $_GET['weight_date'];
	$weight_value = $_GET['weight_value'];
	$height_value = $_GET['height_value'];
	$recumbent = $_GET['recumbent'];
	$breast_feed = $_GET['breast_feed'];
	$watsi_id = $_GET['watsi_id'];
  $guardian_lname = $_GET['guardian_lname'];
	$guardian_fname = $_GET['guardian_fname'];
	$discharge_status = $_GET['discharge_status'];
	$discharge_week = $_GET['discharge_week'];
	$username = $_GET['username'];
	$application_pk = $_GET['application_pk'];
	$b_day = date_create($bday);
	$w_day = date_create($weight_date);
	$b_day = $b_day->format('Y-m-d');
	$w_day = $w_day->format('Y-m-d');

	if($w_day != "") {
		$month_age = dateDifference($b_day,$w_day);
	}

	if($month_age > 60) {
		$measure = "BMI";
		$value = compute_bmi_score($bday,$weight_date,$weight_value,$height_value,$sex);
		if($value <= -2 && $value > -3)
			$condition = "MAM";
		else if($value <= -3)
			$condition = "SAM";
		else if($value == 99999)
			$condition = "Undefined";
		else
			$condition = "Normal";
	}
	else {
		$measure = "WHZ";
		$value = compute_wasting_score($weight_value,$height_value,$sex);
		if($value <= -2 && $value > -3)
			$condition = "MAM";
		else if($value <= -3)
			$condition = "SAM";
		else if($value == 99999)
			$condition = "Undefined";
		else
			$condition = "Normal";
	}

	if((5 <= $month_age && 156 >= $month_age) && ("MAM" == $condition || "SAM" == $condition)) {
		//qualified
		$tag = 1;
	}
	else if(("MAM" == $condition || "SAM" == $condition) && ( 6 > $month_age || 156 < $month_age)) {
		//disqualified by age
		$tag = 2;
	}
	else if("Undefined" == $condition) {
		//undefined
		$tag = 4;
	}
	else if("MAM" != $condition || "SAM" != $condition) {
		//disqualified
		$tag = 3;
	}
	else {
		$tag = 4;
	}

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_hbf_patient
	  				SET
						week_entry = '$week_entry',
						fk_participant_pk = '$participant_pk',
						first_name = '$child_fname',
						last_name = '$child_lname',
						birthday = '$bday',
						gender = '$sex',
						initial_weight_date = '$weight_date',
						initial_weight = '$weight_value',
						initial_height = '$height_value',
						tag = '$tag',
						guardian_fname = '$guardian_fname',
						guardian_lname = '$guardian_lname',
						recumbent = '$recumbent',
						breast_feed = '$breast_feed',
						initial_wasting_status = '$value',
						measure = '$measure',
						watsi_id = '$watsi_id',
						updated_date = TIMESTAMP '$timestamp',
						updated_by = '$username',
						discharge_status = '$discharge_status',
						discharge_week = '$discharge_week',
						initial_condition = '$condition'
						WHERE
						id = '$patient_pk'";

	$result = pg_query($dbconn, $query);
	if (!$result)
	{
		echo "An error occurred.\n";
		exit;
	}

	if($week_entry > 2) {
		$col = "week_".$week_entry;
		$query = "UPDATE list_hbf_patient SET $col = '$weight_value' WHERE id = '$patient_pk'";
		$result = pg_query($dbconn, $query);
	}

	echo "SUCCESS: Patient Updated, please refresh page.";
}

else if($command == "update_week_weight") {
	$week = $_GET['week'];
	$patient_pk = $_GET['patient_pk'];
	$weight = $_GET['weight'];
	$username = $_GET['username'];
	$patient = getHBF_patient_details($patient_pk);
	$height_value = $patient['initial_height'];
	$sex = $patient['gender'];
	$application_pk = $patient['fk_application_pk'];
	$wasting_score = compute_wasting_score($weight,$height_value,$sex);
	if($wasting_score < -3 && $wasting_score >= -5)
		$condition = "SAM";
	else if($wasting_score >= -3 && $wasting_score <= -2)
		$condition = "MAM";
	else if($wasting_score > -2)
		$condition = "Normal";
	else
		$condition = "Undefined";

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	echo $x = check_hbf_existence($patient_pk,$week);
	//insert
	if($x == "No") {
		echo $query = "INSERT INTO log_hbf_weekly (
								fk_patient_pk,
								fk_application_pk,
								week_entry,
								weight,
								status,
								updated_by,
								updated_date)
			  VALUES
		 	 ('$patient_pk'
		 	 	,'$application_pk'
		 	 	,'$week'
		 	 	,'$weight'
		 	  ,'$condition'
		 	  ,'$username'
				,TIMESTAMP '$timestamp')";
	}
	else {
		echo $query = "UPDATE log_hbf_weekly SET weight = '$weight', status = '$condition', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE (fk_patient_pk = '$patient_pk' AND week_entry = '$week')";
	}
		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	$query = "UPDATE list_hbf_patient SET current_condition = '$condition' WHERE id = '$patient_pk'";
	$result = pg_query($dbconn, $query);

	echo "SUCCESS";
}

else if($command == "update_final_height") {
	$patient_pk = $_GET['patient_pk'];
	$height = $_GET['height'];
	$username = $_GET['username'];

	$query = "UPDATE list_hbf_patient SET week_15_height = '$height' WHERE id = '$patient_pk'";
	$result = pg_query($dbconn, $query);

	echo "SUCCESS";
}

else if($command == "update_16_height") {
	$patient_pk = $_GET['patient_pk'];
	$height = $_GET['height'];
	$username = $_GET['username'];

	$query = "UPDATE list_hbf_patient SET week_16_height = '$height' WHERE id = '$patient_pk'";
	$result = pg_query($dbconn, $query);

	echo "SUCCESS";
}

else if($command == "update_final_weight") {
	$patient_pk = $_GET['patient_pk'];
	$weight = $_GET['weight'];
	$username = $_GET['username'];

	$query = "UPDATE list_hbf_patient SET week_15_weight = '$weight' WHERE id = '$patient_pk'";
	$result = pg_query($dbconn, $query);

	echo "SUCCESS";
}

else if($command == "update_16_weight") {
	$patient_pk = $_GET['patient_pk'];
	$weight = $_GET['weight'];
	$username = $_GET['username'];

	$query = "UPDATE list_hbf_patient SET week_16_weight = '$weight' WHERE id = '$patient_pk'";
	$result = pg_query($dbconn, $query);

	echo "SUCCESS";
}

else if($command == "compute_w_score") {
	$application_pk = $_GET['application_pk'];
	$bday = $_GET['bday'];
	$wday = $_GET['wday'];
	$sex = $_GET['sex'];
	$weight_value = $_GET['w_value'];
	$height_value = $_GET['h_value'];
	$b_day = date_create($bday);
	$w_day = date_create($wday);
	$today = new DateTime();

	$b_day = $b_day->format('Y-m-d');
	$w_day = $w_day->format('Y-m-d');
	$today = $today->format('Y-m-d');
	if($wday != "") {
		$month_age = dateDifference($b_day,$w_day);
	}
	else {
		$month_age = dateDifference($b_day,$today);
	}

	if($month_age > 60) {
		$wasting_score = compute_bmi_score($bday,$wday,$weight_value,$height_value,$sex);
		echo "Measure: ".$measure = "BMI";

		echo "<br/>Value: ".$wasting_score;
		if($wasting_score <= -3 && $wasting_score >= -5)
			$condition = "SAM";
		else if($wasting_score <= -2 && $wasting_score > -3)
			$condition = "MAM";
		else if($wasting_score > -2)
			$condition = "Normal";
		else {
			$condition = "Undefined";
		}
		echo "<br/>Condition: ".$condition;
	}
	else {
		echo "Measure: ".$measure = "WHZ";
		echo "<br/>Value: ".$wasting_score = compute_wasting_score($weight_value,$height_value,$sex);
		if($wasting_score <= -3 && $wasting_score >= -5)
			$condition = "SAM";
		else if($wasting_score <= -2 && $wasting_score > -3)
			$condition = "MAM";
		else if($wasting_score > -2)
			$condition = "Normal";
		else {
			$condition = "Undefined";
		}

		echo "<br/>Condition: ".$condition;
	}
}

  else if($command == "get_r2_report")
  {
    $batch = $_GET['batch'];
  	$program = $_GET['program'];
  	$format = $_GET['format'];

    if($batch != '0' && $program != '0' && $format != '0') {
      echo "
      <table border='1' width='90%' id='table'>
        <th>BASE</th>
        <th>WK 1</th>
        <th>WK 2</th>
        <th>WK 3</th>
        <th>WK 4</th>
        <th>WK 5</th>
        <th>WK 6</th>
        <th>WK 7</th>
        <th>WK 8</th>
        <th>WK 9</th>
        <th>WK 10</th>
        <th>WK 11</th>
        <th>WK 12</th>
        <th>WK 13</th>
        <th>WK 14</th>
        <th>WK 15</th>
        <th>WK 16</th>";

      echo "<tr><td colspan='17'>PARTICIPANT</td></tr>";

      $base_id = 1;
      $last = 0;
      $total_a = 0;
      $total_b = 0;
      $total_c = 0;
      $total_d = 0;
      $total_e = 0;
      $total_f = 0;
      $total_g = 0;
      $total_h = 0;
      $total_i = 0;
      $total_j = 0;
      $total_k = 0;
      $total_l = 0;
      $total_m = 0;
      $total_n = 0;
      $total_o = 0;
      $total_p = 0;

      while($base_id<11)
      {
        $icon = "";
        $color = "";
        $sum = 0;
        echo "<tr>
              <td align='center'>".getBaseName($base_id)."</td>";
        for($a='a';$a<'q';$a++) {

          if($format == "2") {
            $number = count_attendance_byProgram_byClass($batch,$base_id,$program,$a,"general","participant");

            if($a == "a")
              $total_a = $total_a + $number;
            else if($a == "b")
              $total_b = $total_b + $number;
            else if($a == "c")
              $total_c = $total_c + $number;
            else if($a == "d")
              $total_d = $total_d + $number;
            else if($a == "e")
              $total_e = $total_e + $number;
            else if($a == "f")
              $total_f = $total_f + $number;
            else if($a == "g")
              $total_g = $total_g + $number;
            else if($a == "h")
              $total_h = $total_h + $number;
            else if($a == "i")
              $total_i = $total_i + $number;
            else if($a == "j")
              $total_j = $total_j + $number;
            else if($a == "k")
              $total_k = $total_k + $number;
            else if($a == "l")
              $total_l = $total_l + $number;
            else if($a == "m")
              $total_m = $total_m + $number;
            else if($a == "n")
              $total_n = $total_n + $number;
            else if($a == "o")
              $total_o = $total_o + $number;
            else if($a == "p")
              $total_p = $total_p + $number;
          }
          else if($format == "1") {
            $number = count_attendance_byProgram_byClass($batch,$base_id,$program,$a,"general","participant");
            $community_count = countCommunity_byBase_byBatch($batch,$base_id,$program);
            if($community_count != 0)
              $number = round($number/$community_count);
          }

          if($a>'a') {
            $icon = compare($last,$number);
          }
            echo "<td align='right'>$icon $number</td>";

          $last = $number;
        }
        echo "</tr>";

        $base_id++;
      }

      if($format == 2) {
        echo "<tr>
                <td>TOTAL</td>
                <td>$total_a</td>
                <td>".compare($total_a,$total_b)." $total_b</td>
                <td>".compare($total_b,$total_c)." $total_c</td>
                <td>".compare($total_c,$total_d)." $total_d</td>
                <td>".compare($total_d,$total_e)." $total_e</td>
                <td>".compare($total_e,$total_f)." $total_f</td>
                <td>".compare($total_f,$total_g)." $total_g</td>
                <td>".compare($total_g,$total_h)." $total_h</td>
                <td>".compare($total_h,$total_i)." $total_i</td>
                <td>".compare($total_i,$total_j)." $total_j</td>
                <td>".compare($total_j,$total_k)." $total_k</td>
                <td>".compare($total_k,$total_l)." $total_l</td>
                <td>".compare($total_l,$total_m)." $total_m</td>
                <td>".compare($total_m,$total_n)." $total_n</td>
                <td>".compare($total_n,$total_o)." $total_o</td>
                <td>".compare($total_o,$total_p)." $total_p</td>
              </tr>";
      }

      echo "<tr><td colspan='17'>COUNSELOR</td></tr>";

      $base_id = 1;
      $total_a = 0;
      $total_b = 0;
      $total_c = 0;
      $total_d = 0;
      $total_e = 0;
      $total_f = 0;
      $total_g = 0;
      $total_h = 0;
      $total_i = 0;
      $total_j = 0;
      $total_k = 0;
      $total_l = 0;
      $total_m = 0;
      $total_n = 0;
      $total_o = 0;
      $total_p = 0;
      $last = 0;

      while($base_id<11)
      {
        $icon = "";
        $color = "";
        echo "<tr>
              <td align='center'>".getBaseName($base_id)."</td>";
        for($a='a';$a<'q';$a++) {
          if($format == "2") {
            $number = count_attendance_byProgram_byClass($batch,$base_id,$program,$a,"general","counselor");

            if($a == "a")
              $total_a = $total_a + $number;
            else if($a == "b")
              $total_b = $total_b + $number;
            else if($a == "c")
              $total_c = $total_c + $number;
            else if($a == "d")
              $total_d = $total_d + $number;
            else if($a == "e")
              $total_e = $total_e + $number;
            else if($a == "f")
              $total_f = $total_f + $number;
            else if($a == "g")
              $total_g = $total_g + $number;
            else if($a == "h")
              $total_h = $total_h + $number;
            else if($a == "i")
              $total_i = $total_i + $number;
            else if($a == "j")
              $total_j = $total_j + $number;
            else if($a == "k")
              $total_k = $total_k + $number;
            else if($a == "l")
              $total_l = $total_l + $number;
            else if($a == "m")
              $total_m = $total_m + $number;
            else if($a == "n")
              $total_n = $total_n + $number;
            else if($a == "o")
              $total_o = $total_o + $number;
            else if($a == "p")
              $total_p = $total_p + $number;
          }
          else if($format == "1") {
            $number = count_attendance_byProgram_byClass($batch,$base_id,$program,$a,"general","counselor");
            $community_count = countCommunity_byBase_byBatch($batch,$base_id,$program);
            if($community_count != 0)
              $number = round($number/$community_count);
          }

          if($a>'a') {
            $icon = compare($last,$number);
          }
            echo "<td align='right'>$icon $number</td>";

            $last = $number;
        }
        echo "</tr>";
        $base_id++;
      }

      if($format == 2) {
      echo "<tr>
              <td>TOTAL</td>
              <td>$total_a</td>
              <td>".compare($total_a,$total_b)." $total_b</td>
              <td>".compare($total_b,$total_c)." $total_c</td>
              <td>".compare($total_c,$total_d)." $total_d</td>
              <td>".compare($total_d,$total_e)." $total_e</td>
              <td>".compare($total_e,$total_f)." $total_f</td>
              <td>".compare($total_f,$total_g)." $total_g</td>
              <td>".compare($total_g,$total_h)." $total_h</td>
              <td>".compare($total_h,$total_i)." $total_i</td>
              <td>".compare($total_i,$total_j)." $total_j</td>
              <td>".compare($total_j,$total_k)." $total_k</td>
              <td>".compare($total_k,$total_l)." $total_l</td>
              <td>".compare($total_l,$total_m)." $total_m</td>
              <td>".compare($total_m,$total_n)." $total_n</td>
              <td>".compare($total_n,$total_o)." $total_o</td>
              <td>".compare($total_o,$total_p)." $total_p</td>
            </tr>";
      }

      echo "</table><br/><br/>";
    }
  }


	else if($command == "get_r3_report")
  {
    $batch = $_GET['batch'];
  	$base = $_GET['base'];
  	$format = $_GET['format'];

    if($batch != '0' && $base != '0' && $format != '0') {
      echo "
      <table border='1' width='90%' id='table'>
        <th>BASE</th>
        <th>WK 5</th>
        <th>WK 7</th>
        <th>WK 9</th>
        <th>WK 10</th>
        <th>WK 11</th>";

			$result = base_hhid($base,"community_id",$batch);
			$x = 1;

			while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
				$community_id = $community['community_id'];
				echo "<td>$community_id</td>";
      }
      echo "</table><br/><br/>";
    }
  }

  else if($_GET['command']=="update_payment") {
    $payment_pk = $_GET['payment_pk'];
    $value = $_GET['value'];
    $username = $_GET['username'];
    $what = $_GET['what'];

    $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		if($what == "update_payment_sale")
			$query = "UPDATE log_bib_payment SET sale = '$value', created_by = '$username', created_date = TIMESTAMP '$timestamp' WHERE id = '$payment_pk'";
		else if($what == "update_payment_cash") {
			$payment = getBIB_payment_byPK($payment_pk);
			$c_cash = $payment['payment_cash'];
			$offset = $c_cash - $value;
			$bib_participant_pk = $payment['fk_bib_participant_pk'];
			$bib_participant = getList_BIB_participant_details($bib_participant_pk);
			$c_balance = $bib_participant['balance'];
			$balance = $c_balance + $offset;

			$query = "UPDATE list_bib_participant SET balance = '$balance', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
			$result = pg_query($dbconn, $query);

			$query = "UPDATE log_bib_payment SET payment_cash = '$value', created_by = '$username', created_date = TIMESTAMP '$timestamp' WHERE id = '$payment_pk'";
		}
		else if($what == "update_payment_noncash") {
			$payment = getBIB_payment_byPK($payment_pk);
			$c_cash = $payment['payment_noncash'];
			$offset = $c_cash - $value;
			$bib_participant_pk = $payment['fk_bib_participant_pk'];
			$bib_participant = getList_BIB_participant_details($bib_participant_pk);
			$c_balance = $bib_participant['balance'];
			$balance = $c_balance + $offset;

			$query = "UPDATE list_bib_participant SET balance = '$balance', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
			$result = pg_query($dbconn, $query);
			$query = "UPDATE log_bib_payment SET payment_noncash = '$value', created_by = '$username', created_date = TIMESTAMP '$timestamp' WHERE id = '$payment_pk'";
		}

		$result = pg_query($dbconn, $query);
  }

	else if($_GET['command']=="update_bib_participant") {
		$bib_participant_pk = $_GET['bib_participant_pk'];
		$value = $_GET['value'];
		$username = $_GET['username'];
		$what = $_GET['what'];
		$result = pg_query($dbconn, "select sum(payment_cash) + sum(payment_noncash) as total_payment
							from log_bib_payment where fk_bib_participant_pk = '$bib_participant_pk'");
		$total_payment = pg_fetch_result($result,0);

		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		if($what == "update_bib_type") {
			$query = "UPDATE list_bib_participant SET type = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
			$result = pg_query($dbconn, $query);
			echo "<span style='color:#53db6a;'>SUCCESS: BIB Type has been changed. Reloading page in <span id='counter'>5</span> seconds.</span>";
		}
		else if($what == "update_kit" && $value > 0) {
			$bib_participant = getList_BIB_participant_details($bib_participant_pk);
			$bib_class = $bib_participant['bib_class'];
			$bib_community_pk = $bib_participant['fk_bib_community_pk'];
			$week = $bib_participant['week'];
			$week_letter = substr($week,-1);
			$c_capital = $bib_participant['capital'];
			$c_dispersal = $bib_participant['kit_count'];
			$c_balance = $bib_participant['balance'];

			//checks if max dispersal have been reached
			if($value < $c_dispersal) {
				$community_capital_rate = getBIB_community_kit_week_capital($bib_community_pk,$week_letter);
				$n_capital = $community_capital_rate*$value;
				//$balance_offset = -($c_capital-$n_capital);
				$n_balance = $n_capital-$total_payment;

				$query = "UPDATE list_bib_participant SET capital = '$n_capital', balance = '$n_balance', kit_count = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
				$result = pg_query($dbconn, $query);
				echo "<span style='color:#53db6a;'>SUCCESS: ox Kit dispersal has been updated, capital and balance have been adjusted accordingly. Reloading page in <span id='counter'>5</span> seconds.</span>";
			}
			else {
				$dispersal_max = getBIB_dispersal_max($bib_class);
				$dispersal_total = sumBIB_community_dispersal($week,$bib_community_pk);

				if($dispersal_total + ($value-$c_dispersal) <= $dispersal_max) {
					$community_capital_rate = getBIB_community_kit_week_capital($bib_community_pk,$week_letter);
					$n_capital = $community_capital_rate*$value;
					$n_balance = $n_capital-$total_payment;
					//$balance_offset = -($c_capital-$n_capital);
					//$n_balance = $c_balance+$balance_offset;

					$query = "UPDATE list_bib_participant SET capital = '$n_capital', balance = '$n_balance', kit_count = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
					$result = pg_query($dbconn, $query);
					echo "<span style='color:#53db6a;'>SUCCESS: Kit dispersal has been updated, capital and balance have been adjusted accordingly. Reloading page in <span id='counter'>5</span> seconds.</span>";
	      }
	      else {
	        if($dispersal_total >= $dispersal_max)
	          echo "<strong style='color:#fdc414;'>FAILED: The maximum dispersal for this kit has been reached. Reloading page in <span id='counter'>5</span> seconds.</strong>";
	        else {
	          $diff = $dispersal_max - $dispersal_total;
	          echo "<span style='color:#fdc414;'>FAILED: Only $diff remaining kit/s available to disperse. Reloading page in <span id='counter'>5</span> seconds.</span>";
	        }
	      }
			}
		}

		else if($what == "update_capital" && $value > 0) {
			$query = "UPDATE list_bib_participant SET capital = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$bib_participant_pk'";
			$result = pg_query($dbconn, $query);
			echo "<span style='color:#53db6a;'>SUCCESS: Total capital has been updated. If status is <u>Inconsistent</u>, please contact National Livelihood Team. Reloading page in <span id='counter'>5</span> seconds.</span>";
		}
	}

	else if($command == "delete_payment") {
		$payment_pk = $_GET['payment_pk'];

		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT sum(payment_noncash)+sum(payment_cash) FROM log_bib_payment WHERE id = $payment_pk";
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$offset = $row[0];
		$payment = getBIB_payment_byPK($payment_pk);
		$bib_participant_pk = $payment['fk_bib_participant_pk'];
		$bib_participant = getList_BIB_participant_details($bib_participant_pk);
		$c_balance = $bib_participant['balance'];
		$balance = $c_balance + $offset;

		$query = "UPDATE list_bib_participant SET balance = '$balance' WHERE id = '$bib_participant_pk'";
		$result = pg_query($dbconn, $query);
		delete_bib_payment($payment_pk);
		echo "<span style='color:#53db6a;'>SUCCESS:</span> Transaction deleted. Reloading page in <span id='counter'>5</span> seconds.</span>";
	}

	else if($command == "list_raw") {
		$year = $_GET['year'];
		$batch = $_GET['batch'];
		$type = $_GET['type'];
		$lookup = substr($year,2,2)."___".$batch."%";

	if($type == 1 || $type == 2) {
			echo "<br/>
			<table border='1'>";

			if($type == 1) {
				$key = "list_hbf_patient";
				echo "
					<tr>
						<th>community_id</th>
						<th>id</th>
						<th>fk_participant_pk</th>
						<th>last_name</th>
						<th>first_name</th>
						<th>birthday</th>
						<th>gender</th>
						<th>initial_weight_date</th>
						<th>initial_weight</th>
						<th>initial_height</th>
						<th>abcdef_check</th>
						<th>appetite_test</th>
						<th>rhu_check</th>
						<th>target_sam_weight</th>
						<th>target_mam_weight</th>
						<th>week_15_weight</th>
						<th>week_15_height</th>
						<th>tag</th>
						<th>fk_application_pk</th>
						<th>week_entry</th>
						<th>guardian_fname</th>
						<th>guardian_lname</th>
						<th>initial_wasting_status</th>
						<th>updated_date</th>
						<th>updated_by</th>
						<th>initial_condition</th>
						<th>current_condition</th>
						<th>recumbent</th>
						<th>breast_feed</th>
						<th>contact_number</th>
						<th>discharge_status</th>
						<th>measure</th>
						<th>watsi_id</th>
						<th>month_age</th>
						<th>discharge_week</th>
					</tr>";
				}
				else if($type == 2) {
					$key = "log_hbf_weekly";
					echo "<tr>
						<th>community_id</th>
						<th>id</th>
						<th>fk_patient_pk</th>
						<th>fk_application_pk</th>
						<th>week_entry</th>
						<th>weight</th>
						<th>height</th>
						<th>status</th>
						<th>updated_by</th>
						<th>updated_date</th>
						<th>tag</th>
						<th>h2h</th>
						<th>mcn</th>
					</tr>";
				}

				$query = "SELECT $key.id as hbf_id, $key.tag as hbf_tag, $key.updated_by as hbf_updated_by, $key.updated_date as hbf_updated_date, *
									FROM list_transform_application
									LEFT JOIN $key
									ON list_transform_application.id = $key.fk_application_pk
									WHERE community_id ilike '$lookup'
									ORDER BY community_id";
				$result = pg_query($dbconn, $query);

				while($arr = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					if($type == 1) {
						$community_id = $arr['community_id'];
						$id = $arr['hbf_id'];
						$fk_participant_pk = $arr['fk_participant_pk'];
						$last_name = $arr['last_name'];
						$first_name = $arr['first_name'];
						$birthday = $arr['birthday'];
						$gender = $arr['gender'];
						$initial_weight_date = $arr['initial_weight_date'];
						$initial_weight = $arr['initial_weight'];
						$initial_height = $arr['initial_height'];
						$abcdef_check = $arr['abcdef_check'];
						$appetite_test = $arr['appetite_test'];
						$rhu_check = $arr['rhu_check'];
						$target_sam_weight = $arr['target_sam_weight'];
						$target_mam_weight = $arr['target_mam_weight'];
						$week_15_weight = $arr['week_15_weight'];
						$week_15_height = $arr['week_15_height'];
						$tag = $arr['hbf_tag'];
						$fk_application_pk = $arr['fk_application_pk'];
						$week_entry = $arr['week_entry'];
						$guardian_fname = $arr['guardian_fname'];
						$guardian_lname = $arr['guardian_lname'];
						$initial_wasting_status = $arr['initial_wasting_status'];
						$updated_date = $arr['hbf_updated_date'];
						$updated_by = $arr['hbf_updated_by'];
						$initial_condition = $arr['initial_condition'];
						$current_condition = $arr['current_condition'];
						$recumbent = $arr['recumbent'];
						$breast_feed = $arr['breast_feed'];
						$contact_number = $arr['contact_number'];
						$discharge_status = $arr['discharge_status'];
						$measure = $arr['measure'];
						$watsi_id = $arr['watsi_id'];
						$month_age = $arr['month_age'];
						$discharge_week = $arr['discharge_week'];

						echo "
								<tr>
									<td>$community_id</td>
									<td>$id</td>
									<td>$fk_participant_pk</td>
									<td>$last_name</td>
									<td>$first_name</td>
									<td>$birthday</td>
									<td>$gender</td>
									<td>$initial_weight_date</td>
									<td>$initial_weight</td>
									<td>$initial_height</td>
									<td>$abcdef_check</td>
									<td>$appetite_test</td>
									<td>$rhu_check</td>
									<td>$target_sam_weight</td>
									<td>$target_mam_weight</td>
									<td>$week_15_weight</td>
									<td>$week_15_height</td>
									<td>$tag</td>
									<td>$fk_application_pk</td>
									<td>$week_entry</td>
									<td>$guardian_fname</td>
									<td>$guardian_lname</td>
									<td>$initial_wasting_status</td>
									<td>$updated_date</td>
									<td>$updated_by</td>
									<td>$initial_condition</td>
									<td>$current_condition</td>
									<td>$recumbent</td>
									<td>$breast_feed</td>
									<td>$contact_number</td>
									<td>$discharge_status</td>
									<td>$measure</td>
									<td>$watsi_id</td>
									<td>$month_age</td>
									<td>$discharge_week</td>
								</tr>";
					}

					else if($type == 2) {
						$community_id = $arr['community_id'];
						$id = $arr['hbf_id'];
						$fk_patient_pk = $arr['fk_patient_pk'];
						$fk_application_pk = $arr['fk_application_pk'];
						$week_entry = $arr['week_entry'];
						$weight = $arr['weight'];
						$height = $arr['height'];
						$status = $arr['status'];
						$updated_by = $arr['hbf_updated_by'];
						$updated_date = $arr['hbf_updated_date'];
						$tag = $arr['hbf_tag'];
						$h2h = $arr['h2h'];
						$mcn = $arr['mcn'];

						echo "<tr>
									<td>$community_id</td>
									<td>$id</td>
									<td>$fk_patient_pk</td>
									<td>$fk_application_pk</td>
									<td>$week_entry</td>
									<td>$weight</td>
									<td>$height</td>
									<td>$status</td>
									<td>$updated_by</td>
									<td>$updated_date</td>
									<td>$tag</td>
									<td>$h2h</td>
									<td>$mcn</td>
									</tr>";
				}
			}
			echo "</table>";
	}

	if($type == 3) {
		echo "<br/><table border='1'>
		  <tr>
		    <th>patient_pk</th>
		    <th>participant_pk</th>
		    <th>community_id</th>
		    <th>birthday</th>
		    <th>month_age</th>
		    <th>gender</th>
		    <th>initial_weigh_date</th>
		    <th>initial_weight</th>
		    <th>initial_height</th>
		    <th>hbf_tag</th>
		    <th>week_entry</th>
		    <th>initial_condition</th>
		    <th>current_condition</th>
		    <th>measure</th>
		    <th>watsi_id</th>
		    <th>discharge_week</th>
		    <th>discharge_status</th>
		    <th>weight_2</th>
		    <th>height_2</th>
		    <th>weight_3</th>
		    <th>height_3</th>
		    <th>weight_4</th>
		    <th>height_4</th>
		    <th>weight_5</th>
		    <th>height_5</th>
		    <th>weight_6</th>
		    <th>height_6</th>
		    <th>weight_7</th>
		    <th>height_7</th>
		    <th>weight_8</th>
		    <th>height_8</th>
		    <th>weight_9</th>
		    <th>height_9</th>
		    <th>weight_10</th>
		    <th>height_10</th>
		    <th>weight_11</th>
		    <th>height_11</th>
		    <th>weight_12</th>
		    <th>height_12</th>
		    <th>weight_13</th>
		    <th>height_13</th>
		    <th>weight_14</th>
		    <th>height_14</th>
		    <th>weight_15</th>
		    <th>height_15</th>
		    <th>weight_16</th>
		    <th>height_16</th>
		  </tr>";

		$connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
		$db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$result = pg_query($db_connect, "SELECT list_hbf_patient.id as patient_pk, list_hbf_patient.fk_participant_pk as participant_pk, list_hbf_patient.tag as hbf_tag, * FROM list_hbf_patient LEFT JOIN list_transform_application ON list_hbf_patient.fk_application_pk = list_transform_application.id WHERE community_id ilike '$lookup' ORDER BY community_id, list_hbf_patient.id");
		while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		  $patient_pk = $row['patient_pk'];
		  $participant_pk = $row['participant_pk'];
		  $community_id = $row['community_id'];
		  $bday = $row['birthday'];
		  $gender = $row['gender'];
		  $initial_weigh_date = $row['initial_weight_date'];
		  $initial_weight = $row['initial_weight'];
		  $initial_height = $row['initial_height'];
		  $tag = $row['hbf_tag'];
		  $application_pk = $row['fk_application_pk'];
		  $weeky_entry = $row['week_entry'];
		  $initial_condition = $row['initial_condition'];
		  $current_condition = $row['current_condition'];
		  $discharge_status = $row['discharge_status'];
		  $measure = $row['measure'];
		  $watsi_id = $row['watsi_id'];
		  $month_age = $row['month_age'];
		  $discharge_week = $row['discharge_week'];

		  echo "
		        <tr>
		          <td>$patient_pk</td>
		          <td>$participant_pk</td>
		          <td>$community_id</td>
		          <td>$bday</td>
		          <td>$month_age</td>
		          <td>$gender</td>
		          <td>$initial_weigh_date</td>
		          <td>$initial_weight</td>
		          <td>$initial_height</td>
		          <td>$tag</td>
		          <td>$weeky_entry</td>
		          <td>$initial_condition</td>
		          <td>$current_condition</td>
		          <td>$measure</td>
		          <td>$watsi_id</td>
		          <td>$discharge_week</td>
		          <td>$discharge_status</td>
		       ";

		  for ($i=2;$i<17;$i++) {
		    $resultx = pg_query($db_connect, "SELECT * FROM log_hbf_weekly WHERE fk_patient_pk = '$patient_pk' AND week_entry = '$i'");
		    $rowx = pg_fetch_array($resultx,NULL,PGSQL_BOTH);
		    echo "
		    <td>".$rowx['weight']."</td>
		    <td>".$rowx['height']."</td>";
		  }

		  echo "</tr>";
		}
		echo "</table>";
	}
}
?>
