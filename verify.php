<?php
//ob_start();
include "_parentFunctions.php";

$a = strtolower($_GET['a']);
$b = $_GET['b'];

$c = verifyLogin($a, $b);

if($c=="True")
header('location: /ICM/Index.php');

else if($c=="False")
header('location: /ICM/Login.php?a=1');

else
header('location: /ICM/Login.php?a=1');

//ob_flush();
?>
