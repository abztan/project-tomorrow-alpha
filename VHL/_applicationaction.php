<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="_shortlistapplication.php";

include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];

//defaults
$setup = $_GET['a'];
$attribute1 = $_GET['b'];
$attribute2 = $_GET['c'];
$attribute3 = $_GET['d'];
$attribute4 = $_GET['e'];
$attribute5 = $_GET['f'];


// setup 1 checks capacity of for ocular applications and moves application forward
// setup 2 drop to application
// setup 3 checks capacity of selection and moves application forward
// setup 4 drop to for ocular
// setup 5 override participant
// setup 7 drop community or application
// setup 8 drop participant
// setup 9 return participant
// setup 10 delete application
// setup 11 delete participant

if($setup == "1")
{
	$for_approval_total = countApplicationTagByBase('2',$attribute2);
	$selection_total = countApplicationTagByBase('3',$attribute2);



	if(($for_approval_total + $selection_total == '40') && $attribute2 != '3' && $attribute2 != '4' && $attribute2 != '5' && $attribute2 != '7')
	{
		header('location: /ICM/VHL/viewApplication.php?a='.$attribute1.'&b=Sorry the maximum applications for approval has been reached.');
	}

	else
	{
		updateApplicationTag($attribute1,2,$username);
		header('location: /ICM/VHL/viewApplication.php?a='.$attribute1.'&b=You have successfully approved this application.');
	}
}

else if($setup == "2")
{
	updateApplicationTag($attribute1,1,$username);
	header('location: /ICM/VHL/viewApplication.php?a='.$attribute1.'&b=You have successfully dropped this application.');
}

else if($setup == "3")
{
	updateApplicationTag($attribute1,3,$username);
	header('location: /ICM/VHL/viewApplication.php?a='.$attribute1.'&b=You have successfully approved this application.');
}

else if($setup == "4")
{
	updateApplicationTag($attribute1,2,$username);
	header('location: /ICM/VHL/viewApplication.php?a='.$attribute1.'&b=You have successfully dropped this application.');
}

else if($setup == "5") {
	//att1 is application_pk
	//att2 is participant pk
	//att3 is reason
	//att4 is participant_tag

	if($attribute5 == 2)
		$limit = 50;
	else
		$limit = 50;

	if($attribute4 == "5") {
		updateParticipantTag($attribute2,2,$username);
		updateParticipant_PS_Remark($attribute2,$attribute3,$username);
	}
	else if($attribute4 == "2") {

		if(countParticipantTag($attribute1,5) >= $limit) {
			echo "ERROR";
		}
		else {
			updateParticipantTag($attribute2,5,$username);
			updateParticipant_PS_Remark($attribute2,$attribute3,$username);
		}
	}
	else if($attribute4 == "3") {
		if(countParticipantTag($attribute1,5) >= $limit) {
			header('location: /ICM/VHL/headApprovalView.php?a=$attribute1');
		}
		else {
			updateParticipantTag($attribute2,5,$username);
			updateParticipant_PS_Remark($attribute2,$attribute3,$username);
		}
	}

	header('location: /ICM/VHL/headApprovalView.php?a='.$attribute1);
}

/*
else if($setup == "6") {
	updateParticipantTag($attribute2,6,$username);
	updateParticipant_PS_Remark($attribute2,$attribute3,$username);
	header('location: /ICM/VHL/viewCommunity.php?a='.$attribute1);
}*/

else if($setup == "7") {
	updateApplicationTag($attribute1,4,$username);
	updateApplication_reason($attribute1, $attribute2, $username);
	$q = getParticipant_forApplication_byTag($attribute1,5,"id","=");
	while($participant = pg_fetch_array($q,NULL,PGSQL_BOTH)) {
		$participant_pk = $particpant['id'];
		updateParticipantTag($participant_pk,9,$username);
	}

	header('location: /ICM/VHL/new_community_view.php?a='.$attribute1);
}

else if($setup == "8") {
	updateParticipantTag($attribute1,9,$attribute2);
	header('location: /ICM/VHL/people.php?a='.$attribute3);
}

else if($setup == "9") {
	updateParticipantTag($attribute1,5,$attribute2);
	header('location: /ICM/VHL/people.php?a='.$attribute3);
}

//delete application
else if($setup == "10") {
	deleteApplication($attribute1);
	header('location: /ICM/VHL/listApplication.php');
}

else if($setup == "11") {
	deleteParticipant($attribute1);
	header("location: /ICM/VHL/viewSelection.php?a=".$attribute2);
}



?>
