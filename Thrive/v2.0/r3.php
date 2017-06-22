<html>
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto+Condensed' rel='stylesheet' type='text/css'>
<?php
	include_once "../../dbconnect.php";
	include_once "../../_parentFunctions.php";
	include_once "_ptrfunctions.php";

	//defaults

?>

<style>
body {
  font-family: 'Roboto Condensed', sans-serif;
}

table {
  border-collapse: collapse;
  overflow: hidden;
  border-spacing: 0;
  white-space: nowrap;
  font-family: 'Roboto Mono';
	font-size: 14px;
}

td {
  padding: 7px;
  position: relative;
}

th {
  font-family: 'Roboto Condensed', sans-serif;
  padding: 5px;
  position: relative;
  background-color: #545454;
  color: white;
}

#text {
  font-family: 'Roboto Condensed', sans-serif;
  font-size: 12px;
}

tr:hover {
  background-color: #ffd5d5;
}

td:hover::after,
th:hover::after {
  content: "";
  position: absolute;
  background-color: #ffd5d5;
  left: 0;
  top: -5000px;
  height: 10000px;
  width: 100%;
  z-index: -1;
}

td:focus::after,
th:focus::after {
  content: '';
  background-color: lightblue;
  position: absolute;
  left: 0;
  height: 10000px;
  top: -5000px;
  width: 100%;
  z-index: -1;
}

td:focus::before {
  background-color: lightblue;
  content: '';
  height: 100%;
  top: 0;
  left: -5000px;
  position: absolute;
  width: 10000px;
  z-index: -1;
}
</style>

<form name = "form" action = "" method = "POST"></form>
		Report
		<select id='control_value' onchange='show_control(this.value)'>
			<option selected disabled>(Select One)</option>
			<option value = '1'>Thrive Attendance</option>
			<option value = '2'>Thrive Consistency Rate</option>
			<option value = '3'>Thrive Overlap</option>
		</select>
		<br/>
		<br/>

		<div id="attendance_count">
			Program
			<select id='a_prog' name='attendance_program' onchange='insert_attendance()'>
				<option selected disabled>(Select One)</option>
				<option value = '1'>First Day</option>
				<option value = '2'>Second Day</option>
			</select>
	    Date Range
	    <select id='a_range' name='attendance_range' onchange='insert_attendance()'>
	      <option selected disabled>(Select One)</option>
	      <option value = '1'>Current Month</option>
	      <option value = '4'>Last 4 Months</option>
	      <option value = '6'>Last 6 Months</option>
	      <option value = '12'>Last 12 Months</option>
	    </select>
		</div>

		<div id="attendance_rate">
	    Last <input id="ar_instances" type="text" name="instances" onchange='insert_consistency_report()' style='width:25px;'> meetings (Range: 1-12) for
			<select id='ar_prog' name='attendance_program' onchange='insert_consistency_report()'>
				<option selected value = '1'>First Day Attendance</option>
				<option value = '2'>Second Day Attendance</option>
			</select>.
		</div>

		<div id="attendance_overlap">
	    Date Range
	    <select id='ov_range' name='attendance_range' onchange='insert_overlap_report()'>
	      <option selected disabled>(Select One)</option>
	      <option value = '1'>Current Month</option>
	      <option value = '4'>Last 4 Months</option>
	      <option value = '6'>Last 6 Months</option>
	      <option value = '12'>Last 12 Months</option>
	    </select>
		</div>
    <br/><br/>
</form>
<div id='insertAttendance'></div>
<br/>
<div id='insertDetails'></div>
<br/>
<div id='moar_details'></div>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>

<script>
document.getElementById('attendance_count').style.display = "none";
document.getElementById('attendance_rate').style.display = "none";
document.getElementById('attendance_overlap').style.display = "none";

function show_control(value) {
	if(value == "1") {
		document.getElementById('attendance_count').style.display = "";
		document.getElementById('attendance_rate').style.display = "none";
		document.getElementById('attendance_overlap').style.display = "none"
	}
	else if(value == "2") {
		document.getElementById('attendance_count').style.display = "none";
		document.getElementById('attendance_rate').style.display = "";
		document.getElementById('attendance_overlap').style.display = "none"
	}
	else if(value == "3") {
		document.getElementById('attendance_count').style.display = "none";
		document.getElementById('attendance_rate').style.display = "none";
		document.getElementById('attendance_overlap').style.display = ""
	}
}

function insert_attendance()
{
	var formName = 'form';
	var program = document.getElementById("a_prog").value;
	var range = document.getElementById("a_range").value;

	if(program >= "1" && range >= "1") {
		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_action.php?command=get_attendance_summary&program='+program+'&range='+range, true);

	  xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insert_attendance_result(xmlhttp);
		};
		xmlhttp.send(null);
	}
}

function insert_consistency_report()
{
	var formName = 'form';
	var instances = document.getElementById("ar_instances").value;
	var program = document.getElementById("ar_prog").value;

	if(instances >= 1 && instances <= 12) {
		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_action.php?command=get_attendance_rate&instances='+instances+'&program='+program, true);

	  xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insert_attendance_result(xmlhttp);
		};
		xmlhttp.send(null);
	}
}

function insert_overlap_report()
{
	var formName = 'form';
	var range = document.getElementById("ov_range").value;

	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_attendance_overlap&range='+range, true);

  xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		window.insert_attendance_result(xmlhttp);
	};
	xmlhttp.send(null);
}


function insert_attendance_result(xhr){
		if(xhr.status == 200){
				document.getElementById('insertAttendance').innerHTML = xhr.responseText;
		}else
				throw new Error('Server has encountered an error\n'+
						'Error code = '+xhr.status);
}

function show_details(base,month,year)
{
	var program = document.getElementById("a_prog").value;
	var formName = 'form';
	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_attendance_details&base='+base+'&month='+month+'&year='+year+'&program='+program, true);
  xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		window.show_details_result(xmlhttp);
	};
	xmlhttp.send(null);
}

function show_details_result(xhr){
		if(xhr.status == 200){
				document.getElementById('insertDetails').innerHTML = xhr.responseText;
		}else
				throw new Error('Server has encountered an error\n'+
						'Error code = '+xhr.status);
}

function show_me_the_money(district_pk,month,year)
{
	var program = document.getElementById("a_prog").value;
	var formName = 'form';
	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_attendance_detail_plus&district_pk='+district_pk+'&month='+month+'&year='+year+'&program='+program, true);
  xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		window.show_details_result_moar(xmlhttp);
	};
	xmlhttp.send(null);
}

function show_details_result_moar(xhr){
		if(xhr.status == 200){
				document.getElementById('moar_details').innerHTML = xhr.responseText;
		}else
				throw new Error('Server has encountered an error\n'+
						'Error code = '+xhr.status);
}
</script>
</html>
