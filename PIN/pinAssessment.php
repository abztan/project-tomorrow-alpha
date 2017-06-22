<?php 
	//session
	session_start();
	if(empty($_SESSION['username']))
	header('location: Login.php?a=2');
	$username = $_SESSION['username'];

	
	/*pastor info
		include "functions.php";
	$pid = $_GET['a'];
	$row = getPastorDetails($pid);
	$pid = $row['id'];
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];
	$middlename = $row['middlename'];
	
	
	/*if(isset($_POST['add']))
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
		
		
		*/
	
	include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		input, select {width: 175px;}
	</style>
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
	<legend>Initial Assessment</legend>
	<label>PIN Pastor: </label><?php// echo "<text>".$lastname.", ".$firstname." <font style='font-weight: bold;color: #1abc9c;'>".$middlename."</font></text>";?>
	<br/>
	<label>Contact Number</label><br/>
	  <input class="form-control input-sm"  placeholder="Contact Number 1" maxlength="11" type="text" name="contact1">
	  <input class="form-control input-sm"  placeholder="Contact Number 2" maxlength="11" type="text" name="contact2">
	<br/>
	<label>Nature of Incident</label><br/>
	  <select id="nature" name="nature">
	  <option disabled selected>Please Choose</option>
	  <option value="Conflict">Conflict</option>
	  <option value="Displaced">Displaced People</option>
	  <option value="Drought">Drought</option>
	  <option value="Earthquake">Earthquake</option>
	  <option value="Famine">Famine</option>
	  <option value="Fire">Fire</option>
	  <option value="Flood">Flood</option>
	  <option value="Landslide">Landslide</option>
	  <option value="Typhoon">Typhoon</option>
	  <option value="Others">Others</option>	  
	  </select>
	<br/>
	<label>Incident Date</label><br/>
	  <input class="form-control input-sm" type="date" name="idate">
	<br/>
	<label>Local Church Damage</label><br/>
	  <select id="ch_state" name="ch_state">
	  <option disabled selected>Please Choose</option>
	  <option value="None">None</option>
	  <option value="Minor">Minor</option>
	  <option value="Major">Major</option>
	  <option value="Gone">Totally Destroyed</option>
	  </select>
	<br/>
	<?php
		// $query=getProvinceList();
		 //$result=pg_query($dbconn, $query);
	?>

	<label>Address (If outside local community)</label><br/>
	<select id="province" name="province" onChange="window.loadCityList()">
	<option disabled selected>Province</option>
	<?php
		 while ($row=pg_fetch_row($result)) {
		 echo "<option value='$row[0]'>$row[0]</option>";
		 }
	?>
	</select>
	<select id='provinceContent' name='city' onChange='window.loadBarangayList()'>
	<option disabled selected>City/Municipality</option>
	</select>
	<select id='barangayContent' name='barangay'>
	<option disabled selected>Barangay</option>
	</select>
	<br/>
	<h2>Section 1 - Immediate Needs Assessment</h2>
	<label>1.1 How many people live in your community?</label><br/>
	<input  id="num" type="number" class="form-control input-sm" maxlength="3" name="s1_1"/><br/>
	<label>1.2 Total number of people affected by the disaster?</label><br/>
	<input type="number" class="form-control input-sm" maxlength="3" name="s1_2" ><br/>
	<label>1.3 How many people have died?</label><br/>
	<input type="text" class="form-control input-sm" maxlength="3" name="s1_3" ><br/>
	<label>1.4 How many people were seriously injured?</label><br/>
	<input type="text" class="form-control input-sm" maxlength="3" size="1" name="s1_4" ><br/>
	<label>1.5 How many people were slightly injured?</label><br/>
	<input type="text" class="form-control input-sm" maxlength="3" size="1" name="s1_5" ><br/>
	<label>1.6 How many homes have been partially damaged?</label><br/>
	<input type="text" class="form-control input-sm" maxlength="3" size="1" name="s1_6" ><br/>
	<label>1.7 How many homes have been totally destroyed?</label><br/>
	<input type="text" class="form-control input-sm" maxlength="3" size="1" name="s1_7" ><br/>
	
	<br/>
	<h2>Section 2 - Community Needs Assessment</h2>
	<label>2.1 How many families have no remaining stocks of food?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_1"><br/>
	<label>2.2 How many families cannot access sufficient clean water?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_2"><br/>
	<label>2.3 Does the barangay have access to markets/shops?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_3"><br/>
	<label>2.4 Does the barangay have access to health services/facilities?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_4"><br/>
	<label>2.5 Does the barangay have electricity?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_5"><br/>
	<label>2.6 Are roads in/out of the community clear/accessible?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_6"><br/>
	<label>2.7 Are food sources available within the community?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_7"><br/>
	<label>2.8 How many days needed to re-establish shelter?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_8"><br/>
	<label>2.9 How many days needed to re-establish livelihood?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_9"><br/>
	<label>2.10 Which NGOs/relief organizations were present this week and what did they do?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_10"><br/>
	<label>2.11 What relief items are still needed?</label><br/>
	<input class="form-control input-sm" maxlength="3" type="number" name="s2_11"><br/>
	
	<br/>
	<h2>Section 3 - Immediate need of affected community</h2>

	<br/><br/><br/>
	<button class="btn btn-embossed btn-primary" type = "submit" name = "add">Done</button>
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>