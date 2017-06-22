<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";
	include_once "_tnsFunctions.php";
	$application_pk = $_GET['a'];
	$week = "week_".$_GET['b'];
	$capital = "capital_".$_GET['b'];
	$query = getParticipant_forApplication_byTag($application_pk,"5","participant_id","=");
	$bib_community = getBIB_community($application_pk);

?>

<style type="text/css">
	#title {
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		font-size:24px;
		color: #F4511E;
		padding: 20px 16px 24px 16px;
	}
	td {
		vertical-align:middle;
	}
	#label_style {
		display: inline-block;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 15px;
		font-weight: 700;
		color: #a1a1a1;
		padding: 12px 12px 12px 36px;
		vertical-align: top;
		text-align: right;
		width: 136px;
		background: ;
		height: auto;
	}
	#input_style {
		width: 160px;
		height: 36px;
	}
	#content_style {
		display: inline-block;
		font-family: 'Roboto', sans-serif;
		font-size:14px;
		font-weight: 400;
		color: #212121;
		padding-left: 24px;
		min-height: 60px;
		padding-bottom: 0;
	}
	#label_style1 {
		display: inline-block;
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 15px;
		font-weight: 700;
		color: #a1a1a1;
		padding: 6px 6px 6px 18px;
		vertical-align: top;
		text-align: right;
		background: ;
		height: auto;
	}
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
		height: 24px;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Y</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</head>

<body>
	<div class="mdl-grid"><div class="mdl-cell mdl-cell--1-col">
		<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Back"><i class="material-icons">chevron_left</i></button>
		<button name="summary" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="View Summary" onclick="contentChange('1')"><i class="material-icons">chrome_reader_mode</i></button>
		<button name="add_participant" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Add Participant" onclick="contentChange('2')"><i class="material-icons">person_add</i></button>
	</div></div>
<span id="notice"></span>

<div class="mdl-grid">
  <div class="mdl-grid mdl-cell--9-col mdl-cell--stretch mdl-cell--middle" style="background:#E0E0E0;">
		<?php echo getBIB_string($bib_community[$week])." Kit";?>
		<br/>
		Capital: <?php echo $bib_community[$capital];?>
		<br/>
		Total Sales: <?php echo "5";?>
	</div>
	<table class="mdl-data-table mdl-js-data-table" align="center" width="77%" style="background:#E0E0E0;" border='0'>
  	<thead>
  		<tr>
  			<th class="mdl-data-table__cell--non-numeric">Name</th>
  			<th class="mdl-data-table__cell--non-numeric">Type</th>
  			<th>Kits Taken</th>
  			<th>Capital per Participant</th>
  			<th>Balance</th>
  			<th>Action</th>
  		</tr>
  	</thead>
  	<tbody>
  		<tr>
				<td class='mdl-data-table__cell--non-numeric'>1. (1501113201) Abraham Tan</td>
				<td class='mdl-data-table__cell--non-numeric'>Individual</td>
				<td>5</td>
				<td>5</td>
				<td>152.00</td>
				<td><a href="#" onclick="makePayment('(1501113201) Abraham Tan')">Payment</a></td>
			</tr>
  		<tr>
				<td class='mdl-data-table__cell--non-numeric'>2. (1501113202) Michael Coman</td>
				<td class='mdl-data-table__cell--non-numeric'>Group</td>
				<td>10</td>
				<td>5</td>
				<td>152.00</td>
				<td><a href="#" onclick="makePayment('(1501113202) Michael Coman')">Payment</a></td>
			</tr>
  		<tr>
				<td class='mdl-data-table__cell--non-numeric'>3. (1501113203) Bruce Wayne</td>
				<td class='mdl-data-table__cell--non-numeric'>Group</td>
				<td>20</td>
				<td>5</td>
				<td>152.00</td>
				<td><a href="#" onclick="makePayment('(1501113203) Bruce Wayne')">Payment</a></td>
				</tr>
  	</tbody>
  </table>
</div>

<form name='form' method='POST'>
	<div class="mdl-grid mdl-cell--9-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E0E0E0;" id="content3">
		<span id="label_style1">Participant</span>
		<span id="payee" style="vertical-align: middle;background: red;"></span>
		<span id="label_style1">Week</span>
		<select id="week" class="mdl-select__input" name="gender">
			<option disabled selected value="Empty">Please Choose</option>
			<?php
				for($i=5;$i<=16;$i++) {
					echo "<option value='$i'>$i</option>";
				}
			?>
		</select>
		<span id="label_style1">Sale</span>
		<select id="sale" class="mdl-select__input" name="gender">
			<option disabled selected value="Empty">Please Choose</option>
			<option value="True">Yes</option>
			<option value="False">No</option>
		</select>
		<span id="label_style1">Cash Payment</span>
		<input type="number" min="0"/>
		<span id="label_style1">Non-Cash Payment</span>
		<input type="number" min="0"/>
		<div class="icon material-icons">add</div>
	</div>

	<div class="mdl-grid" id="content1">
	  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center" width="70%" style="background:#E7E4DF;" border='0'>
	  	<thead>
	  		<tr>
	  			<th class="mdl-data-table__cell--non-numeric">Participant</th>
	  			<th class="mdl-data-table__cell--non-numeric">Week</th>
	  			<th class="mdl-data-table__cell--non-numeric">Sale</th>
	  			<th>Cash Payment</th>
					<th>Non-Cash Payment</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  		<tr>
					<td class='mdl-data-table__cell--non-numeric'>1. (1501113201) Abraham Tan</td>
					<td class='mdl-data-table__cell--non-numeric'>6</td>
					<td class='mdl-data-table__cell--non-numeric'>No</td>
					<td>50.00</td>
					<td>152.00</td>
				</tr>
	  		<tr>
					<td class='mdl-data-table__cell--non-numeric'>3. (1501113203) Bruce Wayne</td>
					<td class='mdl-data-table__cell--non-numeric'>6</td>
					<td class='mdl-data-table__cell--non-numeric'>Yes</td>
					<td>120.00</td>
					<td>30.00</td>
				</tr>
	  		<tr>
					<td class='mdl-data-table__cell--non-numeric'>1. (1501113201) Abraham Tan</td>
					<td class='mdl-data-table__cell--non-numeric'>7</td>
					<td class='mdl-data-table__cell--non-numeric'>Yes</td>
					<td>50.00</td>
					<td>152.00</td>
				</tr>
	  		<tr>
					<td class='mdl-data-table__cell--non-numeric'>2. (1501113202) Michael Coman</td>
					<td class='mdl-data-table__cell--non-numeric'>7</td>
					<td class='mdl-data-table__cell--non-numeric'>Yes</td>
					<td>50.00</td>
					<td>152.00</td>
				</tr>
	  		<tr>
					<td class='mdl-data-table__cell--non-numeric'>3. (1501113203) Bruce Wayne</td>
					<td class='mdl-data-table__cell--non-numeric'>7</td>
					<td class='mdl-data-table__cell--non-numeric'>Yes</td>
					<td>50.00</td>
					<td>152.00</td>
				</tr>
				<tr>
					<td class='mdl-data-table__cell--non-numeric'>1. (1501113201) Abraham Tan</td>
					<td class='mdl-data-table__cell--non-numeric'>8</td>
					<td class='mdl-data-table__cell--non-numeric'>No</td>
					<td>50.00</td>
					<td>152.00</td>
				</tr>
	  	</tbody>
	  </table>
	</div>

	<div class="mdl-grid" id="content2">
	  <div class="mdl-grid mdl-cell--4-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E7E4DF;">
		<div id="title">Add Participant</div>
		<table border="0" width="70%">
			<tr>
				<td id="label_style">Participant</td>
				<td id="content_style">
					<select id="education_graduate" class="mdl-select__input" name="bib_1">
						<option disabled selected value="">Please Choose</option>
						<?php
							while($participant=pg_fetch_array($query,NULL,PGSQL_BOTH)){
								$participant_pk = $participant['id'];
								$participant_id = $participant['participant_id'];
								$participant_name = $participant['last_name'].", ".$participant['first_name'];
								echo "<option value='$participant_pk'>($participant_id) $participant_name</option>";
							}?>
					</select>
				</td>
			</tr>
			<tr>
				<td id="label_style">Type</td>
				<td id="content_style">
						<select id="gender" class="mdl-select__input" name="gender">
							<option disabled selected value="Empty">Please Choose</option>
							<option value="1">Individual</option>
							<option value="2">Group</option>
						</select>
				</td>
			</tr>
			<tr>
				<td id="label_style">Kits Taken</td>
				<td id="content_style">
					<input type='number' min='1' value='$b' $lock id='$capital_id' onchange='updateCommunity_Capital(this.value,this.id)'/>
				</td>
			</tr>
		</table>
		<br/>
			<div style="padding: 0 16 16 0;" align="right">
				<i id="result" style="color:#E68A2E;"></i>&nbsp;
				<a class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onClick="insertCard()">
					<i class="material-icons">check</i></a>
			</div>
		</div>
	</div>

</form>

<script type="text/javascript">
document.getElementById('content2').style.display = "none";
document.getElementById('content3').style.display = "none";

function contentChange(a) {
	if(a == 1) {
		document.getElementById('content1').style.display = "";
		document.getElementById('content2').style.display = "none";
		document.getElementById('content3').style.display = "none";
	}
	else if(a == 2) {
		document.getElementById('content1').style.display = "none";
		document.getElementById('content2').style.display = "";
		document.getElementById('content3').style.display = "none";
	}
}

function makePayment(a) {
	document.getElementById('content1').style.display = "";
	document.getElementById('content2').style.display = "none";
	document.getElementById('content3').style.display = "";
	document.getElementById("payee").innerHTML = a;
}
</script>
</body>
</html>
