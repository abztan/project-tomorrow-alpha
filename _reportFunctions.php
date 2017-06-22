<?php
	function get4P_summary_byBatch($bib_participant_pk)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	  $query = "SELECT count(*)
							from list_transform_participant
							left join log_transform_attendance
							on list_transform_participant.id = log_transform_attendance.fk_participant_pk
							where variable1 = 'Yes'
							and category = '1'
							and list_transform_participant.tag = '9'
							and participant_id ilike '1501_1____'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
	}

	function countCEAIU_people($application_pk,$gender,$people,$tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$gender_string = !empty($gender) || $gender != ''? "gender = '$gender'" : "gender IS NULL";

		if($people == "Participant") {
		  $query = "SELECT count(*)
								FROM list_transform_participant
								LEFT JOIN log_transform_attendance
								ON list_transform_participant.id = log_transform_attendance.fk_participant_pk
								WHERE fk_entry_id = '$application_pk'
								AND $gender_string
								AND	(category = '1'
									or category = '2'
									or category = '3'
									or category = '4'
									or category = '5'
									or category = '6')
									AND (list_transform_participant.tag = '$tag')
									AND attendance_set <> ''";
		}

		if($people == "Counselor") {
		  $query = "SELECT count(*)
								FROM list_transform_participant
								LEFT JOIN log_transform_attendance
								ON list_transform_participant.id = log_transform_attendance.fk_participant_pk
								WHERE fk_entry_id = '$application_pk'
								AND $gender_string
								AND	(category = '20'
									or category = '21'
									or category = '22')
									AND (list_transform_participant.tag = '$tag')
									AND attendance_set <> ''";
		}

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row[0];
	}

?>
