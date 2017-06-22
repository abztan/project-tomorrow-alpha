<?php
include "_tnsFunctions.php";
include "../_parentFunctions.php";

$appid = '630';
$application = getApplication_Data_byID($appid);
$apptype = $application['application_type'];


$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
$app = getApplicationDetails($appid);

//year
$date = $app['updated_date'];
$year = date("y",strtotime($date));

//base
$baseid = $app['base_id'];
$baseid = str_pad($baseid, 2, 0, STR_PAD_LEFT);

//program
$programid = $app['application_type'];

//batch
$month = date("m",strtotime($date));
$batch = getBatch("specified",$month);
if($batch == "1")
  $batch = "2";
else if($batch == "2")
  $batch = "3";
else if($batch == "3")
  $batch = "1";
else
  echo "";

echo "<br/>next_comm_id:".$next_comm_id = $year."".$baseid."".$programid.""."1";

$max_id = getApplication_MaxCommunityId_ByBaseByType($baseid,$apptype);
echo "<br/>current:".$current_id = substr($max_id, 0, 6);
echo "<br/>current_rep:".$community_count = substr($current_id, -1)."1";

if($next_comm_id == $current_id)
{
  //append
  $community_count = substr($max_id, -2);
  $community_count = $community_count +1;
}

else
  $community_count = "1";

echo "<br/>community_count:".$community_count = str_pad($community_count, 2, 0, STR_PAD_LEFT);

//final id
echo "<br/>next:".$next_comm_id = $next_comm_id.$community_count;
?>
