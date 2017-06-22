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

        ['City',   'Population', 'Transform Communities'],
		
        ['Bacolod City',        511820, 8,],
		['Cadiz City',          151500, 13],
		['Valladolid',          36416, 1],
		['Murcia',              75207, 1],
		['Enrique B. Magalona', 59434, 1],
		
		['Carmen, Bohol',       43579, 2],
		['Pilar, Bohol',        26887, 2],
		['Mabini, Bohol',       28174, 1],
		['Alicia, Bohol',       22285, 1],
		['San Isidro, Bohol',   9125, 2],
		['Trinidad, Bohol',     28828, 1],
		['Buenavista, Bohol',   27031, 1],
		['Baclayon',            18630, 2],
		['Antequera',           14481, 2],
		['Inabanga',            43291, 1],
		['Garcia Hernandez',    23038, 2],
		['Ubay',                68578, 2],
		['Guindulman',          31789, 1],
		['Bilar',               17098, 1],
		['Dauis',               39448, 1],
		['Jetafe',              27788, 1],
		['Tagbilaran, Bohol',     96792, 2],
		
		['General Santos City',     538086, 4],
		['Polomolok City',     138273, 5],
		['Tboli',     7186, 1],
		['Tupi',     61843, 1],
		
		['Maitum',     41675, 2],
		['Malungon',     95044, 2],
		['Kiamba',     54871, 2],
		['Glan',     106518, 2],
		['Malapatan',     72386, 2]
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
	<option value="http://localhost/ICM/temp1.php">Population and Communities</option>
	<option value="http://localhost/ICM/temp2.php">Coordinates</option>
	<option value="http://localhost/ICM/temp5.php">Pastor Map</option>
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