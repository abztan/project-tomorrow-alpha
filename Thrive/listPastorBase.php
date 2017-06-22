<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="listThrive.php";

include "../_css/bareringtonbear.css";
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_ptrFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
$accesslevel = $_SESSION['accesslevel'];
$baseid = $_SESSION['baseid'];

//defaults
$count = 1;
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
$hidden1 = "yes";


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

	if($accesslevel == "7" || $accesslevel == "9")
	{
		$query1 = getListPastor_Base($baseid);
		$hidden = "yes";
	}

	else
	{
		if($selected_base == "0")
			$query1 = getListPastor();
		else
			$query1 = getListPastor_Base($selected_base);

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

<nav>
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<legend>Thrive Pastors</legend>
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
	</select><br/><br/>';

	/* display by thrive
	$query2 = getThriveDistinct($baseid);
	$result2 = pg_query($dbconn, $query2);
	while($row2=pg_fetch_array($result2,NULL,PGSQL_BOTH))
	{
		echo $row2['thriveid'].", ";
	}*/
	?>



	<table id="listtable" border ="0">
	<tr>
	  <th></th>
	  <th>ID</th>
	  <th>Name</th>
	  <th>Is Member</th>
	  <th>Thrive</th>
	  <th>Base</th>
	  <th>Profile</th>
	</tr>
	<?php

		$result = pg_query($dbconn, $query1);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$id = $row['id'];
		$pid = "P".str_pad($id, 6, 0, STR_PAD_LEFT);
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$middlename = $row['middlename'];
		$ismember = $row['member'];
		$thrive = $row['thriveid'];
		$base = $row['baseid'];

		$base = getBaseName($base);
		if($ismember == "f")
			$ismember = "Non-Member";
		else if($ismember == "t")
			$ismember = "Member";
		else
			$ismember = "Undefined";

		echo "<tr>
				  <td>$count</td>
				  <td align = 'center' id ='numeric'>$pid</td>
				  <td align = 'left'>".$lastname.", ".$firstname." ".$middlename."</td>";

		echo "<td align='center'><a href = 'editMember.php?a=$id'>$ismember</a></td>";

		echo	"<td align='center'>$thrive</td>
				  <td align='center'>$base</td>
					<td align='center'><a href = 'viewPastor.php?a=$id'>View</a></td>
				  </tr>";

		$count++;
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
