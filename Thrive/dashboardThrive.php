<br/>
<table border = "1">
<tr align = "center">
<td>
<div id="text_style1">PASTORS</div>
<div id="text_style2"><?php echo number_format(countListPastor("Total Pastor"));?></div>
</td>
<td style="border-bottom: 1px solid #F7F7F7;"><div id="text_style1">DENOMINATION</div></td>
<td style="border-bottom: 1px solid #F7F7F7;"><div id="text_style1">EDUCATION</div></td>
<td style="border-bottom: 1px solid #F7F7F7;"><div id="text_style1">COUNT</div></td>
</tr>

<tr align = "center">
<td>
<div id="text_style1">MEMBERS</div>
<div id="text_style2"><?php echo number_format(countListPastor("Total Member"));?></div>
</td>
<td rowspan = "2"><div id="denomination_chart" style="width: 300px; height: 300px;"></td>
<td rowspan = "2"><div id="education_chart" style="width: 300px; height: 300px;"></td>
<td rowspan = "2"></td>
</tr>

<tr align = "center">
<td>
<div id="text_style1">CHURCHES</div>
<div id="text_style2"><?php $ch_total = countListChurch("Total Church"); echo number_format($ch_total);?></div>
</td>
</tr>

<tr>
<td colspan = "4" bgcolor = "#DBDBDB"><div id="member_chart" style="width: 1300px; height: 250px;"></div></td>
</tr>

<tr>
<td colspan = "4"  >
<table border = "0" width = "100%">
<th width = "10%">BAC</th>
<th width = "10%">BOH</th>
<th width = "10%">DGT</th>
<th width = "10%">GNS</th>
<th width = "10%">KOR</th>
<th width = "10%">PWN</th>
<th width = "10%">DPG</th>
<th width = "10%">ILO</th>
<th width = "10%">CEB</th>
<th width = "10%">RXS</th>
<tr>
<td align = "center"><?php echo number_format(countListPastor_Base("1","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("2","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("3","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("4","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("5","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("6","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("7","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("8","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("9","Total Profile"));?></td>
<td align = "center"><?php echo number_format(countListPastor_Base("10","Total Profile"));?></td>
</tr>
</table>
</td>
</tr>

<tr bgcolor = "#DBDBDB"><td colspan = "4"  ><div id="map_div" style="width: 1300px; height: 850px;"></div></td></tr>
</table>
<br/>

<?php
echo "Last added data: ".getMostRecent("created");
echo "<br/>";
echo "Last updated data : ".getMostRecent("updated"); ?>

<script src='default.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
//denomination
var data1 = google.visualization.arrayToDataTable([
['Denomination', ''],
['Baptist',  <?php echo countListChurch("Total Baptist");?>],
['Evangelical',  <?php echo countListChurch("Total Evangelical");?>],
['Pentecostal', <?php echo countListChurch("Total Pentecostal");?>],
['Others', <?php echo countListChurch("Total Denomination Others");?>]
]);

//education
var data2 = google.visualization.arrayToDataTable([
['Education', ''],
['Blank',  <?php echo countListPastor("Total Education Empty");?>],
['None',  <?php echo countListPastor("Total Education None");?>],
['Elementary',  <?php echo countListPastor("Total Education Elementary");?>],
['High School',  <?php echo countListPastor("Total Education High School");?>],
['College', <?php echo countListPastor("Total Education College");?>],
['Post College', <?php echo countListPastor("Total Education Post College");?>]
]);

//members
var data3 = google.visualization.arrayToDataTable([
['Bases', 'Members', 'Non-Members'],
['BAC', <?php echo countListPastor_Base("1", "Total Member");?>, <?php echo countListPastor_Base("1", "Total Nonmember");?>],
['BOH', <?php echo countListPastor_Base("2", "Total Member");?>, <?php echo countListPastor_Base("2", "Total Nonmember");?>],
['DGT', <?php echo countListPastor_Base("3", "Total Member");?>, <?php echo countListPastor_Base("3", "Total Nonmember");?>],
['GNS', <?php echo countListPastor_Base("4", "Total Member");?>, <?php echo countListPastor_Base("4", "Total Nonmember");?>],
['KOR', <?php echo countListPastor_Base("5", "Total Member");?>, <?php echo countListPastor_Base("5", "Total Nonmember");?>],
['PWN', <?php echo countListPastor_Base("6", "Total Member");?>, <?php echo countListPastor_Base("6", "Total Nonmember");?>],
['DPG', <?php echo countListPastor_Base("7", "Total Member");?>, <?php echo countListPastor_Base("7", "Total Nonmember");?>],
['ILO', <?php echo countListPastor_Base("8", "Total Member");?>, <?php echo countListPastor_Base("8", "Total Nonmember");?>],
['CEB', <?php echo countListPastor_Base("9", "Total Member");?>, <?php echo countListPastor_Base("9", "Total Nonmember");?>],
['RXS', <?php echo countListPastor_Base("10", "Total Member");?>, <?php echo countListPastor_Base("10", "Total Nonmember");?>]
]);

var options1 = {
title: '',
titleTextStyle: {
color: '#505050',
fontSize: 31,
fontName: 'Source Sans Pro,sans-serif',
},
legend: 'none',
pieSliceText: 'label',
chartArea: {'width': '70%', 'height': '70%'},
slices: {
0: { color: '#9BD7D5' },
1: { color: '#FF7260' },
2: { color: '#505050' },
3: { color: '#129793' }
},
backgroundColor: 'transparent',

};

var options2 = {
title: '',
titleTextStyle: {
color: '#505050',
fontSize: 31,
fontName: 'Source Sans Pro,sans-serif',
},

legend: 'none',
pieSliceText: 'label',
chartArea: {'width': '70%', 'height': '70%'},
pieStartAngle: 220,
slices: {
0: { color: '#DCD9DF', offset: 0.2 },
1: { color: '#723FA9' },
2: { color: '#9BD7D5' },
3: { color: '#129793' },
4: { color: '#FF7260' },
5: { color: '#505050' }
},
backgroundColor: 'transparent',
};

var options3 = {
title: 'Members vs Non-Members',
legend: { position: "none" },
colors: ['#129793', '#FF7260'],
isStacked: false,
backgroundColor: 'transparent',
chartArea:{left:0,width:'100%',height:'100%'},

};

var view = new google.visualization.DataView(data3);
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
         role: "annotation" }]);

var chart1 = new google.visualization.PieChart(document.getElementById('denomination_chart'));
chart1.draw(data1, options1);

var chart2 = new google.visualization.PieChart(document.getElementById('education_chart'));
chart2.draw(data2, options2);

var chart3 = new  google.visualization.ColumnChart(document.getElementById('member_chart'));
chart3.draw(view, options3);
}
</script>

<script type='text/javascript'>
google.load('visualization', '1', {'packages': ['geochart']});
google.setOnLoadCallback(drawMap);

function drawMap() {
var data = google.visualization.arrayToDataTable([
['Base',   'Pastors', 'Churches'],
['Bacolod', <?php echo number_format(countListPastor_Base("1","Total Profile")).",".number_format(countListPastor_Base("1","Total Church")) ;?>],
['Bohol', <?php echo number_format(countListPastor_Base("2","Total Profile")).",".number_format(countListPastor_Base("2","Total Church")) ;?>],
['Dumaguete', <?php echo number_format(countListPastor_Base("3","Total Profile")).",".number_format(countListPastor_Base("3","Total Church")) ;?>],
['General Santos', <?php echo number_format(countListPastor_Base("4","Total Profile")).",".number_format(countListPastor_Base("4","Total Church")) ;?>],
['Koronadal', <?php echo number_format(countListPastor_Base("5","Total Profile")).",".number_format(countListPastor_Base("5","Total Church")) ;?>],
['Palawan', <?php echo number_format(countListPastor_Base("6","Total Profile")).",".number_format(countListPastor_Base("6","Total Church")) ;?>],
['Dipolog', <?php echo number_format(countListPastor_Base("7","Total Profile")).",".number_format(countListPastor_Base("7","Total Church")) ;?>],
['Iloilo', <?php echo number_format(countListPastor_Base("8","Total Profile")).",".number_format(countListPastor_Base("8","Total Church")) ;?>],
['Cebu', <?php echo number_format(countListPastor_Base("9","Total Profile")).",".number_format(countListPastor_Base("9","Total Church")) ;?>],
['Roxas', <?php echo number_format(countListPastor_Base("10","Total Profile")).",".number_format(countListPastor_Base("10","Total Church")) ;?>]
]);

var options = {
region: 'PH',
resolution: 'provinces',
legend: 'none',
displayMode: 'markers',
colorAxis: {colors: ['#FF7260', 'Red']},
backgroundColor: 'transparent',
datalessRegionColor: '#129793',
keepAspectRatio: 'false',
enable: true, zoomFactor: 7.5
};

var chart = new google.visualization.GeoChart(document.getElementById('map_div'));
chart.draw(data, options);
};
</script>
