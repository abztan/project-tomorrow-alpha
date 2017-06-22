<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";
	include_once "_tnsFunctions.php";

	$application_pk = $_GET['a'];
	$bib = getBIB_community($application_pk);
	if($bib['id']=='') {
		generateBIB_community($application_pk);
		$bib = getBIB_community($application_pk);
	}
	$lock = "disabled";

	$dt = new DateTime();
	$date = $dt->format('Y-m-d');
	//$today = getDate_details('2015-11-22');
	$today = getDate_details($date);
	$today_week = $today['week_number'];

	$application = getApplicationDetails($application_pk);
	$application_tag = $application['tag'];

	if($application_tag == 6)
		$today_week = 16;
?>

<style type="text/css">
	.mdl-select__input {
		border: none;
		border-bottom: 1px solid rgba(0,0,0, 0.12);
		display: inline-block;
		font-size: 14px;
		margin: 0;
		padding: 4px 0;
		width: 160px;
		background: 14px;
		text-align: left;
		color: inherit;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>BIB</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
<br/>
<form name='form1' action='' id='profile_form' method='POST'>
<span id="notice"></span>
<div class="mdl-grid">
  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center" width="80%" style="background:#FAFAFA;" border='0'>
  	<thead>
  		<tr>
  			<th class="mdl-data-table__cell--non-numeric">Week</th>
  			<th class="mdl-data-table__cell--non-numeric">BIB</th>
  			<th>Capital</th>
  			<th>Dispersal</th>
  			<th>Actual/Total Capital (PHP)</th>
  			<th>WK <?php echo $today_week;?>: Repayment Rate (%)</th>
  			<th>Action</th>
  		</tr>
  	</thead>
  	<tbody>
			<?php
			for($i=1;$i<6;$i++) {
				if($i == "1") {
					$week = "a";
					$week_num = "5";
				}
				else if($i == "2") {
					$week = "b";
					$week_num = "7";
				}
				else if($i == "3") {
					$week = "c";
					$week_num = "9";
				}
				else if($i == "4") {
					$week = "d";
					$week_num = "10";
				}
				else if($i == "5") {
					$week = "e";
					$week_num = "11";
				}

				$capital_id = "capital_".$week;
				$week_id = "week_".$week;
				$bib_pk = $bib['id'];
				$query = getBIB_kit();
				$v = $bib[$week_id];
				$b = $bib[$capital_id];
				$dispersal_total = sumBIB_community_dispersal($week_id,$bib_pk);
				$dispersal_total = ($dispersal_total > 0) ? $dispersal_total : 0;

				echo "
		  		<tr>
						<td class='mdl-data-table__cell--non-numeric'>$week_num</td>
						<td class='mdl-data-table__cell--non-numeric'>
							<select id='$week' class='mdl-select__input' onchange='updateCommunity_BIB(this.value,this.id)'>";

				if($v > 0) {
					echo "<option selected disabled value='$v'>".getBIB_string($v)."</option>";
					$lock = "";
				}
				else {
					echo "<option disabled selected value=''>Please Choose</option>";
					$lock = "disabled";
				}
									while($bib_kit=pg_fetch_array($query,NULL,PGSQL_BOTH)){
										$kit_pk = $bib_kit['id'];
										$kit_string = $bib_kit['kit_name'];
										echo "<option value='$kit_pk'>$kit_string</option>";
									}
				//if($b == '')
					echo "
								</select>
							</td>

							<td><input type='number' min='1' value='$b' id='$capital_id' onchange='updateCommunity_Capital(this.value,this.id)'/></td>";
			/*	else
					echo "
								</select>
							</td>
							<td>$b</td>";*/

				$hue = "";
				$target_week = 0;

				if($application_tag == 6) {
					$week_diff = 4;
				}
				else {
					$week_diff = abs($today_week - $week_num);
				}

				if($week_diff == 0)
					$multiplier = 0;
				else if($week_diff == 1)
					$multiplier = 0.25;
				else if($week_diff == 2)
					$multiplier = 0.5;
				else if($week_diff == 3)
					$multiplier = 0.75;
				else
					$multiplier = 1;

				$actual_payment = sumBIB_community_payment($bib_pk,$week_id);
				if($actual_payment == '')
					$actual_payment = 0;
				$overall_capital = $b*$dispersal_total;
				$target_capital = $overall_capital*$multiplier;
				if($multiplier != 0 && $target_capital != 0 && $b != '') {
					$target_week = $actual_payment/$target_capital*100;
				if($target_week < 100)
					$hue = "red";
				}

				echo "<td>$dispersal_total</td>
						<td><strong>".number_format($actual_payment,2)."</strong>/".number_format($overall_capital,2)."</td>
						<td><span title='".number_format($actual_payment,2)."/".number_format($target_capital,2)."'><strong><font color='$hue'>".number_format($target_week)."%</font></strong></span></td>";

				if($v != '')
					echo "<td><a href='bib_view.php?a=$application_pk&b=$week&c=$bib_pk'>View</a></td>";
				else
					echo "<td>View</td>";
					echo "</tr>";
			}?>
  	</tbody>
  </table>
	<span id="note"></span>
</div>
</form>

<script type="text/javascript">
function updateCommunity_BIB(bib,id) {
	var application_pk = '<?php echo $application_pk;?>';
  var username = '<?php echo $username;?>';
  var xmlhttp = null;

  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues_livelihood.php?command=update_community_bib&b='+application_pk+'&c='+id+'&d='+bib+'&e='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      document.getElementById('note').innerHTML = xmlhttp.responseText;
			location.reload();
  };
  xmlhttp.send(null);
}

function updateCommunity_Capital(value,id) {
	var application_pk = '<?php echo $application_pk;?>';
  var username = '<?php echo $username;?>';
  var xmlhttp = null;

  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues_livelihood.php?command=update_community_capital&b='+application_pk+'&c='+id+'&d='+value+'&e='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      document.getElementById('note').innerHTML = xmlhttp.responseText;
			location.reload();
  };
  xmlhttp.send(null);
}
</script>
</body>
</html>
