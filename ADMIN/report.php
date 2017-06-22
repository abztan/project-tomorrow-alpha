<?php
/*$baptist = 0;
$evangelical = 0;
$pentecostal = 0;
$others = 0;
$base = 1;

while($base!=10)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT churchid
				  FROM list_pastor
				  WHERE baseid = $base";
							
	$result = pg_query($dbconn, $query);



	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){

		$churchid=$row['0'];
		
		$query1="SELECT denomination
				   FROM list_church
				   WHERE churchid ='$churchid'";
		
		$result1 = pg_query($dbconn, $query1);
		$row1=pg_fetch_array($result1,NULL,PGSQL_BOTH);
		$denomination=$row1['0'];

		if(preg_match("/(baptist)/i", $denomination))
			$baptist++;
		
		else if(preg_match("/(Evangel)/i", $denomination) || preg_match("/(alliance)/i", $denomination))
			$evangelical++;
			
		else if(preg_match("/(Pentecostal)/i", $denomination) || preg_match("/(assembli)/i", $denomination) || preg_match("/(four)/i", $denomination))
			$pentecostal++;
			
		else
			$others++;
	}

	echo "base".$base."<br/>";
	echo "B".$baptist."<br/>E".$evangelical."<br/>P".$pentecostal."<br/>O".$others."<br/>";
	
	$base++;
}*/


//denominaton
/*
$baptist = 0;
$evangelical = 0;
$pentecostal = 0;
$others = 0;
$base = 10;

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT churchid
				  FROM list_pastor
				  WHERE baseid = $base";
							
	$result = pg_query($dbconn, $query);



	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){

		$churchid=$row['0'];
		
		$query1="SELECT denomination
				   FROM list_church
				   WHERE churchid ='$churchid'";
		
		$result1 = pg_query($dbconn, $query1);
		$row1=pg_fetch_array($result1,NULL,PGSQL_BOTH);
		$denomination=$row1['0'];

		if(preg_match("/(baptist)/i", $denomination))
			$baptist++;
		
		else if(preg_match("/(Evangel)/i", $denomination) || preg_match("/(alliance)/i", $denomination))
			$evangelical++;
			
		else if(preg_match("/(Pentecostal)/i", $denomination) || preg_match("/(assembli)/i", $denomination) || preg_match("/(four)/i", $denomination))
			$pentecostal++;
			
		else
			$others++;
	}

	echo "base".$base."<br/>";
	echo "B".$baptist."<br/>E".$evangelical."<br/>P".$pentecostal."<br/>O".$others."<br/>";
	
*/

//education
/*
$blank = 0;
$none = 0;
$elementary = 0;
$highschool = 0;
$college = 0;
$postcollege = 0;
$base = 1;

while($base < 11)
{
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'Empty'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$blank=$row['0'];

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'None'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$none=$row['0'];
	
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'Elementary'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$elementary=$row['0'];
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'High School'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$highschool=$row['0'];
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'College'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$college=$row['0'];
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
				  FROM list_pastor
				  WHERE baseid = $base
				  AND education = 'Post College'";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$postcollege=$row['0'];

	

	echo "base".$base."<br/>";
	echo "B".$blank."<br/>N".$none."<br/>E".$elementary."<br/>HS".$highschool."<br/>C".$college."<br/>PC".$postcollege."<br/><br/>";

		$base++;
	}
	*/
	
	//age
	$blank = 0;
$seta = 0;
$setb = 0;
$setc = 0;
$setd = 0;
$base = 1;

while($base < 11)
{
	/*
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select count(*)
					from list_pastor
					where IS NULL
					AND baseid = $base";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$blank=$row['0'];*/

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "	select count(*)
					from list_pastor
					where EXTRACT(YEAR FROM birthday) >= 1996
					AND baseid = $base";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$seta=$row['0'];
	
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select count(*)
					from list_pastor
					where EXTRACT(YEAR FROM birthday) <= 1995
					AND EXTRACT(YEAR FROM birthday) >= 1976
					AND baseid = $base";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$setb=$row['0'];
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select count(*)
					from list_pastor
					where EXTRACT(YEAR FROM birthday) <= 1975
					AND EXTRACT(YEAR FROM birthday) >= 1956
					AND baseid = $base";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$setc=$row['0'];
	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select count(*)
					from list_pastor
					where EXTRACT(YEAR FROM birthday) <= 1955
					AND baseid = $base";
							
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$setd=$row['0'];
	
	

	echo "base".$base."<br/>";
	echo "N".$blank."<br/>A".$seta."<br/>B".$setb."<br/>C".$setc."<br/>D".$setd."<br/><br/>";

		$base++;
	}
?>

