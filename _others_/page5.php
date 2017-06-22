<?php 
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="page2.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	//php
	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "../controller.php";
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
		$variableA=$_POST['p_score'];
		$variableB=$_POST['t_income'];
		$variableC=$_POST['t_members'];
		$variableD=$_POST['s_members'];
		
		if(checkParticipantEntry($last_name,$first_name,$middle_initial)!="")
			echo $notice = "Sorry but this entry already exists.";
			
		else
		{
			addParticipant($last_name,$first_name,$middle_initial,$variableA,$variableB,$variableC,$variableD,$entry_id,$username);
			header('location: /ICM/page2.php?a='.$entry_id);
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
	
	if(isset($_POST['completed']))
	{
		header('location: page3.php?a='.$entry_id);
	}
	
	if(isset($_POST['back']))
	{
		header('location: /ICM/page1.php');
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

<nav id="navstyle">
<?php //include "controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<h1><?php echo $notice;?></h1>
<table>
	<th colspan = "2" align = "left">Application Information</th>
	<tr><td>Entry ID</td><td><?php echo "APP".$row['id'];?></td></tr>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	
	<tr><td>Pastor</td><td><?php echo "<a href='Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."."."</a>";?></td></tr>
	
</table>

<br/>
	
</section>
<br/>
<section id="col2">

	<table border="1">
	<tr>
	<th>#</th>
	<th>Name</th>
	<th>Poverty Score</th>
	<th>Total Household Income</th>
	<th>Total Number of Household Members</th>
	<th>Total Number of Sick Household Members</th>

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
				$status = "Disqualified";
			}
			else if($participant_tag == "3")
			{
				$status = "Qualified";
			}
			else
			{
				$status = "Unknown";
			}
			
			echo "<tr>
					<td>".$count."</td>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_initial']."."."</a></td>
					<td align = 'right'>".$variableA."</td>
					<td align = 'right'>". number_format($variableB)."</td>
					<td align = 'right'>".$variableC."</td>
					<td align = 'right'>".$variableD."</td>
					<td align = 'right' bgcolor=$hue1>".$ew."</td>
					<td align = 'right' bgcolor=$hue2 ".$hidden.">".$pw."</td>
					<td align = 'right' bgcolor=$hue3 ".$hidden.">".$iw."</td>
					<td>".$status."</td>
					<td><button name='approve' value='".$row['id']."'>&#10004;</button><button name='disapprove' value='".$row['id']."'>&#x2717;</button></td>
					</tr>";
					
			$count++;
		}
		?>
	</table>
<button name='back'>Back</button>

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