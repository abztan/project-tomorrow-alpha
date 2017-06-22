<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	include "_ptrFunctions.php";
	

$pid = $_GET['a'];
updatePastorTag(0, $pid);

header('location: listPastor.php');
?>