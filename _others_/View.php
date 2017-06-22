<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

	include "pastor_functions.php";
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
		 
		<?php	
			 $query = getCommunityList();
			 $result = pg_query($dbconn, $query);
		?>

	<legend>ICM Communities</legend>
	<table id="listtable">
	<tr>
	  <th>Category</th>
	  <th>Code</th>
	  <th>Province</th>
	  <th>City</th>
	  <th>Barangay</th>
	  <th>Pastor</th>
	  <th>Geotype</th>
	  <th>Address</th>
	</tr>
	<?php
		 while ($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){
			 echo "<tr><td>".$row['category']."</td>
				  <td>".$row['code']."</td>
				  <td>".$row['province']."</td>
				  <td>".$row['city']."</td>
				  <td>".$row['barangay']."</td>
				  <td>".$row['pastor']."</td>
				  <td>".$row['geotype']."</td>
				  <td>".$row['address']."</td></tr>";
		 }
	?>
	</table>		
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>