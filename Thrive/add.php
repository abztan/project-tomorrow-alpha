<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	include "_ptrFunctions.php";

	$lname = "";
	$fname = "";
	$mname = "";
	$gender = "";
	$status = "";
	$bday = "";
	$education = "";
	$seminary = "";
	$province = "";
	$city = "";
	$barangay = "";
	$notice = "";

	if(isset($_POST['add']))
	{
		$lname=ucwords(strtolower($_POST['lname']));
		$fname=ucwords(strtolower($_POST['fname']));
		$mname=ucwords(strtolower($_POST['mname']));

		if(isset($_POST['gender']))
		  $gender=$_POST['gender'];
		else
		  $gender="";

		if(isset($_POST['bday']))
		  $bday=$_POST['bday'];
		else
		  $bday="";

		if(isset($_POST['status']))
		  $status=$_POST['status'];
		else
		  $status="";

		$contact1=$_POST['contact1'];
		$contact2=$_POST['contact2'];
		$contact3=$_POST['contact3'];
		$email=$_POST['email'];

		if(isset($_POST['educ']))
		  $education=$_POST['educ'];
		else
		  $education="";

		if(isset($_POST['seminary']))
		  $seminary=$_POST['seminary'];
		else
		  $seminary="";

		$position=ucwords(strtolower($_POST['position']));
		$th_area=strtoupper($_POST['th_area']);
		if(isset($_POST['province']))
		  $province=$_POST['province'];
		else
		  $province="";
		if(isset($_POST['city']))
		  $city=$_POST['city'];
		else
		  $city="";
		if(isset($_POST['barangay']))
		  $barangay=$_POST['barangay'];
		else
		  $barangay="";

		$address=$_POST['address'];

		$cname=ucwords(strtolower($_POST['c_name']));
		$denomination=ucwords(strtolower($_POST['c_denomination']));

		if(isset($_POST['c_province']))
		  $cprovince=$_POST['c_province'];
		else
		  $cprovince="";

		if(isset($_POST['c_city']))
		  $ccity=$_POST['c_city'];
		else
		  $ccity="";

		if(isset($_POST['c_barangay']))
		  $cbarangay=$_POST['c_barangay'];
		else
		  $cbarangay="";

		  $caddress=$_POST['c_address'];

		if(isset($_POST['c_planted']))
		  $isPlanted=$_POST['c_planted'];
		else
		  $isPlanted="";

		if(isset($_POST['base']))
		  $baseid=$_POST['base'];
		else
		  $baseid="";

		if($lname == "" || $fname == "" || $mname == "" || $gender == "" || $bday == "" || $status == "" || $education  == "" || $seminary == "" || $baseid  == "" || $province == "" || $city == "" || $barangay == "")
		  $notice = "There is a blank required* field.";


		else
		{
			//second level duplication test
			$unique_last = strtolower($lname);
			$unique_last = str_replace('-','', $unique_last);
			$unique_last = preg_replace('/[^A-Za-z0-9\-]/', '', $unique_last);

			$unique_first = strtolower($fname);
			$unique_first = str_replace('-','', $unique_first);
			$unique_first = preg_replace('/[^A-Za-z0-9\-]/', '', $unique_first);

			$unique_mi = strtolower($mname);
			$unique_mi = str_replace(' ','', $unique_mi);
			$unique_mi = substr($unique_mi, 0,1);

			$unique_birthday = $bday;
			$unique_birthday = date("mdY",strtotime(str_replace('-','', $unique_birthday)));

			$unique = $unique_first.$unique_last.$unique_mi.$unique_birthday;
			$uid_check = checkDuplicateByUniqueId($unique);

			$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);

			if($uid_check=="")
			{
				addPastor($lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
				$contact3,$email,$education,$seminary,$position,$th_area,$unique,$username,$baseid);

				if($cid=="")
				{
					addChurch($cname,$denomination,$cprovince,$ccity,$cbarangay,$caddress,$isPlanted,$username);
					$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
					$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);
				}
				else
				{
					$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
				}

				setChurch($pid,$cid);
				addLogPastorProgram($pid);

				$notice = "Entry Successfully Added!";
			}
			else
				$notice="Entry already exists.";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Add</title>
	<link rel="stylesheet" href="/ICM/_css/material.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>
<style type="text/css">
	#title {
		font-family: 'Roboto Thin', sans-serif;
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

	#spike {
		position: sticky;
		background: red;
	}

</style>

<body>
<form name='form1' action='' method='POST'>
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
	<div id="title">Personal Information</div>
	<table>
		<tr>
			<td id="label_style">Name</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="fname" pattern="[A-Z,a-z, ]*"/>
					<label class="mdl-textfield__label" for="fname">First Name/s</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;"!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="mname" pattern="[A-Z,a-z, ]*"/>
					<label class="mdl-textfield__label" for="mname">Middle Name</label>
					<span class="mdl-textfield__error">Alphabetic characters only, no "&ntilde;"!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="lname" pattern="[A-Z,a-z, ]*"/>
					<label class="mdl-textfield__label" for="lname">Last Name</label>
					<span class="mdl-textfield__error">Alphabetic characters only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Status</td>
			<td id="content_style">
				<select id="status" class="mdl-select__input" name="status">
					<option disabled selected>Please Choose</option>
					<option value="Single">Single</option>
					<option value="Married">Married</option>
					<option value="Widowed">Widowed</option>
					<option value="Separated">Separated</option>
					<option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Gender</td>
			<td id="content_style">
				<select id="gender" class="mdl-select__input" name="gender">
					<option disabled selected>Please Choose</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr><td id="label_style">Birthday</td><td id="content_style"><input class="mdl-select__input" type="date" name="bday"></td></tr>
		<tr>
			<td id="label_style">Education Level</td><td id="content_style">
				<select id="educ" class="mdl-select__input" name="educ">
					<option disabled selected>Please Choose</option>
					<option value="None">None</option>
					<option value="Elementary">Elementary</option>
					<option value="High School">High School</option>
					<option value="College">College</option>
					<option value="Post College">Post College</option>
					<option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Graduated</td>
			<td id="content_style">
				<select id="education_graduated" class="mdl-select__input" name="education_graduated">
					<option disabled selected>Please Choose</option>
					<option value="True">Yes</option>
					<option value="False">No</option>
					<option value="Null">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Attended Seminary</td>
			<td id="content_style">
				<select id="seminary" class="mdl-select__input" name="seminary">
					<option disabled selected>Please Choose</option>
					<option value="True">Yes</option>
					<option value="False">No</option>
					<option value="Null">Not Indicated</option>
				</select>
			</td>
		</tr>
		<?php
			 $query=getProvinceList();
			 $result=pg_query($dbconn, $query);
		?>
		<tr>
			<td id="label_style">Location</td>
			<td id="content_style">
				<select id="province" class="mdl-select__input" name="province" onChange="window.loadCityList()">
				<option disabled selected>Province</option>
				<?php
					 while ($row=pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>
				<select id='provinceContent' class="mdl-select__input" name='city' onChange='window.loadBarangayList()'>
				<option disabled selected>City/Municipality</option>
				</select>
				<select id='barangayContent' class="mdl-select__input" name='barangay'>
				<option disabled selected>Barangay</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="address"/>
					<label class="mdl-textfield__label" for="address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Contact Numbers</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact1" />
			    <label class="mdl-textfield__label" for="contact1">Contact Number 1</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact2" />
			    <label class="mdl-textfield__label" for="contact2">Contact Number 2</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact3" />
			    <label class="mdl-textfield__label" for="contact3">Contact Number 2</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Email</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="email" id="email" />
					<label class="mdl-textfield__label" for="email">Email</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Base</td>
			<td id="content_style">
				<select id="base" class="mdl-select__input" name="base" onChange="window.loadDistrictList()">
				<option disabled selected>Please Choose</option>
				<option value="1">Bacolod</option>
				<option value="2">Bohol</option>
				<option value="3">Dumaguete</option>
				<option value="4">General Santos</option>
				<option value="5">Koronadal</option>
				<option value="6">Palawan</option>
				<option value="7">Dipolog</option>
				<option value="8">Iloilo</option>
				<option value="9">Cebu</option>
				<option value="10">Roxas</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Thrive</td>
			<td id="content_style">
				<select id='districtContent' class="mdl-select__input"  name='th_area'>
				<option disabled selected>District</option>
				</select>
			</td>
		</tr>
	</table>
	</div>
</div>

<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--5-col mdl-shadow--2dp" style="background:#FAFAFA;">
		<div id="title">Church Information</div>
		<table>
		<tr>
			<td id="label_style">Church Name</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_name" pattern="[A-Z,a-z, ]*"/>
					<label class="mdl-textfield__label" for="c_name">Church Name</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Denomination</td>
			<td id="content_style">
				<select id="c_denomination" class="mdl-select__input" name="c_denomination">
				<option disabled selected>Please Choose</option>
				<option value="1">Alliance of Bible Christian Communities of the Philippines (ABCCOP)</option>
				<option value="2">Assemblies of God</option>
				<option value="3">Bible Baptist Church</option>
				<option value="4">Christian and Missionary Alliance</option>
				<option value="5">Christian Reformed Church</option>
				<option value="6">Church of God</option>
				<option value="7">Church of the Nazarene</option>
				<option value="8">Conservative Baptist Association </option>
				<option value="9">Convention Baptist</option>
				<option value="10">Converge Philippines/Baptist Conference of the Philippines</option>
				<option value="8">Evangelical Free Church</option>
				<option value="9">Full Gospel Ministry</option>
				<option value="10">Free Methodist Church</option>
				<option value="8">Foursquare Gospel Church</option>
				<option value="9">IEMELIF</option>
				<option value="10">Iglesia Ebanghelika Unida de Dios (UNIDA)</option>
				<option value="8">Jesus is Lord</option>
				<option value="9">Philippine Missionary Fellowship</option>
				<option value="10">Presbyterian Church</option>
				<option value="10">Southern Baptist Convention</option>
				<option value="10">The Salvation Army</option>
				<option value="10">UCCP</option>
				<option value="10">United Methodist Church</option>
				<option value="10">Wesleyan Church</option>
				<option value="10">Independent or No Affiliation/Denomination</option>
				<option value="10">Others</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Church Position</td>
			<td id="content_style">
				<select id="position" class="mdl-select__input" name="position">
				<option disabled selected>Please Choose</option>
				<option value="1">Senior/Resident/Head/Lead Pastor/Minister</option>
				<option value="2">Associate Pastor/Minister</option>
				<option value="3">Assistant Pastor/Minister</option>
				<option value="4">Administrative Pastor/Minister</option>
				<option value="5">Worship Pastor/Minister</option>
				<option value="6">Youth/Children Pastor/Minister</option>
				<option value="7">Church Planting/Outreach/Pioneering/Missionary Pastor/Minister</option>
				<option value="8">Bishop/Coordinator of Denomination</option>
				<option value="9">District/Area Supervisor/Pastor/Minister of Denomination</option>
				</select>
			</td>
		</tr>
		<?php
				 $query=getProvinceList();
				 $result=pg_query($dbconn, $query);
		?>
		<tr>
			<td id="label_style">Location</td>
			<td id="content_style">
				<select id="c_province" class="mdl-select__input" name="c_province" onChange="window.loadCityList1()">
				<option disabled selected>Province</option>
				<?php
					 while ($row=pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>
				<select id='provinceContent1' class="mdl-select__input" name='c_city' onChange='window.loadBarangayList1()'>
				<option disabled selected>City/Municipality</option>
				</select>
				<select id='barangayContent1' class="mdl-select__input" name='c_barangay'>
				<option disabled selected>Barangay</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_address"/>
					<label class="mdl-textfield__label" for="c_address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Planted Through ICM?</td>
			<td id="content_style">
				<select id="c_planted" class="mdl-select__input" name="c_planted">
					<option disabled selected>Please Choose</option>
					<option value="Yes">Yes</option>
				  <option value="No">No</option>
				  <option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		</table>
		<button class="btn btn-embossed btn-primary" type = "submit" name = "add">Done</button>
	</div>
</div>
</form>

<script type="text/javascript">
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

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
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

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
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

	    xmlhttp.open('GET', '_insertvalues.php?base='+base, true);
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

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
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

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
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
</script>
<script src='default.js'></script>
</body>

</html>
