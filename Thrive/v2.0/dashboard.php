
<?php
	//include "_ptrFunctions.php";

	/*session_start();
	$username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];*/
	$selected_base = "0";
	$option_a = "selected";
	$option_b = "";
	$option_c = "";
	$option_d = "";
	$option_e = "";
	$option_f = "";
	$option_g = "";
	$option_h = "";
	$option_i = "";
	$option_j = "";
	$option_k = "";
	$is_hidden = "yes";

	//selected community
	if(isset($_POST['base_display'])) {
		$selected_base = $_POST['base_display'];
	}

	$pastor_count = countListPastor_byBase("Total Pastor",$selected_base);
	$member_count = countListPastor_byBase("Total Member",$selected_base);
	$php_count = countPHP(1);
 	$e_1 = countListPastor_byBase("Total Education Empty",$selected_base);
	$e_2 = countListPastor_byBase("Total Education None",$selected_base);
	$e_3 = countListPastor_byBase("Total Education Elementary",$selected_base);
	$e_4 = countListPastor_byBase("Total Education High School",$selected_base);
	$e_5 = countListPastor_byBase("Total Education College",$selected_base);
	$e_6 = countListPastor_byBase("Total Education Post College",$selected_base);
	$seminary_count = countListPastor_byBase("Total Seminary",$selected_base);
	$male_count = countListPastor_byBase("Total Male",$selected_base);
	$female_count = countListPastor_byBase("Total Female",$selected_base);

	//check access level
	if($account_base == "99" || $account_base == "98") {
		$isvisible_base_list = "";
	}
	else {
		$isvisible_base_list = "hidden";
	}
?>

<style>
	table {
		border-collapse: collapse;
		border-spacing:0;
		white-space: nowrap;
	}
	td {
		padding: 16 16 16 16;
	}

	.td_style1 {
		padding: 0 0 0 0;
	}
}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
<br/><br/>
<form name="form1" action="" method="POST">
<table border = "1" align="center" width="50%" style="background:#FAFAFA;">
	<tr>
		<th colspan="4">
			<select <?php //echo $isvisible_base_list;?> style='width:automatic;' class='mdl-select__input' name='base_display' onChange='form.submit()'>
			<option selected value="0">All</option>
			<?php
				$result = getListBase();

				while($base_array = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					$base_id = $base_array['id'];
					$base_name = $base_array['name'];
					if($selected_base == $base_id)
						$isselected = "selected";
					else
						$isselected = "";
					echo "<option $isselected value='$base_id'>$base_name</option>";
				}
			?>
			</select>
		</th>
	</tr>
	<tr>
		<td colspan="2">Pastors<br/><?php echo $pastor_count;?></td>
		<td class="td_style1" rowspan="6"><div id="chart_div" style="width: 600px; height: 400px;"></div></td>
	</tr>
	<tr>
		<td>Members<br/><?php echo $member_count;?></td>
		<td>Program Holders<br/><?php echo $php_count;?></td>
	</tr>
	<tr>
		<td>Education<br/>
			<?php
				echo "EMPTY:$e_1</br>";
				echo "NONE:$e_2</br>";
				echo "ELEMENTARY:$e_3</br>";
				echo "HIGH SCHOOL:$e_4</br>";
				echo "COLLEGE:$e_5</br>";
				echo "POST GRADUATE:$e_6</br>";
			?>
		</td>
		<td>Attended Seminary<br/><?php echo $seminary_count;?></td>
	</tr>
	<tr>
		<td>Gender<br/><?php echo "MALE:$male_count<br/>FEMALE:$female_count";?></td>
		<td>Age<br/>
			<?php
				echo "20 below: ".countAge_byBase(1,20,$selected_base)."<br/>";
				echo "21-29: ".countAge_byBase(21,29,$selected_base)."<br/>";
				echo "30-39: ".countAge_byBase(30,39,$selected_base)."<br/>";
				echo "40-49: ".countAge_byBase(40,49,$selected_base)."<br/>";
				echo "50-59: ".countAge_byBase(50,59,$selected_base)."<br/>";
				echo "60-69: ".countAge_byBase(60,69,$selected_base)."<br/>";
				echo "70+: ".countAge_byBase(70,150,$selected_base)."<br/>";
			?>
		</td>
	</tr>
	<tr>
		<td>Churches<br/><?php echo countListPastor_byBase("Total Church",$selected_base)?></td>
		<td>Denomination<br/>5</td>
	</tr>
	<tr>
		<td>Districts<br/><?php echo $cd = countDistrict($selected_base);?></td>
		<td>Avg. Population<br/>
			<?php
				$x = 0;
				if($selected_base == "0")
					$query = getDistrict_List(21, 'district_id');
				else
					$query = getDistrict_List($selected_base, 'district_id');
				$result = pg_query($dbconn, $query);

				while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					$district_id = $row['district_id'];
					$x = $x + countPopulation_byTHID($district_id);
				}
				echo round($x/$cd,0);?>

		</td>
	</tr>
</table>
<br/>
<!--show if viewed per base-->
<table border = "1" align="center" width="50%" style="background:#FAFAFA;">
	<tr>
		<td class="td_style1" colspan="8"><div id="chart_div1" style="width: 100%; height: 400px;"></div></td>
	</tr>
	<tr>
		<th>District Name</th>
		<th>Pastors</th>
		<th>Members</th>
		<th>Current Program Holders</th>
		<th>Churches</th>
	</tr>
	<?php

		$query = getDistrict_List($selected_base, "district_id");
		$result = pg_query($dbconn, $query);
		while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
		{
			$district_id = $row['district_id'];
			$district_name = $row['alternate_name'];
			$base = getBaseName($base_id);
			$population = countPopulation_byTHID($district_id);
			$members = countMembers_byTHID($district_id,"t");
			$church = countChurches_byTHID($district_id);

			echo "<tr>
						<td class='mdl-data-table__cell--non-numeric'>($district_id) $district_name</td>
						<td>$population</td>
						<td>$members</td>
						<td>".date('Y').getBatch("current","")."</td>
						<td>$church</td>
						</tr>";
		}
	?>
</table>
<div align="center">--</div>
</form>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type='text/javascript'>
 google.load('visualization', '1', {'packages': ['geochart']});
 google.setOnLoadCallback(drawMarkersMap);

  function drawMarkersMap() {
  var data = google.visualization.arrayToDataTable([
    ['City',   'Population', 'Area Percentage'],
    ['Bacolod City',  65700000, 50],
    ['City of Talisay', 81890000, 27],
    ['Silay City',  38540000, 23]
  ]);

  var options = {
    sizeAxis: { minValue: 0, maxValue: 100 },
		region: 'PH',
		resolution: 'cities',
    displayMode: 'markers',
		backgroundColor: 'transparent',
		datalessRegionColor: '#129793',
		legend: 'none',
    colorAxis: {colors: ['#e7711c', '#4374e0']} // orange to blue
  };

  var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
  var chart1 = new google.visualization.GeoChart(document.getElementById('chart_div1'));

  chart.draw(data, options);
  chart1.draw(data, options);
 };
</script>
</html>
