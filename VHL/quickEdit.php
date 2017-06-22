<?php

	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="updateParticipant.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$entry_id=$_GET['a'];
	$participant_pk = $_GET['b'];
	$notice = "";
	$row=getParticipantDetails($participant_pk);
	$row1=getParticipantPscDetails($participant_pk);
	$last_name=$row['last_name'];
	$first_name=$row['first_name'];
	$middle_name=$row['middle_name'];
	$gender=$row['gender'];
	$birthday=$row['birthday'];
	$contact_number=$row['contact_number'];
	$variable1=$row['variable1'];
	$category=$row['category'];

	if(isset($_POST['update']))
	{

		//participant info
		if(isset($_POST['l_name']))
		  $last_name=ucwords(strtolower($_POST['l_name']));
		if(isset($_POST['f_name']))
		  $first_name=ucwords(strtolower($_POST['f_name']));
		if(isset($_POST['m_name']))
		  $middle_name=ucwords(strtolower($_POST['m_name']));
		if(isset($_POST['hh_member']))
		  $hh_member=$_POST['hh_member'];
		if(isset($_POST['hh_sick']))
		  $hh_sick=$_POST['hh_sick'];
		if(isset($_POST['hh_income']))
		  $hh_income=$_POST['hh_income'];
		if(isset($_POST['contact_number']))
		  $contact_number=$_POST['contact_number'];
		if(isset($_POST['four_ps']))
		  $variable1=$_POST['four_ps'];
		if(isset($_POST['category']))
		  $category=$_POST['category'];
		if(isset($_POST['gender']))
			$gender=$_POST['gender'];
		if(isset($_POST['bday']))
		  $birthday=$_POST['bday'];

		//check duplicate
		if(checkParticipantEntry($last_name,$first_name,$middle_name)!="" && checkParticipantEntry($last_name,$first_name,$middle_name)!=$participant_pk)
			$notice = "Sorry but an entry with this name already exists.";

		else
		{
				updateParticipant($participant_pk,$last_name,$first_name,$middle_name,$username,$contact_number,$variable1,$category,$gender,$birthday);

				$notice =  "You have successfully updated this entry, redirecting page in <span id='counter'>3</span>";
				$address = "people.php?a=$entry_id";
		}
	}

	if(isset($_POST['delete']))	{
		header('location: _delete_participant.php?a='.$participant_pk);
	}

	if(isset($_POST['back']))	{
		header('location: /ICM/VHL/people.php?a='.$entry_id);
	}




	include "../_css/bareringtonbear.css";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
	<style type="text/css">
		select {min-width:225px;}
		input {min-width:30px;}
	</style>
</head>

<link href="/ICM/_css/flat-ui.css" rel="stylesheet">

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>

<fieldset>
	<legend>Update Participant</legend>
	<label>First Name</label><br/>
	<input placeholder="First Name/s" class="form-control input-sm" type="text" name="f_name" value = "<?php echo $first_name;?>">
	<br/>
	<label>Middle Name</label><br/>
	<input placeholder="Middle Name" class="form-control input-sm" type="text" name="m_name" value = "<?php echo $middle_name;?>">
	<br/>
	<label>Last Name</label><br/>
	<input placeholder="Last Name" class="form-control input-sm" type="text" name="l_name" value = "<?php echo $last_name;?>">
	<br/>
	<label>Gender</label><br/>
		<select id="gender" name="gender">
		<?php
		$a="";
		$b="";

		if($gender == "Male")
			 $a = "selected";
		else if($gender == "Female")
			 $b = "selected";
		else
			 echo "<option disabled selected>Please Choose</option>";
		?>
		<option <?php echo $a;?> value="Male">Male</option>
		<option <?php echo $b;?> value="Female">Female</option>
		</select>
	<br/>
	<label>Birthday</label><br/>
	<input class="form-control input-sm" type="date" name="bday" id="bday" value="<?php echo $birthday;?>">
	<br/>
	<label>Phone Number</label><br/>
	<input placeholder="Phone Number" class="form-control input-sm" type="text" min="0" max=""  maxlength="11" name="contact_number" value = "<?php echo $contact_number;?>">
	<br/>
	<label>4Ps Member</label><br/>
	<select id="four_ps" name="four_ps" <?php if($variable1!="") echo "disabled";?> >
	<?php

	$a="";
	$b="";

	if($variable1 == "Yes")
		$a = "selected";
	else if($variable1 == "No")
		$b = "selected";
	else
		echo "<option disabled selected>Please Choose</option>";
	?>
	<option <?php echo $a;?> value="Yes">Yes</option>
	<option <?php echo $b;?> value="No">No</option>
	</select>
	<br/>
	<label>Category</label><br/>
	<select id="category" name="category">
	<?php

	$a="";
	$b="";
	$c="";
	$d="";
	$e="";
	$f="";
	$g="";
	$h="";

	if($category == "1")
		$a = "selected";
	else if($category == "2")
		$b = "selected";
	else if($category == "3")
		$c = "selected";
	else if($category == "4")
		$d = "selected";
	else if($category == "5")
		$e = "selected";
	else if($category == "20")
		$f = "selected";
	else if($category == "21")
		$g = "selected";
	else if($category == "22")
		$h = "selected";
	else
		echo "<option disabled selected>Please Choose</option>";
	?>
	<option <?php echo $a; ?> value="1">Participant - Original</option>
	<option <?php echo $b; ?> value="2">Participant - Replacement Prior</option>
	<option <?php echo $c; ?> value="3">Participant - Replacement During</option>
	<option <?php echo $d; ?> value="4">Participant - Eyeball Prior</option>
	<option <?php echo $e; ?> value="5">Participant - Eyeball During</option>
	<option <?php echo $f; ?> value="20">Counselor - Original</option>
	<option <?php echo $g; ?> value="21">Counselor - Replacement Prior</option>
	<option <?php echo $h; ?> value="22">Counselor - Replacement During</option>
	</select>
	<br/><br/><br/>
	<button class="btn btn-embossed btn-primary" name="delete">Delete</button>
	<button class="btn btn-embossed btn-primary" name='update'>Update</button>
	<button class="btn btn-embossed btn-primary" name='back'>Back</button>
</fieldset>
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
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
</body>

</html>
