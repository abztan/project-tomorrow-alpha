<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="page4.php";

include "../_css/bareringtonbear.css";
include "../dbconnect.php";
include "../_parentFunctions.php";
include "../controller.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
$tag = $_SESSION['accesslevel'];

//defaults
$selected_base = "0";
$message = "";
$option_a = "selected";
$option_b = "";
$option_c = "";
$option_d = "";
$option_e = "";
$option_f = "";
$option_g = "";
$option_h = "";
$option_i = "";
$option_j = "";
$option_k = "";
$hidden = "no";
	
	if(isset($_POST['base_display']))
	{
		$selected_base=$_POST['base_display'];
		
		if($selected_base=="1")
			$option_b="selected";
		else if($selected_base=="2")
			$option_c="selected";
		else if($selected_base=="3")
			$option_d="selected";
		else if($selected_base=="4")
			$option_e="selected";
		else if($selected_base=="5")
			$option_f="selected";
		else if($selected_base=="6")
			$option_g="selected";
		else if($selected_base=="7")
			$option_h="selected";
		else if($selected_base=="8")
			$option_i="selected";
		else if($selected_base=="9")
			$option_j="selected";
		else if($selected_base=="10")
			$option_k="selected";
		else
			$option_a="selected";
	}
	
	if($tag == "5")
	{
		$query1 = getBaseCommunitiesByID($_SESSION['baseid']);
		$hidden = "yes";
	}
	else
	{
		$query1 = getBaseCommunitiesByID($selected_base);
		$hidden = "no";
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
<?php// include "controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<?php 
	if($hidden != "yes")
	echo '
	<label>Displayed Base:</label>
	<select id="base_display" name="base_display" onchange="form.submit()">
	<option '.$option_a.' value="0" >All</option>
	<option '.$option_b.' value="1">Bacolod</option>
	<option '.$option_c.' value="2">Bohol</option>
	<option '.$option_d.' value="3">Dumaguete</option>
	<option '.$option_e.' value="4">General Santos</option>
	<option '.$option_f.' value="5">Koronadal</option>
	<option '.$option_g.' value="6">Palawan</option>
	<option '.$option_h.' value="7">Dipolog</option>
	<option '.$option_i.' value="8">Iloilo</option>
	<option '.$option_j.' value="9">Cebu</option>
	<option '.$option_k.' value="10">Roxas</option>
	</select><br/><br/>';?>
	
	<table border ="1">
	<tr>
	  <th style="text-align: center; width: %">Entry ID</th>
	  <th style="text-align: center; width: %">Base</th>
	  <th style="text-align: center; width: %">Pastor</th>
	  <th style="text-align: center; width: %">Community ID</th>
	</tr>
	<?php
		
		$result = pg_query($dbconn, $query1);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$base_id = $row['base_id'];
		$entry_id = $row['id'];
		$base = getBaseName($base_id);
		$participant_total = countParticipantTotal($entry_id);
		
		echo "<tr>
				<td align='center'><a href='page5.php?a=".$row['id']."'>APP".$row['id']."</a></td>
				<td align='center'>".$base."</td>
				<td><a href='Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."."."</a></td>
				<td align='center'>".$row['community_id']."</td></tr>";
	}
?>
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>