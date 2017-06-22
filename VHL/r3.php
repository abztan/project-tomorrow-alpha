<html>
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto+Condensed' rel='stylesheet' type='text/css'>
<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$count = 1;
	$selected_batch = date("Y").getBatch("current","");

	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "application_id";

	//selected community
	if(isset($_POST['batch_display'])) {
		$selected_batch = $_POST['batch_display'];
		$list_year = substr($selected_batch,2,4);
		$list_batch = substr($selected_batch,-1);
	}

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

    Batch
    <select id='batch_display' name='batch_display' onchange='insert_report()'>
      <option value='0' selected disabled>(Select One)</option>
      <?php
        $result = getBatch_list();
        while($batch_of_year = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
          $year = $batch_of_year['year'];
          $batch = $batch_of_year['batch'];
          echo "<option value='".$year.$batch."'>$batch of $year</option>";
        }
      ?>
    </select>&nbsp;&nbsp;
    Program
    <select id="base_id" name="base_id" onchange="insert_report()">
      <option value = '0' disabled>(Select One)</option>
      <option value = '1'>Community</option>
      <option value = '2'>Kits</option>
    </select>
    &nbsp;&nbsp;
    Format
    <select id='format' onchange='insert_report()'>
      <option value='0' selected disabled>(Select One)</option>
      <option value='1'>Average</option>
      <option value='2'>Numeric</option>
    </select>
    <br/><br/>

    <img src='../_media/301.gif' hidden id='loader'>

<div id='insertAttendance'></div>
<div>
  <table>
    <tr>
      <th colspan="2">BIB Kit</th>
      <th>Target</th>
      <th>Actual</th>
      <th>Cash</th>
      <th>Non-Cash</th>
    </tr>
    <tr>
      <td>Target</td>
      <td>Actual</td>
      <td>Cash</td>
      <td>Non-Cash</td>
    </tr>
    <tr>
      <td></td>
    </tr>
  </table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script>

function insert_report()
{
	batch = document.getElementById('batch_display').value;
	base = document.getElementById('base_id').value;
	format = document.getElementById('format').value;
  alert(batch);
	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
      if(batch != 0 && base !=0 && format !=0)
      $('#loader').show();
      $('#table').hide();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_action.php?command=get_r3_report&batch='+batch+'&base='+base+'&format='+format, true);
  xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		window.insert_attendance_result(xmlhttp);
	};
	xmlhttp.send(null);
}

function insert_attendance_result(xhr){
		if(xhr.status == 200){
      $('#loader').hide();
				document.getElementById('insertAttendance').innerHTML = xhr.responseText;
		}else
				throw new Error('Server has encountered an error\n'+
						'Error code = '+xhr.status);
}
</script>
</html>
