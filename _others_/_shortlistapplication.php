<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="_shortlistapplication.php";

include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];

//defaults
$entry_id = $_GET['a'];
$base = $_GET['b'];


echo $for_approval_total = countApplicationTagByBase('2',$base);

if($for_approval_total == '40')
{
	header('location: /ICM/Transform/listApplication.php?a=Sorry the maximum applications for approval has been reached.');
}

else
{
	updateApplicationTag($entry_id,2,$username);
	header('location: /ICM/Transform/listApplication.php');
}
?>
