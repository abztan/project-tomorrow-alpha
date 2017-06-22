<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire');

session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];
	$account_name = $_SESSION['last_name'].", ".$_SESSION['first_name'];
}
?>
