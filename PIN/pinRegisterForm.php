<?php
	//$pid = $_GET['a'];
	$pid = "22";
	$row = getPastorDetails($pid);
	$pid = $row['id'];
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];
	$middlename = $row['middlename'];


echo "Pastor: ".$lastname.", ".$firstname." ".$middlename;
Address
Birthday

Has access to the following within a kilometer radius:

Medical Facility

Market/Stores

Clean Water

Electricity


?>