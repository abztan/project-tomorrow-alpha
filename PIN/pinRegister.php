<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');
	$username = $_SESSION['username'];
	include "functions.php";
	
	$checker = "";
	
	if(isset($_POST['add']))
	{
		$lname=$_POST['lname'];
		$fname=$_POST['fname'];
		$mname=$_POST['mname'];
		$gender=$_POST['gender'];
		$bday=$_POST['bday'];
		$status=$_POST['status'];
		$contact1=$_POST['contact1'];
		$contact2=$_POST['contact2'];
		$contact3=$_POST['contact3'];
		$email=$_POST['email'];
		$education=$_POST['educ'];
		$seminary=$_POST['seminary'];
		$position=$_POST['position'];
		$th_area=$_POST['th_area'];
		$province=$_POST['province'];
		$city=$_POST['city'];
		$barangay=$_POST['barangay'];
		$address=$_POST['address'];
		
		$cname=$_POST['c_name'];
		$denomination=$_POST['c_denomination'];
		$cprovince=$_POST['c_province'];
		$ccity=$_POST['c_city'];
		$cbarangay=$_POST['c_barangay'];
		$caddress=$_POST['c_address'];
		$isPlanted=$_POST['c_planted'];
		
		$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
		$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);
		
		if($pid=="")
		{
			addPastor($lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
			$contact3,$email,$education,$seminary,$position,$th_area,$username);
			
			if($cid=="")
			{
				addChurch($cname,$denomination,$cprovince,$ccity,$cbarangay,$caddress,$isPlanted,$username);
				$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
				$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);
				setChurch($pid,$cid);
			}
			else
			{
				$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
				setChurch($pid,$cid);
			}
			
			$checker = "Completed!";
		}
		else
			$checker="Entry already exists";	
		
		
		
	}
	
	if(isset($_POST['search']) && $_POST['search'] != "")
	{
		$query = searchPastor($_POST['search']);
		$result = pg_query($dbconn, $query);
	}
	
	include "bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		input, select {width: 225px;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">
<legend>ICM Pastors</legend> <input type="text" placeholder="Search" name="search" onchange="form.submit()">
	<table id="listtable">
	<tr>
	  <th>Province</th>
	  <th>City</th>
	  <th>Barangay</th>
	  <th>Name</th>
	  <th>Contact Number</th>
	  <th>Thrive Area</th>
	  <th></th>
	</tr>
	<?php
		 while ($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){
			
			$counter++;
			
			$churchid = $row['churchid'];
			
			$church = getChurchName($churchid);
			
			 echo "<tr><td>".$row['province']."</td>
				  <td>".$row['city']."</td>
				  <td>".$row['barangay']."</td>
				  <td><a href = 'viewPastor.php?a=".$row['id']."'>".$row['lastname'].", ".$row['firstname']."</a></td>
				  <td>".$row['contact1']."</td>
				  <td>".$row['thriveid']."</td>
				  <td><a href='#'>Register</a></td></tr>";
		 }
	?>
	</table>
	<br/>
	<span>Total: <?php echo $counter;?></span>
</section>

<section>
<?php include "pinRegisterForm.php"; ?>
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>