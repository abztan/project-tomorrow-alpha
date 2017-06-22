<?php
include_once "_ptrFunctions.php";
include_once "../../_parentFunctions.php";
ini_set('MAX_EXECUTION_TIME', -1);

$query = "select pastor_pk, id
from thrive_attendance";

$result = pg_query($dbconn, $query);

while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
	$pastor_pk = $row['pastor_pk'];
	$id = $row['id'];

	$query1 = "select community_id
		from list_transform_application where pastor_id = '$pastor_pk'";
	$result1 = pg_query($dbconn, $query1);
	$row1 = pg_fetch_array($result1,NULL,PGSQL_BOTH);
	$foo = $row1['community_id'];
	$foo_x = substr($foo , -3, 1);

	if($foo != '') {
		$query2 = "update thrive_attendance set app_pk = '$foo_x' where id = '$id'";
		$result2 = pg_query($dbconn, $query2);
	}
}

?>
