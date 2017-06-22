<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
<link rel="shortcut icon" href="_media/ptfavicon1.ico" >

<?php
error_reporting(0);
include "dbconnect.php";

function verifyLogin($a, $b)
{
	$conn_string="host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn=pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$return="";

	$query="SELECT * FROM individual where username = '$a'";
	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
    $tag=$row['tag'];
	$pword=$row['password'];

	if($b==$pword&&$tag<>0)
	{
			$username = $row['username'];
			$userid = $row['uid'];
			$tag = $row['tag'];
			$baseid = $row['baseid'];
			$account_lname = $row['lastname'];
			$account_fname = $row['firstname'];
			session_start();
			$_SESSION['username']=$username;
			$_SESSION['userid']=$userid;
			$_SESSION['accesslevel']=$tag;
			$_SESSION['baseid']=$baseid;
			$_SESSION['last_name']=$account_lname;
			$_SESSION['first_name']=$account_fname;
			$return = "True";
	}
	else
		$return = "False";

	return $return;
}

function getUserInfo($a)
{
	$query = "SELECT *
			 FROM individual
			 WHERE uid='$a'";

	return $query;
}

function getDate_details($date) {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
							FROM list_batch_week
							WHERE date_start <= '$date'
							AND date_end >= '$date'";
		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
}

function getBase_list() {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
							FROM list_base
							ORDER BY id";
		$result = pg_query($dbconn, $query);

		return $result;
}


function getBaseName($a)
{
	if($a == "1")
	  $base = "Bacolod";
	else if($a == "2")
	  $base = "Bohol";
	else if($a == "3")
	  $base = "Dumaguete";
	else if($a == "4")
	  $base = "General Santos";
	else if($a == "5")
	  $base = "Koronadal";
	else if($a == "6")
	  $base = "Palawan";
	else if($a == "7")
	  $base = "Dipolog";
	else if($a == "8")
	  $base = "Iloilo";
	else if($a == "9")
	  $base = "Cebu";
	else if($a == "10")
	  $base = "Roxas";
	else if($a == "99")
	  $base = "Hong Kong";
	else if($a == "98")
	  $base = "Manila";
	else
	  $base = "Undefined";

	return $base;
}

function getProgram($a)
{
	if($a == "1")
	  $program = "Transform - Regular";
	else if($a == "2")
	  $program = "Transform - Jumpstart Parents";
	else if($a == "3")
	  $program = "Transform - OSY";
	else if($a == "4")
	  $program = "Transform - SLP";
	else if($a == "5")
	  $program = "Transform - PBSP";
	else
	  $program = "Undefined";

	return $program;
}

function getUserRole($a)
{
	if($a == "1")
	  $role = "Administrator";
	else if($a == "2")
	  $role = "Head";
	else if($a == "3")
	  $role = "Pastor Profile Encoder";
	else if($a == "4")
	  $role = "Disaster Coordinator";
	else if($a == "5")
	  $role = "Transform Encoder";
	else if($a == "6")
	  $role = "Area Head";
	else if($a == "7")
	  $role = "Network Leader";
	else if($a == "99")
	  $role = "Super Admin";
	else
	  $role = "Undefined";

	return $role;
}

function getTransform_class($a)
{
	if($a == "1")
	  $role = "Participant: Original";
	else if($a == "2")
	  $role = "Participant: Replacement Prior";
	else if($a == "3")
	  $role = "Participant: Replacement During";
	else if($a == "4")
	  $role = "Participant: Eyeball Prior";
	else if($a == "5")
	  $role = "Participant: Eyeball During";
	else if($a == "6")
	  $role = "Paricipant: w/o Scorecard";
	else if($a == "20")
	  $role = "Counselor: Original";
	else if($a == "21")
	  $role = "Counselor: Replacement Prior";
	else if($a == "22")
	  $role = "Counselor: Replacement During";
	else
	  $role = "Undefined";

	return $role;
}

function getBatch($condition,$date)
{
	if($condition == "specified")
		$month = $date;

	else if($condition == "current")
		$month = date("n");

	if($month >= 6 && $month <= 9)
		$batch  = "1";

	else if($month >= 10 && $month <= 12 || $month == 1)
		$batch  = "2";

	else if($month >= 2 && $month <= 5)
		$batch  = "3";

	else
		$batch  = "0";

	return $batch;
}

function countNewPastorProfiles()
{
	$conn_string="host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn=pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query="select count (*)
				from list_pastor
				where createddate >= date_trunc('month', CURRENT_DATE)";
	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$count=$row['0'];

	return $count;
}

function countNewPastorProfiles_Base($base)
{
	$conn_string="host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn=pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query="select count (*)
				from list_pastor
				where createddate >= date_trunc('month', CURRENT_DATE)
				AND baseid = '$base'";
	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$count=$row['0'];

	return $count;
}

function compare($first,$second) {
  if($first > $second) {
    $icon = "arrow_drop_down";
    $color = "#d25050";
  }
  else if($first < $second) {
    $icon = "arrow_drop_up";
    $color = "#0fae32";
  }
  else {
    $icon = "";
    $color = "";
  }

  return "<i class='material-icons' style='font-size: 15px; color:$color;'>$icon</i>";
}
?>
