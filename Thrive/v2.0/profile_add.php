<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	include_once "_ptrFunctions.php";
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
	<title>Add</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
<form name='form1' action='' id='profile_form' method='POST'>
<span id="note"></span>
<div class="mdl-grid">
	<div class="mdl-cell mdl-cell--7-col mdl-shadow--2dp" style="background:#E7E4DF;">
	<div id="title">Personal Information</div>
	<table>
		<tr>
			<td id="label_style">Name</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="fname" name='fname' pattern="[A-Z,a-z, ]*" onChange="window.duplicate_lookup()"/>
					<label class="mdl-textfield__label" for="fname">First Name/s</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;" or "." marks!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="mname" name='mname' pattern="[A-Z,a-z, ]*" onChange="window.duplicate_lookup()"/>
					<label class="mdl-textfield__label" for="mname">Middle Name</label>
					<span class="mdl-textfield__error">Alphabetic characters only, no "&ntilde;" or "." marks!</span>
				</div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="lname" name='lname' pattern="[A-Z,a-z, ]*" onChange="window.duplicate_lookup()"/>
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
					<option disabled selected value="Empty">Please Choose</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr><td id="label_style">Birthday</td><td id="content_style"><input class="mdl-select__input" type="date" name="bday" id="bday"></td></tr>
		<tr>
			<td id="label_style" value="Empty">Education Level</td><td id="content_style">
				<select id="education_level" class="mdl-select__input" name="education_level" onChange="showGraduate_input(this.value)">
					<option disabled selected value="Empty">Please Choose</option>
					<option value="None">None</option>
					<option value="Elementary">Elementary</option>
					<option value="High School">High School</option>
					<option value="College">College</option>
					<option value="Post College">Post College</option>
					<option value="Empty">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr id="graduate">
			<td id="label_style">Graduated</td>
			<td id="content_style">
				<select id="education_graduate" class="mdl-select__input" name="education_graduate">
					<option disabled selected value="">Please Choose</option>
					<option value="True">Yes</option>
					<option value="False">No</option>
					<option value="">Not Indicated</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Attended Seminary</td>
			<td id="content_style">
				<select id="seminary" class="mdl-select__input" name="seminary">
					<option disabled selected value="">Please Choose</option>
					<option value="True">Yes</option>
					<option value="False">No</option>
					<option value="">Not Indicated</option>
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
				<option disabled selected value='Empty'>Province</option>
				<?php
					 while ($row=pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>
				<select id='provinceContent' class="mdl-select__input" name='city' onChange='window.loadBarangayList()'>
				<option disabled selected value='Empty'>City/Municipality</option>
				</select>
				<select id='barangayContent' class="mdl-select__input" name='barangay'>
				<option disabled selected value='Empty'>Barangay</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="pastor_address"/>
					<label class="mdl-textfield__label" for="address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Contact Numbers</td>
			<td id="content_style">
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_1" />
			    <label class="mdl-textfield__label" for="contact_1">Contact Number 1</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_2" />
			    <label class="mdl-textfield__label" for="contact_2">Contact Number 2</label>
			    <span class="mdl-textfield__error">Input is not a number!</span>
			  </div>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
			    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" maxlength="11" id="contact_3" />
			    <label class="mdl-textfield__label" for="contact_3">Contact Number 3</label>
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
				<option disabled selected value="0">Please Choose</option>
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
					<input class="mdl-textfield__input" type="text" id="c_name" pattern="[A-Z,a-z,0-9, ]*" onChange="enableChurch_fields(this.value)"/>
					<label class="mdl-textfield__label" for="c_name">Church Name</label>
					<span class="mdl-textfield__error">Alphanumeric only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Denomination</td>
			<td id="content_style">
				<select id="c_denomination" class="mdl-select__input" name="c_denomination" onChange="showSpecify_input(this.value)" disabled>
				<option disabled selected value="0">Please Choose</option>
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
				<option value="11">Evangelical Free Church</option>
				<option value="12">Full Gospel Ministry</option>
				<option value="13">Free Methodist Church</option>
				<option value="14">Foursquare Gospel Church</option>
				<option value="15">IEMELIF</option>
				<option value="16">Iglesia Ebanghelika Unida de Dios (UNIDA)</option>
				<option value="17">Jesus is Lord</option>
				<option value="18">Philippine Missionary Fellowship</option>
				<option value="19">Presbyterian Church</option>
				<option value="20">Southern Baptist Convention</option>
				<option value="21">The Salvation Army</option>
				<option value="22">UCCP</option>
				<option value="23">United Methodist Church</option>
				<option value="24">Wesleyan Church</option>
				<option value="25">Independent or No Affiliation/Denomination</option>
				<option value="99">Others</option>
				</select>
				<div id="specify" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_specify" pattern="[A-Z,a-z, ]*"/>
					<label class="mdl-textfield__label" for="c_specify">Please Specify</label>
					<span class="mdl-textfield__error">Alphabet only, no "&ntilde;"!</span>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Church Position</td>
			<td id="content_style">
				<select id="c_position" class="mdl-select__input" name="c_position">
				<option disabled selected value="0">Please Choose</option>
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
				<select id="c_province" class="mdl-select__input" name="c_province" onChange="window.loadCityList1()" disabled>
				<option disabled selected value="Empty">Province</option>
				<?php
					 while ($row=pg_fetch_row($result)) {
						 echo "<option value='$row[0]'>$row[0]</option>";
					 }
				?>
				</select>
				<select id='provinceContent1' class="mdl-select__input" name='c_city' onChange='window.loadBarangayList1()'>
				<option disabled selected value="Empty">City/Municipality</option>
				</select>
				<select id='barangayContent1' class="mdl-select__input" name='c_barangay'>
				<option disabled selected value="Empty">Barangay</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="label_style">Other Info</td>
			<td id="content_style">
				<div class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="c_address" disabled/>
					<label class="mdl-textfield__label" for="c_address">Street Name, Etc</label>
				</div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Planted Through ICM?</td>
			<td id="content_style">
				<select id="c_planted" class="mdl-select__input" name="c_planted" disabled>
					<option disabled selected value="">Please Choose</option>
					<option value="Yes">Yes</option>
				  <option value="No">No</option>
				  <option value="">Not Indicated</option>
				</select>
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
<br/><br/><br/>
<br/><br/><br/>
</form>

<script type="text/javascript">
	document.getElementById('graduate').hidden=true;
	document.getElementById('specify').hidden=true;

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

	function insertProfile()
	{
	    var formName = 'form1';
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

			if(base == 0) {
				document.getElementById('result').innerHTML = "FAILED: Base cannot be empty.";
			}

			else if(lname == '' || fname == '' || mname == '') {
				document.getElementById('result').innerHTML = "FAILED: Names cannot be empty.";
			}

			else {
				window.location='_action.php?command=add_profile&lname='+lname+
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
				'&username='+username;

				/*var xmlhttp = null;
		    if(typeof XMLHttpRequest != 'udefined'){
		        xmlhttp = new XMLHttpRequest();
		    }else if(typeof ActiveXObject != 'undefined'){
		        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		    }else
		        throw new Error('You browser doesn\'t support ajax');

		    xmlhttp.open('GET',
					'_insertvalues.php?command=add_profile&lname='+lname+
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
		    xmlhttp.send(null);*/
			}
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

	function duplicate_lookup()
	{
    var formName = 'form1';
		var lname = document.getElementById("lname").value;
		var fname = document.getElementById("fname").value;
		var mname = document.getElementById("mname").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?command=check_duplicate_pastor&lname='+lname+'&fname='+fname+'&mname='+mname, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertNote(xmlhttp);
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
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
</body>
</html>
