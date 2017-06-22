<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="headApprovalList.php";

include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
$accesslevel = $_SESSION['accesslevel'];

include "../_css/bareringtonbear.css";

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

	if($accesslevel == "6")
	{
		$query1 = getListApplication_Head_ByBase($_SESSION['baseid']);
		$hidden = "yes";
	}

	else
	{
		if($selected_base == "0")
			$query1 = getListApplication_Head();
		else
			$query1 = getListApplication_Head_ByBase($selected_base);

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
<header>
  <h2>Community List</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>


<form name='form1' action='' method='POST'>

<article id="content">
	<?php
	if($hidden != "yes")
	echo '

	<select id="sup" name="base_display" onchange="form.submit()">
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
	</select>';
	?>
	<br/><br/>
	<table id="listtable">
	<tr>
	  <th></th>
	  <th>Application ID</th>
	  <th>Base</th>
	  <th>Community ID</th>
	  <th>Pastor</th>
	  <th>Status</th>
	  <th>Action</th>
	</tr>
	<?php

		$result = pg_query($dbconn, $query1);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$base_id = $row['base_id'];
		$entry_id = $row['id'];
		$base = getBaseName($base_id);
		$city = $row['application_city'];
		$barangay = $row['application_barangay'];
		$location = $base." - ".$city.", ".$barangay;
		$community_id = $row['community_id'];
		if($community_id == "")
		  $community_id = "-";
		else
		  $community_id = $community_id;

		$tag = $row['tag'];

		if($tag == "3" && (countParticipantTag($entry_id,2) >= 30 || countParticipantTag($entry_id,5) >= 30))
		  $status = "Ready for Area Head Approval";
		else if($tag == "3" && (countParticipantTag($entry_id,2) <= 29 || countParticipantTag($entry_id,5) <= 29))
		  $status = "Not Enough Qualified Participants";
		else if($tag == "5")
		  $status = "Completed";
		else if($tag == "9")
		  $status = "Upcoming Batch";
		else
		  $status = "Unidentified";



			echo "<tr id='tr_style1'>
					  <td>".$count."</td>
					  <!--<td align='center'><a href='viewApplication.php?a=".$row['id']."'>".$row['application_id']."</a></td>-->
					  <td align='center'>".$row['application_id']."</td>
					  <td align='left'>".$location."</td>
					  <td align='center'>".$community_id."</td>";

			if($row['pastor_id'] != 0)
				echo "<td><a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a></td>";
			else
				echo "<td>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</td>";

			echo "<td align='center'>$status</td>
					  <td align='center'>";

		    if($tag == "5")
			  echo "<a href='viewCommunity.php?a=$entry_id'>";
			else
			  echo "<a href='headApprovalView.php?a=$entry_id'>";

			echo "View</a></td></tr>";
			$count++;
		//}
	}
	?>

</article>
</form>

<script src='default.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>
