<?php
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="headApprovalView.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$entry_id = $_GET['a'];
	$notice = "";
	$count = "1";
	$next_comm_id = "";
	$application = "";
	$row = getApplicationDetails($entry_id);
	$base_id = $row['base_id'];
	$application_tag = $row['tag'];
	$application_type = $row['application_type'];
	$application_status = getApplication_Status($application_tag);
	$application_string = getApplication_String($application_type);
	$base_string = getBaseName($base_id);

	//hidden
	$hidden = "";
	$show_approve = "";

	//if admin - extra view
	if($accesslevel == "5")
	  $hidden = "hidden";
	else
	  $hidden = "";

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/headApprovalList.php');
	}

	//upon application approval
	if(isset($_POST['approve']))
	{
		$show_approve = "hidden";
		echo $next_comm_id = generateCommunityID($entry_id,$application_type);

		if(checkDuplicate_communityID($next_comm_id) == "false") {
			$batch = substr($next_comm_id,5,1);
			updateApplicationTag($entry_id,9,$username);
			updateApplicationBatch($entry_id,$batch,$username);
			updateApplicationCommunityID($entry_id,$next_comm_id,$username);

			$query1 = getParticipantTag($entry_id,5,'last_name','ASC');
			$result1 = pg_query($dbconn, $query1);

			while($row1=pg_fetch_array($result1,NULL,PGSQL_BOTH)) {
				$participant_tag = $row1['tag'];
				$participant_pk = $row1['id'];
				$participant_id = $row1['participant_id'];

				if($participant_id == "") {
					$row2 = getApplicationDetails($entry_id);
					$comm_id = $row2['community_id'];
					$participant_id = generateParticipantID($entry_id,$participant_pk,$comm_id);
					updateApplication_ParticipantID($participant_pk,$participant_id,$username);
				}
			}

			$notice =  "You have successfully approved this application, redirecting page in... <span id='counter'>3</span>";
			$address = "viewCommunity.php?a=$entry_id";
		}

		else
			$notice = "Fail to approve this application, please contact system administrator.".$next_comm_id;
	}

	include "../_css/bareringtonbear.css";

	//conditions for approval
	$max_participants = getProgram_maxParticipants($application_type);
	$qualified_participants = countParticipantTag($entry_id,5);

	if(($qualified_participants >= 30 &&  $qualified_participants <= $max_participants) && ($accesslevel == 1 || $accesslevel == 6 || $accesslevel == 99) && $application_tag == 3)
	$show_approve = "";

	 //iloilo special
	 if($base_id == "8" && countParticipantTag($entry_id,5) < 32)
	 {
		if($application_type == 1)
 			$max = 31;
 		else if($application_type == 2)
 			$max = 40;
 		else if($application_type == 3)
 			$max = 40;
 		else if($application_type == 4)
 			$max = 31;

		$query9 = "SELECT *
					 FROM list_transform_participant
					 WHERE fk_entry_id = '$entry_id'
					 AND tag = '2'
					 ORDER BY variable2 DESC
					 LIMIT $max";

 		$result9 = pg_query($dbconn, $query9);

 		while($row9=pg_fetch_array($result9,NULL,PGSQL_BOTH))
 		{
 			$participant_pk = $row9['id'];

 			updateParticipantTag($participant_pk,5,$username);
 		}
	 }

	 //tag top 30
	 else if (countParticipantTag($entry_id,5) < 30)
	 {
		$query9 = getParticipantShortlisted($application_type, $entry_id);
		$result9 = pg_query($dbconn, $query9);

		while($row9=pg_fetch_array($result9,NULL,PGSQL_BOTH))
		{
			$participant_pk = $row9['id'];

			updateParticipantTag($participant_pk,5,$username);
		}
	 }

	 if($application_tag == 9)
	 $show_approve = "hidden";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<header>
  <h2>Community List + View</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>
<table>
	<th colspan = "2" style="text-align: left;">Application Information</th>
	<tr><td>Base</td><td><?php echo $base_string;?></td></tr>
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
	<tr><td>Application ID</td><td><?php echo $row['application_id'];?></td></tr>
	<tr><td>Status</td><td><?php echo $application_status; ?></td></tr>
	<tr><td>Application Date</td><td><?php echo $row['application_date'];?></td></tr>
	<tr><td>Program</td><td><?php echo $application_string; ?></td></tr>
	<tr><td>Community Participant Count</td><td><?php echo countParticipantTag($entry_id,5);?></td></tr>
	<tr><td>Replacement Participant Count</td><td><?php echo countParticipantTag($entry_id,2);?></td></tr>

</table>
<br/>
<button class="btn btn-embossed btn-primary" name='back'>Back</button>
<button class='btn btn-embossed btn-primary' <?php echo $show_approve;?> name='approve'>Approve</button>
</section>

<section id="col2">
	<table>
	<tr>
	<th colspan = "4" >Community Participants</th>
	</tr>

	<?php
			$query = getParticipantTag($entry_id,5,'variable2','DESC');
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			$score = $row['variable2'];
			$score = round($score);
			$score1 = getParticipantScoreNullIncome($participant_pk);

			echo "<tr id='tr_style1'>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>
					<td align = 'center'><strong><font color ='".scoreHue($score1)."'>$score1</font></strong></td>";

				  echo "<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag,$application_type)'>Dropout</a></td></tr>";

			$count++;
		}
		?>

	</table>
</section>

<section id="col3">
	<table>
	<tr>
	<th colspan = "3">Replacement Participants</th>
	</tr>

	<?php
			$query = getParticipantTag($entry_id,2,'variable2','DESC');
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			$score = $row['variable2'];
			$score = round($score);

			echo "<tr id='tr_style1'>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>";

			echo "<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag,$application_type)'>Replacement</a></td></tr>";

			$count++;
		}
		?>

	</table>
</section>

<section id="col4">
	<table>
	<tr>
	<th colspan = "4">Disqualified Participants</th>
	</tr>

	<?php
			$query = getParticipantTag($entry_id,3,'variable2','DESC');
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			$score = $row['variable2'];
			$score = round($score);
			$score1 = getParticipantScoreNullIncome($participant_pk);

			echo "<tr id='tr_style1'>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>
					<td align = 'center'><strong><font color ='".scoreHue($score1)."'>$score1</font></strong></td>";

			echo "<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag,$application_type)'>Qualify</a></td></tr>";

			$count++;
		}
		?>

	</table>

</section>
</article>
</form>
<script>
function overrideStatus(entry_id,participant_pk,tag,application_type) {
    var reason = prompt("Please state the reason for overwriting participant status:");

	if (reason == null)
	{
		document.getElementById("notice").innerHTML = "";
	}

    else if (reason != "")
	{
		 window.location.href = '_applicationaction.php?a=5&b='+entry_id+'&c='+participant_pk+'&d='+reason+'&e='+tag+'&f='+application_type;
    }

	else
	{
		document.getElementById("notice").innerHTML = "A reason must be provided to override the participant status.";
	}

}
</script>
<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>

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
