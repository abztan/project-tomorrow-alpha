<?php
include "../dbconnect.php";

function addPerson($lname,$fname,$email,$uname,$pword,$role,$base)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$dt = new DateTime();
	$timestamp = $dt->format('Y-m-d H:i:s');

	$query = "SELECT MAX(uid)
				  FROM individual";
	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$uid=$row['0'];
	$uid=$uid+1;

	$query = "INSERT INTO individual
	 (uid
	 ,firstname
	 ,lastname
	 ,username
	 ,password
	 ,email
	 ,tag
	 ,baseid
	 ,created_date)

	 VALUES
	 ('$uid','$fname','$lname','$uname','$pword','$email','$role','$base',TIMESTAMP '$timestamp')";

	$result = pg_query($dbconn, $query);
	if (!$result)
		{
			echo "An error occurred.\n";
			exit;
		}
}

function isPasswordUnique($pword)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				  FROM individual
				  WHERE password = '$pword'";

	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$i=$row['0'];
	if($i=="")

	return "t";

}

function updateParticipantID($participant_pk,$partcipant_id) {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant
					   SET
					   participant_id = '$partcipant_id',
					   updated_date = TIMESTAMP '$timestamp',
					   updated_by = 'SYSTEM'
					   WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
}

?>
