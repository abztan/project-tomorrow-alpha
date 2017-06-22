<?php 
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

	include "functions.php";
	//php
	include "bareringtonbear.css";
	include "dbconnect.php";
?>

<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>

google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawMarkersMap);

      function drawMarkersMap() {
      var data = google.visualization.arrayToDataTable([

        ['City', 'Barangay', 'Total Pastors'],
		
		<?php
		$counter = "0";
		$query = getPastorProvinceCity();
		$result = pg_query($dbconn, $query);
		
		 while ($row=pg_fetch_array($result,NULL,PGSQL_BOTH)){
			
			$province = $row['province'];
			$city = $row['city'];
			$barangay = countOn("onBarangay",$city);
			$pastor = countOn("onCity",$city);
			
			echo "['".$province.",".$city."', ".$barangay.",".$pastor."],";
		 }		 
		 ?>
	
      ]);

		var options = {
	    backgroundColor: 'gray',
        region: 'PH',
		displayMode: 'markers',
		resolution: 'provinces',
		magnifyingGlass: {enable: true, zoomFactor: 4.0},
        colorAxis: {colors: ['yellow', 'orange']}
      };

      var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    };
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section> 
	<select id="viewby" name="viewby" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
	<option value="http://localhost/ICM/temp5.php">Pastor Map</option>
	<option value="http://localhost/ICM/temp1.php">Population and Communities</option>
	<option value="http://localhost/ICM/temp2.php">Coordinates</option>
	</select>
</section>
<br/>

<section>
	<div id="chart_div" style="width: 1200px; height: 800px;"></div>	
</section>
</article>
</form>

</body>

</html>