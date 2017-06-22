<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	include "_ptrFunctions.php";

$pastor_pk = $_GET['a'];
$hidden = "hidden";

//box 1
$pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
$pastor = getPastorDetails($pastor_pk);
$first_name = $pastor['firstname'];
$last_name = $pastor['lastname'];
$middle_name = $pastor['middlename'];
$gender = $pastor['gender'];
$status = $pastor['status'];
$bday = $pastor['birthday'];
$education = $pastor['education'];
$graduated = $pastor['education_graduated'];
$seminary = $pastor['seminary'];

$address = $pastor['address'];
$barangay = $pastor['barangay'];
$city = $pastor['city'];
$province = $pastor['province'];
$contact_1 = $pastor['contact1'];
$contact_2 = $pastor['contact2'];
$contact_3 = $pastor['contact3'];
$email = $pastor['email'];
$district_pk = $pastor['thriveid'];
$base_id = $pastor['baseid'];

//box 2
$church_pk = $pastor['churchid'];
if($church_pk != "")
{
	$church = getChurch_details($church_pk);
	$denomination = $church['denomination'];
	$church_name = $church['churchname'];
	$is_planted = $church['plantedbyicm'];
	$church_barangay = $church['barangay'];
	$church_city = $church['city'];
	$church_province = $church['province'];
	$church_address = $church['address'];
}

$position = $pastor['position'];

//box 3
$member = $pastor['member'];
$membership_date = $pastor['membershipdate'];
?>

<style type="text/css">
	#title {
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		font-size:24px;
		color: #F4511E;
		padding: 20px 16px 24px 16px;
	}

	#label_style {
		display: inline-block;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 15px;
		font-weight: 700;
		color: #a1a1a1;
		padding: 12px 12px 12px 36px;
		vertical-align: top;
		text-align: right;
		width: 136px;
		background: ;
		height: automatic;
	}
	tr {
		background: ;
	}
	#input_style {
		width: 160px;
	}

	#content_style {
		display: inline-block;
		font-family: 'Roboto', sans-serif;
		font-size:14px;
		font-weight: 400;
		color: #212121;
		padding-left: 24px;
		min-height: 60px;
		padding-bottom: 0;
	}

	.mdl-select__input {
	  border: none;
	  border-bottom: 1px solid rgba(0,0,0, 0.12);
	  display: inline-block;
	  font-size: 14px;
	  margin: 0;
	  padding: 4px 0;
	  width: 160px;
	  background: 14px;
	  text-align: left;
	  color: inherit;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Edit Profile</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
<form name='form1' action='' id='profile_form' method='POST'>
<span id="notice"></span>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--7-col mdl-shadow--2dp" style="background:#E7E4DF;">
	<div id="title">Personal Information</div>
	<table>
		<tr>
			<td id="label_style">Name</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="fname" name='fname' pattern="[A-Z,a-z, ]*" value="<?php echo $first_name;?>"/>
					<label class="mdl-textfield__label" for="fname">First Name/s</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;" or "." marks!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="mname" name='mname' pattern="[A-Z,a-z, ]*" value="<?php echo $middle_name;?>"/>
					<label class="mdl-textfield__label" for="mname">Middle Name</label>
					<span class="mdl-textfield__error">Alphabetic characters only, no "&ntilde;" or "." marks!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="lname" name='lname' pattern="[A-Z,a-z, ]*" value="<?php echo $last_name;?>"/>
					<label class="mdl-textfield__label" for="lname">Last Name</label>
					<span class="mdl-textfield__error">Alphabetic characters only, no "&ntilde;" or "." marks!</span>
				</div>
				<i id="note" style="color:rgb(222, 50, 38);"></i>
			</td>
		</tr>
		<tr>
			<td id="label_style">Status</td>
			<td id="content_style">
				<select id="status" class="mdl-select__input" name="status">
					<option disabled selected value="Empty">Please Choose</option>
					<option <?php if($status == "Single") echo "selected"; ?> value="Single">Single</option>
					<option <?php if($status == "Married") echo "selected"; ?> value="Married">Married</option>
					<option <?php if($status == "Widowed") echo "selected"; ?> value="Widowed">Widowed</option>
					<option <?php if($status == "Separated") echo "selected"; ?> value="Separated">Separated</option>
					<option <?php if($status == "Empty") echo "selected"; ?> value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Gender</td>
			<td id="content_style">
				<select id="gender" class="mdl-select__input" name="gender">
					<option disabled selected value="Empty">Please Choose</option>
					<option <?php if($gender == "Male") echo "selected"; ?> value="Male">Male</option>
					<option <?php if($gender == "Female") echo "selected"; ?> value="Female">Female</option>
					<option <?php if($gender == "Empty") echo "selected"; ?> value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Birthday</td>
			<td id="content_style"><input class="mdl-select__input" type="date" name="bday" id="bday" value="<?php echo $bday;?>"></td>
		</tr>
		<tr>
			<td id="label_style" value="Empty">Education Level</td><td id="content_style">
				<select id="education_level" class="mdl-select__input" name="education_level" onChange="showGraduate_input(this.value)">
					<option disabled selected value="Empty">Please Choose</option>
					<option <?php if($education == "None") echo "selected"; ?> value="None">None</option>
					<option <?php if($education == "Elementary") echo "selected"; ?> value="Elementary">Elementary</option>
					<option <?php if($education == "High School") echo "selected"; ?> value="High School">High School</option>
					<option <?php if($education == "College") echo "selected"; ?> value="College">College</option>
					<option <?php if($education == "Post College") echo "selected"; ?> value="Post College">Post College</option>
					<option <?php if($education == "Empty") echo "selected"; ?> value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr id="graduate">
			<td id="label_style">Graduated</td>
			<td id="content_style">
				<select id="education_graduate" class="mdl-select__input" name="education_graduate">
					<option disabled selected value="">Please Choose</option>
					<option <?php if($graduated == "t") echo "selected"; ?> value="True">Yes</option>
					<option <?php if($graduated == "f") echo "selected"; ?> value="False">No</option>
					<option <?php if($graduated == "") echo "selected"; ?> value="">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Attended Seminary</td>
			<td id="content_style">
				<select id="seminary" class="mdl-select__input" name="seminary">
					<option disabled selected value="">Please Choose</option>
					<option <?php if($seminary == "t") echo "selected"; ?> value="True">Yes</option>
					<option <?php if($seminary == "f") echo "selected"; ?> value="False">No</option>
					<option <?php if($seminary == "") echo "selected"; ?> value="">Not Indicated</option>
				</select>
			</td>
		</tr>

		<tr>
			<td id="label_style">Location</td>
			<td id="content_style">
				<?php
					 $query = getProvinceListEx($province);
					 $result = pg_query($dbconn, $query);
				?>
				<select id="province" class="mdl-select__input" name="province" onChange="window.loadCityList()">
				<option disabled>Province</option>
				<option selected value="<?php echo $province?>"><?php echo $province?></option>
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
				<select id='provinceContent' class="mdl-select__input" name='city' onChange='window.loadBarangayList()'>
				<option disabled>City/Municipality</option>
				<option selected value="<?php echo $city?>"><?php echo $city?></option>
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
				<select id='barangayContent' class="mdl-select__input" name='barangay'>
				<option disabled>Barangay</option>
				<option selected value="<?php echo $barangay?>"><?php echo $barangay?></option>
				<?php
					 while ($row = pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="pastor_address" value="<?php echo $address;?>"/>
					<label class="mdl-textfield__label" for="address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Contact Numbers</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_1" value="<?php echo $contact_1;?>"/>
			    <label class="mdl-textfield__label" for="contact_1">Contact Number 1</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_2" value="<?php echo $contact_2;?>"/>
			    <label class="mdl-textfield__label" for="contact_2">Contact Number 2</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_3" value="<?php echo $contact_3;?>"/>
			    <label class="mdl-textfield__label" for="contact_3">Contact Number 3</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Email</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="email" id="email" value="<?php echo $email;?>"/>
					<label class="mdl-textfield__label" for="email">Email</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Base</td>
			<td id="content_style">
				<select id="base" class="mdl-select__input" name="base" onChange="window.loadDistrictList()">
				<option disabled selected value="0">Please Choose</option>
				<option <?php if($base_id == "1") echo "selected"; ?> value="1">Bacolod</option>
				<option <?php if($base_id == "2") echo "selected"; ?> value="2">Bohol</option>
				<option <?php if($base_id == "3") echo "selected"; ?> value="3">Dumaguete</option>
				<option <?php if($base_id == "4") echo "selected"; ?> value="4">General Santos</option>
				<option <?php if($base_id == "5") echo "selected"; ?> value="5">Koronadal</option>
				<option <?php if($base_id == "6") echo "selected"; ?> value="6">Palawan</option>
				<option <?php if($base_id == "7") echo "selected"; ?> value="7">Dipolog</option>
				<option <?php if($base_id == "8") echo "selected"; ?> value="8">Iloilo</option>
				<option <?php if($base_id == "9") echo "selected"; ?> value="9">Cebu</option>
				<option <?php if($base_id == "10") echo "selected"; ?> value="10">Roxas</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Thrive</td>
			<td id="content_style">
				<?php
					 $query = getThriveListEx($base_id, $district_pk);
					 $result = pg_query($dbconn, $query);
				?>

				<select id='districtContent' class="mdl-select__input" name='th_area'>
				<option disabled>District</option>
				<option selected><?php echo $district_pk?></option>
					<?php
						 while ($row = pg_fetch_row($result)) {
							 echo "<option value='$row[0]'>$row[0]</option>";
						 }
					?>
				</select>
			</td>
		</tr>
	</table>
	<br/>
	</div>
</div>

<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--7-col mdl-shadow--2dp" style="background:#E7E4DF;">
		<div id="title">Church Information</div>
		<table>
		<tr>
			<td id="label_style">Church Name</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_name" pattern="[A-Z,a-z,0-9, ]*" value="<?php echo $church_name;?>"/>
					<label class="mdl-textfield__label" for="c_name">Church Name</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Denomination</td>
			<td id="content_style">
				<select id="c_denomination" class="mdl-select__input" name="c_denomination" onChange="showSpecify_input(this.value)">
				<option <?php if($denomination == "0") echo "selected";?> disabled value="0">Please Choose</option>
				<option <?php if($denomination == "1") echo "selected";?> value="1">Alliance of Bible Christian Communities of the Philippines (ABCCOP)</option>
				<option <?php if($denomination == "2") echo "selected";?> value="2">Assemblies of God</option>
				<option <?php if($denomination == "3") echo "selected";?> value="3">Bible Baptist Church</option>
				<option <?php if($denomination == "4") echo "selected";?> value="4">Christian and Missionary Alliance</option>
				<option <?php if($denomination == "5") echo "selected";?> value="5">Christian Reformed Church</option>
				<option <?php if($denomination == "6") echo "selected";?> value="6">Church of God</option>
				<option <?php if($denomination == "7") echo "selected";?> value="7">Church of the Nazarene</option>
				<option <?php if($denomination == "8") echo "selected";?> value="8">Conservative Baptist Association </option>
				<option <?php if($denomination == "9") echo "selected";?> value="9">Convention Baptist</option>
				<option <?php if($denomination == "10") echo "selected";?> value="10">Converge Philippines/Baptist Conference of the Philippines</option>
				<option <?php if($denomination == "11") echo "selected";?> value="11">Evangelical Free Church</option>
				<option <?php if($denomination == "12") echo "selected";?> value="12">Full Gospel Ministry</option>
				<option <?php if($denomination == "13") echo "selected";?> value="13">Free Methodist Church</option>
				<option <?php if($denomination == "14") echo "selected";?> value="14">Foursquare Gospel Church</option>
				<option <?php if($denomination == "15") echo "selected";?> value="15">IEMELIF</option>
				<option <?php if($denomination == "16") echo "selected";?> value="16">Iglesia Ebanghelika Unida de Dios (UNIDA)</option>
				<option <?php if($denomination == "17") echo "selected";?> value="17">Jesus is Lord</option>
				<option <?php if($denomination == "18") echo "selected";?> value="18">Philippine Missionary Fellowship</option>
				<option <?php if($denomination == "19") echo "selected";?> value="19">Presbyterian Church</option>
				<option <?php if($denomination == "20") echo "selected";?> value="20">Southern Baptist Convention</option>
				<option <?php if($denomination == "21") echo "selected";?> value="21">The Salvation Army</option>
				<option <?php if($denomination == "22") echo "selected";?> value="22">UCCP</option>
				<option <?php if($denomination == "23") echo "selected";?> value="23">United Methodist Church</option>
				<option <?php if($denomination == "24") echo "selected";?> value="24">Wesleyan Church</option>
				<option <?php if($denomination == "25") echo "selected";?> value="25">Independent or No Affiliation/Denomination</option>
				<option <?php if(is_string($denomination) == "true" || $denomination > 25) { echo "selected"; $hidden="";}?> value="99">Others</option>
				</select>
				<div id="specify" class="mdl-textfield mdl-js-textfield" <?php echo $hidden;?>>
					<input class="mdl-textfield__input" type="text" id="c_specify" pattern="[A-Z,a-z, ]*" value="<?php echo $denomination;?>"/>
					<label class="mdl-textfield__label" for="c_specify">Please Specify</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Church Position</td>
			<td id="content_style">
				<select id="c_position" class="mdl-select__input" name="c_position">
				<option <?php if($position == "0" || is_string($position) == "true") echo "selected";?> disabled value="0">Please Choose</option>
				<option <?php if($position == "1") echo "selected";?> value="1">Senior/Resident/Head/Lead Pastor/Minister</option>
				<option <?php if($position == "2") echo "selected";?> value="2">Associate Pastor/Minister</option>
				<option <?php if($position == "3") echo "selected";?> value="3">Assistant Pastor/Minister</option>
				<option <?php if($position == "4") echo "selected";?> value="4">Administrative Pastor/Minister</option>
				<option <?php if($position == "5") echo "selected";?> value="5">Worship Pastor/Minister</option>
				<option <?php if($position == "6") echo "selected";?> value="6">Youth/Children Pastor/Minister</option>
				<option <?php if($position == "7") echo "selected";?> value="7">Church Planting/Outreach/Pioneering/Missionary Pastor/Minister</option>
				<option <?php if($position == "8") echo "selected";?> value="8">Bishop/Coordinator of Denomination</option>
				<option <?php if($position == "9") echo "selected";?> value="9">District/Area Supervisor/Pastor/Minister of Denomination</option>
				</select>
			</td>
		</tr>
		<?php
			 $query = getProvinceListEx($church_province);
			 $result = pg_query($dbconn, $query);
		?>
		<tr>
			<td id="label_style">Location</td>
			<td id="content_style">
				<select id="c_province" class="mdl-select__input" name="c_province" onChange="window.loadCityList1()">
				<option disabled>Province</option>
				<option selected><?php echo $church_province?></option>
				<?php
					 while ($row=pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>

				<?php
					 $query = getCityListEx($church_province, $church_city);
					 $result = pg_query($dbconn, $query);
				?>

				<select id='provinceContent1' class="mdl-select__input" name='c_city' onChange='window.loadBarangayList1()'>
				<option disabled>City/Municipality</option>
				<option selected><?php echo $church_city?></option>
					<?php

						 while ($row = pg_fetch_row($result)) {
							 echo "<option value='$row[0]'>$row[0]</option>";
						 }
					?>
				</select>

				<?php
					 $query = getBarangayListEx($church_province,$church_city,$church_barangay);
					 $result = pg_query($dbconn, $query);
				?>

				<select id='barangayContent1' class="mdl-select__input" name='c_barangay'>
				<option disabled>Barangay</option>
				<option selected><?php echo $church_barangay?></option>
					<?php

						 while ($row = pg_fetch_row($result)) {
							 echo "<option value='$row[0]'>$row[0]</option>";
						 }
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_address" value="<?php echo $church_address;?>"/>
					<label class="mdl-textfield__label" for="c_address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Planted Through ICM?</td>
			<td id="content_style">
				<select id="c_planted" class="mdl-select__input" name="c_planted">
					<option disabled>Please Choose</option>
					<option <?php if($is_planted == "Yes") echo "selected";?> value="Yes">Yes</option>
				  <option <?php if($is_planted == "No") echo "selected";?> value="No">No</option>
				  <option <?php if($is_planted == "") echo "selected";?> value="">Not Indicated</option>
				</select>
			</td>
		</tr>
		</table>
		<br/>
	</div>
</div>

<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--7-col mdl-shadow--2dp" style="background:#E7E4DF;">
		<div id="title">Membership Information</div>
		<table>
		<tr>
			<td id="label_style">Member</td>
			<td id="content_style">
				<select id="member" class="mdl-select__input" name="member">
					<option disabled selected value="">Please Choose</option>
					<option <?php if($member == "t") echo "selected";?> value="t">Member</option>
					<option <?php if($member == "f") echo "selected";?> value="f">Non-Member</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Membership Date</td>
			<td id="content_style">
				<input class="mdl-select__input" type="date"  name="mdate" value="<?php echo $membership_date;?>">
			</td>
		</tr>
		</table>
		<br/>

		<div style="padding: 0 16 16 0;" align="right">
			<i id="result" style="color:#E68A2E;"></i>&nbsp;
			<a class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onClick="insertProfile()">
				<i class="material-icons">check</i>
			</a>
		</div>
	</div>
</div>
</form>
<br/><br/>

<script type="text/javascript">
	document.getElementById('graduate').hidden=false;

	function showGraduate_input(a) {
		if(a == "Elementary" || a == "High School" || a == "College" || a == "Post College") {
			document.getElementById('graduate').hidden=false;
		}
		else {
			document.getElementById('graduate').hidden=true;
		}
	}

	function enableChurch_fields(a) {
		if(a != '') {
			document.getElementById('c_denomination').disabled=false;
			document.getElementById('c_province').disabled=false;
			document.getElementById('c_address').disabled=false;
			document.getElementById('c_planted').disabled=false;
		}
	}

	function showSpecify_input(a) {
		if(a == 99) {
			document.getElementById('specify').hidden=false;
		}
		else {
			document.getElementById('specify').hidden=true;
		}
	}

	function updateProfile()
	{
	    var formName = 'form1';
			var pastor_pk = "<?php echo $pastor_pk;?>";
			var lname = document.getElementById("lname").value;
			var fname = document.getElementById("fname").value;
			var mname = document.getElementById("mname").value;
			var status = document.getElementById("status").value;
			var gender = document.getElementById("gender").value;
			var bday = document.getElementById("bday").value;
			var education_level = document.getElementById("education_level").value;
			var education_graduate = document.getElementById("education_graduate").value;
			var seminary = document.getElementById("seminary").value;
			var pastor_province = document.getElementById("province").value;
			var pastor_city = document.getElementById("provinceContent").value;
			var pastor_barangay = document.getElementById("barangayContent").value;
			var pastor_address = document.getElementById("pastor_address").value;
			var contact_1 = document.getElementById("contact_1").value;
			var contact_2 = document.getElementById("contact_2").value;
			var contact_3 = document.getElementById("contact_3").value;
			var email = document.getElementById("email").value;
			var base = document.getElementById("base").value;
			var district = document.getElementById("districtContent").value;
			var church = document.getElementById("c_name").value;
			var denom = document.getElementById("c_denomination").value;
			var specify = document.getElementById("c_specify").value;
			var position = document.getElementById("c_position").value;
			var church_province = document.getElementById("c_province").value;
			var church_city = document.getElementById("provinceContent1").value;
			var church_barangay = document.getElementById("barangayContent1").value;
			var church_address = document.getElementById("c_address").value;
			var planted = document.getElementById("c_planted").value;
			var username = "<?php echo $username;?>";

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET',
				'_insertvalues.php?command=update_profile&pastor_pk='+pastor_pk+
				'&lname='+lname+
				'&fname='+fname+
				'&mname='+mname+
				'&status='+status+
				'&gender='+gender+
				'&bday='+bday+
				'&ed_lvl='+education_level+
				'&ed_grd='+education_graduate+
				'&sem='+seminary+
				'&pa_prv='+pastor_province+
				'&pa_cty='+pastor_city+
				'&pa_bgy='+pastor_barangay+
				'&pa_add='+pastor_address+
				'&cnt_1='+contact_1+
				'&cnt_2='+contact_2+
				'&cnt_3='+contact_3+
				'&base='+base+
				'&dist='+district+
				'&email='+email+
				'&church='+church+
				'&denom='+denom+
				'&d_spec='+specify+
				'&position='+position+
				'&ch_prv='+church_province+
				'&ch_cty='+church_city+
				'&ch_bgy='+church_barangay+
				'&ch_add='+church_address+
				'&planted='+planted+
				'&username='+username, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.returnResult(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadCityList()
	{
	    var formName = 'form1';
	    var province = document[formName]['province'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?command=get_city&province='+province, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertProvince(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadBarangayList()
	{
    var formName = 'form1';
		var province = document[formName]['province'].value;
    var city = document[formName]['city'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?command=get_barangay&province='+province+'&city='+city, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertBarangay(xmlhttp);
    };
    xmlhttp.send(null);
	}

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

	function loadCityList1()
	{
	    var formName = 'form1';
	    var province = document[formName]['c_province'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?command=get_city&province='+province, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertProvince1(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadBarangayList1()
	{
	    var formName = 'form1';
		var province = document[formName]['c_province'].value;
	    var city = document[formName]['c_city'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?command=get_barangay&province='+province+'&city='+city, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertBarangay1(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function insertProvince(xhr){
	    if(xhr.status == 200){
	        document.getElementById('provinceContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertBarangay(xhr){
	    if(xhr.status == 200){
	        document.getElementById('barangayContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertNote(xhr){
	    if(xhr.status == 200){
	        document.getElementById('note').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertDistrict(xhr){
	    if(xhr.status == 200){
	        document.getElementById('districtContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertProvince1(xhr){
	    if(xhr.status == 200){
	        document.getElementById('provinceContent1').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertBarangay1(xhr){
	    if(xhr.status == 200){
	        document.getElementById('barangayContent1').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function returnResult(xhr){
	    if(xhr.status == 200){
	        document.getElementById('result').innerHTML = xhr.responseText;
					document.getElementById("profile_form").reset();
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
</body>
</html>
