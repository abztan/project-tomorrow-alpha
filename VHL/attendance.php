<?php
	session_start();

	if(empty($_SESSION['username']))
		header('location: /ICM/login.php?a=2');

	include "_tnsFunctions.php";
	include "../_parentFunctions.php";
	include "../_css/bareringtonbear.css";
	include "../dbconnect.php";
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'a').","; else	echo getAttendance_total_byBase($i,'a');} ?>],
          ['2',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'b').","; else	echo getAttendance_total_byBase($i,'b');} ?>],
          ['3',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'c').","; else	echo getAttendance_total_byBase($i,'c');} ?>],
          ['4',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'d').","; else	echo getAttendance_total_byBase($i,'d');} ?>],
          ['5',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'e').","; else	echo getAttendance_total_byBase($i,'e');} ?>],
          ['6',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'f').","; else	echo getAttendance_total_byBase($i,'f');} ?>],
          ['7',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'g').","; else	echo getAttendance_total_byBase($i,'g');} ?>],
          ['8',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'h').","; else	echo getAttendance_total_byBase($i,'h');} ?>],
          ['9',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'i').","; else	echo getAttendance_total_byBase($i,'i');} ?>],
          ['10',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'j').","; else	echo getAttendance_total_byBase($i,'j');} ?>],
          ['11',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'k').","; else	echo getAttendance_total_byBase($i,'k');} ?>],
          ['12',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'l').","; else	echo getAttendance_total_byBase($i,'l');} ?>],
          ['13',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'m').","; else	echo getAttendance_total_byBase($i,'m');} ?>],
          ['14',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'n').","; else	echo getAttendance_total_byBase($i,'n');} ?>],
          ['15',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'o').","; else	echo getAttendance_total_byBase($i,'o');} ?>],
          ['16',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase($i,'p').","; else	echo getAttendance_total_byBase($i,'p');} ?>],
        ]);

        var data2 = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'a','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'a','counselor');} ?>],
          ['2',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'b','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'b','counselor');} ?>],
          ['3',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'c','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'c','counselor');} ?>],
          ['4',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'d','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'d','counselor');} ?>],
          ['5',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'e','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'e','counselor');} ?>],
          ['6',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'f','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'f','counselor');} ?>],
          ['7',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'g','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'g','counselor');} ?>],
          ['8',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'h','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'h','counselor');} ?>],
          ['9',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'i','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'i','counselor');} ?>],
          ['10',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'j','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'j','counselor');} ?>],
          ['11',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'k','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'k','counselor');} ?>],
          ['12',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'l','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'l','counselor');} ?>],
          ['13',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'m','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'m','counselor');} ?>],
          ['14',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'n','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'n','counselor');} ?>],
          ['15',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'o','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'o','counselor');} ?>],
          ['16',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'p','counselor').","; else	echo getAttendance_total_byBase_byClass($i,'p','counselor');} ?>],
        ]);

				var data3 = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'a','participant').","; else	echo getAttendance_total_byBase_byClass($i,'a','participant');} ?>],
          ['2',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'b','participant').","; else	echo getAttendance_total_byBase_byClass($i,'b','participant');} ?>],
          ['3',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'c','participant').","; else	echo getAttendance_total_byBase_byClass($i,'c','participant');} ?>],
          ['4',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'d','participant').","; else	echo getAttendance_total_byBase_byClass($i,'d','participant');} ?>],
          ['5',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'e','participant').","; else	echo getAttendance_total_byBase_byClass($i,'e','participant');} ?>],
          ['6',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'f','participant').","; else	echo getAttendance_total_byBase_byClass($i,'f','participant');} ?>],
          ['7',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'g','participant').","; else	echo getAttendance_total_byBase_byClass($i,'g','participant');} ?>],
          ['8',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'h','participant').","; else	echo getAttendance_total_byBase_byClass($i,'h','participant');} ?>],
          ['9',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'i','participant').","; else	echo getAttendance_total_byBase_byClass($i,'i','participant');} ?>],
          ['10',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'j','participant').","; else	echo getAttendance_total_byBase_byClass($i,'j','participant');} ?>],
          ['11',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'k','participant').","; else	echo getAttendance_total_byBase_byClass($i,'k','participant');} ?>],
          ['12',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'l','participant').","; else	echo getAttendance_total_byBase_byClass($i,'l','participant');} ?>],
          ['13',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'m','participant').","; else	echo getAttendance_total_byBase_byClass($i,'m','participant');} ?>],
          ['14',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'n','participant').","; else	echo getAttendance_total_byBase_byClass($i,'n','participant');} ?>],
          ['15',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'o','participant').","; else	echo getAttendance_total_byBase_byClass($i,'o','participant');} ?>],
          ['16',	<?php	for($i='1';$i<11;$i++) { if($i!='10')	echo getAttendance_total_byBase_byClass($i,'p','participant').","; else	echo getAttendance_total_byBase_byClass($i,'p','participant');} ?>],
        ]);

				var data4 = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'a');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['2',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'b');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['3',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'c');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['4',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'d');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['5',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'e');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['6',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'f');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['7',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'g');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['8',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'h');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['9',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'i');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['10',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'j');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['11',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'k');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['12',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'l');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['13',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'m');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['14',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'n');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['15',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'o');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['16',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase($i,'p');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>]
				]);

				var data5 = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'a','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['2',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'b','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['3',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'c','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['4',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'d','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['5',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'e','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['6',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'f','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['7',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'g','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['8',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'h','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['9',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'i','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2,2);} ?>],
          ['10',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'j','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['11',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'k','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['12',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'l','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['13',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'m','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['14',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'n','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['15',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'o','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['16',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'p','counselor');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>]
				]);

				var data6 = google.visualization.arrayToDataTable([
          ['Week', 'BAC','BOH','DGT','GNS','KOR','PWN','DPG','ILO','CEB','RXS'],
          ['1',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'a','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['2',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'b','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['3',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'c','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['4',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'d','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['5',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'e','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['6',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'f','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['7',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'g','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['8',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'h','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['9',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'i','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['10',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'j','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['11',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'k','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['12',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'l','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['13',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'m','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['14',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'n','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['15',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'o','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>],
          ['16',
					<?php	for($i='1';$i<11;$i++) { $a = getAttendance_total_byBase_byClass($i,'p','participant');	$b = countApplicationTagByBase(5,$i);
						if($i!='10') echo round($a/$b,2).","; else	echo round($a/$b,2);} ?>]
				]);

        var options = {
          hAxis: {title: 'Week',  titleTextStyle: {color: '#333'}},
					legend: {position: 'top', maxLines: 10},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('total_all_div'));
        chart.draw(data, options);
				/*
				var chart1 = new google.visualization.AreaChart(document.getElementById('total_pastor_div'));
        chart1.draw(data1, options);*/

				var chart2 = new google.visualization.AreaChart(document.getElementById('total_counselor_div'));
        chart2.draw(data2, options);

				var chart3 = new google.visualization.AreaChart(document.getElementById('total_participant_div'));
        chart3.draw(data3, options);

				var chart4 = new google.visualization.AreaChart(document.getElementById('total_AVG_div'));
        chart4.draw(data4, options);

				var chart5 = new google.visualization.AreaChart(document.getElementById('total_counselorAVG_div'));
        chart5.draw(data5, options);

				var chart6 = new google.visualization.AreaChart(document.getElementById('total_participantAVG_div'));
        chart6.draw(data6, options);

      }
    </script>
  </head>
  <body>
		Total Attendance (AVG)
    <div id="total_AVG_div" style="width: 1900px; height: 800px;"></div>
		<!--Pastor Attendance
    <div id="total_pastor_div" style="width: 1900px; height: 800px;"></div>-->
		Counselor Attendance (AVG)
    <div id="total_counselorAVG_div" style="width: 1900px; height: 800px;"></div>
		Participant Attendance (AVG)
    <div id="total_participantAVG_div" style="width: 1900px; height: 800px;"></div>
		Total Attendance
    <div id="total_all_div" style="width: 1900px; height: 800px;"></div>
		<!--Pastor Attendance
    <div id="total_pastor_div" style="width: 1900px; height: 800px;"></div>-->
		Counselor Attendance
    <div id="total_counselor_div" style="width: 1900px; height: 800px;"></div>
		Participant Attendance
    <div id="total_participant_div" style="width: 1900px; height: 800px;"></div>
  </body>
</html>
