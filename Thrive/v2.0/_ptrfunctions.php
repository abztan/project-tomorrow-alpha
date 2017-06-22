<?php
$root = realpath($_SERVER['DOCUMENT_ROOT']);
//include_once $root."/ICM/dbconnect.php";
include_once $root."/ICM/_parentFunctions.php";

function list_thrive_attendance_rate($base_id, $table, $instances) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$condition_arr = "";
	$base = str_pad($base_id, 2, 0, STR_PAD_LEFT);

	$base = "TD".$base."%";
	if($table == "1")
		$table = "log_thrive_card";
	else if($table == "2")
		$table = "log_thrive_second_day";

	for($i=0;$i<$instances;$i++) {
			$month = date('m', strtotime("-$i month"));
			$year = date('Y', strtotime("-$i month"));
			$condition = "(month = '$month' and year = '$year')";
			if($i==0)
				$condition_arr = $condition_arr.$condition;
			else
				$condition_arr = $condition_arr." or ".$condition;
	}
	$query = "SELECT fk_pastor_pk, count(*)
						from $table
						where
						fk_thrive_pk ilike '$base'
						and
						($condition_arr)
						group by fk_pastor_pk";

	$result = pg_query($dbconn, $query);
	return $result;
}

function count_thrive_overlap_rate($base_id, $month, $year) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$condition_arr = "";
	$base = str_pad($base_id, 2, 0, STR_PAD_LEFT);

	$base = "TD".$base."%";

	$query = "SELECT count(*)
						from log_thrive_card
						where fk_thrive_pk ilike '$base'
						and (month = '$month' and year = '$year')
						and EXISTS
						(select * from log_thrive_second_day
						where log_thrive_card.fk_pastor_pk = log_thrive_second_day.fk_pastor_pk
						and fk_thrive_pk ilike '$base' and ($month = '$month' and year = '$year'))";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$count = $row['count'];
	return $count;
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

function countAge_byBase($age_start,$age_end,$base_id)
{
	$dt = new DateTime();
	$current_year = $dt->format('Y');
  $age_year_start = $current_year - $age_start;
	$age_year_end = $current_year - $age_end;


	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($base_id == 0)
		$query = "select count(*)
							from list_pastor
							where (date_part('year', birthday) >= '$age_year_end'
							AND date_part('year', birthday) <= '$age_year_start')";
	else
		$query = "select count(*)
							from list_pastor
							where (date_part('year', birthday) >= '$age_year_end'
							AND date_part('year', birthday) <= '$age_year_start')
							and baseid = '$base_id'";

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
						where thriveid = '$district_id'
						and tag <> 0";

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
						AND member = '$status'
						AND tag <> 0";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	$count = $row['count'];
	return $count;
}

function countChurches_byTHID($district_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT DISTINCT(COUNT(churchid))
						FROM list_pastor
						WHERE thriveid = '$district_id'
						AND tag <> 0";

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
					FROM
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

function countDistrict($base_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($base_id == 0)
		$query = "SELECT count(DISTINCT(district_id))
							FROM list_thrive_district";
	else
		$query = "SELECT count(DISTINCT(district_id))
							FROM list_thrive_district
							WHERE base_id = '$base_id'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['0'];
}

function countListPastor_byBase($a,$base)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	if($base != 0)
		$b = "AND baseid = '".$base."'";
	else
		$b = "";

	if($a == "Total Pastor") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE tag <> 0 $b";
	}

	else if($a == "Total Member") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE member = 't'
						AND tag <> 0 $b";
	}

	else if($a == "Total Active")	{
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE active = 't'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education None") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'None'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education Elementary") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Elementary'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education High School") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'High School'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education College") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'College'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education Post College") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Post College'
						AND tag <> 0 $b";
	}

	else if($a == "Total Education Empty") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE education = 'Empty'
						AND tag <> 0 $b";
	}

	else if($a == "Total Seminary") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE seminary = 't'
						AND tag <> 0 $b";
	}

	else if($a == "Total Male") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE gender = 'Male'
						AND tag <> 0 $b";
	}

	else if($a == "Total Female") {
		$query = "SELECT count (*)
						FROM list_pastor
						WHERE gender = 'Female'
						AND tag <> 0 $b";
	}

	else if($a == "Total Church") {
		$query = "SELECT count(DISTINCT(churchid))
							from list_pastor
							where tag <> 0 $b";
	}

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$count = $row['count'];
	return $count;
}

//count program holder pastors
function countPHP($base) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	if($base != 0)
		$b = "AND base_id = '".$base."'";
	else
		$b = "";

	$query = "SELECT COUNT (*)
						FROM list_transform_application
						WHERE pastor_id <> '0'
						AND (tag = '5' OR tag = '9' OR tag = '6') $b";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
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

function getChurchId($match_name)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 FROM list_church
			 WHERE match_name='$match_name'";

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

function checkDuplicateByUniqueId($unique_id) //depreciate
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
			 			FROM list_pastor
			 			WHERE type1='$unique_id'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	if($row['0']=="") {
		$query = "SELECT id
				 			FROM list_pastor
				 			WHERE type2='$unique_id'";
		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		if($row['0']=="") {
			$query = "SELECT id
					 			FROM list_pastor
					 			WHERE type3='$unique_id'";
			$result = pg_query($dbconn, $query);
			$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

			if($row['0']=="") {
				$query = "SELECT id
						 			FROM list_pastor
						 			WHERE type4='$unique_id'";
				$result = pg_query($dbconn, $query);
				$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

				if($row['0']=="") {
					return "pass";
				}
				else
					return "fail";
			}
			else
				return "fail";
		}
		else
			return "fail";
	}
	else {
		return "fail";
	}
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

function getThriveListEx($base_id, $district_id)
{
	$query = "SELECT district_id
			 FROM list_thrive_district
			 WHERE base_id = '$base_id'
			 AND district_id <> '$district_id'
			 ORDER BY district_id ASC";

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

function updatePastor_Profile($pastor_pk,$last_name,$first_name,$middle_name,$gender,$birthday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
			$contact3,$email,$education_level,$education_graduate,$seminary,$position,$th_area,$username,$baseid,$match_name)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	$birthday = !empty($birthday) ? "'$birthday'" : "NULL";
	$education_graduate = !empty($education_graduate) ? "'$education_graduate'" : "NULL";
	$seminary = !empty($seminary) ? "'$seminary'" : "NULL";

	$query = "UPDATE list_pastor SET lastname = '$last_name', firstname = '$first_name', middlename = '$middle_name', gender = '$gender', birthday = $birthday, status = '$status', address = '$address', province = '$province', city = '$city', barangay = '$barangay', contact1 = '$contact1',
	contact2 = '$contact2', contact3 = '$contact3', email = '$email', education = '$education_level', education_graduated = $education_graduate, position = '$position', thriveid = '$th_area', seminary = $seminary, updatedby = '$username', updateddate = TIMESTAMP '$timestamp', baseid = '$baseid' WHERE id = '$pastor_pk'";

	$result = pg_query($dbconn, $query);
}

//depreciate
function updateChurchProfile($churchid,$name,$denomination,$province,$city,$barangay,$address,$isPlanted,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_church SET churchname = '$name', denomination = '$denomination', province = '$province', city = '$city', barangay = '$barangay', address = '$address', plantedbyicm = '$isPlanted', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE churchid = '$churchid'";

	$result = pg_query($dbconn, $query);
}

function updateChurch_Profile($church_pk,$church_name,$denomination,$denom_spec,$church_province,$church_city,$church_barangay,$church_address,$planted,$match_church,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE list_church SET churchname = '$church_name', denomination = '$denomination', denomination_specify = '$denom_spec', province = '$church_province',
						city = '$church_city', barangay = '$church_barangay', address = '$church_address', plantedbyicm = '$planted', match_name = '$match_church', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE churchid = '$church_pk'";

	$result = pg_query($dbconn, $query);
}

function updatePastorTag($a, $pid, $username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	$query = "UPDATE list_pastor SET tag = '$a', updatedby = '$username', updateddate = TIMESTAMP '$timestamp' WHERE id = '$pid'";

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

//new functions
//v2 update
//
function getPastor_list_byBase($a,$sort) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	//national view
	if($a > 20 )
		$query = "SELECT *
							FROM list_pastor
							WHERE tag <> 0
							ORDER BY $sort, firstname ASC";
	//local view
	else
		$query = "SELECT *
							FROM list_pastor
							WHERE baseid = '$a' AND tag <> 0
							ORDER BY $sort, firstname ASC";

	return $query;
}

function getAttendance_ID($year,$month,$pastor_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
						FROM
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
						FROM
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row;
}

function checkAttendance_tag($attendance_pk,$pastor_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT tag
						FROM
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function updateAttendance_tag($attendance_pk,$tag,$username) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "UPDATE log_thrive_card
						SET tag = '$tag', updated_by = '$username', updated_date = TIMESTAMP '$timestamp'
						WHERE id = '$attendance_pk'";
	$result = pg_query($dbconn, $query);
}

function addPastor_attendance($year,$month,$pastor_pk,$thrive_pk,$username) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "INSERT INTO log_thrive_card
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
					FROM log_thrive_card
					WHERE fk_thrive_pk = '$district_pk'
					AND year = '$year'
					AND month = '$month'
					AND tag <> 0
					ORDER BY id ASC";

	return $query;
}

//new functions
function addPastor($first_name,$middle_name,$last_name,$status,$gender,$birthday,$education_level,$education_graduate,$seminary,$pastor_province,
$pastor_city,$pastor_barangay,$pastor_address,$contact_1,$contact_2,$contact_3,$email,$base,$district_id,$position,$match_name,$username)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	$birthday = !empty($birthday) ? "'$birthday'" : "NULL";
	$education_graduate = !empty($education_graduate) ? "'$education_graduate'" : "NULL";
	$seminary = !empty($seminary) ? "'$seminary'" : "NULL";

	$query = "INSERT INTO list_pastor
	 (firstname
	 ,middlename
	 ,lastname
	 ,status
	 ,gender
	 ,birthday
	 ,education
	 ,education_graduated
	 ,seminary
	 ,province
	 ,city
	 ,barangay
	 ,address
	 ,contact1
	 ,contact2
	 ,contact3
	 ,email
	 ,baseid
	 ,thriveid
	 ,position
	 ,match_name
	 ,createddate
	 ,createdby)

	 VALUES
	 ('$first_name','$middle_name','$last_name','$status','$gender',$birthday,'$education_level',$education_graduate,$seminary,'$pastor_province','$pastor_city',
		 '$pastor_barangay','$pastor_address','$contact_1','$contact_2','$contact_3','$email','$base','$district_id','$position','$match_name',TIMESTAMP '$timestamp','$username')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function addChurch($church_name,$denomination,$denom_spec,$church_province,$church_city,$church_barangay,$church_address,$planted,$match_name,$username,$tag)
{
	$conn_string="host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn=pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt=new DateTime();
	$timestamp=$dt->format('Y-m-d H:i:s');
	$planted = !empty($planted) ? "'$planted'" : "NULL";

	$query1 = "INSERT INTO list_church
	 (churchname, denomination, denomination_specify, province, city, barangay, address, plantedbyicm, match_name, tag, createddate, createdby)

	 VALUES
	 ('$church_name','$denomination','$denom_spec','$church_province','$church_city','$church_barangay','$church_address', $planted, '$match_name', $tag, TIMESTAMP '$timestamp','$username')";

	$result=pg_query($dbconn, $query1);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function addCard($pastor_pk,$thrive_pk,$year,$month,$att_adt,$att_kid,$tithe,$username,$updated,$date)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');
	$updated = !empty($updated) ? "'$updated'" : "NULL";
	$att_adt = !empty($att_adt) ? "$att_adt" : "NULL";
	$att_kid = !empty($att_kid) ? "$att_kid" : "NULL";
	$tithe = !empty($tithe) ? "$tithe" : "NULL";

	$query = "INSERT INTO log_thrive_card
	 (fk_pastor_pk
	 ,fk_thrive_pk
	 ,year
	 ,month
	 ,attendance_adult
	 ,attendance_child
	 ,amount_tithe
	 ,updated_by
	 ,updated_date
	 ,is_updated
	 ,attendance_date)

	 VALUES
	 ('$pastor_pk','$thrive_pk','$year','$month',$att_adt,$att_kid,$tithe,'$username',TIMESTAMP '$timestamp',$updated,'$date')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function addCard_sd($pastor_pk,$thrive_pk,$year,$month,$username,$date)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "INSERT INTO log_thrive_second_day
	 (fk_pastor_pk
	 ,fk_thrive_pk
	 ,year
	 ,month
	 ,updated_by
	 ,updated_date
	 ,attendance_date)

	 VALUES
	 ('$pastor_pk','$thrive_pk','$year','$month','$username',TIMESTAMP '$timestamp','$date')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function checkDuplicate_pastor($match_name) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM list_pastor
					WHERE match_name = '$match_name'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function checkDuplicate_card($pastor_pk,$month,$year) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_thrive_card
					WHERE month = '$month'
					AND year = '$year'
					AND fk_pastor_pk = '$pastor_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function checkDuplicate_card_sd($pastor_pk,$month,$year) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_thrive_second_day
					WHERE month = '$month'
					AND year = '$year'
					AND fk_pastor_pk = '$pastor_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function checkDuplicate_church($match_name) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM list_church
					WHERE match_name = '$match_name'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function getDistrict_List($a,$sort) {
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
	$query = "SELECT *
						FROM list_pastor
						WHERE thriveid = '$district_id'
						AND tag <> '0'
						ORDER BY lastname, firstname";
	return $query;
}

function getDistrict_alternateName($district_pk)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT alternate_name
						FROM list_thrive_district
						WHERE district_id = '$district_pk'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
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

function checkProgramPastor($id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 			FROM list_transform_application
			 			WHERE pastor_id='$id'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	if($row['0']!='')
		$value = "1";
	else
		$value = "0";
	return $value;
}

function getListBase()
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
			 			FROM list_base
						ORDER BY id ASC";
	$result = pg_query($dbconn, $query);

	return $result;
}

function getPastorId($match_name)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					  FROM list_pastor
					  WHERE match_name='$match_name'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function goSearch($value,$base_id) {
	if($base_id < 98)
		$base_limited = "AND baseid='$base_id'";
	else
		$base_limited = "";

	 	$query = "SELECT *
					  FROM list_pastor
					  WHERE (match_name ilike '%$value%'
						OR cast(id as text) ilike '%$value%')
						$base_limited AND tag <> '0'";

	return $query;
}

function countThrive_card_byBase($base,$month,$year) {
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	$base = "TD".$base."%";
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT COUNT(*)
		FROM log_thrive_card
		WHERE year = '$year'
		AND month = '$month'
		AND fk_thrive_pk ilike '$base'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function countThrive_card_byBase_sd($base,$month,$year) {
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	$base = "TD".$base."%";
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT COUNT(*)
		FROM log_thrive_second_day
		WHERE year = '$year'
		AND month = '$month'
		AND fk_thrive_pk ilike '$base'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function countThrive_card_byMonth($month,$year) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT COUNT(*)
		FROM log_thrive_card
		WHERE year = '$year'
		AND month = '$month'
		AND fk_thrive_pk ilike 'TD%'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function countThrive_card_byMonth_sd($month,$year) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT COUNT(*)
		FROM log_thrive_second_day
		WHERE year = '$year'
		AND month = '$month'
		AND fk_thrive_pk ilike 'TD%'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function countThrive_card_byDistrict_byMembership($program,$month,$year,$district,$membership) {
	if (1 == $program) {
		$table = "log_thrive_card";
	}
	else if (2 == $program) {
		$table = "log_thrive_second_day";
	}

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
		FROM $table
		LEFT JOIN list_pastor
		ON $table.fk_pastor_pk = list_pastor.id
		WHERE year = '$year'
		AND month = '$month'
		AND fk_thrive_pk ilike '$district'
		AND member = '$membership'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	return $row['0'];
}

function get_district_attendee_bySchedule($district_pk,$program,$year,$month) {
	if ("1" == $program) {
		$table = "log_thrive_card";
	}
	else if("2" == $program) {
		$table = "log_thrive_second_day";
	}

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *, $table.id AS att_pk,$table.updated_by AS entry_updated_by, $table.updated_date AS entry_updated_date FROM $table LEFT JOIN list_pastor ON $table.fk_pastor_pk = list_pastor.id WHERE fk_thrive_pk = '$district_pk' AND year = '$year' AND month = '$month'";
	$result = pg_query($dbconn, $query);
	if (!$result) {
		echo "A result error occurred.\n";
		exit;
	}
	else {
		return $result;
	}
}
?>
