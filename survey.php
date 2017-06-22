<?php
 $conn_string = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
 $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

function countSurvey() {
	$conn_string = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = 'select count(*)
						from "SURVEY_3_9_R5_CORE"';

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$value = $row['0'];
	return $value;
}

function getSurvey() {
	$conn_string = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = 'select *
						from "SURVEY_3_9_R5_CORE"';

	return $query;
}

function check_existence($participant_id) {
  //$participant_id = !empty($participant_id) ? '$participant_id' : "NULL";
  $conn_string = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = 'select *
						from "SURVEY_3_9_R5_CORE"
            where "PARTICIPANT_ID_VERIFY"=\''.$participant_id.'\'';
  $result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$value = $row['0'];
  if($value!='')
    $return = 1;
  else
    $return = 0;
	return $return;
}

function base_hhid($base,$sort,$yearbatch) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);
	echo $query = "SELECT *
							FROM list_transform_application
							WHERE (tag = '9' or tag = '5' or tag = '6' or tag = '4' or tag = '3')
							AND community_id ilike '".$year.$base."_".$batch."%'
							ORDER BY $sort, base_id, pastor_last_name ASC";
	$result = pg_query($dbconn, $query);
	return $result;
}

function base_participants($entry_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				 FROM list_transform_participant
				 WHERE
				 fk_entry_id = '$entry_id'
				 AND (tag = '5' or tag = '6' or tag = '9')
				 ORDER BY last_name";
	$result = pg_query($dbconn, $query);

	return $result;
}

function count_instances($value) {
  $value = !empty($value) ? "'$value%'" : "NULL";
  $conn_string = "host=icmdb.cfewawr1rnp0.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password connect_timeout=5";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = 'select count(*)
    from "SURVEY_3_9_R5_CORE"
    where "PARTICIPANT_ID_VERIFY" ilike '.$value;
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$value = $row['0'];
	return $value;
}


echo "Submitted Surveys: ".countSurvey();
?>
<table border="1">
<tr><th>#</th><th>Community ID</th><th>Count</th></tr>
<?php
  //$query = getSurvey();
  $result = base_hhid("08","community_id","20161");
  $counter=1;
  while($z=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $commid = $z['community_id'];
    $application_pk = $z['id'];
    $result_1 = base_participants($application_pk);
    $start_count = 0;
    $matches = 0;
    while($y=pg_fetch_array($result_1,NULL,PGSQL_BOTH)) {
      $start_count++;
      //$participant_id = $y['participant_id'];
      //$matches=$matches+check_existence($participant_id);
    }

    $instances = count_instances($commid);
    if($instances!="0")
      $color = "#66ffb3";
    else {
      $color = "";
    }
    echo "<tr><td>$counter</td><td>$commid</td><td style='background-color:$color;'>".$matches.":".$instances."/".$start_count."</td></tr>";
    $counter++;
  }
?>
</table>
<br/><br/>
<!--<table border=1>
<td>Participants</td><td>SUBMITTED BY</td><td>TIME SUBMITTED</td>
<?php /*
$query = getSurvey();
$result = pg_query($dbconn, $query);

$i=1;
while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
	$user = $row['_CREATOR_URI_USER'];
	$fname = $row['CST_SEC_A_FNAME1'];
	$lname = $row['CST_SEC_A_LNAME1'];
	$mname = $row['CST_SEC_A_MNAME1'];
	$hhid = $row['PARTICIPANT_ID_VERIFY'];
	$timestamp = $row['DEVICE_TODAY'];
	$start = $row['START_TIME'];
	$i++;

	echo "<tr><td>".$hhid." - ".$lname." ".$fname." ".$mname."</td><td>$user</td><td>$start</td></tr>";
}*/
?>

</table>-->
