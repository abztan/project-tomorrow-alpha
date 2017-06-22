<?php

    $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
    $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

    function getBaseName($a)
    {
    	if($a == "1")
    	  $base = "Bacolod";
    	else if($a == "2")
    	  $base = "Bohol";
    	else if($a == "3")
    	  $base = "Dumaguete";
    	else if($a == "4")
    	  $base = "General Santos";
    	else if($a == "5")
    	  $base = "Koronadal";
    	else if($a == "6")
    	  $base = "Palawan";
    	else if($a == "7")
    	  $base = "Dipolog";
    	else if($a == "8")
    	  $base = "Iloilo";
    	else if($a == "9")
    	  $base = "Cebu";
    	else if($a == "10")
    	  $base = "Roxas";
    	else if($a == "99")
    	  $base = "Hong Kong";
    	else if($a == "98")
    	  $base = "Manila";
    	else
    	  $base = "Undefined";

    	return $base;
    }

$query = "
select
 base_id,
 community_id,
 application_province,
 application_city,
 application_barangay,
 location_note,
 participant_id,
 first_name,
 middle_name,
 last_name,
 pastor_last_name,
 pastor_first_name
from list_transform_participant
left join list_transform_application
on list_transform_application.id = list_transform_participant.fk_entry_id
where list_transform_application.tag = '9'
and (base_id = '3' or base_id = '4' or base_id = '5' or base_id = '7')
order by community_id, participant_id";

?>

  <table border="1" width="100%">
    <tr>
      <th>Base</th>
      <th>Community ID</th>
      <th>Pastor</th>
      <th>Location</th>
      <th>Participant ID (HHID)</th>
      <th>First Name</th>
      <th>Middle Name</th>
      <th>Last Name</th>
    </tr>

    <?php
      $result = pg_query($db_connect, $query);

      while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))	{

        echo "<tr>
                <td>".getBaseName($row['base_id'])."</td>
                <td>".$row['community_id']."</td>
                <td>".$row['pastor_last_name'].", ".$row['pastor_first_name']."</td>
                <td>".$row['application_province'].": ".$row['application_city']." ".$row['application_barangay']." ".$row['location_note']."</td>
                <td>".$row['participant_id']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['middle_name']."</td>
                <td>".$row['last_name']."</td>
              </tr>";
      }

    ?>
