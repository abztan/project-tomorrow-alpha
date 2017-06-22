<?php

	function generateUniqueId($pid)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		
		$query = "SELECT *
				 FROM list_pastor
				 WHERE id = '$pid'";
				 
		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
		
		$lastname = strtolower($row['lastname']);
		$lastname = str_replace('-','', $lastname);
		$lastname = preg_replace('/[^A-Za-z0-9\-]/', '', $lastname);
		
		$firstname = strtolower($row['firstname']);
		$firstname = str_replace('-','', $firstname);
		$firstname = preg_replace('/[^A-Za-z0-9\-]/', '', $firstname);
		//$firstname = str_replace('Ñ','n', $firstname);
		//$firstname = str_replace('ñ','n', $firstname);
		
		$mi = strtolower($row['middlename']);
		$mi = str_replace(' ','', $mi);
		$mi = substr($mi, 0,1);
		$birthday = $row['birthday'];
		$birthday = date("mdY",strtotime(str_replace('-','', $birthday)));
		
		$unique = $firstname.$lastname.$mi.$birthday;
		
		$query = "UPDATE list_pastor SET unique_id = '$unique' WHERE id = '$pid'";
		$result = pg_query($dbconn, $query);
	}
	
	
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		
		$query = "SELECT *
					FROM list_pastor
					WHERE tag <> 0
					ORDER BY id DESC";
					
		$result = pg_query($dbconn, $query);
		
	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$pid = $row['id'];
		generateUniqueId($pid);
	}
?>
 