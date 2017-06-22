<?php
	
	$count = 0;

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
				  FROM mtemp";
			 
	$result = pg_query($dbconn, $query);
	
	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$fname = $row['firstname'];
		$lname = $row['lastname'];
		$minitial = $row['middleinitial'];
		$mdate = $row['mdate'];
		
			/*if($fname != "" && $lname != "")
			{
				$query1 = "UPDATE list_pastor SET membership = 't' 
								WHERE lastname ilike '%$lname%' 
								AND firstname ilike '%$fname%'";
				$result1 = pg_query($dbconn, $query1);	
			}*/
			
			if($mdate != "")
			{
				$query1 = "UPDATE list_pastor SET membershipdate = '$mdate' 
								WHERE lastname ilike '%$lname%' 
								AND firstname ilike '%$fname%'";
				$result1 = pg_query($dbconn, $query1);	
			}
	}
	
	echo "done";
?>