<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');

	include "_ptrFunctions.php";

	$a="hidden";

	$pid=$_GET['a'];
	$row=getPastorDetails($pid);
	$fname=$row['firstname'];
	$lname=$row['lastname'];
	$mname=$row['middlename'];
	$gender=$row['gender'];
	$status=$row['status'];
	$bday=$row['birthday'];
	$contact1=$row['contact1'];
	$contact2=$row['contact2'];
	$contact3=$row['contact3'];
	$email=$row['email'];
	$education=$row['education'];
	$seminary=$row['seminary'];
	$active=$row['active'];
	$thrive=$row['thriveid'];
	$baseid=$row['baseid'];
	$member=$row['member'];
	$mdate=$row['membershipdate'];
	if($row['address']=="")
		$address="";
	else
		$address=$row['address'];
	$barangay=$row['barangay'];
	$city=$row['city'];
	$province=$row['province'];
	$address=$row['address'];
	$churchid=$row['churchid'];
	$position=$row['position'];
	if($churchid != "") {
		$row=getChurchInfo($churchid);
		$cname=$row['churchname'];
		$denomination=$row['denomination'];
		$cprovince=$row['province'];
		$ccity=$row['city'];
		$cbarangay=$row['barangay'];
		$caddress=$row['address'];
		$isPlanted=$row['plantedbyicm'];
	}
	else {
		$cname="";
		$denomination="";
		$cprovince="";
		$ccity="";
		$cbarangay="";
		$caddress="";
		$isPlanted="";
	}
		if(isset($_POST['update']))
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
			if(isset($_POST['base']))
					$baseid=$_POST['base'];

			$cname=$_POST['c_name'];
			$denomination=$_POST['c_denomination'];
			$cprovince=$_POST['c_province'];
			$ccity=$_POST['c_city'];
			$cbarangay=$_POST['c_barangay'];
			$caddress=$_POST['c_address'];
			$isPlanted=$_POST['c_planted'];
			$membership=$_POST['member'];
			$membershipdate=$_POST['mdate'];

			updatePastorMembership($pid, $membership, $membershipdate, $_SESSION['username']);

			updatePastorProfile($pid,$lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
				$contact3,$email,$education,$seminary,$position,$th_area,$_SESSION['username'],$baseid);

			updateChurchProfile($churchid,$cname,$denomination,$cprovince,$ccity,$cbarangay,$caddress,$isPlanted,$_SESSION['username']);

			header('location: profile_view.php?a='.$pid.'');
		}



	include "../../_css/bareringtonbear.css";
?>
<!--
<script>
function update() {

    if (confirm("Apply changes? ( ﾟoﾟ)") == true) {

    } else {

    }
}
</script>-->

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

<form name='form1' action='' method='POST'>
<article id="content">
<section id="col1">
	<legend>Personal Information</legend>
	<label>Name</label><br/>
	  <input class="form-control input-sm" placeholder="First" type="text" name="fname" value="<?php echo $fname;?>">
	  <input class="form-control input-sm" placeholder="Middle" type="text" name="mname" value="<?php echo $mname;?>">
	  <input class="form-control input-sm" placeholder="Last" type="text" name="lname" value="<?php echo $lname;?>">
	<br/>
	<label>Status</label><br/>
	  <?php
	  $b="";
	  $c="";
	  $d="";
	  if($status == "Single")
		$b = "selected";
	  else if($status == "Married")
		$c = "selected";
	  else if($status == "Widowed")
		$d = "selected";
	  else
	    $a = "selected";
	  ?>
	  <select id="status" name="status">
	  <option disabled <?php echo $a;?>>Please Choose</option>
	  <option <?php echo $b;?> value="Single">Single</option>
	  <option <?php echo $c;?> value="Married">Married</option>
	  <option <?php echo $d;?> value="Widowed">Widowed</option>
	  </select>
	<br/>
	<label>Gender</label><br/>
	  <?php
	  $b="";
	  $c="";

	  if($gender == "Male")
		$b = "selected";
	  else if($gender == "Female")
		$c = "selected";
	  else
	    $a = "selected";
	  ?>
	  <select id="gender" name="gender">
	  <option disabled <?php echo $a;?>>Please Choose</option>
	  <option <?php echo $b;?> value="Male">Male</option>
	  <option <?php echo $c;?> value="Female">Female</option>
	  </select>
	<br/>
	<label>Birthday</label><br/>
	  <input class="form-control input-sm" type="date" name="bday" value="<?php echo $bday;?>">
	<br/>
	<label>Contact Number</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Contact Number 1" maxlength="11" type="text" name="contact1" value="<?php echo $contact1;?>">
	  <input class="form-control input-sm" placeholder="Contact Number 2" maxlength="11" type="text" name="contact2" value="<?php echo $contact2;?>">
	  <input class="form-control input-sm" placeholder="Contact Number 3" maxlength="11" type="text" name="contact3" value="<?php echo $contact3;?>">
	<br/>
	<label>Email</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Email Address" type="email" name="email" value="<?php echo $email;?>">
	<br/>
	<label>Education</label><br/>
	<?php
	$a="";
	$b="";
	$c="";
	$d="";
	$e="";
	$f="";
	$g="";
	  if($education == "None")
		$b = "selected";
	  else if($education == "Elementary")
		$c = "selected";
	  else if($education == "High School")
		$d = "selected";
	  else if($education == "College")
		$e = "selected";
	  else if($education == "Post College")
		$f = "selected";
	  else if($education == "Empty")
		$g = "selected";
	  else
	    $a = "selected";
	 ?>
	  <select id="educ" name="educ">
	  <option disabled <?php echo $a;?>>Please Choose</option>
	  <option <?php echo $b;?> value="None">None</option>
	  <option <?php echo $c;?> value="Elementary">Elementary</option>
	  <option <?php echo $d;?> value="High School">High School</option>
	  <option <?php echo $e;?> value="College">College</option>
	  <option <?php echo $f;?> value="Post College">Post College</option>
	  <option <?php echo $g;?> value="Empty">Not Indicated</option>
	  </select>
	<br/>
	<label>Attended Seminary</label><br/>
	<?php
	$a="";
	$b="";
	$c="";
	  if($seminary=="True")
		$b = "selected";
	  else if($seminary=="False")
		$c = "selected";
	  else
	    $a = "selected";
	 ?>
	  <select id="seminary" name="seminary">
	  <option <?php echo $b;?> value="True">Yes</option>
	  <option <?php echo $c;?> value="False">No</option>
	  <option <?php echo $a;?> value="Null">Not Indicated</option>
	  </select>
	<br/>
	<label>Base - Thrive Area</label>
	<br/>
	<?php
	$a="";
	$b="";
	$c="";
	$d="";
	$e="";
	$f="";
	$g="";
	$h="";
	$i="";
	$j="";
	$k="";
	  if($baseid == "1")
		$b = "selected";
	  else if($baseid == "2")
		$c = "selected";
	  else if($baseid == "3")
		$d = "selected";
	  else if($baseid == "4")
		$e = "selected";
	  else if($baseid == "5")
		$f = "selected";
	  else if($baseid == "6")
		$g = "selected";
	  else if($baseid == "7")
		$h = "selected";
	  else if($baseid == "8")
		$i = "selected";
	  else if($baseid == "9")
		$j = "selected";
	  else if($baseid == "10")
		$k = "selected";
	  else
	    $a = "selected";
	 ?>
	  <select id="base" name="base" onChange="window.loadDistrictList()">
	  <option disabled <?php echo $a;?>>Please Choose</option>
	  <option value="1" <?php echo $b;?>>Bacolod</option>
	  <option value="2" <?php echo $c;?>>Bohol</option>
	  <option value="3" <?php echo $d;?>>Dumaguete</option>
	  <option value="4" <?php echo $e;?>>General Santos</option>
	  <option value="5" <?php echo $f;?>>Koronadal</option>
	  <option value="6" <?php echo $g;?>>Palawan</option>
	  <option value="7" <?php echo $h;?>>Dipolog</option>
	  <option value="8" <?php echo $i;?>>Iloilo</option>
	  <option value="9" <?php echo $j;?>>Cebu</option>
	  <option value="10" <?php echo $k;?>>Roxas</option>
	  </select><label>-</label>

							<?php
								 $query = getThriveListEx($baseid, $thrive);
								 $result = pg_query($dbconn, $query);
							?>

							<select id='districtContent' name='th_area'>
							<option disabled>District</option>
							<option selected><?php echo $thrive?></option>
								<?php
									 while ($row = pg_fetch_row($result)) {
										 echo "<option value='$row[0]'>$row[0]</option>";
									 }
								?>
							</select>


	<br/>
	<label>Address</label>
	<br/>
		<?php
			 $query = getProvinceListEx($province);
			 $result = pg_query($dbconn, $query);
		?>

	<select id="province" name="province" onChange="window.loadCityList()">
	<option disabled>Province</option>
	<option selected><?php echo $province?></option>
		<?php

			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>

		<?php
			 $query = getCityListEx($province, $city);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='provinceContent' name='city' onChange='window.loadBarangayList()'>
	<option disabled>City/Municipality</option>
	<option selected><?php echo $city?></option>
		<?php

			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>

		<?php
			 $query = getBarangayListEx($province,$city,$barangay);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='barangayContent' name='barangay'>
	<option disabled>Barangay</option>
	<option selected><?php echo $barangay?></option>
		<?php

			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<br/>

	<label>Other Info</label><br/>
	<input class="form-control input-sm" placeholder="Street Name, Etc" type="text" name="address" value="<?php echo $address;?>">
	<br/><br/>

<legend>Church Information</legend>
	<label>Name &#40;no abbreviations&#41;</label><br/>
	  <input class="form-control input-sm" placeholder="Church Name" type="text" name="c_name" value="<?php echo $cname;?>">
	<br/>
	<label>Denomination</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Church Denomination" type="text" name="c_denomination" value="<?php echo $denomination;?>">
	<br/>
	<label>Church Position</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Indicate Church Position or Role" type="text" name="position" value="<?php echo $position;?>">
	<br/>

	<?php
			 $query = getProvinceListEx($cprovince);
			 $result = pg_query($dbconn, $query);
	?>

	<label>Address</label><br/>
	<select id="c_province" name="c_province" onChange="window.loadCityList1()">
	<option disabled>Province</option>
	<option selected><?php echo $cprovince?></option>
		<?php
			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<?php
			 $query = getCityListEx($cprovince, $ccity);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='provinceContent1' name='c_city' onChange='window.loadBarangayList1()'>
	<option disabled>City/Municipality</option>
	<option selected><?php echo $ccity?></option>
		<?php

			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>

		<?php
			 $query = getBarangayListEx($cprovince,$ccity,$cbarangay);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='barangayContent1' name='c_barangay'>
	<option disabled>Barangay</option>
	<option selected><?php echo $cbarangay?></option>
		<?php

			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<br/>

	<label>Other Info</label><br/>
	<input class="form-control input-sm" placeholder="Street Name, Etc" type="text" name="c_address" value="<?php echo $caddress;?>">
	<br/>

	<label>Planted Through ICM?</label>
	<br/>
		<?php
			$a="";
			$b="";
			$c="";
		  if($isPlanted=="Yes")
			$b = "selected";
		  else if($isPlanted=="No")
			$c = "selected";
		  else
			$a = "selected";
		 ?>
	  <select id="c_planted" name="c_planted">
	  <option <?php echo $b;?> value="Yes">Yes</option>
	  <option <?php echo $c;?> value="No">No</option>
	  <option <?php echo $a;?> value="Empty">Not Indicated</option>
	  </select>
	<br/>
	<br/>

	<legend>Membership</legend>
	<label>Member</label>
		  <?php
		  $b="";
		  $c="";

		  if($member == "t")
			$b = "selected";
		  else if($member == "f")
			$c = "selected";
		  else
		    $a = "selected";
		  ?>
	<br/>
	<select id="member" name="member">
	<option disabled>Please Choose</option>
	<option <?php echo $b;?> value="t">Member</option>
	<option <?php echo $c;?> value="f">Non-Member</option>
	</select>
		 <br/>

	<label>Membership Date</label><br/>
		  <input class="form-control input-sm" type="date" name="mdate" value="<?php echo $mdate;?>">

	<br/><br/><br/>
	<button class="btn btn-embossed btn-primary" name = "update">Update</button>
</section>
</article>
</form>

<script src='default.js'></script>
<script>
function loadDistrictList()
{
  var formName = 'form1';
	var base = document[formName]['base'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?command=set_district&base='+base, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertDistrict(xmlhttp);
    };
    xmlhttp.send(null);
}

function insertDistrict(xhr){
		if(xhr.status == 200){
				document.getElementById('districtContent').innerHTML = xhr.responseText;
		}else
				throw new Error('Server has encountered an error\n'+
						'Error code = '+xhr.status);
}
</script>
</body>

</html>
