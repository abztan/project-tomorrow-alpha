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
	
	$entry_id = $_GET['a'];
	$count = "1";
	$hidden = "";
	$notice = "";

	$row = getApplicationDetails($entry_id);
	$base_id = $row['base_id'];
	$base = getBaseName($base_id);
	
	if($accesslevel == "5")
	{
		$hidden = "hidden";
	}
	else
	{
		$hidden = "";
	}
	
	if(isset($_POST['add']))
	{
		$last_name=$_POST['l_name'];
		$first_name=$_POST['f_name'];
		$middle_initial=$_POST['m_initial'];
		/*$variableA=$_POST['p_score'];
		$variableB=$_POST['t_income'];
		$variableC=$_POST['t_members'];
		$variableD=$_POST['s_members'];*/
		
		if(checkParticipantEntry($last_name,$first_name,$middle_initial)!="")
			echo $notice = "Sorry but this entry already exists.";
			
		else
		{
			//addParticipant($last_name,$first_name,$middle_initial,$variableA,$variableB,$variableC,$variableD,$entry_id,$username);
			addParticipant($last_name,$first_name,$middle_initial,$entry_id,$username);
			
			header('location: /ICM/Transform/viewApplication.php?a='.$entry_id);
		}
	}
	
	if(isset($_POST['approve']))
	{
		if(countTotalParticipantStatus($entry_id,3) == "30")
		 $notice = "The maximum qualified applicants has already been reached.";
		 
		else
		{
			$participant_id = $_POST['approve'];
			updateParticipantTag($participant_id,3,$username);
		}
	}
	
	if(isset($_POST['disapprove']))
	{
		$participant_id = $_POST['disapprove'];
		updateParticipantTag($participant_id,2,$username);
	}
	
	if(isset($_POST['participant_update']))
	{
		$participant_id = $_POST['participant_update'];
		header('location: updateParticipant.php?a='.$participant_id);
	}
	
	if(isset($_POST['completed']))
	{
		header('location: page3.php?a='.$entry_id);
	}
	
	if(isset($_POST['back']))
	{
		header('location: /ICM/Transform/listApplication.php');
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

<nav>
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><?php echo $notice;?></h1>
<table>
	<th colspan = "2" align = "left">Application Information</th>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	<tr><td>Pastor</td><td><?php echo "<a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."."."</a>";?></td></tr>
	<tr><td>Application ID</td><td><?php echo $row['application_id'];?></td></tr>
	<tr><td>Application Date</td><td><?php echo $row['application_date'];?></td></tr>
	<tr><td>Total Applicants</td><td><?php echo countParticipantTotal($entry_id);?></td></tr>
	<tr><td>Total Qualified</td><td><?php echo countTotalParticipantStatus($entry_id,3);?></td></tr>
	<tr><td>Total Disqualified</td><td><?php echo countTotalParticipantStatus($entry_id,2);?></td></tr>
	<tr><td>Total Pending</td><td><?php echo countTotalParticipantStatus($entry_id,1);?></td></tr>
</table>

<br/>

<fieldset style="width:1500px;">
	<legend>Add Participant</legend>

	<label>First Name</label>
	<input placeholder="First Name/s" class="form-control input-sm" type="text" name="f_name">
	<br/>
	<label>Middle Name</label>
	<input placeholder="Middle Name" class="form-control input-sm" type="text" name="m_initial">
	<br/>
	<label>Last Name</label>
	<input placeholder="Last Name" class="form-control input-sm" type="text" name="l_name">
	
	<br/>
	<label>Phone Number</label>
	<input placeholder="" class="form-control input-sm" type="number" min="0" max="" name="p_score">
	
	<br/>
	<label>Total Household Members</label>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="t_income">
	
	<br/>
	<label>Chronically Sick Household Members</label>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="s_members">
	
	<br/>
	<label>Household Income Last Week</label>
	<input placeholder="" class="form-control input-sm" type="number"  min="0" max= "" name="t_members">
	
	<br/>
	<label>4Ps Member</label>
	  <select id="4ps" name="4ps">
	  <option disabled selected>Please Choose</option>
	  <option value="Yes">Yes</option>
	  <option value="No">No</option>
	  </select>
	  
	 <br/>
	 <label>PS1 - What is the size of the home/building?</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Small (&lt;15 sqm)</option>
	  <option value="">Medium (&gt;15 sqm &amp; &lt;25 sqm)</option>
	  <option value="">Large (&gt;25 sqm)</option>
	  </select>
	  
	 <br/>
	 <label>PS2 - Floor Materials</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Weak (Dirt)</option>
	  <option value="">Moderate (Bamboo/Plyboard)</option>
	  <option value="">Strong (Concrete)</option>
	  </select>
	  
	 <br/>
	 <label>PS3 - Roof Materials</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Scrap</option>
	  <option value="">Old GI Sheet</option>
	  <option value="">New GI Sheet</option>
	  </select>
	  
	 <br/>
	 <label>PS4 - Wall Materials</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Scrap</option>
	  <option value="">Bamboo</option>
	  <option value="">Plywood</option>
	  <option value="">Lawanit</option>
	  <option value="">Wood</option>
	  <option value="">Concrete</option>
	  </select>  
	   
	 <br/>
	 <label>PS5 - Land Status</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Squatting</option>
	  <option value="">Tenant</option>
	  <option value="">Renting</option>
	  <option value="">Amortization</option>
	  <option value="">Inherited</option>
	  <option value="">Owned</option>
	  </select>
	  
	 <br/>
	 <label>PS6 - Water Supply</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Faucet (gripo) at home or own deep well</option>
	  <option value="">Shared deep well or faucet within 50 meters</option>
	  <option value="">None within 50 meters</option>
	  </select>
	  
	 <br/>
	 <label>PS7 - Furniture (select all that apply)</label><br/>
	  <input type="checkbox" name="furniture" value="" />Sala Set
      <input type="checkbox" name="furniture" value="" />Bed
      <input type="checkbox" name="furniture" value="" />Dining Set
      <input type="checkbox" name="furniture" value="" />TV Cabinet
      <input type="checkbox" name="furniture" value="" />Closet
      <input type="checkbox" name="furniture" value="" />Kitchen Cabinet
	  </select>
	 
	 <br/>
	 <label>PS8 - Appliances (select all that apply)</label><br/>
	  <input type="checkbox" name="appliances" value="" />Digital Clock
      <input type="checkbox" name="appliances" value="" />Television
      <input type="checkbox" name="appliances" value="" />Radio
      <input type="checkbox" name="appliances" value="" />Electric Fan
      <input type="checkbox" name="appliances" value="" />Washing Machine
      <input type="checkbox" name="appliances" value="" />Microwave<br />
      <input type="checkbox" name="appliances" value="" />Computer
      <input type="checkbox" name="appliances" value="" />Laptop
      <input type="checkbox" name="appliances" value="" />Telephone
      <input type="checkbox" name="appliances" value="" />Electric Kettle
      <input type="checkbox" name="appliances" value="" />Gas Stove
      <input type="checkbox" name="appliances" value="" />Refrigerator
	  </select>
	  
	 <br/>
	 <label>PS9 - How many telephones/mobile phones does the family own?</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">None</option>
	  <option value="">One</option>
	  <option value="">Two</option>
	  <option value="">Three or more</option>
	  </select>
	  
	  <br/>
	 <label>PS10 - Vehicle</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">None</option>
	  <option value="">Bicycle</option>
	  <option value="">Trisikad</option>
	  <option value="">Tricycle</option>
	  <option value="">Motorcycle</option>
	  <option value="">Jeepney/Car</option>
	  </select> 
	  
	 <br/>
	 <label>PS11 - Electricity</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">None</option>
	  <option value="">Shared Meter</option>
	  <option value="">Own Meter</option>
	  </select>
	  
	 <br/>
	 <label>PS12 - Fuel for Cooking</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">Charcoal/Wood</option>
	  <option value="">Kerosene</option>
	  <option value="">LPG/Electricity</option>
	  </select>

	 <br/>
	 <label>PS13 - Toilet</label>
	  <select id="" name="">
	  <option disabled selected>Please Choose</option>
	  <option value="">None</option>
	  <option value="">Shared</option>
	  <option value="">Communal</option>
	  <option value="">Pit</option>
	  <option value="">Manual</option>
	  <option value="">Flush</option>
	  </select>
	  
	  
	<button type = "submit" class="btn btn-embossed btn-primary" name = "add">Add</button>
</fieldset>
	
</section>
<br/>
<section id="col2">

	<table border="1">
	<tr>
	<th>#</th>
	<th>Name</th>
	<th>Poverty Score</th>
	<th>Household Income</th>
	<th>Household Members</th>
	<th>Sick Members</th>

	<th>EW</th>
	<th <?php echo $hidden;?>>PW</th>
	<th <?php echo $hidden;?>>IW</th>
	<th>Status</th>
	<th>Action</th></tr>

	<?php
			$query = getApplicationParticipants($entry_id);
			$result = pg_query($dbconn, $query);
			
		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))                                                                                                                                                
		{
		
			$variableA = $row['variable_a'];
			$variableB = $row['variable_b'];
			$variableC = $row['variable_c'];
			$variableD = $row['variable_d'];
			$participant_tag = $row['tag'];
			
			/*$i = ($variableB*4)/($variableC+$variableD);
			$ew = round(100-((($variableA/25)*25)+(($i/800)*25)), 1);
			$pw = round(100-((($variableA/25)*35)+(($i/800)*15)), 1);
			$iw = round(100-((($variableA/25)*15)+(($i/800)*35)), 1);*/
			
			$ew = $row['answer1'];
			$pw = $row['answer2'];
			$iw = $row['answer3'];
			
			$hue1 = scoreHue($ew);
			$hue2 = scoreHue($pw);
			$hue3 = scoreHue($iw);
			
			$participant_tag = $row['tag'];
			if($participant_tag == "1")
			{
				$status = "Enlisted";
			}
			else if($participant_tag == "2")
			{
				$status = "Pending";
			}
			else if($participant_tag == "3")
			{
				$status = "Disqualified";
			}
			else if($participant_tag == "4")
			{
				$status = "Qualified";
			}
			else
			{
				$status = "Unknown";
			}
			
			echo "<tr>
					<td>".$count."</td>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_initial']."</a></td>
					<td align = 'right'>".$variableA."</td>
					<td align = 'right'>". number_format($variableB)."</td>
					<td align = 'right'>".$variableC."</td>
					<td align = 'right'>".$variableD."</td>
					<td align = 'right'><font color=$hue1>".$ew."</td>
					<td align = 'right'".$hidden."><font color=$hue2>".$pw."</td>
					<td align = 'right'".$hidden."><font color=$hue3>".$iw."</td>
					<td>".$status."</td>";
			
			if($participant_tag == "2")
				echo "<td><button name='approve' value='".$row['id']."'>&#10004;</button><button name='disapprove' value='".$row['id']."'>&#x2717;</button></td>";
			else
				echo "<td><button name='participant_update' value='".$row['id']."'>Update</button></td>";
					
			echo "</tr>";
					
			$count++;
		}
		?>
	</table>
	<br/>
<button class="btn btn-embossed btn-primary" name='back'>Back</button>

<?php 
if(countTotalParticipantStatus($entry_id,3) >= "30" && $accesslevel != "5")
	echo "<button name='completed'>Completed</button>";
?>
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>