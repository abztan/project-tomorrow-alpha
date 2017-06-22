<?php
include_once "_ptrFunctions.php";
include_once "../../_parentFunctions.php";
ini_set('MAX_EXECUTION_TIME', -1);

/*
function matchSequence($lname) {
  $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

  $query = "SELECT *
            FROM thrive_attendance
            WHERE pastor_string ilike '%$lname%'";
  $result = pg_query($dbconn, $query);
  $row = pg_fetch_array($result,NULL,PGSQL_BOTH);
  $instance_id = $row['0'];
  $score = 0;
	/*
  if($instance_id <> '') {
    $score = "1";
    $query = "select id
               from thrive_attendance
               where pastor_pk = '$instance_id'
               and pastor_string ilike '%$fname%'";
    $result = pg_query($dbconn, $query);
    $row = pg_fetch_array($result,NULL,PGSQL_BOTH);
    $instance_id = $row['id'];

    if($instance_id <> '') {
      $score = "2";
      $query = "select id
                 from thrive_attendance
                 where pastor_pk = '$instance_id'
                 and pastor_string ilike '%$m_ini%'";
      $result = pg_query($dbconn, $query);
      $row = pg_fetch_array($result,NULL,PGSQL_BOTH);

      $instance_id_repeat = $row['id'];
      if($instance_id == $instance_id_repeat)
        $score = "3";

      echo $score;
      //insertPastorID($pastor_pk,$instance_id,$score);
    }
  }

  return $score."-";
}*/

$query = "select *
          from list_transform_application
          where tag ='5' or tag = '6' or tag = '9'";

$result = pg_query($dbconn, $query);

while($pastor = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $last_name = trim($pastor['pastor_last_name']);
    $first_name = trim($pastor['pastor_first_name']);

    $pastor_pk = $pastor['pastor_id'];
    $hhid = $pastor['community_id'];
    $app_batch = substr($hhid,-3,1);
    $tag = $pastor['tag'];
    //echo $last_name,$first_name,$middle_initial."<br/>";

    //echo matchSequence($last_name,$first_name,$middle_initial,$pastor_pk)."<br/>";
    $query1 = "SELECT id
              FROM thrive_attendance
              WHERE (pastor_string ilike '%$last_name%' and pastor_string ilike '%$first_name%')";
    $result1 = pg_query($dbconn, $query1);
    while ($row1 = pg_fetch_array($result1,NULL,PGSQL_BOTH)) {
    $x = $row1['id'];


    if($x != '') {

  	$query3 = "UPDATE thrive_attendance
     SET pastor_pk = '$pastor_pk', app_pk = '$app_batch', tag ='$tag' WHERE id = '$x'";
  	$result3 = pg_query($dbconn, $query3);

    }
  }
  $x = "";
  $pastor_pk = "";
}

echo "done.";
?>
