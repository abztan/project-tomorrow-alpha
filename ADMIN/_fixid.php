<?php

include "../VHL/_tnsFunctions.php";
include "../_parentFunctions.php";
include "_adminFunctions.php";

if(isset($_POST['fix']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "select *
	from list_transform_application
	where tag = '9'";

	$result = pg_query($dbconn, $query);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$appid = $row['id'];
		$apptype = $row['application_type'];
		$username = $row['updated_by'];
		echo $community_id = generateCommunityID($appid,$apptype);
		updateApplicationCommunityID($appid,$community_id,$username);
	}
}

if(isset($_POST['fix2']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	//gets all applications with community ids
	$query = "select *
	from list_transform_application
	where community_id ilike '16___2%' AND tag='9'";

	$result = pg_query($dbconn, $query);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$appid = $row['id'];
		$appd = getApplicationDetails($appid);
		$community_id = $appd['community_id'];

		//gets application's participant with participant ids in order
		$query1 = "select *
		from list_transform_participant
		where fk_entry_id = '$appid'
		ORDER BY category, last_name, first_name ASC";

		$result1 = pg_query($dbconn, $query1);

		$i = 0;

		while($row1=pg_fetch_array($result1,NULL,PGSQL_BOTH))
		{
			$participant_pk = $row1['id'];

			if($participant_pk == "")
				echo "no instances<br/>";

			else {
				$max_query = "select MAX(participant_id)
											from list_transform_participant
											where fk_entry_id = '$appid'";
				$max_result = pg_query($dbconn, $max_query);
				$max_row = pg_fetch_array($max_result,NULL,PGSQL_BOTH);
				$max_id = $max_row['0'];

				//first instance
				if($max_id <> '')
				  $max_last = substr($max_id, -2) + 1;
				else
					$max_last = 1;

				$max_last = str_pad($max_last, 2, 0, STR_PAD_LEFT);
				$participant_id = $community_id.$max_last;

				echo "PID".$participant_id."PPK".$participant_pk;

				if(checkDuplicate_participantID($participant_id) == "false")
				{
					updateApplication_ParticipantID($participant_pk,$participant_id,'SYSTEM');
					echo "HHID: ".$row1['participant_id']." SUPP: ".$participant_id."<br/>";
				}
				else {
					echo "will duplicate!";
				}
			}
		}
	}
}

if(isset($_POST['fix3']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	//gets all applications with community ids
	$query = "SELECT
      participant_id, COUNT(*)
  FROM
      list_transform_participant
  GROUP BY
       participant_id
  HAVING
      COUNT(*) > 1";

	$result = pg_query($dbconn, $query);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$participant_id_duplicate = $row['participant_id'];

		//gets application's participant with participant ids in order
		$query1 = "select *
		from list_transform_participant
		where participant_id = '$participant_id_duplicate'
		ORDER BY last_name, first_name ASC";
		$result1 = pg_query($dbconn, $query1);
		$c = 0;

		while($row1=pg_fetch_array($result1,NULL,PGSQL_BOTH))
		{
			$application_pk = $row1['fk_entry_id'];
			$participant_pk = $row1['id'];

			if($c!=0)
			{
				//clears duplicate participant ID
				$query2 = "UPDATE list_transform_participant
									 SET participant_id = NULL
									WHERE id ='$participant_pk'";
				$result2 = pg_query($dbconn, $query2);

				//updates new participant ID
				$max_query = "select MAX(participant_id)
											from list_transform_participant
											where fk_entry_id = '$application_pk'";
				$max_result = pg_query($dbconn, $max_query);
				$max_row = pg_fetch_array($max_result,NULL,PGSQL_BOTH);
				$max_id = $max_row['0'];
				$community_id = substr($max_id, 0, 8);
				//first instance
				if($max_id == "")
				  $max_last = 01;
				else
					$max_last = substr($max_id, -2) + 1;

				$counter = str_pad($max_last, 2, 0, STR_PAD_LEFT);
				$participant_id = $community_id.$counter;
				if(checkDuplicate_participantID($participant_id) == "false")
				{
					updateApplication_ParticipantID($participant_pk,$participant_id,'SYSTEM');
				}
				else {
					echo "will duplicate!";
				}
				echo "this instance was edited- ";
			}
			else
				echo "this instance was ignored: ";

			echo "ENTRY:".$application_pk = $row1['fk_entry_id'];
			echo "DUP:".$participant_id_duplicate;
			echo "PART_ID:".$participant_pk = $row1['id'];
			echo "<br/>";
		}
	}
}

if(isset($_POST['fix4']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
			 FROM list_pastor
			 where match_name is null or match_name = ''";

	$result = pg_query($dbconn, $query);
	while ($row = pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$first_name = $row['firstname'];
		$middle_name = $row['middlename'];
		$last_name = $row['lastname'];
		$pid = $row['id'];

		$match_first_name = str_replace('-','', $first_name);
		$match_first_name = str_replace('.','', $match_first_name);
		$match_first_name = str_replace(' ','', $match_first_name);
		$match_first_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_first_name);
		$match_first_name = strtolower($match_first_name);

		$match_middle_name = substr($middle_name,0,2);
		$match_middle_name = strtolower($match_middle_name);

		$match_last_name = str_replace('-','', $last_name);
		$match_last_name = str_replace('.','', $match_last_name);
		$match_last_name = str_replace(' ','', $match_last_name);
		$match_last_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_last_name);
		$match_last_name = strtolower($match_last_name);

		$match_name = $match_last_name.$match_first_name.$match_middle_name;

		$query1 = "UPDATE list_pastor SET match_name = '$match_name' WHERE id = '$pid'";
		$result1 = pg_query($dbconn, $query1);
	}
	echo "Success";
}

if(isset($_POST['fix5']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
			 FROM list_church";

	$result = pg_query($dbconn, $query);
	while ($row = pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$church = trim($row['churchname']);
		$church_prov = $row['province'];
		$church_city = $row['city'];
		$church_brgy = $row['barangay'];
		$church_id = $row['churchid'];

		$match_church = str_replace('-','', $church);
		$match_church = str_replace('.','', $match_church);
		$match_church = str_replace(' ','', $match_church);
		$match_church = preg_replace('/[^A-Za-z0-9\-]/', '', $match_church);
		$match_church = strtolower($match_church);

		$match_prov = str_replace('-','', $church_prov);
		$match_prov = str_replace('.','', $match_prov);
		$match_prov = str_replace(' ','', $match_prov);
		$match_prov = preg_replace('/[^A-Za-z0-9\-]/', '', $match_prov);
		$match_prov = strtolower($match_prov);

		$match_city = str_replace('-','', $church_city);
		$match_city = str_replace('.','', $match_city);
		$match_city = str_replace(' ','', $match_city);
		$match_city = preg_replace('/[^A-Za-z0-9\-]/', '', $match_city);
		$match_city = strtolower($match_city);

		$match_brgy = str_replace('-','', $church_brgy);
		$match_brgy = str_replace('.','', $match_brgy);
		$match_brgy = str_replace(' ','', $match_brgy);
		$match_brgy = preg_replace('/[^A-Za-z0-9\-]/', '', $match_brgy);
		$match_brgy = strtolower($match_brgy);

		$match_name = $match_church.$match_prov.$match_city.$match_brgy;

		$query1 = "UPDATE list_church SET match_name = '$match_name' WHERE churchid = '$church_id'";
		$result1 = pg_query($dbconn, $query1);
	}
	echo "Success";
}
if(isset($_POST['fix6']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
			 FROM list_pastor";

	$result = pg_query($dbconn, $query);
	while ($row = pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$first_name = trim($row['firstname']);
		$middle_name = trim($row['middlename']);
		$last_name = trim($row['lastname']);
		$pid = $row['id'];

		$query1 = "UPDATE list_pastor SET firstname = '$first_name', middlename = '$middle_name', lastname = '$last_name' WHERE id = '$pid'";
		$result1 = pg_query($dbconn, $query1);
	}
	echo "Success";
}
?>

<form name='form1' action='' method='POST'>
<button class="btn btn-embossed btn-primary" type = "submit" name = "fix">FIX ID</button>

<button class="btn btn-embossed btn-primary" type = "submit" name = "fix2">FIX Participant ID</button>

<button class="btn btn-embossed btn-primary" type = "submit" name = "fix3">Purge Duplicate</button>

<button class="btn btn-embossed btn-primary" type = "submit" name = "fix4">Generate Pastor Unique</button>

<button class="btn btn-embossed btn-primary" type = "submit" name = "fix5">Generate Church Unique</button>

<button class="btn btn-embossed btn-primary" type = "submit" name = "fix6">Trim Names</button>
</form>
