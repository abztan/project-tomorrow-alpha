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

	$mi = strtolower($row['middlename']);
	$mi = str_replace(' ','', $mi);
	$mi = substr($mi, 0,1);
	$birthday = $row['birthday'];
	$birthday = date("mdY",strtotime(str_replace('-','', $birthday)));

	$unique = $firstname.$lastname.$mi.$birthday;

	$query = "UPDATE list_pastor SET unique_id = '$unique' WHERE id = '$pid'";
	$result = pg_query($dbconn, $query);
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
		$contact3,$email,$education,$seminary,$position,$th_area,$unique,$username,$baseid)
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
	 ,createdby
	 ,baseid)

	 VALUES
	 ('$lname','$fname','$mname','$gender','$bday','$status','$address','$province','$city','$barangay','$contact1','$contact2','$contact3',
	 '$email','$education',$seminary,'$position','$th_area',TIMESTAMP '$timestamp',TIMESTAMP '$timestamp','$username','$baseid')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function countMembers_byMonth($base, $month, $year)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($base == 0)
		$query = "SELECT count(*)
						FROM list_pastor
						WHERE extract(year FROM membershipdate) = '$year'
						AND extract(month FROM membershipdate) = '$month'";
	else
		$query = "SELECT count(*)
							FROM list_pastor
							WHERE baseid = '$base'
							AND (extract(year FROM membershipdate) = '$year'
							AND extract(month FROM membershipdate) = '$month')";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function countProfiles_byMonth($base, $month, $year)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($base == 0)
		$query = "SELECT count(*)
						FROM list_pastor
						WHERE extract(year FROM createddate) = '$year'
						AND extract(month FROM createddate) = '$month'";
	else
		$query = "SELECT count(*)
							FROM list_pastor
							WHERE baseid = '$base'
							AND (extract(year FROM createddate) = '$year'
							AND extract(month FROM createddate) = '$month')";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function countProfiles_byTag($base, $tag)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($base == 0)
		$query = "SELECT count(*)
						FROM list_pastor
						WHERE tag = '$tag'";
	else
		$query = "SELECT count(*)
							FROM list_pastor
							WHERE baseid = '$base'
							AND tag = '$tag'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function countPopulation_byTHID($district_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT COUNT(*)
						FROM list_pastor
						where thriveid = '$district_id'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function countMembers_byTHID($district_id,$status)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT COUNT(*)
						FROM list_pastor
						WHERE thriveid = '$district_id'
						AND member = '$status'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function getAttendance_Recent($pastor_pk)
{

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT max(year) as year, max(month) as month
					FROM log_thrive_attendance
					WHERE fk_pastor_pk = '$pastor_pk'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function countActive_byTHID($district_id,$status)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT COUNT(*)
						FROM list_pastor
						WHERE thriveid = '$district_id'
						AND active = '$status'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function getListPastor_Base($baseid)
{
	$query = "SELECT *
				  FROM list_pastor
				  WHERE baseid = '$baseid'
				  ORDER BY thriveid ASC";
	return $query;
}

function getListPastor_BaseThrive($baseid, $thrive)
{
	$query = "SELECT *
				  FROM list_pastor
				  WHERE baseid = '$tag'
				  ORDER BY created_date ASC";
	return $query;
}

function countListPastor($a)
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
	}

	else if($a == "Total Member")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE member = 't'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Active")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE active = 't'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education None")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'None'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education Elementary")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Elementary'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education High School")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'High School'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education College")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'College'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education Post College")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Post College'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($a == "Total Education Empty")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Empty'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	$count = $row['count'];
	return $count;
}

function countListPastor_Base($baseid,$entity)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($entity == "Total Profile")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE baseid = '$baseid'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($entity == "Total Church")
	{
		$query = "SELECT count (DISTINCT churchid)
						FROM list_pastor
						WHERE baseid = '$baseid'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($entity == "Total Member")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE baseid = '$baseid'
						AND member = 't'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	else if($entity == "Total Nonmember")
	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE baseid = '$baseid'
						AND member = 'f'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	}

	$count = $row['count'];

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

	else if($a == "Total Baptist")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE denomination ilike '%baptist%' AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}

	else if($a == "Total Evangelical")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE denomination ilike '%Evangel%' OR denomination ilike '%alliance%' AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}

	else if($a == "Total Pentecostal")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE denomination ilike '%Pentecostal%'
						OR denomination ilike '%assembli%'
						OR denomination ilike '%four%'
						AND tag <> 0";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];
	}

	else if($a == "Total Denomination Others")
	{
		$query = "SELECT count (*)
						FROM list_church
						WHERE denomination not ilike '%Pentecostal%'
						AND denomination not ilike '%alliance%'
						AND denomination not ilike '%Evangel%'
						AND denomination not ilike '%baptist%'
						AND denomination not ilike '%assembli%'
						AND denomination not ilike '%four%'
						AND tag <> 0";

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

function getChurch_details($churchid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_church
			 WHERE churchid='$churchid'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
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

function getThriveListEx($base_id, $district_id)
{
	$query = "SELECT district_id
			 FROM list_thrive_district
			 WHERE base_id = '$base_id'
			 AND district_id <> '$district_id'
			 ORDER BY district_id ASC";

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
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function getMostRecent($setup)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($setup == "created")
	{
	$query = "SELECT DISTINCT (createddate)
				FROM list_pastor
				WHERE tag <> '0'
				ORDER BY createddate DESC";
	}

	else if($setup == "updated")
	{
	$query = "SELECT DISTINCT (updateddate)
				FROM list_pastor
				WHERE tag <> '0'
				AND updateddate IS NOT NULL
				ORDER BY updateddate DESC";
	}

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['0'];
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

function getPastorId_byName($lname,$fname)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					  FROM list_pastor
					  WHERE lastname='$lname' AND
					  firstname='$fname'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function checkDuplicateByUniqueId($unique_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
			 			FROM list_pastor
			 			WHERE unique_id='$unique_id'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}
/*
function checkAttendance_duplicate($year,$month,$pastor_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
						FROM log_thrive_attendance
						WHERE fk_pastor_pk = '$pastor_id'
						AND year = '$year'
						AND month = '$month'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	if($row['id'] == "")
		$isDuplicate = "false";
	else
		$isDuplicate = "true";
	return $isDuplicate;
}*/

function getAttendance_ID($year,$month,$pastor_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
						FROM log_thrive_attendance
						WHERE year = '$year'
						AND month = '$month'
						AND fk_pastor_pk = '$pastor_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function getAttendance_detail($attendance_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
						FROM log_thrive_attendance
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row;
}

function checkAttendance_tag($attendance_pk,$pastor_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT tag
						FROM log_thrive_attendance
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function updateAttendance_tag($attendance_pk,$tag,$username) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE log_thrive_attendance
						SET tag = '$tag', updated_by = '$username'
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
}

function addPastor_attendance($year,$month,$pastor_pk,$thrive_pk,$username) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "INSERT INTO log_thrive_attendance
	 (fk_pastor_pk
	 ,fk_thrive_pk
	 ,year
	 ,month
	 ,updated_by
	 ,updated_date)

	 VALUES
	 ('$pastor_pk','$thrive_pk','$year','$month','$username',TIMESTAMP '$timestamp')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function getPastor_attendance_byThrive_byYear_byMonth($district_pk,$year,$month) {
	$query = "SELECT *
					FROM log_thrive_attendance
					WHERE fk_thrive_pk = '$district_pk'
					AND year = '$year'
					AND month = '$month'
					AND tag <> 0
					ORDER BY id ASC";

	return $query;
}

function getDistrict_List($a,$sort) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	//national view
	if($a > 20 )
		$query = "SELECT *
							FROM list_thrive_district
							WHERE tag <> 0
							ORDER BY $sort, base_id, alternate_name ASC";
	//local view
	else
		$query = "SELECT *
							FROM list_thrive_district
							WHERE base_id = '$a' AND tag <> 0
							ORDER BY $sort, base_id, alternate_name ASC";

	return $query;
}

function getDistrict_Details_byTHID($district_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
						FROM list_thrive_district
						WHERE district_id = '$district_id'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row;
}

function getPastor_byTHID($district_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
						FROM list_pastor
						WHERE thriveid = '$district_id'
						ORDER BY lastname, firstname";
	return $query;
}

function getListPastor()
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

function getThriveDistinct($baseid)
{
	$query = "SELECT DISTINCT thriveid
					FROM list_pastor
					WHERE baseid ='$baseid'";

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
			$contact3,$email,$education,$seminary,$position,$th_area,$username,$baseid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_pastor SET lastname = '$lname', firstname = '$fname', middlename = '$mname', gender = '$gender', birthday = '$bday', status = '$status', address = '$address', province = '$province', city = '$city', barangay = '$barangay', contact1 = '$contact1',
	contact2 = '$contact2', contact3 = '$contact3', email = '$email', education = '$education', position = '$position', thriveid = '$th_area', updatedby = '$username', updateddate = TIMESTAMP '$timestamp', baseid = '$baseid' WHERE id = '$pid'";

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

function updatePastorMembership($pid, $membership, $membershipdate, $username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_pastor SET member = '$membership', membershipdate = '$membershipdate', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE id = '$pid'";

	$result = pg_query($dbconn, $query);
}

//new function formats
function addLogPastorProgram($pastor_pk)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query1 = "INSERT INTO log_pastor_program
	 (id)

	 VALUES
	 ('$pastor_pk')";

	$result=pg_query($dbconn, $query1);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function getLogPastorProgram_details($pid)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM log_pastor_program
			 WHERE id='$pid'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function updateLogPastorProgram($pastor_pk, $pg1, $pg2, $username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE log_pastor_program SET program_1 = '$pg1', program_2 = '$pg2', program_1_updated_by = '$username', program_2_updated_by = '$username' WHERE id = '$pastor_pk'";

	$result = pg_query($dbconn, $query);
}

function updateListPastor_comment($pastor_pk, $comment, $username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_pastor SET comment = '$comment', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE id = '$pastor_pk'";

	$result = pg_query($dbconn, $query);
}
?>
