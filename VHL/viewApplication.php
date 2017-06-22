<?php

	//session
	session_start();
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$application_pk = $_GET['a'];
	$notice = "";
	$application = getApplicationDetails($application_pk);

	if(isset($_GET['b']))
		$notice = $_GET['b'];
	else
		$notice = "";

	$pastor_name = $application['pastor_last_name'].", ".$application['pastor_first_name']." ".$application['pastor_middle_initial'];
	$base_id = $application['base_id'];
	$base = getBaseName($base_id);
	$program_id = $application['application_type'];
	$program = getApplication_String($program_id);
	$province = $application['application_province'];
	$city = $application['application_city'];
	$barangay = $application['application_barangay'];
	$application_tag = $application['tag'];
	$application_id = $application['application_id'];
	$application_date = $application['application_date'];
	$created_date = $application['created_date'];
	$updated_date = $application['updated_date'];
	$updated_by = $application['updated_by'];
	$application_date = date("F j, Y",strtotime(str_replace('-','/', $application_date)));
	$created_date = date("F j, Y",strtotime(str_replace('-','/', $created_date)));
	$created_by = $application['created_by'];

	if(!$updated_date)
		$updated_date = 'na';
	else
		$updated_date = date("F j, Y",strtotime(str_replace('-','/', $updated_date)));
	if(!$updated_by)
		$updated_by = 'na';
	$tag_string = getApplication_Status($application_tag);

	if($application_tag == 1) {

	}
	else if($application_tag == 2) {

	}
	else if($application_tag == 3) {

	}

	if(isset($_POST['approve1']))//to ocular
	{
		header('location:_applicationaction.php?a=1&b='.$application_pk.'&c='.$base_id);
	}

	if(isset($_POST['approve2']))//to selection
	{
		header('location:_applicationaction.php?a=3&b='.$application_pk.'&c='.$base_id);
	}

	if(isset($_POST['delete']))//delete
	{
		header('location:_applicationaction.php?a=8&b='.$application_pk.'&c='.$base_id);
	}

	if(isset($_POST['drop1']))//from ocular to application
	{
		header('location:_applicationaction.php?a=2&b='.$application_pk.'&c='.$base_id);
	}

	if(isset($_POST['drop2']))//from selection to ocular
	{
		header('location:_applicationaction.php?a=4&b='.$application_pk.'&c='.$base_id);
	}

	if(isset($_POST['add']))//from selection to ocular
	{
		header('location:viewSelection.php?a='.$application_pk);
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

<header>
  <h2>View Application</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>

	<table>
		<th colspan = "2" style="text-align: left;">Application Details (<a href='editApplication.php?a=<?php echo $application_pk;?>'>edit</a>)</th>
		<tr><td>Application ID</td><td><?php echo $application_id; ?></td></tr>
		<tr><td>Status</td><td><?php echo $tag_string; ?></td></tr>
		<tr><td>Pastor:</td><td><?php echo $pastor_name; ?></td></tr>
		<tr><td>Base:</td><td><?php echo $base; ?></td></tr>
		<tr><td>Program Type:</td><td><?php echo $program; ?></td></tr>
		<tr><td>Location:</td><td><?php if($province != "" && $city != "" && $barangay != "") echo $province."-".$barangay.", ".$city.","; else echo "Empty";?></td></tr>
		<tr><td>Last Updated Date:</td><td><?php echo $updated_date;?></td></tr>
		<tr><td>Last Updated By:</td><td><?php echo $updated_by;?></td></tr>
		<tr><td></td></tr>
		<tr><td>Application Date</td><td><?php echo $application_date;?></td></tr>
		<tr><td>Created Date:</td><td><?php echo $created_date;?></td></tr>
		<tr><td>Created By:</td><td><?php echo $created_by;?></td></tr>

		<tr><td>Total Participants:</td><td><?php echo countParticipantTotal($application_pk);?></td></tr>
		<tr><td>Community Participants:</td><td><?php echo countParticipantTag($application_pk,5);?></td></tr>
		<tr><td>Qualified Participants:</td><td><?php echo countParticipantTag($application_pk,2);?></td></tr>
		<tr><td>Disqualified Participants:</td><td><?php echo countParticipantTag($application_pk,3);?></td></tr>
		<tr><td>Flagged Participants:</td><td><?php echo countParticipantTag($application_pk,4);?></td></tr>
	</table>

</section>

<section id="col2">
<?php

 if($application_tag == "1") {
	echo "<button onClick=\"javascript: return confirm('Move to &lsquo;For Ocular Visit&lsquo; list?');\" name='approve1'>Approve</button><br/>";
	echo "<button onClick=\"javascript: return confirm('Are you sure you want to delete this entry?');\" name='delete'>Delete</button>";
 }
 else if($application_tag == "2") {
	echo "<button onClick=\"javascript: return confirm('Move to &lsquo;Selection&lsquo; list?');\" name='approve2'>Approve</button><br/>";
	echo "<button onClick=\"javascript: return confirm('Are you sure you want to drop this entry?');\" name='drop1'>Drop</button>";
 }
 else if($application_tag == "3") {
	echo "<button onClick=\"javascript: return confirm('Move to &lsquo;Selection&lsquo; list?');\" name='approve3'>Approve</button><br/>";
	echo "<button name='add'>Add Particpant</button><br/>";
	echo "<button onClick=\"javascript: return confirm('Are you sure you want to drop this entry?');\" name='drop2'>Drop</button>";
 }
?>
</section>

<section>
	<table border="0">
	<tr>
	<th>Participant Name</th>
	<th>Poverty Score</th>
	<th>Status</th>
	<th>Action</th>
	</tr>

	<?php
		$count="1";
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
					<td align = 'center'>$status</td>";

				if($participant_tag == "2" || $participant_tag == "3" || $participant_tag == "5")
				  echo "<td align = 'center'><a href='viewParticipantPsc.php?a=$application_pk&b=$participant_pk'>View</a></td>";
				else
				  echo "<td align = 'center'><a href='editParticipantPsc.php?a=$application_pk&b=$participant_pk'>Update</a></td>";

			echo "</tr>";

			$count++;
		}

		if(countParticipantTag($application_pk,2)>"30")
		 echo '<tr><td colspan = "4" align = "right"><br/><button type = "submit" class="btn btn-embossed btn-primary" name = "approve">Approve</button></td></tr>';
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

//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
<script src='default.js'></script>
</body>

</html>
