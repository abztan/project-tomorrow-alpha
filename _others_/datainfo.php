<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

	include "functions.php";
	//php
	include "bareringtonbear.css";
	include "dbconnect.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">	
<table>
<th colspan = "2" align = "left">Data</th>
<tr><td>Total Number of Pastors</td><td id="t_numvalue"><?php echo number_format(countPastorList("Total Pastor"));?></td></tr>
<tr><td>Total Number of Churches</td><td id="t_numvalue"><?php echo number_format(countListChurch("Total Church"));?></td></tr>
<tr><td>Total Number of Provinces</td><td id="t_numvalue"><?php echo number_format(countListBarangay("Total Province"));?></td></tr>
<tr><td>Total Number of Cities or Municipalities</td><td id="t_numvalue"><?php echo number_format(countListBarangay("Total City"));?></td></tr>
<tr><td>Total Number of Barangay</td><td id="t_numvalue"><?php echo number_format(countListBarangay("Total Barangay"));?></td></tr>
<tr><td>Total Number of Denominations</td><td id="t_numvalue"><?php echo number_format(countListChurch("Total Denomination"));?></td></tr>
<?php
$query = getDenominationList();
$result = pg_query($dbconn, $query);

while ($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){
$denomination = $row['denomination']; 
$d_total = countOn("onDenomination", $denomination);
echo "<tr>";
echo "<td>&nbsp;&nbsp;-".$denomination."</td><td id='t_numvalue'>".$d_total."</td>";
echo "</tr>";
}?>
</table>
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>