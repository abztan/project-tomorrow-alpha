	<?php

	?>
	
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Planted By ICM', 'Total'],
          ['Yes',  <?php echo number_format(countListChurch("Total Planted Yes"));?>],
          ['No',   <?php echo number_format(countListChurch("Total Planted No"));?>],
          ['Undefined',  <?php echo number_format(countListChurch("Total Planted Blank"));?>]
        ]);

        var options = {
          title: 'ICM Planted Churches',
		  legend: 'none',
		  colors:[ '#26BF67','#22AA44','#B0B0B0'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>


    <div id="piechart" style="width: 600px; height: 200px;"></div>

