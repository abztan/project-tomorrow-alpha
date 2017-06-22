<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

include "bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Project Tomorrow</title>
</head>

You are currently viewing a raw version of ICM's metrics system. <br/>
<img src="../Media/welcome_earthling.gif" / width="300px">
<br/>
<a href="View.php">Database</a>
<a href="temp1.php">That sample map</a>
</html>