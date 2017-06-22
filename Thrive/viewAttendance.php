	<?php
		session_start();
		if(empty($_SESSION['username']))
		  header('location: /ICM/Login.php?a=2');
		else {
		  $username = $_SESSION['username'];
		  $access_level = $_SESSION['accesslevel'];
		  $account_base = $_SESSION['baseid'];
		}

		include "../_parentFunctions.php";
		include "_ptrFunctions.php";

		$district_pk = $_GET['a'];
		if(isset($_GET['notice']))
			$notice = $_GET['notice'];
		$district_pk = $_GET['a'];
		$district = getDistrict_Details_byTHID($district_pk);
		$district_pk = $district['district_id'];
		$base_id = $district['base_id'];
		$district_name = $district['alternate_name'];
		$base = getBaseName($base_id);
		$population = countPopulation_byTHID($district_pk);
		$notice = "";
		$year = date("Y");

		$query = getPastor_byTHID($district_pk);
		$result = pg_query($dbconn, $query);
		if(isset($_POST['month_display']))
			$month = $_POST['month_display'];
	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<link rel="stylesheet" href="/ICM/_css/material.css" />
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	</head>

	<body>
	<form name="FORM" action="" method="POST">

	<article>
	<input id="district_pk" value="<?php echo $district_pk;?>" hidden>
	<div class="mdl-grid mdl-cell--2-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E0E0E0;">
			Year&nbsp;
			<select id="year" name="year_display" onChange="window.activateMonth()">
				<option disabled selected>(Select Year)</option>
				<option value="2013">2013</option>
				<option value="2014">2014</option>
				<option value="2015">2015</option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
			</select>&nbsp;
			Month&nbsp;
			<select name="month_display" id="month" onChange="window.loadAttendanceLog()" disabled>
				<option disabled selected>(Select Month)</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
		</div>
		<br/>

		<div class="mdl-grid mdl-cell--2-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E0E0E0;">
			<select name="selected_pastor" id="pastor" style="width:250px;">
				<option disabled selected>(Select Pastor)</option>
			<?php
				while($pastor = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					$pastor_name = $pastor['lastname'].", ".$pastor['firstname'];
					$pastor_id = $pastor['id'];
					$pastor_id_string = "P".str_pad($pastor_id, 6, 0, STR_PAD_LEFT);

					echo "<option value='$pastor_id'>$pastor_id_string - $pastor_name</option>";
				}
			?>
			</select>
			<button type="button" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored" id="add" name="add" onClick="addPastor()" disabled>
				<i class="material-icons md-24">add</i>
			</button>
		</div>

		<div class="mdl-grid mdl-cell--2-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#DEC452;">
			<div id="loading"></div>
			<div id="notice"><?php echo $notice;?></div>
		</div>

	<br/>
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center">
		  <thead>
		    <tr>
		      <th class="mdl-data-table__cell--non-numeric" style="text-align: center;">ID</th>
		      <th align="center" style="text-align: center;">Name</th>
		      <th align="center" style="text-align: center;">Adult Attendance</th>
		      <th align="center" style="text-align: center;">Children Attendance</th>
		      <th align="center" style="text-align: center;">Tithe</th>
		      <th align="center" style="text-align: center;"></th>
		    </tr>
		  </thead>
		  <tbody id="attendanceLog">
		  </tbody>
			</table>
		</div>
	</article>
	</form>
	</body>

	<script type="text/javascript">
	function activateMonth() {
		document.getElementById("month").disabled=false;
	}

	function addPastor() {
		var month = document.getElementById("month").value;
		var district = document.getElementById("district_pk").value;
		var pastor_pk = document.getElementById("pastor").value;
		var year = document.getElementById("year").value;
		var username = "<?php echo $username;?>";

		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_insertvalues.php?pastor_pk='+pastor_pk+'&district_pk='+district+'&year='+year+'&month='+month+'&username='+username, true);
		xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				{
			document.getElementById("notice").innerHTML="You have successfully added pastor "+pastor_pk;
			window.loadAttendanceLog();
			}
		};
		xmlhttp.send(null);
	}

	function updateAttendanceData(a,b) {
		var username = "<?php echo $username;?>";
		//a -type b -att_pk c -value
		if(a=="ADT") {
			var c = document.getElementById(a+b).value;
		}
		else if(a=="CHD")	{
			var c = document.getElementById(a+b).value;
		}
		else if(a=="TTH")	{
			var c = document.getElementById(a+b).value;
		}

		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		//document.getElementById("loading").innerHTML = '<img src="../_media/pac-load.gif" />';
		xmlhttp.open('GET', '_insertvalues.php?attendance_pk='+b+'&classification='+a+'&value='+c+'&username='+username, true);
		xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				{
					document.getElementById("loading").innerHTML = '';
			}
		};
		xmlhttp.send(null);
	}

	function removePastor(a,b,c) {
		var username = "<?php echo $username;?>";

		var xmlhttp = null;
		if(typeof XMLHttpRequest != 'udefined'){
				xmlhttp = new XMLHttpRequest();
		}else if(typeof ActiveXObject != 'undefined'){
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}else
				throw new Error('You browser doesn\'t support ajax');

		xmlhttp.open('GET', '_insertvalues.php?attendance_pk='+a+'&username='+username+'&delete=1', true);
		xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				{
					document.getElementById("notice").innerHTML="Pastor ("+c+") "+b+" has been removed.";
					window.loadAttendanceLog();
			}
		};
		xmlhttp.send(null);
	}

	function loadAttendanceLog()
	{
		document.getElementById("add").disabled=false;
    var month = document.getElementById("month").value;
    var district = document.getElementById("district_pk").value;
    var year = document.getElementById("year").value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?attendance_month='+month+'&district_pk='+district+'&attendance_year='+year, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertAttendanceLog(xmlhttp);
    };
    xmlhttp.send(null);
	}

	function insertAttendanceLog(xhr){
	    if(xhr.status == 200){
	        document.getElementById('attendanceLog').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
	</script>
	</html>
