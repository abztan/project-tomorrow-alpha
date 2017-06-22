<?php
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="viewApplication.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$application_pk=$_GET['a'];
	$notice="";
	$count="1";
	$hidden="";
	$last_name="";
	$first_name="";
	$middle_name="";
	$contact_number="";
	$birthday="";
	$gender="";
	$variable1="";
	$hh_member="";
	$hh_sick="";
	$hh_income="";
	$ps1="";
	$ps2="";
	$ps3="";
	$ps4="";
	$ps5="";
	$ps6="";
	$ps7="";
	$ps8="";
	$ps9="";
	$ps10="";
	$ps11="";
	$ps12="";
	$ps13="";
	$flag_count=0;

	$sc_view="";
	$wsc="Yes";

	$row = getApplicationDetails($application_pk);
	$base_id = $row['base_id'];
	$base = getBaseName($base_id);

	//if admin - extra view
	if($accesslevel == "5")
	{
		$hidden = "hidden";
	}
	else
	{
		$hidden = "";
	}

	if(isset($_POST['wsc']))
	{
		$wsc = $_POST['wsc'];
		if($wsc == "No")
			$sc_view = "hidden";
		else
			$sc_view = "";
	}

	if(isset($_POST['add']))
	{

		//participant info
		if(isset($_POST['l_name']))
		  $last_name=ucwords(strtolower($_POST['l_name']));
		if(isset($_POST['f_name']))
		  $first_name=ucwords(strtolower($_POST['f_name']));
		if(isset($_POST['m_name']))
		  $middle_name=ucwords(strtolower($_POST['m_name']));
		if(isset($_POST['contact_number']))
		  $contact_number=$_POST['contact_number'];
		if(isset($_POST['four_ps']))
		  $variable1=$_POST['four_ps'];
		if(isset($_POST['gender']))
			$gender=$_POST['gender'];
		if(isset($_POST['bday']))
		  $birthday=$_POST['bday'];


		//participant info
		if($wsc == "Yes") {

					//poverty score card info
					if(isset($_POST['hh_member']))
						$hh_member=$_POST['hh_member'];
					if(isset($_POST['hh_sick']))
						$hh_sick=$_POST['hh_sick'];
					if(isset($_POST['hh_income']))
						$hh_income=$_POST['hh_income'];
					if(isset($_POST['ps1']))
					{
						$ps1=$_POST['ps1'];
						$flag_count ++;
					}

					if(isset($_POST['ps2']))
					{
						$ps2=$_POST['ps2'];
						$flag_count ++;
					}

					if(isset($_POST['ps3']))
					{
						$ps3=$_POST['ps3'];
						$flag_count ++;
					}

					if(isset($_POST['ps4']))
					{
						$ps4=$_POST['ps4'];
						$flag_count ++;
					}

					if(isset($_POST['ps5']))
					{
						$ps5=$_POST['ps5'];
						$flag_count ++;
					}

					if(isset($_POST['ps6']))
					{
						$ps6=$_POST['ps6'];
						$flag_count ++;
					}

					if(isset($_POST['ps7']))
					{
						$check = $_POST['ps7'];
						foreach($check as $ch)
						$ps7 = $ps7.$ch;
					 }

					if(isset($_POST['ps8']))
					{
						$check1 = $_POST['ps8'];
						foreach($check1 as $ch1)
						$ps8 = $ps8.$ch1;
					 }

					if(isset($_POST['ps9']))
					{
						$ps9=$_POST['ps9'];
						$flag_count ++;
					}

					if(isset($_POST['ps10']))
					{
						$ps10=$_POST['ps10'];
						$flag_count ++;
					}

					if(isset($_POST['ps11']))
					{
						$ps11=$_POST['ps11'];
						$flag_count ++;
					}

					if(isset($_POST['ps12']))
					{
						$ps12=$_POST['ps12'];
						$flag_count ++;
					}

					if(isset($_POST['ps13']))
					{
						$ps13=$_POST['ps13'];
						$flag_count ++;
					}

					if(!empty($first_name) && !empty($middle_name) && !empty($last_name) && !empty($hh_member) && $hh_sick!="" && $hh_income!="")
					{
						//check duplicate
						if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
						  $notice = "Sorry but this entry already exists.";

						else
						{
								//check not more than 6 missing poverty score data
								if($flag_count > 6)
								{
									addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$variable1,'1',$gender,$birthday);
									$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);
									addParticipant_PS($application_pk,$participant_pk,$hh_member,$hh_sick,$hh_income,$ps1,$ps2,$ps3,$ps4,$ps5,$ps6,$ps7,$ps8,$ps9,$ps10,$ps11,$ps12,$ps13,$username);

									$last_name="";
									$first_name="";
									$middle_name="";
									$contact_number="";
									$variable1="";
									$hh_member="";
									$hh_sick="";
									$hh_income="";
									$gender="";
									$birthday="";
									$ps1="";
									$ps2="";
									$ps3="";
									$ps4="";
									$ps5="";
									$ps6="";
									$ps7="";
									$ps8="";
									$ps9="";
									$ps10="";
									$ps11="";
									$ps12="";
									$ps13="";

									//auto pass or fail
									if($flag_count == "11")
									{
										$score = getParticipantScore($participant_pk);
										if($score >= 50)
										  $tag = 2;
										else if($score < 51 && $score > 0)
										  $tag = 3;
									}

									else
									  $tag = 4;

									updateParticipantTag($participant_pk,$tag,$username);
								}

								else
								  $notice = "Sorry but there is too much missing data.";
						}
					}

					else
						$notice = "Sorry but a required field is missing.";
		}

		else if ($wsc = "No") {
					if(!empty($first_name) && !empty($middle_name) && !empty($last_name))
					{
						//check duplicate
						if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
							$notice = "Sorry but this entry already exists.";

						else
						{
							addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$variable1,'1',$gender,$birthday);
							$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);
							addParticipant_PS($application_pk,$participant_pk,0,0,0,"","","","","","","","","","","","","",$username);
							$tag = 4;
							updateParticipantTag($participant_pk,$tag,$username);

							$last_name="";
							$first_name="";
							$middle_name="";
							$contact_number="";
							$variable1="";
						}
					}

					else
						$notice = "Sorry but a required field is missing.";
		}
	}

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/listSelection.php');
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

<nav>
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>
<table>
	<th colspan = "2" style="text-align: left;">Application Information</th>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	<tr><td>Pastor</td><td><?php

		if($row['pastor_id'] != 0)
		{
			echo "<a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a>";
		}

		else
		{
			echo $row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial'];
		}
		?></td></tr>
	<tr><td>Application ID</td><td><a href='editApplication.php?a=<?php echo $application_pk;?>'><?php echo $row['application_id'];?></a></td></tr>
	<tr><td>Application Date</td><td><?php echo $row['application_date'];?></td></tr>
	<tr><td>Total Applicants</td><td><?php echo countParticipantTotal($application_pk);?></td></tr>
	<tr><td>Total Qualified</td><td><?php echo countParticipantTag($application_pk,2)+countParticipantTag($application_pk,5);?></td></tr>
	<tr><td>Total Disqualified</td><td><?php echo countParticipantTag($application_pk,3);?></td></tr>
	<tr><td>Total Incomplete</td><td><?php echo countParticipantTag($application_pk,4);?></td></tr>
</table>

<br/>

<select id="wsc" name="wsc" onchange="form.submit()">
<?php
$ws="";
$wos="";

if($wsc == "Yes")
	 $ws = "selected";
else if($wsc == "No")
	 $wos = "selected";
?>
<option <?php echo $ws;?> value="Yes">With Poverty Score Card</option>
<option <?php echo $wos;?> value="No">Without Poverty Score Card</option>
</select>

<fieldset>
<legend>Add Participant</legend>
	<label>First Name*</label><br/>
	<input placeholder="First Name/s" class="form-control input-sm" type="text" name="f_name" value = "<?php echo $first_name;?>">
	<br/>
	<label>Middle Name*</label><br/>
	<input placeholder="Middle Name" class="form-control input-sm" type="text" name="m_name" value = "<?php echo $middle_name;?>">
	<br/>
	<label>Last Name*</label><br/>
	<input placeholder="Last Name" class="form-control input-sm" type="text" name="l_name" value = "<?php echo $last_name;?>">
		<br/>
		<label>Gender</label><br/>
		  <select id="gender" name="gender">
		  <?php
			$a="";
			$b="";

			if($gender == "Male")
			   $a = "selected";
			else if($variable1 == "Female")
			   $b = "selected";
			else
			   echo "<option disabled selected>Please Choose</option>";
		  ?>
		  <option <?php echo $a;?> value="Male">Male</option>
		  <option <?php echo $b;?> value="Female">Female</option>
		  </select>
		<br/>
		<label>Birthday</label><br/>
		<input class="form-control input-sm" type="date" name="bday" id="bday">

	<br/>
	<label>Phone Number</label><br/>
	<input placeholder="" class="form-control input-sm" type="text" min="0" max=""  maxlength="11" name="contact_number" value = "<?php echo $contact_number;?>">

	<br/>
	<div <?php echo $sc_view;?>>
	<label>Total Household Members*</label><br/>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_member" value = "<?php echo $hh_member;?>">

	<br/>
	<label>Chronically Sick Household Members*</label><br/>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_sick" value = "<?php echo $hh_sick;?>">

	<br/>
	<label>Household Income Last Week*</label><br/>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "" name="hh_income" value = "<?php echo $hh_income;?>">

	<br/>
	</div>
	<label>4Ps Member</label><br/>
	  <select id="four_ps" name="four_ps">
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
	 <div <?php echo $sc_view;?>>
	 <label>PS1 - What is the size of the home/building?</label><br/>
	  <select id="ps1" name="ps1">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps1 == "a")
		   $a = "selected";
		else if($ps1 == "b")
		   $b = "selected";
		else if($ps1 == "c")
		   $b = "selected";
		else
		   echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Small (&lt;15 sqm)</option>
	  <option <?php echo $b;?> value="b">Medium (&gt;15 sqm &amp; &lt;25 sqm)</option>
	  <option <?php echo $c;?> value="c">Large (&gt;25 sqm)</option>
	  </select>

	 <br/>
	 <label>PS2 - Floor Materials</label><br/>
	  <select id="ps2" name="ps2">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps2 == "a")
		   $a = "selected";
		else if($ps2 == "b")
		   $b = "selected";
		else if($ps2 == "c")
		   $b = "selected";
		else
		   echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Weak (Dirt)</option>
	  <option <?php echo $b;?> value="b">Moderate (Bamboo/Plyboard)</option>
	  <option <?php echo $c;?> value="c">Strong (Concrete)</option>
	  </select>

	 <br/>
	 <label>PS3 - Roof Materials</label><br/>
	  <select id="ps3" name="ps3">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps3 == "a")
		  $a = "selected";
		else if($ps3 == "b")
		  $b = "selected";
		else if($ps3 == "c")
		  $b = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Scrap</option>
	  <option <?php echo $b;?> value="b">Old GI Sheet</option>
	  <option <?php echo $c;?> value="c">New GI Sheet</option>
	  </select>

	 <br/>
	 <label>PS4 - Wall Materials</label><br/>
	  <select id="ps4" name="ps4">
	  <?php
		$a="";
		$b="";
		$c="";
		$d="";
		$e="";
		$f="";

		if($ps4 == "a")
		  $a = "selected";
		else if($ps4 == "b")
		  $b = "selected";
		else if($ps4 == "c")
		  $c = "selected";
		else if($ps4 == "d")
		  $d = "selected";
		else if($ps4 == "e")
		  $e = "selected";
		else if($ps4 == "f")
		  $f = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Scrap</option>
	  <option <?php echo $b;?> value="b">Bamboo</option>
	  <option <?php echo $c;?> value="c">Plywood</option>
	  <option <?php echo $d;?> value="d">Lawanit</option>
	  <option <?php echo $e;?> value="e">Wood</option>
	  <option <?php echo $f;?> value="f">Concrete</option>
	  </select>

	 <br/>
	 <label>PS5 - Land Status</label><br/>
	  <select id="ps5" name="ps5">
	  <?php
		$a="";
		$b="";
		$c="";
		$d="";
		$e="";
		$f="";

		if($ps5 == "a")
		  $a = "selected";
		else if($ps5 == "b")
		  $b = "selected";
		else if($ps5 == "c")
		  $c = "selected";
		else if($ps5 == "d")
		  $d = "selected";
		else if($ps5 == "e")
		  $e = "selected";
		else if($ps5 == "f")
		  $f = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Squatting</option>
	  <option <?php echo $b;?> value="b">Tenant</option>
	  <option <?php echo $c;?> value="c">Renting</option>
	  <option <?php echo $d;?> value="d">Amortization</option>
	  <option <?php echo $e;?> value="e">Inherited</option>
	  <option <?php echo $f;?> value="f">Owned</option>
	  </select>

	 <br/>
	 <label>PS6 - Water Supply</label><br/>
	  <select id="ps6" name="ps6">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps6 == "a")
		  $a = "selected";
		else if($ps6 == "b")
		  $b = "selected";
		else if($ps6 == "c")
		  $c = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">None within 50 meters</option>
	  <option <?php echo $b;?> value="b">Shared deep well or faucet within 50 meters</option>
	  <option <?php echo $c;?> value="c">Faucet (gripo) at home or own deep well</option>
	  </select>

	 <br/>
	 <br/>
	 <label>PS7 - Furniture (select all that apply)</label><br/>
	  <input type="checkbox" <?php echo tick_checkbox($ps7, 'a');?> name="ps7[]" value="a" />Sala Set
      <input type="checkbox" <?php echo tick_checkbox($ps7, 'b');?> name="ps7[]" value="b" />Bed
      <input type="checkbox" <?php echo tick_checkbox($ps7, 'c');?> name="ps7[]" value="c" />Dining Set
      <input type="checkbox" <?php echo tick_checkbox($ps7, 'd');?> name="ps7[]" value="d" />TV Cabinet
      <input type="checkbox" <?php echo tick_checkbox($ps7, 'e');?> name="ps7[]" value="e" />Closet
      <input type="checkbox" <?php echo tick_checkbox($ps7, 'f');?> name="ps7[]" value="f" />Kitchen Cabinet
	  <br/>

	 <br/>
	 <label>PS8 - Appliances (select all that apply)</label><br/>
	  <input type="checkbox" <?php echo tick_checkbox($ps8, 'a');?> name="ps8[]" value="a" />Digital Clock
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'b');?> name="ps8[]" value="b" />Television
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'c');?> name="ps8[]" value="c" />Radio
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'd');?> name="ps8[]" value="d" />Electric Fan
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'e');?> name="ps8[]" value="e" />Washing Machine
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'f');?> name="ps8[]" value="f" />Microwave<br/>
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'g');?> name="ps8[]" value="g" />Computer
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'h');?> name="ps8[]" value="h" />Laptop
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'i');?> name="ps8[]" value="i" />Telephone
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'j');?> name="ps8[]" value="j" />Electric Kettle
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'k');?> name="ps8[]" value="k" />Gas Stove
      <input type="checkbox" <?php echo tick_checkbox($ps8, 'l');?> name="ps8[]" value="l" />Refrigerator
	  <br/>

	 <br/>
	 <label>PS9 - How many telephones/mobile phones does the family own?</label><br/>
	  <select id="ps9" name="ps9">
	  <?php
		$a="";
		$b="";
		$c="";
		$d="";

		if($ps9 == "a")
		  $a = "selected";
		else if($ps9 == "b")
		  $b = "selected";
		else if($ps9 == "c")
		  $c = "selected";
		else if($ps9 == "d")
		  $d = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">None</option>
	  <option <?php echo $b;?> value="b">One</option>
	  <option <?php echo $c;?> value="c">Two</option>
	  <option <?php echo $d;?> value="d">Three or more</option>
	  </select>

	  <br/>
	 <label>PS10 - Vehicle</label><br/>
	  <select id="ps10" name="ps10">
	  <?php
		$a="";
		$b="";
		$c="";
		$d="";
		$e="";
		$f="";

		if($ps10 == "a")
		  $a = "selected";
		else if($ps10 == "b")
		  $b = "selected";
		else if($ps10 == "c")
		  $c = "selected";
		else if($ps10 == "d")
		  $d = "selected";
		else if($ps10 == "e")
		  $e = "selected";
		else if($ps10 == "f")
		  $f = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">None</option>
	  <option <?php echo $b;?> value="b">Bicycle</option>
	  <option <?php echo $c;?> value="c">Trisikad</option>
	  <option <?php echo $d;?> value="d">Tricycle</option>
	  <option <?php echo $e;?> value="e">Motorcycle</option>
	  <option <?php echo $f;?> value="f">Jeepney/Car</option>
	  </select>

	 <br/>
	 <label>PS11 - Electricity</label><br/>
	  <select id="ps11" name="ps11">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps11 == "a")
		  $a = "selected";
		else if($ps11 == "b")
		  $b = "selected";
		else if($ps11 == "c")
		  $c = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">None</option>
	  <option <?php echo $b;?> value="b">Shared Meter</option>
	  <option <?php echo $c;?> value="c">Own Meter</option>
	  </select>

	 <br/>
	 <label>PS12 - Fuel for Cooking</label><br/>
	  <select id="ps12" name="ps12">
	  <?php
		$a="";
		$b="";
		$c="";

		if($ps12 == "a")
		  $a = "selected";
		else if($ps12 == "b")
		  $b = "selected";
		else if($ps12 == "c")
		  $c = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">Charcoal/Wood</option>
	  <option <?php echo $b;?> value="b">Kerosene</option>
	  <option <?php echo $c;?> value="c">LPG/Electricity</option>
	  </select>

	 <br/>
	 <label>PS13 - Toilet</label><br/>
	  <select id="ps13" name="ps13">
	  <?php
		$a="";
		$b="";
		$c="";
		$d="";
		$e="";
		$f="";

		if($ps13 == "a")
		  $a = "selected";
		else if($ps13 == "b")
		  $b = "selected";
		else if($ps13 == "c")
		  $c = "selected";
		else if($ps13 == "d")
		  $d = "selected";
		else if($ps13 == "e")
		  $e = "selected";
		else if($ps13 == "f")
		  $f = "selected";
		else
		  echo "<option disabled selected>Please Choose</option>";
	  ?>
	  <option <?php echo $a;?> value="a">None</option>
	  <option <?php echo $b;?> value="b">Shared</option>
	  <option <?php echo $c;?> value="c">Communal</option>
	  <option <?php echo $d;?> value="d">Pit</option>
	  <option <?php echo $e;?> value="e">Manual</option>
	  <option <?php echo $f;?> value="f">Flush</option>
		</select>
	</div>

	 <br/><br/>
	<button type = "submit" class="btn btn-embossed btn-primary" name = "add">Add</button>
	<button class="btn btn-embossed btn-primary" name='back'>Back</button>
</fieldset>
</section>

<section id="col2">
	<table border="0">
	<tr>
	<th>Participant Name</th>
	<th>Poverty Score</th>
	<th>Status</th>
	<th>Action</th>
	</tr>

	<?php
			$query = getApplicationParticipants($application_pk);
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			if($participant_tag == "2" || $participant_tag == "3" || $participant_tag == "5")
			{
			  $score = getParticipantScore($participant_pk);
			  if($row['variable2'] == "")
			  {
				updateParticipant_PSV($participant_pk,$score);
			  }
			}
			else
			  $score = "-";

			if($participant_tag == "1")
			{
				$status = "New";
			}
			else if($participant_tag == "2" || $participant_tag == "5")
			{
				$status = "Qualified";
			}
			else if($participant_tag == "3")
			{
				$status = "Disqualified";
			}
			else if($participant_tag == "4")
			{
				$status = "Incomplete";
			}
			else
			{
				$status = "Unknown";
			}

			echo "<tr>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>
					<td align = 'center'>$status</td>
					<td align = 'center'>
					<a onClick=\"javascript: return confirm('Are you sure you want to delete this application?');\" href='_applicationaction.php?a=11&b=".$participant_pk."&c=".$application_pk."'>Delete</a>&nbsp;";

				if($participant_tag == "2" || $participant_tag == "3" || $participant_tag == "5")
				  echo "<a href='viewParticipantPsc.php?a=$application_pk&b=$participant_pk'>View</a>";
				else
				  echo "<a href='editParticipantPsc.php?a=$application_pk&b=$participant_pk'>Update</a>";

			echo "</td></tr>";

			//override - overrideStatus($application_pk,$participant_pk,$participant_tag)

			/*if($participant_tag == "2")
				echo "<td><button name='approve' value='".$row['id']."'>&#10004;</button><button name='disapprove' value='".$row['id']."'>&#x2717;</button></td>";
			else
				echo "<td><button name='participant_update' value='".$row['id']."'>Update</button></td>";

			echo "</tr>";*/

			$count++;
		}

		//if(countParticipantTag($application_pk,2)>"30")
		 //echo '<tr><td colspan = "4" align = "right"><br/><button type = "submit" class="btn btn-embossed btn-primary" name = "approve">Approve</button></td></tr>';
		?>

	</table>
	<br/>
</section>

</article>
</form>
<script>
function overrideStatus(entry_id,participant_pk,tag) {
    var reason = prompt("Please state the reason for overwriting participant status:");

	if (reason == null)
	{
		document.getElementById("notice").innerHTML = "";
	}

    else if (reason != "")
	{
		 window.location.href = '_applicationaction.php?a=5&b='+entry_id+'&c='+participant_pk+'&d='+reason+'&e='+tag;
    }

	else
	{
		document.getElementById("notice").innerHTML = "A reason must be provided to override the participant status.";
	}

}
</script>
<script src='default.js'></script>
</body>

</html>
