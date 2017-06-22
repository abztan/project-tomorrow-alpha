<?php
$conn_string = "host=icmsql.c628reaoa0hy.ap-southeast-1.rds.amazonaws.com port=5432 dbname=ProjectTomorrow user=icmadmin password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

$query = "SELECT count(*)
          from list_pastor";

$result = pg_query($dbconn, $query);
$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

echo "THIS:".$row[0];
?>
