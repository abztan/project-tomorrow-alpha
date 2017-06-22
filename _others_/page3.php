<?php
	//session
	session_start();
	$_SESSION['previouspage']=$_SESSION['currentpage'];
	$_SESSION['currentpage']="page3.php";
	if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$accesslevel = $_SESSION['accesslevel'];

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	//include "../controller.php";
	$entry_id = $_GET['a'];
	$row = getApplicationDetails($entry_id);
	$base_id = $row['base_id'];
	$base = getBaseName($base_id);
	
	if(isset($_POST['Done']))
	{
		$community_id = $_POST['community_id'];
		updateApplicationCommunity($entry_id,$community_id,$username);
		updateApplicationTag($entry_id,3,$username);
		header('location: /ICM/Transform/page1.php');
	}
	
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

	<table>
	<th colspan = "2" align = "left">Application Information</th>
	<tr><td>Entry ID</td><td><?php echo "APP".$row['id'];?></td></tr>
	<tr><td>Base</td><td><?php echo $base;?></td></tr>
	
	<tr><td>Pastor</td><td><?php echo "<a href='Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."."."</a>";?></td></tr>
	<tr><td>Application ID</td><td><?php echo $row['application_id'];?></td></tr>
	<tr><td>Application Date</td><td><?php echo $row['application_date'];?></td></tr>
	</table>
	<br/>
	<table border="0">
	<tr>
	<th>#</th>
	<th>Name</th>
	<th>Poverty Score</th>
	<th>Total Household Income</th>
	<th>Total Number of Household Members</th>
	<th>Total Number of Sick Household Members</th>
	<th>EW</th>
	
	<?php
			$query = getParticipantsShortlist($entry_id);
			$result = pg_query($dbconn, $query);
			$count = "1";
			$hidden = "";
			
		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))                                                                                                                                                
		{
		
			$variableA = $row['variable_a'];
			$variableB = $row['variable_b'];
			$variableC = $row['variable_c'];
			$variableD = $row['variable_d'];
			
			$ew = $row['answer1'];
			//$pw = $row['answer2'];
			//$iw = $row['answer3'];
				
			echo "<tr>
					<td>".$count."</td>
					<td>".$row['last_name'].", ".$row['first_name']." ".$row['middle_initial']."."."</a></td>
					<td align = 'right'>".$variableA."</td>
					<td align = 'right'>". number_format($variableB)."</td>
					<td align = 'right'>".$variableC."</td>
					<td align = 'right'>".$variableD."</td>
					<td align = 'right'>".$ew."</td>
					</tr>";
					
			$count++;
		}
		?>
	</table>
	
	<br/>
	
	Community ID
	<?php 
	  echo '<input placeholder="Community ID" class="form-control input-sm" type="text" name="community_id" value = "'.$row['community_id'].'">';
	  echo '<button type = "submit" name = "Done">Done</button>';
	?>
	
	</article>
	</form>
	</body>
	</html>