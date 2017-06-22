<?php

$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$query = "select *
from list_bib_participant";

$result = pg_query($dbconn, $query);
$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

$bib_participant_pk = $row['id'];
$bib_community_pk = $row['fk_bib_community_pk'];
$week_identity = $row['week'];



select *
from list_bib_community
where id = '$fk_bib_community_pk'

$kit_id = $x[$week];

update list_bib_participant
kit_id = '$kit_id'

?>
