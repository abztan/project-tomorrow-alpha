<?php
include_once "_ptrFunctions.php";
include_once "../../_parentFunctions.php";

if(isset($_GET['a']))
	$a = $_GET['a'];
else
	$a = "";

$what = "Nothing";

//delete
if($a == "1") {
  $pastor_pk = $_GET['b'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
  $district_pk = $_GET['c'];
	$address = "district_view.php?a=$district_pk";
  $what = "Success";
  $notice = "$pastor_pk_string - $pastor_name profile has been deleted.
  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
}

//delete from search temporary
if($a == "1_temp") {
  $pastor_pk = $_GET['b'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
  $district_pk = $_GET['c'];
	$address = "district_view.php?a=$district_pk";
  $what = "Success";
  $notice = "$pastor_pk_string - $pastor_name profile has been deleted.
  <br/><br/>You may close this page.";
}

//add profile success
if($a == "2") {
  $what = "Success";
  $pastor_pk = $_GET['b'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
  $notice = "$pastor_pk_string - $pastor_name profile has been added.
  <br/>
	<p>Click <a href='profile_view_search.php?a=$pastor_pk' target='_blank'>here</a> to view the added profile.</p>
  <p>or</p>
  <p>Click <a href='profile_add.php'>here</a> to add another profile.</p>";
}

//add profile fail
if($a == "3") {
  $what = "Failed";
  $address = "profile_add.php";
  $notice = "Sorry, something went wrong with adding this profile. Please contact your Administrator.
  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue.";
}

//add CARD success
if($a == "4") {
	$update = $_GET['d'];
  $what = "Success";
  $address = "data_card_add.php";
	$pastor_pk = $_GET['b'];
	$date = $_GET['c'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);

	if($update == "1") {
		$notice = "$pastor_pk_string - $pastor_name thrive data card has been added.
	  <br/><br/>
		<p><a href='editPastor.php?a=$pastor_pk' target='_blank'>UPDATE</a> profile now or <a href='$address'>ADD</a> another data card.</p>";
	}
	else
		$notice = "$pastor_pk_string - $pastor_name thrive data card of $date has been added.
	  <br/><br/>
	  <p>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue card.</p>";

}

//add CARD failed
if($a == "5") {
  $what = "Failed";
  $address = "data_card_add.php";
	$pastor_pk = $_GET['c'];
	$date = $_GET['d'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);

	if($_GET['b'] == "1") {
		$notice = "$pastor_pk_string - $pastor_name data card of $date has already been encoded.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}

	else if($_GET['b'] == "2") {
		$notice = "$pastor_pk_string - $pastor_name belongs to another base.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}

	else if($_GET['b'] == "3") {
		$notice = "$pastor_pk_string - $pastor_name data card of $date encountered a problem. Please contact your administrator.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}
}

//add SD CARD results
if($a == "6") {
  $address = "sd_attendance_add.php";
	$pastor_pk = $_GET['c'];
	$date = $_GET['d'];
  $pastor = getPastorDetails($pastor_pk);
	$pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);

	if($_GET['b'] == "1") {
		$what = "Success";
		$notice = "$pastor_pk_string - $pastor_name Second Day attendance of $date has been encoded.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}

	else if($_GET['b'] == "2") {
		$what = "Failed";
		$notice = "$pastor_pk_string - $pastor_name belongs to another base.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}

	else if($_GET['b'] == "3") {
	  $what = "Failed";
		$notice = "$pastor_pk_string - $pastor_name Second Day attendance of $date encountered a problem. Please contact your administrator.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}

	else if($_GET['b'] == "4") {
		$what = "Failed";
		$notice = "$pastor_pk_string - $pastor_name Second Day attendance of $date has already been encoded.
	  <br/><br/>Re-directing in <span id='counter'>5</span> seconds or click <a href='$address'>here</a> to continue ";
	}
}
?>

<style>
span:hover{
    background:#FFFDE7;
	font-weight: 400;
}

#title {
	font-family: 'Roboto', sans-serif;
	font-weight: 300;
	font-size:24px;
	color: #F4511E;
	padding: 20px 16px 24px 16px;
}

#message {
	display: inline-block;
	font-family: 'Roboto', sans-serif;
	font-size:14px;
	font-weight: 400;
	color: #212121;
	padding-left: 24px;
}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Profile</title>

	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--6-col" style="background:#E7E4DF;">
      <div id="title"><?php echo $what?></div>
      <div id="message"><?php echo $notice;?></div>
      <br/><br/>
    </div>
  </div>
</body>
</html>

<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');

    if (parseInt(i.innerHTML)<=1) {
        location.href = '<?php echo $address;?>';
    }
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>
