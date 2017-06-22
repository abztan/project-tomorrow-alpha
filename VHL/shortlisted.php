<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="shortlisted.php";

include "../_css/bareringtonbear.css";
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
$accesslevel = $_SESSION['accesslevel'];


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


if(isset($_POST['base_display'])){
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

	if($accesslevel == "5")
	{
		$query1 = getListApplicationByBase_ByTag($_SESSION['baseid'], 2);
		$hidden = "yes";
	}

	else
	{
		if($selected_base == "0")
			$query1 = getListApplicationByTag(2);
		else
			$query1 = getListApplicationByBase_ByTag($selected_base, 2);

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
	<legend>For Ocular Visit List</legend>
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

	if($accesslevel == "5")
	{
		/*if(countApplicationTagByBase('2',$_SESSION['baseid']) == 40)
			echo "Your base has reached the";
			echo $message;*/
	}
	?>

	<table id="listtable" border ="0">
	<tr>
	  <th></th>
	  <th>Application ID</th>
	  <th>Base</th>
	  <th>Program</th>
	  <th>Pastor</th>
	  <th>Last Update</th>
	  <th>Action</th>
	</tr>
	<?php

		$result = pg_query($dbconn, $query1);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$base_id = $row['base_id'];
		$entry_id = $row['id'];
		$application_type = $row['application_type'];

		$base = getBaseName($base_id);

		if($application_type == "1")
			$application_type = "Transform";
		else if($application_type == "2")
			$application_type = "Jumpstart";


		echo "<tr>
				  <td>".$count."</td>
				  <!--<td align='center'><a href='viewApplication.php?a=".$row['id']."'>".$row['application_id']."</a></td>-->
				  <td align='center'>".$row['application_id']."</td>
				  <td align='center'>".$base."</td>
				  <td align='center'>".$application_type."</td>";

		if($row['pastor_id'] != 0)
			echo "<td><a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a></td>";
		else
			echo "<td>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</td>";

		echo "<td align='center'>".$row['updated_date']."</td>
				  <td align='center'>
				  <a href='editApplication.php?a=".$row['id']."'>Edit</a>&nbsp;|&nbsp;
				  <a onClick=\"javascript: return confirm('Are you sure you want to drop this entry?');\" href='_applicationaction.php?a=2&b=".$row['id']."&c='>Drop</a>&nbsp;|&nbsp;
				  <a onClick=\"javascript: return confirm('Move to &lsquo;Selection&lsquo; list?');\" href='_applicationaction.php?a=3&b=".$row['id']."&c=".$base_id."'>Approve</a>
				  </td></tr>";
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
