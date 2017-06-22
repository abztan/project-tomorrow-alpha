<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];
	include_once "_ptrFunctions.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Thrive Card - ADD</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>
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
		vertical-align: middle;
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

	.material-icons.md-18 { font-size: 18px; }
	.material-icons.md-24 { font-size: 24px; }
	.material-icons.md-36 { font-size: 36px; }
	.material-icons.md-48 { font-size: 48px; }
</style>

<body>
<form name='form' action='' id="tc_form" method='POST'>
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--7-col mdl-shadow--2dp" style="background:#E7E4DF;">
	<div id="title">2nd Day Attendance</div>
	<table border="0" width="100%">
		<tr><td id="label_style" width="30%">Thrive Date</td><td width="70%"id="content_style"><input style="padding: 20 0 0 0;" class="mdl-select__input" type="date" id="thrive_date" onchange="checkDuplicate_entry();check_pass();"></td></tr>
		<tr>
			<td id="label_style">Pastor ID</td>
			<td id="content_style"><b style="display: inline-block;">P-</b>
				<div id="input_style" class="mdl-textfield mdl-js-textfield">
					<input class="mdl-textfield__input" type="text" id="pastor_id" name='pastor_id' pattern="-?[0-9]*(\.[0-9]+)?" maxlength="5" onkeypress="pad(this.value);" onchange="getProfile(this.value);getDistrict();checkDuplicate_entry();check_pass();" onkeyup="" onkeydown=""/>
					<label class="mdl-textfield__label" for="pastor_id">00000</label>
					<span class="mdl-textfield__error">Please follow the standard ID format, ex: P-00021.</span>
				</div>
				<a class="mdl-button mdl-js-button mdl-button--icon" onclick="clearField(1)">
				  <i class="material-icons md-18">clear</i>
				</a>
			</td>
		</tr>
		<tr>
			<td id="label_style">Name</td>
			<td id="content_style">
				<div style="padding: 20 0 0 0;" id="pastor_name" onchange="getDistrict()"></div>
			</td>
		</tr>
		<tr>
			<td id="label_style">Base - District</td>
			<td id="content_style">
				<div style="padding: 20 0 0 0;" id="pastor_district"></div>
			</td>
		</tr>
	</table>
	<br/>
	<div style="padding: 0 16 16 0;" align="right">
		<i id="result" style="color:#E68A2E;"></i>&nbsp;
		<a class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onClick="insertCard()">
			<i class="material-icons">check</i></a></div>
	</div>
</div>
</form>
<br/><br/><br/><br/><br/><br/>

<script type="text/javascript">
	function clearField(a)	{
		if(a==1) {
			document.getElementById("pastor_id").value = "";
			getProfile(0);
			getDistrict();
		}
	}

	function pad(num) {
		num = Number(num);
	    var s = num+"";
	    while (s.length < 4) s = "0" + s;
			document.getElementById("pastor_id").value = s;
	}

	function getProfile(a) {
		//make sure format is correct
		a = Number(a);
    var s = a+"";
    while (s.length < 5) s = "0" + s;
		document.getElementById("pastor_id").value = s;
		//enable other fields

		var base = "<?php echo $account_base;?>";

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?command=get_p_name&pastor_id='+a+'&base='+base, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.returnName(xmlhttp);
    };
    xmlhttp.send(null);
	}

	function checkDuplicate_entry() {
		var date = document.getElementById("thrive_date").value;
		var pastor_id = Number(document.getElementById("pastor_id").value);

		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?command=check_dup_card_sd&date='+date+'&pastor_id='+pastor_id, true);
		xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.returnResult(xmlhttp);
    };
    xmlhttp.send(null);
	}

	function returnName(xhr){
	    if(xhr.status == 200){
	      document.getElementById('pastor_name').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function getDistrict() {
		var pastor_id = document.getElementById("pastor_id").value;
		var base = "<?php echo $account_base;?>";

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_insertvalues.php?command=get_p_district&pastor_id='+pastor_id+'&base='+base, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.returnDistrict(xmlhttp);
    };
    xmlhttp.send(null);
}

	function returnDistrict(xhr){
	    if(xhr.status == 200){
	        document.getElementById('pastor_district').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertCard()
	{
		var date = document.getElementById("thrive_date").value;
		var pastor_id = Number(document.getElementById("pastor_id").value);
		var base = "<?php echo $account_base;?>";
		var username = "<?php echo $username;?>";

		if(date == 0 || pastor_id == 0 || pastor_id == "") {
			document.getElementById('result').innerHTML = "FAILED: Date and Pastor ID must be indicated.";
		}

		else {
			window.location='_action.php?command=add_card_sd&date='+date+
			'&pastor_id='+pastor_id+
			'&base='+base+
			'&username='+username;
		}
	}

	function returnResult(xhr){
	    if(xhr.status == 200){
	        var result = document.getElementById('result').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
</body>

</html>
