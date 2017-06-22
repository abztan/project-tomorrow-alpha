<?php
session_start();
$accesslevel = $_SESSION['accesslevel'];

if(empty($_SESSION['username']))
	header('location: /iCM/Login.php?a=2');

else {
	$username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];
}

include "_ptrFunctions.php";
include "../_parentFunctions.php";
//php

include "../dbconnect.php";

$pastor_pk = $_GET['a'];
//box 1
$pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
$pastor = getPastorDetails($pastor_pk);
$first_name = $pastor['firstname'];
$last_name = $pastor['lastname'];
$middle_name = $pastor['middlename'];
$gender = $pastor['gender'];
$status = $pastor['status'];
$birthday = $pastor['birthday'];
$birthday = date("F j, Y",strtotime(str_replace('-','/', $birthday)));
$education = $pastor['education'];
if ($pastor['seminary'] == "t")
	$seminary = "Yes";
else if ($pastor['seminary'] == "f")
	$seminary = "No";
else
	$seminary = "-";

//box 2
$church_id = $pastor['churchid'];
if($church_id != "")
{
	$church = getChurch_details($church_id);
	$denomination = $church['denomination'];
	if($denomination == "")
		$denomination = "-";
	$church_name = $church['churchname'];
	$is_planted = $church['plantedbyicm'];
	if($is_planted == "")
		$is_planted = "-";
	$church_barangay = $church['barangay'];
	$church_city = $church['city'];
	$church_province = $church['province'];
	$church_address = $church['address'];
}
$position = $pastor['position'];

//box 3
if($pastor['address'] == "")
	$address = "";
else
	$address = $pastor['address'].", ";
$barangay = $pastor['barangay'];
$city = $pastor['city'];
$province = $pastor['province'];
$contact_1 = $pastor['contact1'];
$contact_2 = $pastor['contact2'];
$contact_3 = $pastor['contact3'];
if($contact_1 == "") {
	$contact_1 = "-";
}
$email = $pastor['email'];
	if($email == "")
		$email = "-";

//box 4
$active = $pastor['active'];
$district_pk = $pastor['thriveid'];
$base_id = $pastor['baseid'];
$district = getDistrict_Details_byTHID($district_pk);
$base_id = $district['base_id'];
$district_name = $district['alternate_name'];
if($pastor['active']=="f")
	$active = "No";
else
	$active = "Yes";
if($pastor['member']=="f")
	$member = "No";
else
	$member = "Yes";
if($pastor['membershipdate']=="")
	$membership_date = "-";
else
	$membership_date = $pastor['membershipdate'];
$membership_date = date("F j, Y",strtotime(str_replace('-','/', $membership_date)));
$attendance = getAttendance_Recent($pastor_pk);
$attendance_month = $attendance['month'];
$attendance_year = $attendance['year'];
if($attendance_month = "" || $attendance_year == "") {
	$attendance_year = "-";
	$attendance_month = "";
}

if($attendance_month != "") {
	$dateObj = DateTime::createFromFormat('!m', $attendance_month);
	$attendance_month = $dateObj->format('F');
}


$created_by = $pastor['createdby'];
$created_date = $pastor['createddate'];
$created = $created_by.", ".$created_date;
if($pastor['updateddate']=="")
{
	$updated_by="This entry has never been updated";
	$updated_date="";
	$updated=$updated_by.$updated_date;
}
else
{
	$updated_date = $pastor['updateddate'];
	$updated_by = $pastor['updatedby'];
	$updated = $updated_by.", ".$updated_date;
}

//update defaults
$program = getLogPastorProgram_details($pastor_pk);
$pg1 = $program['program_1'];
if($pg1 == "1") {
	$pg1 = "Tearfund Disaster Preparedness Training";
}
else
	$pg1 = "";
$pg2 = $program['program_2'];
if($pg2 == "1") {
	$pg2 = "D5000 Training";
}
else
	$pg2 = "";

$comment = $pastor['comment'];

if(isset($_POST['back'])) {
	header('Location:viewDistrict.php?a='.$district_pk);
}
?>
<style>
span:hover{
    background:#FFFDE7;
	font-weight: 400;
}

#title {
	font-family: 'Roboto Thin', sans-serif;
	font-weight: 300;
	font-size:24px;
	color: #F4511E;
	padding: 20px 16px 24px 16px;
}

#label_style {
	display: inline-block;
	font-family: 'Roboto Condensed', sans-serif;
	font-size: 15px;
	font-weight: 700;
	color: #a1a1a1;
	padding: 12px 12px 12px 36px;
	vertical-align: top;
	text-align: right;
	width: 136px;
}

#content_style {
	display: inline-block;
	font-family: 'Roboto', sans-serif;
	font-size:14px;
	font-weight: 400;
	color: #212121;
	padding-left: 24px;
}

a {
	text-decoration:none;
}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Profile</title>
	<link rel="stylesheet" href="/ICM/_css/material.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
	<form name="FORM" action="" method="POST">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
		<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Back"><i class="material-icons">chevron_left</i></button>
		<button onClick="editPage()" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Edit"><i class="material-icons">mode_edit</i></button>
		<button onClick="deleteProfile()" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Delete"><i class="material-icons">delete</i></button>
		</div>
	</div>
	<div class="mdl-grid">
	  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
		  <div id="title">Personal Information</div>
		  <table>
		  <tr><td id="label_style">ID</td><td id="content_style"><?php echo $pastor_pk_string; ?></td></tr>
		  <tr><td id="label_style">Name</td><td id="content_style"><span title="Last Name"><?php echo $last_name; ?>,</span> <span title="First Name"><?php echo $first_name; ?></span> <span title="Middle Name"><?php echo $middle_name; ?></span></td></tr>
		  <tr><td id="label_style">Status</td><td id="content_style"><?php echo $status; ?></td></tr>
			<tr><td id="label_style">Gender</td><td id="content_style"><?php echo $gender; ?></td></tr>
		  <tr><td id="label_style">Birthday</td><td id="content_style"><?php echo $birthday; ?></td></tr>
		  <tr><td id="label_style">Education Level</td><td id="content_style"><?php echo $education; ?></td></tr>
		  <tr><td id="label_style">Attended Seminary</td><td id="content_style"><?php echo $seminary; ?></td></tr>
		  <tr><td id="label_style">Location</td><td id="content_style"><span title="Address"><?php echo $address; ?></span> <span title="Barangay"><?php echo $barangay; ?></span>, <span title="City"><?php echo $city; ?></span> - <span title="Province"><?php echo $province; ?></span></td></tr>
		  <tr><td id="label_style">Contact Numbers</td><td id="content_style"><?php echo $contact_1; if($contact_2!="") echo ", ".$contact_2; if($contact_3!="")echo ", ".$contact_3;?></td></tr>
		  <tr><td id="label_style">Email</td><td id="content_style"><?php echo $email; ?></td></tr>
		  </table>
			<br/>
	  </div>
	 </div>

	<div class="mdl-grid" >
	  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
			<div id="title">Pastorial Information</div>
		  <table>
		  <tr><td id="label_style">Church Name</td><td id="content_style"><?php echo $church_name; ?>&nbsp;&nbsp;&nbsp;<?php if($is_planted == "Yes") echo '<img title="Planted Through ICM" src="../_media/leaf-icon.png"/>';?></td></tr>
			<tr><td id="label_style">Denomination</td><td id="content_style"><?php echo $denomination; ?></td></tr>
		  <tr><td id="label_style">Church Position</td><td id="content_style"><?php echo $position; ?></td></tr>
		  <tr><td id="label_style">Location</td><td id="content_style"><span title="Address"><?php echo $church_address; ?></span> <span title="Barangay"><?php echo $church_barangay; ?></span>, <span title="City"><?php echo $church_city; ?></span> - <span title="Province"><?php echo $church_province; ?></span></td></tr>
		  </table>
			<br/>
	  </div>
	</div>

	<div class="mdl-grid">
	  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
			<div id="title">Thrive & Program Information</div>
		  <table>
		  <tr><td id="label_style">Base</td><td id="content_style"><?php echo getBaseName($base_id);?></td></tr>
		  <tr><td id="label_style">Thrive</td><td id="content_style">(<span title="Thrive ID"><?php echo $district_pk; ?></span>) <span title="District Name"><?php echo $district_name; ?></span></td></tr>
		  <tr><td id="label_style">Member</td><td id="content_style"><?php echo $member;?></td></tr>
		  <?php if($member != "No")
			  echo "<tr><td id='label_style'>Membership Date</td><td id='content_style'>$membership_date</td></tr>"; ?>
		  <tr><td id="label_style">Active</td><td id="content_style"><?php echo $active; ?></td></tr>
		  <tr><td id="label_style">Last Thrive Attended</td><td id="content_style"><?php echo $attendance_month." ".$attendance_year; ?></td></tr>
		  <tr><td id="label_style">Programs</td><td id="content_style"><?php echo $pg1; if($pg2 != "") echo ", "; echo $pg2; ?></td></tr>
		  <tr><td id="label_style">Comments</td><td id="content_style"><?php echo $comment; ?></td></tr>
		  </table>
			<br/>
	  </div>
	</div>

	<div class="mdl-grid">
	  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
			<div id="title">System Information</div>
		  <table>
		  <tr><td id="label_style">Last Updated By</td><td id="content_style"><?php echo $updated;?></td></tr>
		  <tr><td id="label_style">Created By</td><td id="content_style"><?php echo $created;?></td></tr>
		  </table>
			<br/>
	  </div>
	</div>
	</form>
</body>
<script src='default.js'></script>
</html>
