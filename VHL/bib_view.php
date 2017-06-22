<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";
	include_once "_tnsFunctions.php";
	include_once "../Thrive/v2.0/_ptrFunctions.php";
	$application_pk = $_GET['a'];
	$week_id = "week_".$_GET['b'];
	$week_num = getBIB_week_number($week_id);
	$capital = "capital_".$_GET['b'];
	$bib_community_pk = $_GET['c'];
	$query = getParticipant_forApplication_byTag_temp($application_pk,"5","participant_id","=");
	$bib_community = getBIB_community($application_pk);
	$bib_class = $bib_community[$week_id];
	$community = getApplication_Data_byID($application_pk);
	$community_tag = $community['tag'];
	$hue = "";
	$target_week = 0;

	//dashboard
	$kit_capital = $bib_community[$capital];
	$sales_count = sumBIB_community_sales($bib_community_pk,$week_id);
	$dispersal_total = sumBIB_community_dispersal($week_id,$bib_community_pk);
	$dispersal_total = $dispersal_total > 0 ? $dispersal_total : 0;
	$total_balance = sumBIB_community_balance($week_id,$bib_community_pk);
	$total_balance = $total_balance > 0 ? $total_balance : 0;

	$dt = new DateTime();
	$date = $dt->format('Y-m-d');
	//$today = getDate_details('2015-11-22');
	$today = getDate_details($date);
	$today_week = $today['week_number'];

	$b = $bib_community[$capital];
	if($community_tag == 6)
		$week_diff = 4;
	else
		$week_diff = abs($today_week - $week_num);
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

	$actual_payment = sumBIB_community_payment($bib_community_pk,$week_id);
	if($actual_payment == '')
		$actual_payment = 0;
	$overall_capital = $b*$dispersal_total;
	$target_capital = $overall_capital*$multiplier;
	if($multiplier != 0 && $target_capital != 0 && $b != '')
		$target_week = $actual_payment/$target_capital*100;
	if($target_week < 100)
		$hue = "red";
?>

<style type="text/css">
	.box_parent {
		display: flex;
		height: 20px;
		background: ;
		width: 200px;
		margin: 8 8 8 8;
	}
	.box {
		float: left;
		width: auto;
		min-width: 24px;
    height: 17px;
    padding: 10px;
    background: ;
    border: 1px solid #666;
		position:static;
    color: #666;
    text-align: center;
		font-size: automatic;
		font-family: 'Roboto', sans-serif;
	}

	.box_label {

		width: 125px;
    padding: 8px;
		background: ;
    text-align: left;
		font-size: automatic;
		font-family: 'Roboto', sans-serif;
	}

	#title {
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		font-size:24px;
		color: #F4511E;
		padding: 20px 16px 24px 16px;
		display: inline-block;
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
	<div class="mdl-grid"><div class="mdl-cell mdl-cell--2-col">
		<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Back" onclick="back()"><i class="material-icons">chevron_left</i></button>
		<button name="summary" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="View Summary" onclick="contentChange('1')"><i class="material-icons">chrome_reader_mode</i></button>
		<button name="add_participant" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Add Participant" onclick="contentChange('2')"><i class="material-icons">person_add</i></button>
		<button name="refresh" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Refresh Page" onclick="location.reload()"><i class="material-icons">refresh</i></button>
	</div></div>
<span id="notice"></span>

<div class="mdl-grid mdl-cell--9-col mdl-cell--stretch mdl-cell--middle">
<div style="background:white;width:100%;" border='0'>
	<div id="title">WEEK <?php echo "$week_num - ".getBIB_string($bib_class)." Kit";?></div>
	<table border="0" width="100%">
		<tr>
			<td>
				<div class="box_parent"><div class="box"><?php echo $kit_capital;?></div><div class="box_label">Capital Per Kit</div></div><br/>
				<div class="box_parent"><div class="box"><?php echo $dispersal_total;?></div><div class="box_label">Dispersal</div></div><br/>
			</td>
			<td>
				<div class="box_parent"><div class="box"><?php echo number_format($overall_capital,2);?></div><div class="box_label">Total Capital</div></div><br/>
				<div class="box_parent"><div class="box"><?php echo number_format($total_balance,2);?></div><div class="box_label">Total Balance</div></div><br/>
			</td>
			<td style="vertical-align:top;">
				<div class="box_parent"><div class="box"><?php echo number_format($target_week,0);?>%</div><div class="box_label">Repayment Rate</div></div>
			</td>
		</tr>
	</table>
	<div>&nbsp;</div>
</div>
</div>

<div class="mdl-grid">
	<table class="mdl-data-table mdl-js-data-table" align="center" width="75%" style="background:white;" border='0'>
  	<thead>
  		<tr>
  			<th class="mdl-data-table__cell--non-numeric">Name</th>
  			<th class="mdl-data-table__cell--non-numeric">Type</th>
  			<th>Kits Taken</th>
  			<th>Capital per Participant</th>
  			<th>Total Payment</th>
  			<th>Balance</th>
  			<th>Action</th>
  		</tr>
  	</thead>
  	<tbody>
			<?php
				$count = 1;
				$query1 = getBIB_participant($bib_community_pk,$week_id);
				while($bib_participant=pg_fetch_array($query1,NULL,PGSQL_BOTH)) {
					$person_pk = $bib_participant['fk_participant_pk'];
					$bib_participant_pk = $bib_participant['id'];
					$class = $bib_participant['class'];
					if($class == "a") {
						$pastor = getPastorDetails($person_pk);
						$participant_id = "P".str_pad($pastor['id'], 6, 0, STR_PAD_LEFT);
						$participant_name = $pastor['lastname'].", ".$pastor['firstname'];
					}
					else if($class == "b") {
						$participant = getParticipantDetails($person_pk);
						$participant_id = $participant['participant_id'];
						$participant_name = $participant['last_name'].", ".$participant['first_name'];
					}
					$type = $bib_participant['type'];
					$type_string = $type == 1 ? "Individual" : "Group";
					$kit_count = $bib_participant['kit_count'];
					$capital = $bib_participant['capital'];
					$balance = $bib_participant['balance'];

					$payment = sumBIB_participant_payment($week_id,$bib_participant_pk,$class);
					echo "<tr>
						<td class='mdl-data-table__cell--non-numeric'>$count. ($participant_id) $participant_name</td>
						<td class='mdl-data-table__cell--non-numeric'>$type_string</td>
						<td>$kit_count</td>
						<td>".number_format($capital,2)."</td>
						<td>".number_format($payment,2)."</td>
						<td>".number_format($balance,2)."</td>
						<td>
							<a href='bib_update.php?a=$bib_community_pk&b=$bib_participant_pk'>Update</a>
							<a href='#' onclick='makePayment($bib_community_pk,$bib_participant_pk,\"$participant_name\",\"$class\")'>Payment</a>
							<a href='#' onclick='delete_bib_participant($bib_participant_pk,\"$participant_name\")'>Delete</a>
						</td>
					</tr>";
					$count++;
				}
			?>
  	</tbody>
  </table>
</div>

	<div class="mdl-grid mdl-cell--9-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" id="content3" style="background:#E0E0E0;" >
		<span style="margin-top:5px;margin-left:8px;color: #F4511E;">PAYMENT BY:&nbsp;&nbsp;</span>

		<span id="payee" style="margin-top:5px;font-weight:700;color:#525252;"></span><span hidden id="priceless"></span><span hidden id="p_class"></span>
		<span id="label_style1">Week</span>
		<select id="pay_week" class="mdl-select__input" onchange="activate_button2();checkDuplicate_BIB_payment(this.value)" >
			<option disabled selected value="Empty">Please Choose</option>
			<?php
				for($i=$week_num;$i<=16;$i++) {
					echo "<option value='$i'>$i</option>";
				}
			?>
		</select>
		<span id="label_style1">Sale</span>
		<select id="pay_sale" class="mdl-select__input" onchange="activate_button2()">
			<option disabled selected value="Empty">Please Choose</option>
			<option value="True">Yes</option>
			<option value="False">No</option>
		</select>
		<span id="label_style1">Cash Payment</span>
		<input type="number" min="0" id="pay_cash" onchange="activate_button2()" value="0"/>
		<span id="label_style1">Non-Cash Payment</span>
		<input type="number" min="0" id="pay_noncash" onchange="activate_button2()" value="0"/>
		<button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" id="fcpremix" onclick="insertPayment()"><i class="material-icons">add</i></button>
		<br/><div align="center" id="note"></div>
	</div>
	<br/>
	<div class="mdl-grid" id="content1">
	  <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center" width="70%" style="background:#E7E4DF;" border='0'>
	  	<thead>
	  		<tr>
	  			<th class="mdl-data-table__cell--non-numeric">Transaction ID</th>
	  			<th class="mdl-data-table__cell--non-numeric">Participant</th>
	  			<th class="mdl-data-table__cell--non-numeric">Week</th>
	  			<th class="mdl-data-table__cell--non-numeric">Sale</th>
	  			<th>Cash Payment</th>
					<th>Non-Cash Payment</th>
	  		</tr>
	  	</thead>
	  	<tbody>
				<?php
					$count = 1;
					$query1 = getBIB_payment_log_byWeek($bib_community_pk,$week_id);
					while($bib_payment=pg_fetch_array($query1,NULL,PGSQL_BOTH)){
						$tns_id = $bib_payment['tns_id'];
						$bib_participant_pk = $bib_payment['fk_bib_participant_pk'];
						$class = $bib_payment['class'];
						$person_pk = $bib_payment['fk_participant_pk'];

						if($class == "a") {
							$pastor = getPastorDetails($person_pk);
							$participant_id = "P".str_pad($pastor['id'], 6, 0, STR_PAD_LEFT);
							$participant_name = $pastor['lastname'].", ".$pastor['firstname'];
						}
						else if($class == "b") {
							$participant = getParticipantDetails($person_pk);
							$participant_id = $participant['participant_id'];
							$participant_name = $participant['last_name'].", ".$participant['first_name'];
						}

						$bib_participant = getList_BIB_participant_details($bib_participant_pk);
						$participant_pk = $bib_participant['fk_participant_pk'];

;
						$week_entry = $bib_payment['week_entry'];
						$sale = $bib_payment['sale'];
						$payment_cash = $bib_payment['payment_cash'];
						$payment_noncash = $bib_payment['payment_noncash'];
						$sale = ($sale == 't') ? "Yes" : "No";
						echo "<tr>
										<td class='mdl-data-table__cell--non-numeric' align='center'>$tns_id</td>
										<td class='mdl-data-table__cell--non-numeric'>($participant_id) $participant_name</td>
										<td class='mdl-data-table__cell--non-numeric'>$week_entry</td>
										<td class='mdl-data-table__cell--non-numeric'>$sale</td>
										<td>".number_format($payment_cash,2)."</td>
										<td>".number_format($payment_noncash,2)."</td>
									</tr>";
					}
	  		?>
	  	</tbody>
	  </table>
	</div>

	<div align="center">
	<div class="mdl-cell--6-col mdl-shadow--2dp" align="left" id="content2" style="background:#E7E4DF;">
		<div id="title">Add Participant</div>
		<table border="0" width="70%">
			<tr>
				<td id="label_style">Participant</td>
				<td id="content_style">
					<select id="add_person" class="mdl-select__input" style="width:275px" onchange="activate_button1()">
						<option disabled selected value="">Please Choose</option>
						<?php
							$pastor_pk = $community['pastor_id'];
							if($pastor_pk != 0) {
								$pastor_id = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
								$person_id = $pastor_pk."a";
								echo "<option value='$person_id'>($pastor_id) ".$community['pastor_last_name'].", ".$community['pastor_first_name']."</option>";
							}
							while($participant=pg_fetch_array($query,NULL,PGSQL_BOTH)){
								$participant_pk = $participant['id'];
								$participant_id = $participant['participant_id'];
								$person_id = $participant_pk."b";
								$participant_name = $participant['last_name'].", ".$participant['first_name'];
								echo "<option value='$person_id'>($participant_id) $participant_name</option>";
							}?>
					</select>
				</td>
			</tr>
			<tr>
				<td id="label_style">Type</td>
				<td id="content_style">
						<select id="add_type" class="mdl-select__input" onchange="activate_button1()">
							<option disabled selected value="Empty">Please Choose</option>
							<option value="1">Individual</option>
							<option value="2">Group</option>
						</select>
				</td>
			</tr>
			<tr>
				<td id="label_style">Capital</td>
				<td id="content_style">
					<input type='number' min='1' id='add_capital' onchange="activate_button1()" value="<?php echo $kit_capital;?>"/>
				</td>
			</tr>
			<tr>
				<td id="label_style">Kits Taken</td>
				<td id="content_style">
				<input type='number' min='1' value='$b' id='add_kit_count' onchange="activate_button1()"/>
				</td>
			</tr>
		</table>
		<br/>
			<div style="padding: 0 16 16 0;" align="right">
				<i id="result" style="color:#E68A2E;"></i>&nbsp;
				<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" id="uvdc" onClick="insertPerson()">
					<i class="material-icons">check</i></button>
			</div>
	</div>
	</div>

</body>
<script type="text/javascript">
document.getElementById('content2').style.display = "none";
document.getElementById('content3').style.display = "none";
document.getElementById("uvdc").disabled = true;
document.getElementById("fcpremix").disabled = true;

function delete_bib_participant(bib_participant_pk,name) {
	var del = confirm("Are you sure you want to BIB entry of "+name);
	if(del == true) {
		if(typeof XMLHttpRequest != 'udefined'){
	      xmlhttp = new XMLHttpRequest();
	  }else if(typeof ActiveXObject != 'undefined'){
	      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	  }else
	      throw new Error('You browser doesn\'t support ajax');

	  xmlhttp.open('GET', '_action.php?command=delete_bib_participant&a='+bib_participant_pk+'&b='+name, true);
	  xmlhttp.onreadystatechange = function (){
	      if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
	     	document.getElementById('notice').innerHTML = xmlhttp.responseText;
			}
	  };
	  xmlhttp.send(null);
	}
}

function back() {
	location.href = "bib_list.php?a=<?php echo $application_pk;?>";
}

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

function makePayment(bib_pk,bib_participant_pk,name,p_class) {
	document.getElementById('content1').style.display = "";
	document.getElementById('content2').style.display = "none";
	document.getElementById('content3').style.display = "";
	document.getElementById("priceless").value = bib_participant_pk;
	document.getElementById("p_class").value = p_class;
	document.getElementById("payee").innerHTML = name;
}

function activate_button1() {
	var participant_pk = document.getElementById("add_person").value;
	var type = document.getElementById("add_type").value;
	var kit_count = document.getElementById("add_kit_count").value;
	var capital = document.getElementById("add_capital").value;
	if(participant_pk != '' && type != '' && kit_count != '' && capital != '') {
		document.getElementById("uvdc").disabled = false;
	}
}

function activate_button2() {
	var week = document.getElementById("pay_week").value;
	var sale = document.getElementById("pay_sale").value;
	var cash = document.getElementById("pay_cash").value;
	var noncash = document.getElementById("pay_noncash").value;
	if(week != "Empty" && sale != "Empty" && (cash != '' || noncash != '')) {
		document.getElementById("fcpremix").disabled = false;
	}
}

function insertPerson() {
	var participant_pk = document.getElementById("add_person").value;
	var type = document.getElementById("add_type").value;
	var kit_count = document.getElementById("add_kit_count").value;
	var bib_class = '<?php echo $bib_class;?>';
	var bib_pk = '<?php echo $bib_community_pk;?>';
	var kit_capital = document.getElementById("add_capital").value;
	var week = '<?php echo $week_id;?>';
  var username = '<?php echo $username;?>';
  var xmlhttp = null;

  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues_livelihood.php?command=add_bib_person&a='+participant_pk+
	'&b='+type+
	'&c='+kit_count+
	'&d='+bib_class+
	'&e='+bib_pk+
	'&f='+week+
	'&g='+kit_capital+
	'&h='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
	     	document.getElementById('result').innerHTML = xmlhttp.responseText;
			if(xmlhttp.responseText == "SUCCESS: Participant Added!")
				location.reload();
		}
  };
  xmlhttp.send(null);
}

function insertPayment() {
	var bib_pk = '<?php echo $bib_community_pk;?>';
	var bib_participant_pk = document.getElementById("priceless").value;
	var person_class = document.getElementById("p_class").value;
	var week_id = '<?php echo $week_id;?>';
	var pay_week = document.getElementById("pay_week").value;
	var pay_sale = document.getElementById("pay_sale").value;
	var pay_cash = document.getElementById("pay_cash").value;
	var pay_noncash = document.getElementById("pay_noncash").value;
  var username = '<?php echo $username;?>';
  var xmlhttp = null;

  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues_livelihood.php?command=add_bib_payment&a='+bib_pk+
	'&b='+bib_participant_pk+
	'&c='+pay_week+
	'&d='+pay_sale+
	'&e='+pay_cash+
	'&f='+pay_noncash+
	'&g='+username+
	'&h='+week_id+
	'&i='+person_class, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200) {
     	document.getElementById('note').innerHTML = xmlhttp.responseText;
			document.getElementById("fcpremix").disabled = true;
			setTimeout("location.reload(true);",5000);
		}
  };
  xmlhttp.send(null);
}

function checkDuplicate_BIB_payment(week) {
	var bib_participant_pk = document.getElementById("priceless").value;
	var person_class = document.getElementById("p_class").value;

	var xmlhttp = null;
	if(typeof XMLHttpRequest != 'udefined'){
			xmlhttp = new XMLHttpRequest();
	}else if(typeof ActiveXObject != 'undefined'){
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}else
			throw new Error('You browser doesn\'t support ajax');

	xmlhttp.open('GET', '_insertvalues_livelihood.php?command=check_dup_payment&a='+bib_participant_pk+'&b='+week+'&c='+p_class, true);
	xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		document.getElementById('note').innerHTML = xmlhttp.responseText;
	};
	xmlhttp.send(null);
}

function countdown() {
    var i = document.getElementById('counter');
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>
</html>
