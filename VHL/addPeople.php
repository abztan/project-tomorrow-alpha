<?php
	session_start();
	if(empty($_SESSION['username']))
	  header('location: /ICM/Login.php?a=2');
	else {
	  $username = $_SESSION['username'];
	  $access_level = $_SESSION['accesslevel'];
	  $account_base = $_SESSION['baseid'];
	}

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	$application_pk = $_GET['a'];
	$application = getApplication_Data_byID($application_pk);
	$application_type = $application['application_type'];
	$selected_participant = "";
	$selected_candidate = "";
	$last_name="";
	$first_name="";
	$middle_name="";
	$contact_number="";
	$variable1="";
	$hh_member="";
	$hh_sick="";
	$hh_income="";
	$flag_count = 0;
	$i = 0;
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
	//hide basic
	$isHidden = "hidden";
	//score card
	$isHidden1 = "hidden";
	//replacements
	$isHidden2 = "hidden";
	$isHidden3 = "hidden";
	$a = "";
	$b = "";
	$c = "";
	$d = "";
	$notice = "";

	if(isset($_POST['people']))	{
		if($_POST['people'] == 5)
		{
			$a = "selected";
			$isHidden = "";
			$isHidden1 = "";
			unset($_POST['candidate']);
		}
		else if($_POST['people'] == 3)
		{
			$b = "selected";
			$isHidden = "";
			$isHidden1 = "";
			$isHidden2 = "";
			$isHidden3 = "";
			$query = getParticipant_forApplication_byTag($application_pk,5,"participant_id","=");
			$query1 = getParticipant_forApplication_byTag($application_pk,5,"last_name","<>");
		}
		else if($_POST['people'] == 20)
		{
			$c = "selected";
			$isHidden = "";
			$isHidden1 = "hidden";
			unset($_POST['candidate']);
		}
		else if($_POST['people'] == 6)
		{
			$d = "selected";
			$isHidden = "";
			$isHidden1 = "hidden";
			unset($_POST['candidate']);
		}
		else if($_POST['people'] == 1)
		{
			unset($_POST['participant']);
			$d = "selected";
			$isHidden = "";
			$isHidden1 = "";
			$isHidden3 = "";
			$query1 = getParticipant_forApplication_byTag($application_pk,5,"last_name","<>");
		}
	}

	if(isset($_POST['candidate']) ) {
		if($_POST['people'] == 1)
			"";
		else if(isset($_POST['participant']) && $_POST['people'] != 1 )
			$selected_participant = $_POST['participant'];
		else if(countParticipant_category_tag($application_pk,'all_pType','5') < getProgram_maxParticipants($application_type))
			"";
		else
			$notice = "Please select a participant to replace";

		$selected_candidate = $_POST['candidate'];

		if(!empty($selected_candidate)) {
			$i = 1;
			$row=getParticipantDetails($selected_candidate);
			$row1=getParticipantPscDetails($selected_candidate);
			$last_name=$row['last_name'];
			$first_name=$row['first_name'];
			$middle_name=$row['middle_name'];
			$contact_number=$row['contact_number'];
			$variable1=$row['variable1'];
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
		}
	}

	if(isset($_POST['confirm'])) {
		//
		$community = getApplicationDetails($application_pk);
		$comm_id = $community['community_id'];

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

		//poverty score card info
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

		if($_POST['people'] == 5) {
			if(!empty($first_name) && !empty($middle_name) && !empty($last_name) && $hh_member != "" && $hh_sick != "" && $hh_income != "" && $flag_count == 11) {
				//check duplicate
				if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
				  $notice = "Sorry but this entry already exists.";

				else
				{
					addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$variable1,$_POST['people']);
					$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);
					addParticipant_PS($application_pk,$participant_pk,$hh_member,$hh_sick,$hh_income,$ps1,$ps2,$ps3,$ps4,$ps5,$ps6,$ps7,$ps8,$ps9,$ps10,$ps11,$ps12,$ps13,$username);

					updateParticipantTag($participant_pk,5,$username);
					$participant_id = generateParticipantID($application_pk,$participant_pk,$comm_id);
					if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")	{
						updateApplication_ParticipantID($participant_pk,$participant_id,$username);
						$notice =  "You have successfully added this participant, redirecting page in... <span id='counter'>3</span>";
						$address = "people.php?a=$application_pk";
					}
					else
						$notice = "Something went wrong, please contact system administrator and reference participant ID: $participant_id";
				}
			}
			else
			  $notice = "Sorry, can't continue due to missing information. Make sure all required fields are filled up.";
		}

		else if($_POST['people'] == 3) {
			if(isset($_POST['participant']))
				$selected_participant = $_POST['participant'];

			if(!empty($selected_participant) || (empty($selected_participant && countParticipant_category_tag($application_pk,'all_pType','5') < getProgram_maxParticipants($application_type)))) {
				if($selected_candidate != "") {
					//drop selected participant
					updateParticipantTag($selected_participant,9,$username);
					updateParticipant_replacement($selected_participant,$selected_candidate,$username);
					//set replacement participant
					updateParticipantTag($selected_candidate,5,$username);
					updateParticipant_category($selected_candidate,$_POST['people'],$username);

					$candidate = getParticipantDetails($selected_candidate);
					if($candidate['participant_id']=="") {
						$participant_id = generateParticipantID($application_pk,$selected_candidate,$comm_id);
						if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")
							updateApplication_ParticipantID($selected_candidate,$participant_id,$username);
					}

					$notice =  "You have successfully added this participant, redirecting page in... <span id='counter'>3</span>";
					$address = "people.php?a=$application_pk";
				}

				else if(!empty($first_name) && !empty($middle_name) && !empty($last_name) && $hh_member != "" && $hh_sick != "" && $hh_income != "" && $flag_count == 11)	{
					//check duplicate
					if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
					  $notice = "Sorry but this entry already exists.";

					else	{
						addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,$variable1,$_POST['people']);
						$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);
						addParticipant_PS($application_pk,$participant_pk,$hh_member,$hh_sick,$hh_income,$ps1,$ps2,$ps3,$ps4,$ps5,$ps6,$ps7,$ps8,$ps9,$ps10,$ps11,$ps12,$ps13,$username);

						updateParticipantTag($participant_pk,5,$username);
						$participant_id = generateParticipantID($application_pk,$participant_pk,$comm_id);
						if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")	{
							updateApplication_ParticipantID($participant_pk,$participant_id,$username);

						//drop selected_participant
						updateParticipantTag($selected_participant,9,$username);
						updateParticipant_replacement($selected_participant,$participant_pk,$username);

							$notice =  "You have successfully added this participant, redirecting page in... <span id='counter'>3</span>";
							$address = "people.php?a=$application_pk";
						}
						else
							$notice = "Something went wrong, please contact system administrator and reference participant ID: $participant_id";
					}
				}
				else
					$notice = "Sorry, can't continue due to missing information. Make sure all required fields are filled up.";
			}
			else
				$notice = "You are required to select the participant to replace";

		}

		else if($_POST['people'] == 20) {
			if(!empty($first_name) && !empty($middle_name) && !empty($last_name)) {
				//check duplicate
				if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
				  $notice = "Sorry but this entry already exists.";

				else
				{
					addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,NULL,$_POST['people']);
					$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);

					updateParticipantTag($participant_pk,5,$username);
					$participant_id = generateParticipantID($application_pk,$participant_pk,$comm_id);
					if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")	{
						updateApplication_ParticipantID($participant_pk,$participant_id,$username);
						$notice =  "You have successfully added this participant, redirecting page in... <span id='counter'>3</span>";
						$address = "people.php?a=$application_pk";
					}
					else
						$notice = "Something went wrong, please contact system administrator and reference participant ID: $participant_id";
				}
			}
			else
			  $notice = "Sorry, can't continue due to missing information. Make sure all required fields are filled up.";

		}

		else if($_POST['people'] == 6) {
			if(!empty($first_name) && !empty($middle_name) && !empty($last_name)) {
				//check duplicate
				if(checkParticipantEntry($last_name,$first_name,$middle_name)!="")
				  $notice = "Sorry but this entry already exists.";

				else
				{
					addParticipant($last_name,$first_name,$middle_name,$application_pk,$username,$contact_number,NULL,$_POST['people']);
					$participant_pk = getParticipant_PK($last_name,$first_name,$middle_name,$application_pk);

					updateParticipantTag($participant_pk,5,$username);
					$participant_id = generateParticipantID($application_pk,$participant_pk,$comm_id);
					if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")	{
						updateApplication_ParticipantID($participant_pk,$participant_id,$username);
						$notice =  "You have successfully added this counselor, redirecting page in... <span id='counter'>3</span>";
						$address = "people.php?a=$application_pk";
					}
					else
						$notice = "Something went wrong, please contact system administrator and reference participant ID: $participant_id";
				}
			}
			else
			  $notice = "Sorry, can't continue due to missing information. Make sure all required fields are filled up.";

		}

		else if($_POST['people'] == 1) {
			if(!empty($first_name) && !empty($middle_name) && !empty($last_name)) {
				if(!empty($selected_candidate)) {

					//set replacement participant
					updateParticipantTag($selected_candidate,5,$username);
					updateParticipant_category($selected_candidate,$_POST['people'],$username);

					$candidate = getParticipantDetails($selected_candidate);
					if($candidate['participant_id']=="") {
						$participant_id = generateParticipantID($application_pk,$selected_candidate,$comm_id);
						if($participant_id != "" && checkDuplicate_participantID($participant_id) == "false")
							updateApplication_ParticipantID($selected_candidate,$participant_id,$username);
					}

					$notice =  "You have successfully added this participant, redirecting page in... <span id='counter'>3</span>";
					$address = "people.php?a=$application_pk";
				}
			}
			else
			  $notice = "Sorry, can't continue due to missing information. Make sure all required fields are filled up.";

		}
	}

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/people.php?a='.$application_pk);
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
<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>
	Category
	<select id="people" name="people" onChange="form.submit()">
		<option disabled selected>Please Choose</option>
		<option <?php echo $a; ?> value="5">Participant - New</option>
		<option <?php echo $b; ?> value="3">Participant - Replacement</option>
		<option <?php echo $d; ?> value="6">Participant - w/o Score Card</option>-->
		<option <?php echo $c; ?> value="20">Counselor</option>
	</select>

	<br/>
	<br/>
	<table border = "1" >
		<tr <?php echo $isHidden2; ?>>
			<td>Replace Participant</td>
			<td>
				<select id="participant" name="participant">
				<option disabled selected>Please Choose</option>
				<?php
					while($participant = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
						$set = "";
						$participant_pk = $participant['id'];
						$participant_id = $participant['participant_id'];
						$participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];

						if($selected_participant != "" && $selected_participant == $participant_pk)
							$set = "selected";

						echo "<option $set value='$participant_pk'>".$participant_id."&nbsp;-&nbsp;".$participant_name."</option>";
					}

				?>
				</select>
			</td>
		</tr>
		<tr <?php echo $isHidden3; ?>>
			<td>Possible Replacement List</td>
			<td>
				<select id="candidate" name="candidate" onChange="form.submit()">
				<option disabled selected>Choose one if applicable</option>
				<option value = "">None</option>
				<?php
					while($candidate = pg_fetch_array($query1,NULL,PGSQL_BOTH)) {
						$set = "";
						$candidate_pk = $candidate['id'];
						$candidate_name = $candidate['last_name'].", ".$candidate['first_name']." ".$candidate['middle_name'];

						if($selected_candidate != "" && $selected_candidate == $candidate_pk)
							$set = "selected";

						echo "<option $set value='$candidate_pk'>$candidate_name</option>";
					}
				?>
				</select>
			</td>
		</tr>
	</table>
	<br/>
	<table border = "1" <?php echo $isHidden; ?> width = "1100px">
		<tr>
			<td width = "5%">1*</td>
			<td width = "">First Name</td>
			<td width = ""><input placeholder="First Name/s" class="form-control input-sm" type="text" name="f_name" value = "<?php echo $first_name;?>" <?php if($first_name != "" && $i == 1) echo "disabled";?>></td>
		</tr>
		<tr>
			<td>2*</td>
			<td>Middle Name/Initial</td>
			<td><input placeholder="Middle Name" class="form-control input-sm" type="text" name="m_name" value = "<?php echo $middle_name;?>"  <?php if($middle_name != "" && $i == 1) echo "disabled";?>></td>
		</tr>
		<tr>
			<td>3*</td>
			<td>Last Name</td>
			<td><input placeholder="Last Name" class="form-control input-sm" type="text" name="l_name" value = "<?php echo $last_name;?>" <?php if($last_name != "" && $i == 1) echo "disabled";?>></td>
		</tr>
		<tr>
			<td>4</td>
			<td>Phone Number</td>
			<td><input placeholder="" class="form-control input-sm" type="text" min="0" max=""  maxlength="11" name="contact_number" value = "<?php echo $contact_number;?>" <?php if((strtolower($contact_number) != "none" || $contact_number == "") && $i == 1) echo "disabled";?>></td>
		</tr>
	</table>

	<table border = "1" <?php echo $isHidden1; ?> width = "1100px">
		<tr>
				<td width = "">5*</td>
				<td width = "">Total Household Members</td>
				<td width = ""><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_member" value = "<?php echo $hh_member;?>" <?php if($hh_member != "" && $i == 1) echo "disabled";?>></td>
			</tr>
		<tr>
				<td>6*</td>
				<td>Chronically Sick Household Members</td>
				<td><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_sick" value = "<?php echo $hh_sick;?>" <?php if($hh_sick != "" && $i == 1) echo "disabled";?>></td>
			</tr>
		<tr>
				<td>7*</td>
				<td>Household Income Last Week</td>
				<td><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "" name="hh_income" value = "<?php echo $hh_income;?>" <?php if($hh_income != "" && $i == 1) echo "disabled";?>></td>
			</tr>
		<tr>
				<td>8*</td>
				<td>4Ps Member</td>
				<td>
					<select id="four_ps" name="four_ps" <?php if($variable1 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS1</td>
				<td>What is the size of the home/building?</td>
				<td>
					<select id="ps1" name="ps1" <?php if($ps1 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS2</td>
				<td>Floor Materials</td>
				<td>
					<select id="ps2" name="ps2" <?php if($ps2 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS3</td>
				<td>Roof Materials</td>
				<td>
					<select id="ps3" name="ps3" <?php if($ps3 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS4</td>
				<td>Wall Materials</td>
				<td>
					<select id="ps4" name="ps4" <?php if($ps4 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS5</td>
				<td>Land Status</td>
				<td>
					<select id="ps5" name="ps5" <?php if($ps5 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS6</td>
				<td>Water Supply</td>
				<td>
					<select id="ps6" name="ps6" <?php if($ps6 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS7</td>
				<td>Furniture (select all that apply)</td>
				<td>
				  <input type="checkbox" <?php echo tick_checkbox($ps7, 'a');?> name="ps7[]" value="a" />Sala Set
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'b');?> name="ps7[]" value="b" />Bed
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'c');?> name="ps7[]" value="c" />Dining Set
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'd');?> name="ps7[]" value="d" />TV Cabinet
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'e');?> name="ps7[]" value="e" />Closet
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'f');?> name="ps7[]" value="f" />Kitchen Cabinet
				</td>
			</tr>
		<tr>
				<td>PS8</td>
				<td>Appliances (select all that apply)</td>
				<td>
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
				</td>
			</tr>
		<tr>
				<td>PS9</td>
				<td>How many telephones/mobile phones does the family own?</td>
				<td>
					<select id="ps9" name="ps9" <?php if($ps9 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS10</td>
				<td>Vehicle</td>
				<td>
					<select id="ps10" name="ps10" <?php if($ps10 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS11</td>
				<td>Electricity</td>
				<td>
					<select id="ps11" name="ps11" <?php if($ps11 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS12</td>
				<td>Fuel for Cooking</td>
				<td>
					<select id="ps12" name="ps12" <?php if($ps12 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
		<tr>
				<td>PS13</td>
				<td>Toilet</td>
				<td>
					<select id="ps13" name="ps13" <?php if($ps13 != "" && $i == 1) echo "disabled";?>>
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
				</td>
			</tr>
	</table>
	<br/><br/>
	<button class="btn btn-embossed btn-primary" name='back'>Back</button>
	<button class="btn btn-embossed btn-primary" name='confirm'>Confirm</button>
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
