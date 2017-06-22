<?php
function update($gender,$participant_id) {
  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE list_transform_participant
            SET gender = '$gender'
            WHERE participant_id = '$participant_id'";

  $result = pg_query($dbconn, $query);
}

$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$query_1 = "SELECT *
        FROM philhealth
        where gender is not null
        and participant_id <> ''";

$result = pg_query($dbconn, $query_1);

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
   $participant_id = $row['participant_id'];
   $gender = $row['gender'];

   $query_x = "SELECT * FROM list_transform_participant WHERE participant_id = '$participant_id'";
   $result_x = pg_query($dbconn, $query_x);
   $row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
   $participant_gender = $row_x['gender'];
  
  if($participant_gender == '') {
    echo "seto:$gender";
    update($gender,$participant_id);

    echo "ok<br/>";
  }
 }
 /*
//BDAY TRANSFER
$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$query_1 = "SELECT *
        FROM philhealth
        where birthday is not null
        and participant_id <> ''";

$result = pg_query($dbconn, $query_1);

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
   $participant_id = $row['participant_id'];
   $bday = $row['birthday'];

   $query_x = "SELECT * FROM list_transform_participant WHERE participant_id = '$participant_id'";
   $result_x = pg_query($dbconn, $query_x);
   $row_x = pg_fetch_array($result_x,NULL,PGSQL_BOTH);
   $participant_birthday = $row_x['birthday'];

   if($participant_birthday == NULL || $participant_birthday == '') {
     $query_o = "UPDATE list_transform_participant
   					   SET birthday = '$bday'
   					   WHERE participant_id = '$participant_id'";

     $result_o = pg_query($dbconn, $query_o);

     echo "ok<br/>";
  }
}*/

//4P FIX
/*$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$query_1 = "SELECT *
        FROM list_transform_participant
        where variable1 = 'Yes'
        and participant_id <> ''";

$result = pg_query($dbconn, $query_1);

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
   $participant_pk = $row['id'];
   $query_o = "UPDATE log_transform_attendance
 					   SET
 					   variable_1 = '1'
 					   WHERE fk_participant_pk = '$participant_pk'";

   $result_o = pg_query($dbconn, $query_o);

   echo "ok";
 } */

//PHILHEALTH MATCH
/*
function update($c_fname,$c_lname,$c_mname,$province,$participant_id) {
  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  $dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE philhealth
					   SET
					   last_name = '$c_lname',
					   first_name = '$c_fname',
					   middle_initial = '$c_mname',
					   province = '$province'
					   WHERE participant_id = '$participant_id'";

  $result = pg_query($dbconn, $query);
}

$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$query_1 = "SELECT *
        FROM philhealth";

$result = pg_query($dbconn, $query_1);

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
   $participant_id = $row['participant_id'];
   $gender = $row['gender'];
  $bday = $row['birthday'];
  $barangay = $row['barangay'];

  $query_2 = "SELECT * from list_transform_application
              left join list_transform_participant
              on list_transform_application.id = list_transform_participant.fk_entry_id
              where participant_id = '$participant_id'";

  $result_2 = pg_query($dbconn, $query_2);
  $row_2 = pg_fetch_array($result_2,NULL,PGSQL_BOTH);

  $c_fname = $row_2['first_name'];
  $c_lname = $row_2['last_name'];
  $c_mname = $row_2['middle_name'];
  $province = $row_2['application_province'];


  update($c_fname,$c_lname,$c_mname,$province,$participant_id);

  echo "done<br/>";
}*/


?>
