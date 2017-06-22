<?php
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";
include "../Thrive/_ptrFunctions.php";

$connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
$db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());
/*
$query =  "SELECT *
          FROM list_transform_application
          WHERE tag = '9' AND community_id is null
          order by base_id, pastor_last_name, pastor_first_name";
*/
$query = "SELECT list_transform_participant.id as participant_pk, list_transform_application.id as application_pk, * FROM list_transform_application LEFT JOIN list_transform_participant
          ON list_transform_application.id = list_transform_participant.fk_entry_id
          WHERE list_transform_application.tag = '9'
          AND participant_id is NULL";
$result = pg_query($db_connect,$query);

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))	{
  //echo "application type: ".$application_type = $row['application_type'];
	//echo "application id: ".$entry_id = $row['id'];
	echo "base id: ".$base_id = $row['base_id'];
	echo "community id: ".$comm_id = $row['community_id'];

  echo "application id: ".$entry_id = $row['application_pk'];
  echo "participant id: ".$participant_pk = $row['participant_pk'];


echo "<br/>";
/*
  echo $comm_id = generateCommunityID($entry_id,$application_type);
  echo "<br/>";

  echo $queryx = "UPDATE list_transform_application
            SET community_id = '$comm_id', updated_by = 'SYSTEM'
            WHERE id = '$entry_id'";
  $resultx = pg_query($db_connect,$queryx);

  */

		echo $participant_id = generateParticipantID($entry_id,$participant_pk,$comm_id);
  	echo $queryx = "UPDATE list_transform_participant SET participant_id = '$participant_id', updated_by = 'SYSTEM'
              WHERE id = '$participant_pk'";


echo "<br/>";
  $resultx = pg_query($db_connect,$queryx);
}

echo "ok";
?>
