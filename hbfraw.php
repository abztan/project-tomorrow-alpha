<table border="1">
  <tr>
    <th>PATIENT PK</th>
    <th>PARTICIPANT PK</th>
    <th>APPLICATION PK</th>
    <th>BIRTHDAY</th>
    <th>MONTH AGE</th>
    <th>GENDER</th>
    <th>INITIAL WEIGH DATE</th>
    <th>INITIAL WEIGHT</th>
    <th>INTIAL HEIGHT</th>
    <th>TAG</th>
    <th>WEEK ENTRY</th>
    <th>INITIAL CONDITION</th>
    <th>CURRENT CONDITION</th>
    <th>MEASURE</th>
    <th>WATSI ID</th>
    <th>DISCHARGE WEEK</th>
    <th>DISCHARGE STATUS</th>
    <th colspan="2">WEEK 2 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 3 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 4 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 5 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 6 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 7 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 8 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 9 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 10 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 11 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 12 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 13 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 14 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 15 (WEIGHT|HEIGHT)</th>
    <th colspan="2">WEEK 16 (WEIGHT|HEIGHT)</th>
  </tr>
<?php

$connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
$db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());
$dt = new DateTime();
$timestamp = $dt->format('Y-m-d H:i:s');

$result = pg_query($db_connect, "SELECT list_hbf_patient.id as patient_pk, list_hbf_patient.fk_participant_pk as participant_pk, list_hbf_patient.tag as hbf_tag, * FROM list_hbf_patient LEFT JOIN list_transform_application ON list_hbf_patient.fk_application_pk = list_transform_application.id ORDER BY list_hbf_patient.id");
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
?>
</table>
