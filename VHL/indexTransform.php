<?php
	session_start();

	if(empty($_SESSION['username']))
		header('location: /ICM/login.php?a=2');

	include "_tnsFunctions.php";
	include "../_parentFunctions.php";
	include "../_css/bareringtonbear.css";
	include "../dbconnect.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body bgcolor="white">

<header>
  <h2>VHL Dashboard</h2>
  <nav class="menu"><?php include "../controller.php"; ?></nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
<table>
<th colspan = "2" align = "left">Transform Data Summary</th>
<tr><td width = "automatic">Total Number of Entries</td><td id="t_numvalue"><?php echo number_format(countApplication());?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Applications</td><td id="t_numvalue"><?php echo number_format(countApplicationTag(1));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total For Ocular</td><td id="t_numvalue"><?php echo number_format(countApplicationTag(2));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Selection</td><td id="t_numvalue"><?php echo number_format(countApplicationTag(3));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Community</td><td id="t_numvalue"><?php echo number_format(countApplicationTag(5));?></td></tr>
<tr><td width = "automatic">Total Number of Participants</td><td id="t_numvalue"><?php echo number_format(countParticipantList());?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Selected</td><td id="t_numvalue"><?php echo number_format(countParticipantList_Tag(5));?></td></tr>
<tr><td>&nbsp;&nbsp;-- 4Ps Count</td><td id="t_numvalue"><?php echo number_format(countParticipantList_4Ps(5));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Qualified</td><td id="t_numvalue"><?php echo number_format(countParticipantList_Tag(2));?></td></tr>
<tr><td>&nbsp;&nbsp;-- 4Ps Count</td><td id="t_numvalue"><?php echo number_format(countParticipantList_4Ps(2));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Disqualified</td><td id="t_numvalue"><?php echo number_format(countParticipantList_Tag(3));?></td></tr>
<tr><td>&nbsp;&nbsp;-- 4Ps Count</td><td id="t_numvalue"><?php echo number_format(countParticipantList_4Ps(3));?></td></tr>
<tr><td>&nbsp;&nbsp;-Total Flagged</td><td id="t_numvalue"><?php echo number_format(countParticipantList_Tag(4));?></td></tr>
<tr><td>&nbsp;&nbsp;-- 4Ps Count</td><td id="t_numvalue"><?php echo number_format(countParticipantList_4Ps(4));?></td></tr>
</table>
<br/><br/>here: <?php //echo getAttendance_total_byBase('1','a');?>

<!-----------------------------------------------------------------------CLEAN THIS UP LATER!!---------------------------------------------------------------------->
<?php
$query = getListApplicationByBase_ByTag(1, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $a;
$t_b = $b;
$t_c = $c;
$t_d = $d;
$t_e = $e;
?>

<table border = "1">
<th align = "left">Base</th><th>0-14</th><th>15-19</th><th>20-24</th><th>25-29</th><th>30+</th><th>Total</th><th>Missing Profiles</th>
<tr align = "right"><td>BAC</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(1);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(2, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>BOH</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(2);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(3, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>DGT</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(3);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(4, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>GNS</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(4);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(5, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>KOR</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(5);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(6, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>PWN</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(6);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(7, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>DPG</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(7);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(8, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>ILO</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(8);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(9, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>CEB</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(9);?></td>
</tr>

<?php
$query = getListApplicationByBase_ByTag(10, 3);
$result = pg_query($dbconn, $query);
$a = 0;
$b = 0;
$c = 0;
$d = 0;
$e = 0;

while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
{
	$entry_id = $row['id'];
	$i = countParticipantTag($entry_id,5) + countParticipantTag($entry_id,2);
	if($i >= 0 && $i <= 14)
		$a++;
	else if($i >= 15 && $i <= 19)
		$b++;
	else if($i >= 20 && $i <= 24)
		$c++;
	else if($i >= 25 && $i <= 29)
		$d++;
	else if($i > 29)
		$e++;
}

$t_a = $t_a + $a;
$t_b = $t_b + $b;
$t_c = $t_c + $c;
$t_d = $t_d + $d;
$t_e = $t_e + $e;
?>

<tr align = "right"><td>RXS</td>
<td><?php echo $a;?></td>
<td><?php echo $b;?></td>
<td><?php echo $c;?></td>
<td><?php echo $d;?></td>
<td><?php echo $e;?></td>
<td><?php echo $x = $a+$b+$c+$d+$e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(10);?></td>
</tr>

<tr align = "right"><td>Total</td>
<td><?php echo $t_a;?></td>
<td><?php echo $t_b;?></td>
<td><?php echo $t_c;?></td>
<td><?php echo $t_d;?></td>
<td><?php echo $t_e;?></td>
<td><?php echo $t_a + $t_b + $t_c + $t_d + $t_e;?></td>
<td><?php echo countApplication_withoutProfile_byBase(1)+countApplication_withoutProfile_byBase(2)+countApplication_withoutProfile_byBase(3)+countApplication_withoutProfile_byBase(4)+countApplication_withoutProfile_byBase(5)+countApplication_withoutProfile_byBase(6)+countApplication_withoutProfile_byBase(7)+countApplication_withoutProfile_byBase(8)+countApplication_withoutProfile_byBase(9)+countApplication_withoutProfile_byBase(10)?></td>
</tr>

</table>

<!-----------------------------------------------------------------------CLEAN THIS UP LATER!!---------------------------------------------------------------------->
<br/>
<!--<table border = "1">
<tr><td colspan="3">SPL Communities</td></tr>
<tr><td>Community ID</td><td>Participant Total</td><td>w/ 4P</td></tr>
<?php
	/*$query = getApplication_byProgram(50,4,'community_id');
	$result = pg_query($dbconn, $query);
	while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$community_id = $community['community_id'];
		$application_pk = $community['id'];
		$participants = countParticipantTag($application_pk,5);
		$total_4P = countApplication_Participants_byTag_byVariable1($application_pk,5,"Yes");
		echo "<tr><td>$community_id</td><td>$participants</td><td>$total_4P</td></tr>";
	}*/
?>
</table>-->
<br/>
<div id="columnchart_material" style="width: 1470px; height: 300px;"></div>
<div id="linechart_material"></div>
</section>


<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
	  	google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Bases', 'Applications', 'For Ocular Visit', 'Selection', 'Upcoming Batch', 'Community'],
		  		['BAC', <?php echo countApplicationTagByBase(1,"1");?>, <?php echo countApplicationTagByBase(2,"1");?>, <?php echo countApplicationTagByBase(3,"1");?>, <?php echo countApplicationTagByBase(9,"1");?>, <?php echo countApplicationTagByBase(5,"1");?>],
          ['BOH', <?php echo countApplicationTagByBase(1,"2");?>, <?php echo countApplicationTagByBase(2,"2");?>, <?php echo countApplicationTagByBase(3,"2");?>, <?php echo countApplicationTagByBase(9,"2");?>, <?php echo countApplicationTagByBase(5,"2");?>],
          ['DGT', <?php echo countApplicationTagByBase(1,"3");?>, <?php echo countApplicationTagByBase(2,"3");?>, <?php echo countApplicationTagByBase(3,"3");?>, <?php echo countApplicationTagByBase(9,"3");?>, <?php echo countApplicationTagByBase(5,"3");?>],
          ['GNS', <?php echo countApplicationTagByBase(1,"4");?>, <?php echo countApplicationTagByBase(2,"4");?>, <?php echo countApplicationTagByBase(3,"4");?>, <?php echo countApplicationTagByBase(9,"4");?>, <?php echo countApplicationTagByBase(5,"4");?>],
          ['KOR', <?php echo countApplicationTagByBase(1,"5");?>, <?php echo countApplicationTagByBase(2,"5");?>, <?php echo countApplicationTagByBase(3,"5");?>, <?php echo countApplicationTagByBase(9,"5");?>, <?php echo countApplicationTagByBase(5,"5");?>],
          ['PWN', <?php echo countApplicationTagByBase(1,"6");?>, <?php echo countApplicationTagByBase(2,"6");?>, <?php echo countApplicationTagByBase(3,"6");?>, <?php echo countApplicationTagByBase(9,"6");?>, <?php echo countApplicationTagByBase(5,"6");?>],
          ['DPG', <?php echo countApplicationTagByBase(1,"7");?>, <?php echo countApplicationTagByBase(2,"7");?>, <?php echo countApplicationTagByBase(3,"7");?>, <?php echo countApplicationTagByBase(9,"7");?>, <?php echo countApplicationTagByBase(5,"7");?>],
          ['ILO', <?php echo countApplicationTagByBase(1,"8");?>, <?php echo countApplicationTagByBase(2,"8");?>, <?php echo countApplicationTagByBase(3,"8");?>, <?php echo countApplicationTagByBase(9,"8");?>, <?php echo countApplicationTagByBase(5,"8");?>],
          ['CEB', <?php echo countApplicationTagByBase(1,"9");?>, <?php echo countApplicationTagByBase(2,"9");?>, <?php echo countApplicationTagByBase(3,"9");?>, <?php echo countApplicationTagByBase(9,"9");?>, <?php echo countApplicationTagByBase(5,"9");?>],
          ['RXS', <?php echo countApplicationTagByBase(1,"10");?>, <?php echo countApplicationTagByBase(2,"10");?>, <?php echo countApplicationTagByBase(3,"10");?>, <?php echo countApplicationTagByBase(9,"10");?>, <?php echo countApplicationTagByBase(5,"10");?>]
        ]);

		var options = {
	//legend: { position: "none" },
		colors: ['#505050', '#FF7260', '#9BD7D5', '#FFCC00','#129793'],
		chartArea:{left:0,width:'80%',height:'80%'},
        isStacked: false,
      };


	  var view = new google.visualization.DataView(data);
      view.setColumns([0,
					   1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2,
					   { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" },
						3,
					   { calc: "stringify",
                         sourceColumn: 3,
                         type: "string",
                         role: "annotation" },
						4,
					   { calc: "stringify",
                         sourceColumn: 4,
                         type: "string",
                         role: "annotation" },
						 5,
					   { calc: "stringify",
                         sourceColumn: 5,
                         type: "string",
                         role: "annotation" }]);

        var chart = new  google.visualization.ColumnChart(document.getElementById('columnchart_material'));

        chart.draw(view, options);
      }
    </script>

		<script type="text/javascript">
	    google.load('visualization', '1.1', {packages: ['line']});
	    google.setOnLoadCallback(drawChart);

	    function drawChart() {

	      var data = new google.visualization.DataTable();
	      data.addColumn('number', 'Week');
	      data.addColumn('number', 'BAC');
	      data.addColumn('number', 'BOH');
	      data.addColumn('number', 'DGT');
	      data.addColumn('number', 'GNS');
	      /*data.addColumn('number', 'KOR');
	      data.addColumn('number', 'PWN');
	      data.addColumn('number', 'DPG');
	      data.addColumn('number', 'ILO');
	      data.addColumn('number', 'CEB');
	      data.addColumn('number', 'RXS');*/

	      data.addRows([
	        [1, <?php for($i = '1'; $i < 5; $i++) { if($i!=4) echo getAttendance_total_byBase('$i','a').","; else echo getAttendance_total_byBase('$i','a'); } ?>],
	        [2, <?php for($i = '2'; $i < 5; $i++) { if($i!=4) echo getAttendance_total_byBase('$i','b').","; else echo getAttendance_total_byBase('$i','b'); } ?>],
	        [3, <?php for($i = '3'; $i < 5; $i++) { if($i!=4) echo getAttendance_total_byBase('$i','c').","; else echo getAttendance_total_byBase('$i','c'); } ?>],
	        [4, <?php for($i = '4'; $i < 5; $i++) { if($i!=4) echo getAttendance_total_byBase('$i','d').","; else echo getAttendance_total_byBase('$i','4'); } ?>]
	      ]);

	      var options = {
	        chart: {
	          title: 'Attendance',
	          subtitle: '--'
	        },
	        width: 900,
	        height: 500
	      };

	      var chart = new google.charts.Line(document.getElementById('linechart_material'));

	      chart.draw(data, options);
	    }
	  </script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>
