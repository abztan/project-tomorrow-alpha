<?php
include "../dbconnect.php";

function addChurch($cname,$denomination,$cprovince,$ccity,$cbarangay,$caddress,$isPlanted,$username)
{
	$conn_string="host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn=pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt=new DateTime();
	$timestamp=$dt->format('Y-m-d H:i:s');
	
	$query1 = "INSERT INTO list_church
	 (churchname, address, province, city, barangay, plantedbyicm, denomination, createddate, createdby)
	 
	 VALUES 
	 ('$cname','$caddress','$cprovince','$ccity','$cbarangay','$isPlanted','$denomination',TIMESTAMP '$timestamp','$username')";
			 
	$result=pg_query($dbconn, $query1);
	if (!$result) 
		{
			echo "An error occurred.\n";
			exit;
		}
}

function addCommunity($code,$category,$province,$city,$barangay,$geotype,$pastor,$address,$lat,$lng)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	
	$query = "INSERT INTO list_community
	 (code
	 ,category
	 ,province
	 ,city
	 ,barangay
	 ,geotype
	 ,pastor
	 ,address
	 ,latitude
	 ,longitude
	 ,createddate)
	 
	 VALUES 
	 ('$code', 
	 '$category',
	 '$province',
	 '$city',
	 '$barangay',
	 '$geotype',
	 '$pastor', 
	 '$address', 
	 '$lat', 
	 '$lng', 
	 TIMESTAMP '$timestamp')";
			 
	$result = pg_query($dbconn, $query);
	if (!$result) {
	  $check = "Community was not added, press the BACK button to salvage your work :D";
	}
	else
	  $check = "Community has been added! :D";
	  
	return $check;
}

function addPastor($lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
		$contact3,$email,$education,$seminary,$position,$th_area,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	
	$query = "INSERT INTO list_pastor
	 (lastname
	 ,firstname
	 ,middlename
	 ,gender
	 ,birthday
	 ,status
	 ,address
	 ,province
	 ,city
	 ,barangay
	 ,contact1
	 ,contact2
	 ,contact3
	 ,email
	 ,education
	 ,seminary
	 ,position
	 ,thriveid
	 ,registrationdate
	 ,createddate
	 ,createdby)
	 
	 VALUES 
	 ('$lname','$fname','$mname','$gender','$bday','$status','$address','$province','$city','$barangay','$contact1','$contact2','$contact3',
	 '$email','$education',$seminary,'$position','$th_area',TIMESTAMP '$timestamp',TIMESTAMP '$timestamp','$username')";
			 
	$result = pg_query($dbconn, $query);
	if (!$result) 
		{
			echo "An error occurred.\n";
			exit;
		}
}

function countPastorList($a)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	
	if($a == "Total Pastor")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Member")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE member = 't'
						AND tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Active")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE active = 't'
						AND tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	return $count;
}

function countListChurch($a)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	
	if($a == "Total Church")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Denomination")
	{
		$query = "SELECT count (DISTINCT denomination)
						FROM list_church
						WHERE tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Planted Yes")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE plantedbyicm = 'Yes' AND tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Planted No")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE plantedbyicm = 'No' AND tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "Total Planted Blank")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE plantedbyicm <> 'No' AND plantedbyicm <> 'Yes' AND tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	return $count;
}

function countListBarangay($a)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	
	if($a == "Total Barangay")
	{
		$query = "SELECT count (*)
						FROM list_barangay
						WHERE tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	if($a == "Total City")
	{
		$query = "SELECT count(*) 
						FROM (SELECT DISTINCT province, city from list_barangay WHERE tag <> '0') AS total";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	if($a == "Total Province")
	{
		$query = "SELECT count (DISTINCT province)
						FROM list_barangay
						WHERE tag <> 0";
						
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	return $count;
}

function countSearchedPastor($a)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	
	$query = "SELECT count (*)	
					FROM list_pastor
					WHERE  lastname ilike '%$a%' AND tag <> '0'
					OR firstname ilike '%$a%' AND tag <> '0'
					OR middlename ilike '%$a%' AND tag <> '0'
					OR province ilike '%$a%' AND tag <> '0'";
	
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$count = $row['count'];

	return $count;
}

function countOn($a, $b)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	
	if($a == "onBarangay")
	{
		$query = "SELECT count (DISTINCT barangay)
						FROM list_pastor
						WHERE city = '$b' 
						AND tag <> 0";
							
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "onCity")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE city = '$b' 
						AND tag <> 0";
							
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}
	
	else if($a == "onDenomination")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE denomination = '$b' 
						AND tag <> 0";
							
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}

	
	return $count;
}

function getChurchId($name, $province, $city, $barangay)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_church
			 WHERE churchname='$name' AND
			 province='$province' AND
			 city='$city' AND
			 barangay='$barangay'";
			 
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	
	return $row['0'];
}

function getChurchInfo($churchid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_church
			 WHERE churchid = '$churchid'";
			 
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	
	return $row;
}

function getChurchName($churchid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_church
			 WHERE churchid='$churchid'";
			 
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	
	return $row['churchname'];
}

function getCityListEx($province, $city)
{
	$query = "SELECT DISTINCT city
			 FROM list_barangay
			 WHERE province = '$province' 
			 AND city <> '$city'
			 ORDER BY city ASC";
			 
	return $query;
}

function getBarangayListEx($province, $city, $barangay)
{
	$query = "SELECT barangay
			 FROM list_barangay
			 WHERE province = '$province'
			 AND city = '$city'
			 AND barangay <> '$barangay'
			 ORDER BY barangay ASC";
			 
	return $query;
}

function getCommunityList()
{
	$query = "SELECT *
			 FROM list_community
			 ORDER BY category ASC";
			 
	return $query;
}

function getDenominationList()
{
	$query = "SELECT DISTINCT denomination
					FROM list_church
					ORDER BY denomination ASC";
			 
	return $query;
}

function getPastorDetails($pid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM list_pastor
					WHERE id = '$pid'";
					
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	
	return $row;
}

function getPastorId($lname,$fname,$mname,$bday,$gender)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_pastor
			 WHERE lastname='$lname' AND
			 firstname='$fname' AND
			 middlename='$mname' AND
			 birthday='$bday' AND
			 gender='$gender'";
			 
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	
	return $row['0'];
}

function getPastorList()
{
	$query = "SELECT *
					FROM list_pastor
					WHERE tag <> 0
					ORDER BY id DESC";
			 
	return $query;
}

function getPastorProvinceCity()
{
	$query = "SELECT DISTINCT province,city
					FROM list_pastor";
			 
	return $query;
}

function getProvinceList()
{
	$query = "SELECT DISTINCT province
			 FROM list_barangay
			 ORDER BY province ASC";
			 
	return $query;
}

function getProvinceListEx($province)
{
	$query = "SELECT DISTINCT province
			 FROM list_barangay
			 WHERE province <> '$province'
			 ORDER BY province ASC";
			 
	return $query;
}

function searchPastor($a)
{
	$query = "SELECT *
					FROM list_pastor
					WHERE  lastname ilike '%$a%' AND tag <> '0'
					OR firstname ilike '%$a%' AND tag <> '0'
					OR middlename ilike '%$a%' AND tag <> '0'
					OR province ilike '%$a%' AND tag <> '0'";

	return $query;
}

function setChurch($pid, $cid)
{	
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE list_pastor SET churchid = '$cid' WHERE id = '$pid'";
	
	$result = pg_query($dbconn, $query);
}

function updatePastorProfile($pid,$lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
			$contact3,$email,$education,$seminary,$position,$th_area,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	
	$query = "UPDATE list_pastor SET lastname = '$lname', firstname = '$fname', middlename = '$mname', gender = '$gender', birthday = '$bday', status = '$status', address = '$address', province = '$province', city = '$city', barangay = '$barangay', contact1 = '$contact1', 
	contact2 = '$contact2', contact3 = '$contact3', email = '$email', education = '$education', position = '$position', thriveid = '$th_area', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE id = '$pid'";
	
	$result = pg_query($dbconn, $query);
}

function updateChurchProfile($churchid,$name,$denomination,$province,$city,$barangay,$address,$isPlanted,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	
	$query = "UPDATE list_church SET churchname = '$name', denomination = '$denomination', province = '$province', city = '$city', barangay = '$barangay', address = '$address', plantedbyicm = '$isPlanted', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE churchid = '$churchid'";
	
	$result = pg_query($dbconn, $query);
}

function updatePastorTag($a, $pid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE list_pastor SET tag = '$a' WHERE id = '$pid'";
	
	$result = pg_query($dbconn, $query);
}

?>