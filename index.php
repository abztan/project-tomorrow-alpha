<?php
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

else
{
	include "_parentFunctions.php";
	$_SESSION['previouspage']="none";
	$_SESSION['currentpage']="index.php";
	$userid=$_SESSION['userid'];
	$query=getUserInfo($userid);
	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);
	$_SESSION['batch_dis'] = "";
	$_SESSION['base_dis'] = "";
}


include "_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<header>
  <h2>Home</h2>
  <nav class="menu"><?php include "controller.php"; ?></nav>
</header>

<article id="content">
	<h3>You are currently viewing version 1.0 of ICM's metrics system. </h3><br/>
	<?php
	echo "Hi there ",$row['firstname'], " hope your day is going well. :)";?>
	<br/><br/>
	<?php
	 $dt = new DateTime();
	 echo "Current Date and Time:<br/>";
	 $batch = getDate_details($dt->format('Y-m-d'));
	 $c_week = $batch['week_number'];

	 echo $dt->format('F j, Y H:i:s');
	?>
	<br/><br/>
	<?php
	 echo "current batch: ".getBatch("current","")."</br>";
	 echo "current week: $c_week";

	 $app_date = date("n", strtotime(str_replace('-','/', '6/7/2014')));
	 //echo "indicated batch:".getBatch("specified",$app_date);
	 ?>
	<br/>
	<br/>
	<audio id="bgmusic" preload="pause" controls>
	<source src="_media/home.mp3" type="audio/mpeg">
	</audio>

	<br/>
</article>
</div>
<script src='parent.js'>
</script>

<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>


</html>
