<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');

	include "_pinFunctions.php";
	
	//php
	include "../_css/bareringtonbear.css";
	include "../dbconnect.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">	
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>