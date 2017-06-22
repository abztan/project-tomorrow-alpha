<?php
function base_participants($entry_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				 FROM list_transform_participant
				 WHERE
				 fk_entry_id = '$entry_id'
				 AND (tag > 4)
				 ORDER BY last_name";
	$result = pg_query($dbconn, $query);

	return $result;
}

function get_double_lesson_weeks($application_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT week_number
					 FROM log_transform_weekly
					 WHERE fk_application_pk = '$application_pk'
           AND double_lesson = '1'";

	$result = pg_query($dbconn, $query);

	return $result;
}

function check_graduate_participant($participant_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT attendance_set
					FROM list_transform_participant
          LEFT JOIN log_transform_attendance
          ON list_transform_participant.id = log_transform_attendance.fk_participant_pk
					WHERE list_transform_participant.id = '$participant_pk'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['attendance_set'];
}
$i = 0;


    $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
  	$query = "SELECT *
  							FROM list_transform_participant
  							WHERE participant_id ilike '16___1%'
                AND (tag = 5 or tag = 6)
  							ORDER BY participant_id";
  	$participant = pg_query($dbconn, $query);

		while($person = pg_fetch_array($participant,NULL,PGSQL_BOTH)) {
      $application_pk = $person['fk_entry_id'];
      echo "base:".$base_id = substr($person['participant_id'],2,-6);

      //checks double lessons for the application vs participant
      $dl_query = get_double_lesson_weeks($application_pk);
      while ($dl = pg_fetch_array($dl_query,NULL,PGSQL_BOTH)) {
          $week_number = $dl['week_number'];
          $value = strtolower(chr(64 + $week_number));
          $dl_arr[] = $value;
      }

      $participant_pk = $person['id'];
      $participant_id = $person['participant_id'];
      $attendance_set = check_graduate_participant($participant_pk);
      $attendance_count = strlen($attendance_set);
      $tag = $person['tag'];
      $extra_att = 0;
      foreach ($dl_arr as $key => $instance) {
        if(strpos($attendance_set, $instance) == true)
          $extra_att++;
      }

      $attendance_count = $attendance_count + $extra_att;
      if ("08" != $base_id) {
        if($attendance_count >= 10) {
        	$query = "UPDATE list_transform_participant
        					 SET
        					 tag = '6'
        					 WHERE id = '$participant_pk'";
        	$result = pg_query($dbconn, $query);
          $change = "6";
        }
        else if($attendance_count < 10) {
          $query = "UPDATE list_transform_participant
        					 SET
        					 tag = '7'
        					 WHERE id = '$participant_pk'";
        	$result = pg_query($dbconn, $query);
          $change = "7";
        }
        else {
          $change = "X";
        }
  			echo "<br/>($participant_pk) $participant_id: $tag - $change</br>";
  			if($tag != $change)
          $i++;
      }
    }
