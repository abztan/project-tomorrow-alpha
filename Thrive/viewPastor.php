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

$x = "";
if($access_level == 10) {
	$x ="hidden";
}

$pid = $_GET['a'];
$row = getPastorDetails($pid);
$pid = $row['id'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$middlename = $row['middlename'];
$gender = $row['gender'];
$status = $row['status'];
$birthday = $row['birthday'];
$birthday = date("F j, Y",strtotime(str_replace('-','/', $birthday)));
$contact1 = $row['contact1'];
$contact2 = $row['contact2'];
$contact3 = $row['contact3'];
$email = $row['email'];
$church_id = $row['churchid'];
if($email == "")
	$email = "-";
$education = $row['education'];
if ($row['seminary'] == "t")
	$seminary = "Yes";
else if ($row['seminary'] == "f")
	$seminary = "No";
else
	$seminary = "-";
$active = $row['active'];
$thrive = $row['thriveid'];
if($row['address'] == "")
	$address = "";
else
	$address = $row['address'].", ";
$barangay = $row['barangay'];
$city = $row['city'];
$province = $row['province'];

if($church_id != "")
{
	$church = getChurch_details($church_id);
	$denomination = $church['denomination'];
	$church_name = $church['churchname'];
}
else {
	$church_name = "-";
	$denomination = "-";
}
$position = $row['position'];
$thrive = $row['thriveid'];
$baseid = $row['baseid'];

if($row['active']=="f")
	$active = "No";
else
	$active = "Yes";
if($row['member']=="f")
	$member = "No";
else
	$member = "Yes";
if($row['membershipdate']=="")
	$membershipdate = "-";
else
	$membershipdate = $row['membershipdate'];
$createdby=$row['createdby'];
$createddate=$row['createddate'];
$created=$createdby.", ".$createddate;
if($row['updateddate']=="")
{
	$updatedby="This entry has never been updated";
	$updateddate="";
	$updated=$updatedby.$updateddate;
}
else
{
	$updateddate = $row['updateddate'];
	$updatedby = $row['updatedby'];
	$updated=$updatedby.", ".$updateddate;
}

//update defaults
$program = getLogPastorProgram_details($pid);
$pg1 = $program['program_1'];
$pg2 = $program['program_2'];
$comment = $row['comment'];
$pg1_check = "";
$pg2_check = "";

if($pg1 == "1")
	$pg1_check = "checked";
if($pg2 == "1")
	$pg2_check = "checked";

if(isset($_POST['update'])) {

	if(isset($_POST['pg1'])) {
		$pg1_value = $_POST['pg1'];
		$pg1 = $pg1_value['0'];
	}
	else
		$pg1 = 0;

	if(isset($_POST['pg2'])) {
		$pg2_value = $_POST['pg2'];
		echo $pg2 = $pg2_value['0'];
	}
	else
		$pg2 = 0;

	if(isset($_POST['comment']))
		$comment = $_POST['comment'];

	updateLogPastorProgram($pid, $pg1, $pg2, $username);
	updateListPastor_comment($pid, $comment, $username);

	header('Location: '.$_SERVER['REQUEST_URI']);
}

include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<header>
  <h2>Pastor Profile</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<table >
	<th colspan = "2" style="text-align: left;">Personal Information&nbsp;<?php if($accesslevel == "1" || $accesslevel == "3"  || $accesslevel == "7"  || $accesslevel == "8"  || $accesslevel == "9"  || $accesslevel == "99") echo'(<a href="editPastor.php?a='.$pid.'">edit</a>)';?></th>
	<tr><td width = "30%">Name</td><td><?php echo $lastname.", ".$firstname." ".$middlename;?></td></tr>
	<tr><td>Gender</td><td><?php echo $gender;?></td></tr>
	<tr><td>Birthday</td><td><?php echo $birthday;?></td></tr>
	<tr><td>Status</td><td><?php echo $status;?></td></tr>
	<tr><td>Educational Level</td><td><?php echo $education;?></td></tr>
	<tr><td>Attended Seminary</td><td><?php echo $seminary;?></td></tr>
	<tr><td>Church</td><td><?php echo $church_name;?></td></tr>
	<tr><td>Denomination</td><td><?php echo $denomination;?></td></tr>
	<tr><td>Position</td><td><?php echo $position;?></td></tr>
	</table>
<br/>
<table >
<th colspan = "2" style="text-align: left;">Location and Contact Information</th>
<tr><td width = "30%">Address</td><td><?php echo $address,$barangay;?></td></tr>
<tr><td>City or Municipality</td><td><?php echo $city;?></td></tr>
<tr><td>Province</td><td><?php echo $province;?></td></tr>
<tr><td>Contact Numbers</td><td><?php echo $contact1; if($contact2!="") echo ", ".$contact2; if($contact3!="")echo ", ".$contact3;?></td></tr>
<tr><td>Email</td><td><?php echo $email;?></td></tr>
</table>
<br/>
<table >
<th colspan = "2" style="text-align: left;">ICM Information</th>
<tr><td width = "30%">Base - Thrive Area</td><td><?php echo getBaseName($row['baseid']);?></td></tr>
<tr><td>Thrive</td><td><?php echo $thrive;?></td></tr>
<tr><td>Active</td><td><?php echo $active;?></td></tr>
<tr><td>Member</td><td><?php echo $member;?></td></tr>
<tr><td>Date of Membership</td><td><?php echo $membershipdate;?></td></tr>
<tr><td>Created Information</td><td><?php echo $created;?></td></tr>
<tr><td>Update Information</td><td><?php echo $updated;?></td></tr>
</table>
 </section>
<br/>
<br/>
<table>
	<th colspan = "2" style="text-align: left;">Pastor Programs</th>
	<?php if($access_level == "8" || $access_level == "1" || $access_level == "99")
	echo '
	<tr><td>Tearfund - Disaster Preparedness Training</td><td><input type="checkbox"'.$pg1_check.' name="pg1[]" value="1" /></td></tr>
	<tr><td>D5000 - Training</td><td><input type="checkbox"'.$pg2_check.' name="pg2[]" value="1" /></td></tr>';?>
	<tr><td style="vertical-align:top;">Comment/s</td><td><textarea name="comment" value=""><?php echo $comment;?></textarea></td></tr>
	<tr><td colspan = "2" style="text-align: right;"><button class="btn btn-embossed btn-primary" <?php echo $x;?> name='update'>Update</button></td></tr>
</table>
</article>
</form>


</body>
<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</html>
