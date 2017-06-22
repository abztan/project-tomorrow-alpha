<?php
include "../dbconnect.php";
include_once "_tnsFunctions.php";


  if(isset($_GET['province'])&&$_GET['tag']=="true")	{
		$province = $_GET['province'];
		$query = "SELECT DISTINCT city
				  FROM list_barangay
				  WHERE province = '$province'
				  ORDER BY city ASC";

		$result = pg_query($dbconn, $query);

		if (!$result) {
			echo "An error occurred.\n";
			exit;
		}

		 echo "<option disabled selected>Please Choose</option>";

		 while ($row = pg_fetch_row($result)) {
			 echo "<option value='$row[0]'>$row[0]</option>";
		 }
	}

	 if(isset($_GET['city']))	{
		$province = $_GET['province'];
		$city = $_GET['city'];
		$query = "SELECT DISTINCT barangay
				  FROM list_barangay
				  WHERE province = '$province'
				  AND city = '$city'
				  ORDER BY barangay ASC";

		$result = pg_query($dbconn, $query);

		if (!$result)	{
			echo "An error occurred.\n";
			exit;
		}

		 echo "<option disabled selected>Please Choose</option>";
		 while ($row = pg_fetch_row($result)) {
			 echo "<option value='$row[0]'>$row[0]</option>";
		 }
	}

  if(isset($_GET['base']))	{
   $base = $_GET['base'];
   $query = "SELECT *
             FROM list_pastor
             WHERE baseid = '$base'
             ORDER BY lastname, firstname ASC";

   $result = pg_query($dbconn, $query);

   if (!$result)	{
     echo "An error occurred.\n";
     exit;
   }

    echo "<option disabled selected>Please Choose</option>";
    while ($row = pg_fetch_array($result)) {
      $id = $row['id'];
      $lastname = $row['lastname'];
      $firstname = $row['firstname'];
      $middlename = $row['middlename'];
      $middle_initial = $middlename[0];
      echo "<option value='$id'>$lastname, $firstname $middle_initial</option>";
    }
 }

	if(isset($_GET['people'])) {
		$people = $_GET['people'];

		if($people == "1")
		{
			echo 'Type
			<select id="people" name="people" onChange="">
			<option disabled selected>Please Choose</option>
			<option>New</option>
			<option>Replacement</option>
			</select>';


		}
	}

  if($_GET['command']=="update_participant_tag") {
    $participant_pk = $_GET['b'];
    $username = $_GET['d'];
    $participant = getParticipantDetails($participant_pk);
    $current_tag = $participant['tag'];
    if($current_tag != 6) {
      updateParticipantTag($participant_pk,6,$username);
    }
    else {
      updateParticipantTag($participant_pk,5,$username);
    }
  }

  if($_GET['command']=="update_visitor_grad") {
    $application_pk = $_GET['b'];
    $value = $_GET['c'];
    $username = $_GET['d'];
    updateVisitor_grad($application_pk,$value,$username);
  }


  if($_GET['command']=="update_comm_note") {
    $application_pk = $_GET['application_pk'];
    $note = $_GET['note'];
    $username = $_GET['username'];
    update_application_note($application_pk,$note,$username);
    echo "Successful";
  }

  if($_GET['command']=="delete_participant") {
    $participant_pk = $_GET['participant_pk'];
    deleteParticipant($participant_pk);
    echo "Successful";
  }

  if($_GET['command']=="delete_application") {
    $application_pk = $_GET['application_pk'];
    deleteParticipant_list($application_pk);
    deleteApplication($application_pk);
    echo "Successful";
  }


?>
