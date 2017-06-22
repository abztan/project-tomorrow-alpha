
<?php
	session_start();
	if(empty($_SESSION['username']))
		header('location: /ICM/Login.php?a=2');
	else {
		$username = $_SESSION['username'];
		$access_level = $_SESSION['accesslevel'];
		$account_base = $_SESSION['baseid'];
	}

	include "../_css/bareringtonbear.css";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

  <nav class = "menu">
    <?php include "../controller.php";?>


<br/><br/>
<select id = 'sup' name = 'base_display' onchange="document.getElementById('x').src=this.value">
  <option value='' disabled selected>(Select Report)</option>
	<option value='r1.php'>Attendance Summary (Clean)</option>
	<option value='r2.php'>Attendance Summary (National)</option>
  <option value='attendance_ceaiu.php'>Attendance CEAIU</option>
  <option value='attendance_report.php'>Attendance Report</option>
  <option value='attendance_summary.php'>Attendance Class Summary</option>
  <option value='dani.php'>Active/Dropped/Graduated Report</option>
  <option value='krisha1.php'>Community & Participant ID</option>
  <option value='../../PHX/Transform/HBF/hbf_national.php'>HBF National</option>
</select>
<br/>
<br/>

<iframe src="attendance_ceaiu.php" style="overflow:hidden; height:100%;width:100%; border: 0;" height="100%" width="100%" id="x"></iframe>
</html>
<script>
	//sticky menu
	$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
	scrollIntervalID = setInterval(stickIt, 10);
</script>
