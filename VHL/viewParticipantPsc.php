<?php

	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="viewParticipantPsc.php";
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
	$updated_date = $row['updated_date'];
	$updated_by = $row['updated_by'];
	$contact_number=$row['contact_number'];
	$variable1=$row['variable1'];
	$participant_id=$row['participant_id'];
	$hh_member=$row1['hh_member'];
	$hh_sick=$row1['hh_sick'];
	$hh_income=$row1['hh_income'];
	$ps1=$row1['ps1'];
	$ps2=$row1['ps2'];
	$ps3=$row1['ps3'];
	$ps4=$row1['ps4'];
	$ps5=$row1['ps5'];
	$ps6=$row1['ps6'];
	$ps7=$row1['ps7'];
	$ps8=$row1['ps8'];
	$ps9=$row1['ps9'];
	$ps10=$row1['ps10'];
	$ps11=$row1['ps11'];
	$ps12=$row1['ps12'];
	$ps13=$row1['ps13'];
	$score=$row['variable2'];

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/viewSelection.php?a='.$entry_id);
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

<body>
<!--
<header>
  <h2>Participant Details</h2>
  <nav class="menu"><?php //include "../controller.php"; ?></nav>
</header>-->

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<table>
	<th colspan = "2" style="text-align: left;">Participant Information<?php if($accesslevel == "99" || $accesslevel == "1" || $accesslevel == "5") echo "(<a href='editParticipant.php?a=$entry_id&b=$participant_pk'>edit</a>)";?></th>
	<tr><td>First Name:</td><td><?php echo $first_name;?></td></tr>
	<tr><td>Middle Name:</td><td><?php echo $middle_name;?></td></tr>
	<tr><td>Last Name:</td><td><?php echo $last_name;?></td></tr>
	<tr><td>Gender:</td><td><?php echo $gender;?></td></tr>
	<tr><td>Birthday:</td><td><?php echo $birthday;?></td></tr>
	<tr><td>Phone Number:</td><td><?php echo $contact_number;?></td></tr>
	<tr><td>4Ps Member:</td><td><?php echo $variable1;?></td></tr>
	<tr><td>HHID: </td><td><?php echo $participant_id;?></td></tr>
	<tr><td>Poverty Score:</td><td> <?php echo $score;?></td></tr>
	<tr><td>Last Updated By:</td><td><?php echo $updated_by;?></td></tr>
	<tr><td>Last Updated Date:</td><td><?php echo $updated_date;?></td></tr>
	</table>
	<br/>
	<table>
	<th colspan= "2" style="text-align: left;">Poverty Score Card</th>
	<tr><td>Total Household Members:</td><td><?php echo $hh_member;?></td></tr>
	<tr><td>Chronically Sick Household Members:</td><td><?php echo $hh_sick;?></td></tr>
	<tr><td>Household Income Last Week:</td><td><?php echo $hh_income;?></td></tr>
	<tr><td>PS1 - What is the size of the home/building:</td><td>
	  <?php
		if($ps1 == "a")
		   echo "Small (&lt;15 sqm)";
		else if($ps1 == "b")
		   echo "Medium (&gt;15 sqm &amp; &lt;25 sqm)";
		else if($ps1 == "c")
		   echo "Large (&gt;25 sqm)";
	  ?>
	  </td></tr>
	  <tr><td>
	  PS2 - Floor Materials:</td><td>
	  <?php
		if($ps2 == "a")
		   echo "Weak (Dirt)";
		else if($ps2 == "b")
		   echo "Moderate (Bamboo/Plyboard)";
		else if($ps2 == "c")
		   echo "Strong (Concrete)";
	  ?>
	  </td></tr>
	  <tr><td>
	  PS3 - Roof Materials:</td><td>
	  <?php
		if($ps3 == "a")
		  echo "Scrap";
		else if($ps3 == "b")
		  echo "Old GI Sheet";
		else if($ps3 == "c")
		  echo "New GI Sheet";
	  ?>
	  </td></tr>
	  <tr><td>PS4 - Wall Materials:</td><td>
	  <?php
		if($ps4 == "a")
		  echo "Scrap";
		else if($ps4 == "b")
		  echo "Bamboo";
		else if($ps4 == "c")
		  echo "Plywood";
		else if($ps4 == "d")
		  echo "Lawanit";
		else if($ps4 == "e")
		  echo "Wood";
		else if($ps4 == "f")
		  echo "Concrete";
	  ?>
	  </td></tr>
	  <tr><td>PS5 - Land Status:</td><td>
	  <?php
		if($ps5 == "a")
		  echo "Squatting";
		else if($ps5 == "b")
		  echo "Tenant";
		else if($ps5 == "c")
		  echo "Renting";
		else if($ps5 == "d")
		  echo "Amortization";
		else if($ps5 == "e")
		  echo "Inherited";
		else if($ps5 == "f")
		  echo "Owned";
	  ?>
	  </td></tr>
	  <tr><td>PS6 - Water Supply:</td><td>
	  <?php
		if($ps6 == "a")
		  echo "None within 50 meters";
		else if($ps6 == "b")
		  echo "Shared deep well or faucet within 50 meters";
		else if($ps6 == "c")
		  echo "Faucet (gripo) at home or own deep well";
	  ?>
	  </td></tr>
	  <tr><td>PS7 - Furniture:</td><td>
	 <?php
		 if($ps7 == "")
			echo "None";
		 else
		 {
			$counter = 0;
			$i = 0;
			$repeat = strlen($ps7);

			while($counter != $repeat)
			{
				$value = $ps7[$i];

				if($value == "a")
				  echo "Sala Set";
				else if($value == "b")
				  echo "Bed";
				else if($value == "c")
				  echo "Dining Set";
				else if($value == "d")
				  echo "TV Cabinet";
				else if($value == "e")
				  echo "Closet";
				else if($value == "f")
				  echo "Kitchen Cabinet";

				if($counter+1 == $repeat)
				  echo "";
				else
				  echo ", ";

				$counter++;
				$i++;
			}
		 }
	 ?>
	 </td></tr>
	 <tr><td>PS8 - Appliances:</td><td>

	 <?php
	  if($ps8 == "")
			echo "None";
	  else
	  {
		 $counter = 0;
		 $i = 0;
		 $repeat = strlen($ps8);

		 while($counter != $repeat)
		 {
			$value = $ps8[$i];

			if($value == "a")
			  echo "Digital Clock";
			else if($value == "b")
			  echo "Television";
			else if($value == "c")
			  echo "Radio";
			else if($value == "d")
			  echo "Electric Fan";
			else if($value == "e")
			  echo "Washing Machine";
			else if($value == "f")
			  echo "Microwave";
			else if($value == "g")
			  echo "Computer";
			else if($value == "h")
			  echo "Laptop";
			else if($value == "i")
			  echo "Telephone";
			else if($value == "j")
			  echo "Electric Kettle";
			else if($value == "k")
			  echo "Gas Stove";
			else if($value == "l")
			  echo "Refrigerator";

			if($counter+1 == $repeat)
			  echo "";
			else
			  echo ", ";

			$counter++;
			$i++;
		 }
	  }
	 ?>
	 </td></tr>
	 <tr><td>PS9 - How many telephones/mobile phones does the family own?</td><td>
	  <?php
		if($ps9 == "a")
		  echo "None";
		else if($ps9 == "b")
		  echo "One";
		else if($ps9 == "c")
		  echo "Two";
		else if($ps9 == "d")
		  echo "Three or more";
	  ?>
	  </td></tr>
	  <tr><td>PS10 - Vehicle:</td><td>
	  <?php
		if($ps10 == "a")
		  echo "None";
		else if($ps10 == "b")
		  echo "Bicycle";
		else if($ps10 == "c")
		  echo "Trisikad";
		else if($ps10 == "d")
		  echo "Tricycle";
		else if($ps10 == "e")
		  echo "Motorcycle";
		else if($ps10 == "f")
		  echo "Jeepney/Car";
	  ?>
	  </td></tr>
	  <tr><td>PS11 - Electricity:</td><td>
	  <?php
		if($ps11 == "a")
		  echo "None";
		else if($ps11 == "b")
		  echo "Shared Meter";
		else if($ps11 == "c")
		  echo "Own Meter";
	  ?>
	  </td></tr>
	  <tr><td>PS12 - Fuel for Cooking:</td><td>
	  <?php
		if($ps12 == "a")
		  echo "Charcoal/Wood";
		else if($ps12 == "b")
		  echo "Kerosene";
		else if($ps12 == "c")
		  echo "LPG/Electricity";
	  ?>
	  </td></tr>
	  <tr><td>PS13 - Toilet:</td><td>
	  <?php
		if($ps13 == "a")
		  echo "None";
		else if($ps13 == "b")
		  echo "Shared";
		else if($ps13 == "c")
		  echo "Communal";
		else if($ps13 == "d")
		  echo "Pit";
		else if($ps13 == "e")
		  echo "Manual";
		else if($ps13 == "f")
		  echo "Flush";
	  ?>
	  </td></tr>
	</table>
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>
