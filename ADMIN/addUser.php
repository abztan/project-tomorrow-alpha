<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="addUser.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
include "_adminFunctions.php";

$notice = "";

	if(isset($_POST['add']))
	{
		$lname=ucwords(strtolower($_POST['lname']));
		$fname=ucwords(strtolower($_POST['fname']));

		if(isset($_POST['email']))
		  $email=$_POST['email'];
		else
		  $email="";

		if(isset($_POST['uname']))
		  $uname=$_POST['uname'];
		else
		  $uname="";

		if(isset($_POST['pword']))
		  $pword=$_POST['pword'];
		else
		  $pword="";

		if(isset($_POST['role']))
		  $role=$_POST['role'];
		else
		  $role="";

		if(isset($_POST['base']))
		  $base=$_POST['base'];
		else
		  $base="";

		/*if(isPasswordUnique($pword) == "t")
		{*/
		  addPerson($lname,$fname,$email,$uname,$pword,$role,$base);
			$notice = "ok";
			//$notice = "You have successfully added a user, redirecting page in <span id='counter'>3</span>";
		/*}
		else
		  $notice = "ERROR, ABORT! ABORT! *ALARM*";*/
	}

	include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		input, select {width: 225px;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<header>
  <h2>Manage Users + Add User</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">
<section id="col1">
<legend>User Information</legend>
	<label>Name</label><br/>
	  <input class="form-control input-sm" placeholder="First Name/s" type="text" name="fname">
	  <input class="form-control input-sm" placeholder="Last Name" type="text" name="lname">
	<br/>
	<label>Email</label><br/>
	  <input class="form-control input-sm" placeholder="Email Address" type="text" name="email">
	<br/>
	<label>Username</label><br/>
	  <input class="form-control input-sm" placeholder="Username" type="text" name="uname">
	<br/>
	<label>Password</label><br/>
	  <input class="form-control input-sm" placeholder="Password" type="password" name="pword">
	<br/>
	 <label>Role</label>
	<br/>
	  <select id="role" name="role">
	  <option disabled selected>Please Choose</option>
	  <option value="1">Administrator</option>
	  <option value="2">Head</option>
	  <option value="3">Pastor Profile Encoder</option>
	  <option value="4">Disaster Coordinator</option>
	  <option value="54">Transform Head</option>
	  <option value="7">Thrive Leader</option>
	  <option value="6">Area Head</option>
	  <option value="9">Network Head</option>
	  <option value="99">Super Admin</option>
	  </select>
	<br/>
	 <label>Base - Thrive Area</label>
	<br/>
	  <select id="base" name="base">
	  <option disabled selected>Please Choose</option>
	  <option value="1">Bacolod</option>
	  <option value="2">Bohol</option>
	  <option value="3">Dumaguete</option>
	  <option value="4">General Santos</option>
	  <option value="5">Koronadal</option>
	  <option value="6">Palawan</option>
	  <option value="7">Dipolog</option>
	  <option value="8">Iloilo</option>
	  <option value="9">Cebu</option>
	  <option value="10">Roxas</option>
	  <option value="98">Manila</option>
	  <option value="99">Hong Kong</option>
	  </select>
	<br/><br/>
	<button class="btn btn-embossed btn-primary" type = "submit" name = "add">Done</button>
	<br/>
	<br/><?php echo $notice;?>
</section>
</article>
</form>
<script type="text/javascript">
function countdown() {
    var i = document.getElementById('counter');
    if (parseInt(i.innerHTML)<=1) {
        location.href = 'adminUser.php';
    }
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
<script src='../parent.js'></script>
</body>

</html>
