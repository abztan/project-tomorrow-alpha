<?php
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="viewCommunity.php";
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
	$notice="";
	$count="1";
	$hidden="";

	$row = getApplicationDetails($entry_id);
	$base_id = $row['base_id'];
	$application_tag = $row['tag'];
	$community_id = $row['community_id'];
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

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/headApprovalList.php');
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
  <h2>Community View</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><p id = "notice"><?php echo $notice;?></p></h1>
<?php if($accesslevel == 99 || $accesslevel == 6)?>
<a href='headApprovalView.php?a=<?php echo $entry_id?>'>+Modify</a>
<table>
	<th colspan = "2" style="text-align: left;">Community Information</th>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	<tr><td>Pastor</td><td>
		<?php
		if($row['pastor_id'] != 0)
		{
			echo "<a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a>";
		}

		else
		{
			echo $row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial'];
		}
		?></td></tr>
	<tr><td>Community ID</td><td><?php echo "<a href='/ICM/VHL/viewApplication.php?a=$entry_id'>$community_id</a>";?></td></tr>
</table>

<br/>

<table>
	<th colspan = "4" style="text-align: left;">Community Participants</th>

	<?php
			$query = getParticipantTag($entry_id,5,'last_name, first_name','ASC');
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];
			$participant_id = $row['participant_id'];

			echo "<tr>
					<td><a href='viewParticipantPsc.php?a=$entry_id&b=$participant_pk'>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'>$participant_id</td>";

				  echo "<!--<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag)'>Dropout</a></td>--></tr>";

			$count++;
		}
		?>

	</table>
<br/>
<button class="btn btn-embossed btn-primary" name='back'>Back</button>
</section>

<section id="col2">
	<!--<table>
	<tr>
	<th colspan = "4" >Dropout Participants</th>
	</tr>

	<?php
			$query = getParticipantTag($entry_id,6);
			$result = pg_query($dbconn, $query);


		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{

			$participant_tag = $row['tag'];
			$participant_pk = $row['id'];

			$score = $row['variable2'];
			$score1 = getParticipantScoreNullIncome($participant_pk);

			echo "<tr>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_name']."</a></td>
					<td align = 'center'><strong><font color ='".scoreHue($score)."'>$score</font></strong></td>
					<td align = 'center'><strong><font color ='".scoreHue($score1)."'>$score1</font></strong></td>";

				  echo "<td align = 'center'><a onclick='overrideStatus($entry_id,$participant_pk,$participant_tag)'>Dropout</a></td></tr>";

			$count++;
		}
		?>

	</table>-->

</section>
</article>
</form>
<script>
function overrideStatus(entry_id,participant_pk,tag) {
  var reason = prompt("Please state the reason for participant dropout:");

	if (reason == null)	{
		document.getElementById("notice").innerHTML = "";
	}

  else if (reason != "") {
		 window.location.href = '_applicationaction.php?a=6&b='+entry_id+'&c='+participant_pk+'&d='+reason+'&e='+tag;
  }

	else {
		document.getElementById("notice").innerHTML = "A reason must be provided for participant dropout.";
	}
}
</script>
<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>
