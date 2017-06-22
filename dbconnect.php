<?php
$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
?>