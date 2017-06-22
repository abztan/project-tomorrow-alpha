<?php
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];

//defaults
$entry_id = $_GET['a'];
updateApplicationTag($entry_id,2,$username);

header('location: /ICM/Transform/listApplication.php');
?>
