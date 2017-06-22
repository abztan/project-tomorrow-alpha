<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";

  include_once "_tnsFunctions.php";
  $application_pk = $_GET['a'];
  $patient_pk = $_GET['b'];
  $gender_m = "";
  $gender_f = "";
	$recumbent_t = "";
	$recumbent_f = "";
	$bf_t = "";
	$bf_f = "";

  $application = getApplication_Data_byID($application_pk);
  $patient = getHBF_patient_details($patient_pk);
  $query = getParticipant_forApplication_byTag($application_pk,"5","participant_id","=");

  if($patient['gender'] == 1) {
    $gender_m = "checked";
  }
  else if($patient['gender'] == 2) {
    $gender_f = "checked";
  }

  if($patient['recumbent'] == "t") {
    $recumbent_t = "checked";
  }
  else if($patient['recumbent'] == "f") {
    $recumbent_f = "checked";
  }

  if($patient['breast_feed'] == "t") {
    $bf_t = "checked";
  }
  else if($patient['breast_feed'] == "f") {
    $bf_f = "checked";
  }

	$discharge_status = $patient['discharge_status'];
	$discharge_week = $patient['discharge_week'];
?>
<style>
body {
  padding: 10 10 10 10;
}
fieldset {
  width: 450px;
  padding: 5 5 5 5;
  margin: 5 5 5 5;
}
</style>

<html>
<button name="refresh" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Refresh Page" onclick="location.reload()"><i class="material-icons">refresh</i></button>
<h3>Update HBF Entry</h3>
<!--Community ID: <?php echo $application['community_id']; ?><br/><br/>-->

<fieldset>
Child<br/>
ID:<?php echo $patient['id'];?><br/>
First Name:<input type="text" id="child_fname" value="<?php echo $patient['first_name'];?>"/><br/>
Last name:<input type="text" id="child_lname" value="<?php echo $patient['last_name'];?>"/><br/>
Date of Birth:<input class="mdl-select__input" type="date" min="" max="<?php echo date("Y-m-d");?>" name="bday" id="bday" value="<?php echo $patient['birthday'];?>" onchange="check_age(); abrakadabra();"><br/>
Sex:
  <label class="mdl-radio mdl-js-radio" for="sex-1">
    <input type="radio" id="sex-1" class="mdl-radio__button" name="sex" value="1" <?php echo $gender_m;?> onchange="abrakadabra()">
    <span class="mdl-radio__label">Male</span>
  </label>&nbsp;&nbsp;&nbsp;
  <label class="mdl-radio mdl-js-radio" for="sex-2">
    <input type="radio" id="sex-2" class="mdl-radio__button" name="sex" value="2" <?php echo $gender_f;?> onchange="abrakadabra()">
    <span class="mdl-radio__label">Female</span>
  </label></fieldset>
<br/>

<fieldset>
Guardian<br/>
<select id="guardian_id" class="mdl-select__input" name="guardian_id" onchange="isGuest(this.value)">
  <?php
    $g_selected = "";
    if($patient['fk_participant_pk'] == -99) {
      $g_fname = $patient['guardian_fname'];
      $g_lname = $patient['guardian_lname'];
      echo $g_selected = "selected";
    }
  ?>
  <option <?php echo $g_selected;?> value="-99">Guest</option>
  <?php
    while($participant=pg_fetch_array($query,NULL,PGSQL_BOTH)){
      $participant_pk = $participant['id'];
      $participant_id = $participant['participant_id'];
      $participant_name = $participant['last_name'].", ".$participant['first_name'];
      if($participant_pk == $patient['fk_participant_pk'])
        $g_selected = "selected";
      echo "<option $g_selected value='$participant_pk'>($participant_id) $participant_name</option>";
      $g_selected = "";
    }
  ?>
</select>
<div id="guardian_guest_show">
  First Name: <input class="mdl-textfield__input" type="text" pattern="[A-Z,a-z, ]*" value="<?php echo ($patient['fk_participant_pk'] == -99) ? $g_fname : "";?>" id="guardian_fname" /><br/>
  Last Name: <input class="mdl-textfield__input" type="text" pattern="[A-Z,a-z, ]*" value="<?php echo ($patient['fk_participant_pk'] == -99) ? $g_lname : "";?>" id="guardian_lname" />
</div>
</fieldset>

<br/>
<fieldset>
Initial Data<br/>
<label>Week:</label>
<select id="week_entry" class="mdl-select__input" name="week_entry">
  <option disabled selected value="<?php echo $patient['week_entry'];?>">Week <?php echo $patient['week_entry'];?></option>
  <option value="2">Week 2</option>
  <option value="3">Week 3</option>
  <option value="4">Week 4</option>
  <option value="5">Week 5</option>
  <option value="6">Week 6</option>
  <option value="7">Week 7</option>
  <option value="8">Week 8</option>
  <option value="9">Week 9</option>
  <option value="10">Week 10</option>
  <option value="11">Week 11</option>
  <option value="12">Week 12</option>
  <option value="13">Week 13</option>
</select><br/>
<label>Date of Weighing</label><input type="date" name="weight_date" max="<?php echo date("Y-m-d");?>" id="weight_date" value="<?php echo $patient['initial_weight_date'];?>" onchange="check_age(); abrakadabra();"><br/>
<label>Weight:</label><input type="number" step="0.01" id="weight_value" value="<?php echo $patient['initial_weight'];?>" onchange="abrakadabra()"/><br/>
<label>Height:</label><input type="number" step="0.01" id="height_value" value="<?php echo $patient['initial_height'];?>" onchange="abrakadabra()"/><br/>
<div id= "recumbent_ask">
<label id="label_style">Recumbent?</label>
	<label class="mdl-radio mdl-js-radio" for="recumbent-1">
		<input type="radio" id="recumbent-1" class="mdl-radio__button" name="recumbent" <?php echo $recumbent_t;?>  value="t" >
		<span class="mdl-radio__label">Yes</span>
	</label>&nbsp;&nbsp;&nbsp;
	<label class="mdl-radio mdl-js-radio" for="recumbent-2">
		<input type="radio" id="recumbent-2" class="mdl-radio__button" name="recumbent" <?php echo $recumbent_f;?>  value="f">
		<span class="mdl-radio__label">No</span>
	</label>
</div>
<div id= "breast_feed_ask">
	<label id="label_style">Breastfeeding?</label>
		<label class="mdl-radio mdl-js-radio" for="breast_feed-1">
			<input type="radio" id="breast_feed-1" class="mdl-radio__button" <?php echo $bf_t;?>  name="breast_feed" value="t">
			<span class="mdl-radio__label">Yes</span>
		</label>&nbsp;&nbsp;
		<label class="mdl-radio mdl-js-radio" for="breast_feed-2">
			<input type="radio" id="breast_feed-2" class="mdl-radio__button" <?php echo $bf_f;?>  name="breast_feed" value="f">
			<span class="mdl-radio__label">No</span>
		</label>
</div>
<span id="wasting_score"></span><br/>
</fieldset>
<br/>
<fieldset>
Additional Info<br/>
<label>Watsi ID:</label><input type="text" id="watsi_id" maxlength="5" value="<?php echo $patient['watsi_id'];?>"/><br/>
Tag: <?php echo $patient['tag'];?><br/>
Last Updated By: <?php echo $patient['updated_by'];?><br/>
Last Updated Date: <?php echo $patient['updated_date'];?><br/>
Age During Weight Date: <?php echo $weighing_age = computeHBF_age($patient['initial_weight_date'],$patient['birthday']);?>
</fieldset>
<br/>
<fieldset>
Discharge Patient
<select id="discharge_status" class="mdl-select__input" name="discharge_status">
	<option disabled selected value="0">(Select One)</option>
	<option <?php echo ("1" == $discharge_status ? "selected" : "" );?> value="1">RT - Refused Treated</option>
	<option <?php echo ("2" == $discharge_status ? "selected" : "" );?> value="2">R - Relocated</option>
	<option <?php echo ("3" == $discharge_status ? "selected" : "" );?> value="3">C - Cured</option>
	<option <?php echo ("4" == $discharge_status ? "selected" : "" );?> value="4">D - Defaulter</option>
	<option <?php echo ("5" == $discharge_status ? "selected" : "" );?> value="5">X - Died</option>
</select>
<br/>
Discharge Week
<select id="discharge_week" class="mdl-select__input" name="discharge_week">
	<option disabled selected value="0">(Select One)</option>
	<option <?php echo ("2" == $discharge_week ? "selected" : "" );?> value='2'>Week 2</option>
	<option <?php echo ("3" == $discharge_week ? "selected" : "" );?> value='3'>Week 3</option>
	<option <?php echo ("4" == $discharge_week ? "selected" : "" );?> value='4'>Week 4</option>
	<option <?php echo ("5" == $discharge_week ? "selected" : "" );?> value='5'>Week 5</option>
	<option <?php echo ("6" == $discharge_week ? "selected" : "" );?> value='6'>Week 6</option>
	<option <?php echo ("7" == $discharge_week ? "selected" : "" );?> value='7'>Week 7</option>
	<option <?php echo ("8" == $discharge_week ? "selected" : "" );?> value='8'>Week 8</option>
	<option <?php echo ("9" == $discharge_week ? "selected" : "" );?> value='9'>Week 9</option>
	<option <?php echo ("10" == $discharge_week ? "selected" : "" );?> value='10'>Week 10</option>
	<option <?php echo ("11" == $discharge_week ? "selected" : "" );?> value='11'>Week 11</option>
	<option <?php echo ("12" == $discharge_week ? "selected" : "" );?> value='12'>Week 12</option>
	<option <?php echo ("13" == $discharge_week ? "selected" : "" );?> value='13'>Week 13</option>
	<option <?php echo ("14" == $discharge_week ? "selected" : "" );?> value='14'>Week 14</option>
	<option <?php echo ("15" == $discharge_week ? "selected" : "" );?> value='15'>Week 15</option>
	<option <?php echo ("16" == $discharge_week ? "selected" : "" );?> value='16'>Week 16</option>
</select>
</fieldset>
<br/>
<button class="btn btn-embossed btn-primary" onclick="back()">Back</button>
<button class="btn btn-embossed btn-primary" onclick="updatePatient()">Update</button>
<span id='note'></span>
</html>

<script>
window.onload = abrakadabra(); check_age();
window.onload = isGuest(document.getElementById("guardian_id").value);

function back() {
	location.href = "hbf.php?a=<?php echo $application_pk;?>";
}

function check_age() {
	var dow = document.getElementById("weight_date").value;
	var dob = document.getElementById("bday").value;
	var w_year = dow.substring(0,4);
	var w_month = dow.substring(5,7);
	var w_day = dow.substring(8);
	var b_year = dob.substring(0,4);
	var b_month = dob.substring(5,7);
	var b_day = dob.substring(8);
	var age_year = (w_year-b_year)*12;
	var age_month = w_month-b_month;
	var age_day = w_day - b_day;

	if(age_day > 15) {
		age_month++;
	}

	age = age_year + age_month;

	if(age == -1)
		age = 0;

	if(age < 24) {
		document.getElementById('breast_feed_ask').style.display = "";
		document.getElementById('recumbent_ask').style.display = "";
	}
	else {
		document.getElementById('breast_feed_ask').style.display = "none";
		document.getElementById('recumbent_ask').style.display = "none";
	}
}

function abrakadabra() {
	var application_pk = '<?php echo $application_pk;?>';
  if(document.getElementById("sex-1").checked) {
    var sex = 1;
  }
  else if(document.getElementById("sex-2").checked) {
    var sex = 2;
  }
  var weight_value = document.getElementById("weight_value").value;
  var height_value = document.getElementById("height_value").value;
  var bday = document.getElementById("bday").value;
  var wday = document.getElementById("weight_date").value;

  if(weight_value != "" && height_value != "" && height_value >= 45) {
  	var xmlhttp = null;
  	if(typeof XMLHttpRequest != 'udefined'){
  			xmlhttp = new XMLHttpRequest();
  	}else if(typeof ActiveXObject != 'undefined'){
  			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  	}else
  			throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_action.php?command=compute_w_score&bday='+bday+'&wday='+wday+'&sex='+sex+'&w_value='+weight_value+'&h_value='+height_value+'&application_pk='+application_pk, true);
  	xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
  		window.returnResult(xmlhttp);
    };
    xmlhttp.send(null);
  }
}

function returnResult(xhr){
    if(xhr.status == 200){
      var result = document.getElementById('wasting_score').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function isGuest(guardian_value) {
	if(guardian_value=="-99") {
		document.getElementById('guardian_guest_show').style.display = "";
	}
	else {
		document.getElementById('guardian_guest_show').style.display = "none";
	}
}

function updatePatient()
{
  var patient_pk = "<?php echo $patient_pk;?>";
  var week_entry = document.getElementById("week_entry").value;
  var participant_pk = document.getElementById("guardian_id").value;
  var child_fname = document.getElementById("child_fname").value;
  var child_lname = document.getElementById("child_lname").value;
  var bday = document.getElementById("bday").value;

  if(document.getElementById("sex-1").checked) {
    var sex = 1;
  }
  else if(document.getElementById("sex-2").checked) {
    var sex = 2;
  }
  if(document.getElementById("recumbent-1").checked) {
    var recumbent = "t";
  }
  else if(document.getElementById("recumbent-2").checked) {
    var recumbent = "f";
  }
  if(document.getElementById("breast_feed-1").checked) {
    var breast_feed = "t";
  }
  else if(document.getElementById("breast_feed-2").checked) {
    var breast_feed = "f";
  }

  var weight_date = document.getElementById("weight_date").value;
  var weight_value = document.getElementById("weight_value").value;
  var height_value = document.getElementById("height_value").value;
  var watsi_id = document.getElementById("watsi_id").value;
  var discharge_status = document.getElementById("discharge_status").value;
  var discharge_week = document.getElementById("discharge_week").value;

	if(participant_pk == "-99") {
		var guardian_fname = document.getElementById("guardian_fname").value;
	  var guardian_lname = document.getElementById("guardian_lname").value;
	}
	else {
		var guardian_fname = "";
	  var guardian_lname = "";
	}

  var application_pk = "<?php echo $application_pk;?>";
  var username = "<?php echo $username;?>";

	if (discharge_status > 0 && discharge_week == 0) {
		alert("A discharge week must be selected.");
	}
	else {
		if (child_fname == "" || child_lname == "" || bday == "" || weight_date == "" || weight_value == "" || height_value == "" ) {
	    alert("Opps, you seemed to have left a field blank.");
	  }

	  else {
	    var xmlhttp = null;
	  	if(typeof XMLHttpRequest != 'udefined'){
	  			xmlhttp = new XMLHttpRequest();
	  	}else if(typeof ActiveXObject != 'undefined'){
	  			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	  	}else
	  			throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_action.php?command=update_patient&week_entry='+week_entry+
	    '&participant_pk='+participant_pk+
	    '&child_fname='+child_fname+
	    '&child_lname='+child_lname+
	    '&patient_pk='+patient_pk+
	    '&bday='+bday+
	    '&recumbent='+recumbent+
	    '&breast_feed='+breast_feed+
	    '&sex='+sex+
	    '&weight_date='+weight_date+
	    '&weight_value='+weight_value+
	    '&height_value='+height_value+
	    '&application_pk='+application_pk+
	    '&watsi_id='+watsi_id+
	    '&guardian_lname='+guardian_lname+
	    '&guardian_fname='+guardian_fname+
	    '&discharge_status='+discharge_status+
	    '&discharge_week='+discharge_week+
	    '&username='+username, true);
	  	xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
	  		location.href = "hbf.php?a=<?php echo $application_pk;?>";
	    };
	    xmlhttp.send(null);
	  }
	}
}

function returnResponse(xhr){
    if(xhr.status == 200){
      var result = document.getElementById('note').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

</script>
