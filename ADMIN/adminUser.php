<?php 
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="adminUser.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
include "_adminFunctions.php";
include "../_parentFunctions.php";
	
include "../_css/bareringtonbear.css";

if(isset($_GET['a']))
  $sort=$_GET['a'];
else
  $sort = "baseid";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		input, select {width: 225px;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>
<header>
  <h2>Manage Users</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">
<br/>
<a href = "addUser.php">+User</a>
<br/>
<br/>
<table id="listtable">
	<tr>
	  <th>User ID</th>
	  <th>Name</th>
	  <th><a href="adminUser.php?a=username">Username</a></th>
	  <th><a href="adminUser.php?a=tag">Role</a></th>
	  <th><a href="adminUser.php?a=baseid">Base ID</a></th>
	</tr>
	<?php
	$count = 0;
	$role = 0;

	$query = "SELECT *
				   FROM individual
				   ORDER BY $sort, lastname ASC";
					  
	$result = pg_query($dbconn, $query);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$userid = $row['uid'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$username = $row['username'];
		$tag = $row['tag'];
		$base_id = $row['baseid'];
		
		$role = getUserRole($tag);
		$base = getBaseName($base_id);
			
			echo "<tr id='tr_style1'>
					  <td align='center'>".$userid."</td>
					  <td align=''>".$lastname.", ".$firstname."</td>
					  <td align=''>".$username."</td>
					  <td align=''>".$role."</td>
					  <td align=''>".$base."</td>";

			echo "</td></tr>";
			$count++;
	}
	?>

</article>
</form>

<script src='../parent.js'></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>