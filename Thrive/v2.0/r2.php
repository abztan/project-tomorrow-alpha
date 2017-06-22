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
    Date Range
    <?php
      echo "<select id='sup' name='attendance_range' onchange='insert_attendance(this.value)'>
            <option value = '0' selected disabled>(Select One)</option>
            <option value = '1'>Current Month</option>
            <option value = '4'>Last 4 Months</option>
            <option value = '6'>Last 6 Months</option>
            <option value = '12'>Last 12 Months</option>
            </select>";
    ?>
    <br/><br/>
</form>

<div id='insertAttendance'></div>
<br/>
<div id='insertDetails'></div>
<br/>
<div id='moreDetails'></div>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>

<script>
function insert_attendance(range)
{
	var formName = 'form';
	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_attendance_summary&range='+range, true);
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
	var formName = 'form';
	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_attendance_details&base='+base+'&month='+month+'&year='+year, true);
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
</script>
</html>
