<?php
session_start();

if(empty($_SESSION['username'])) {
	header('location: /ICM/Login.php?a=2');
}
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";
  include_once "_tnsFunctions.php";

  $bib_community_pk = $_GET['a'];
  $bib_participant_pk = $_GET['b'];
	$type_a = "";
	$type_b = "";
	$bib_participant = getList_BIB_participant_details($bib_participant_pk);
	$participant_pk = $bib_participant['fk_participant_pk'];
	$participant_class = $bib_participant['class'];
	$week_id = $bib_participant['week'];
	$week = substr($bib_participant['week'],-1);
  $week_number = getBIB_week_number($week_id);
	$participant = getParticipantDetails($participant_pk);
	$application_pk = $participant['fk_entry_id'];
	$dispersal_type = $bib_participant['type'];
	$bib_class = $bib_participant['bib_class'];
	$x_capital = $bib_participant['capital'];
  $x_balance = $bib_participant['balance'];
  $live_balance = $x_capital - getBIB_payment_total_onKit($bib_participant_pk);
	if($dispersal_type == 1)
		$type_a = "checked";
	else if($dispersal_type == 2)
		$type_b = "checked";
	if($participant_class == "a")
		$class = "Pastor";
	else if($participant_class == "b")
		$class = "Participant";
?>
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto' rel='stylesheet' type='text/css'>
<style>
body {
  padding: 10 10 10 10;
  font-family: 'Roboto', sans-serif;
}
fieldset {
  width: 750px;
  padding: 10 10 10 10;
  margin: 5 5 5 5;
}
table {
	border-collapse: collapse;
	border-spacing:0;
	white-space: nowrap;
}
label {
  color: #4a646c;
  margin: 5 5 5 5;
}
</style>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>
<h2>Edit Participant BIB Data</h2>
<i style="color: red;">Note: All relevant data will immediately update once any of the values below are changed. Payment History data will not show messages on update.</i><br/><br/>
<span id="notice"></span><br/>
<fieldset>
  <u>BIB Information</u><br/><br/>
  <label>BIB Kit:</label> <?php echo "(Week ",$week_number,") ",getBIB_string($bib_class);?><br/>
  <label>Community Capital Rate:</label> <?php echo getBIB_community_kit_week_capital($bib_community_pk,$week);?><br/>
  <label>Status:</label> <?php echo ($live_balance == $x_balance) ? "<span style='color:#3cce80;'>Consistent</span>" : "<span style='color:#db3434;'>Inconsistent</span>";?>
</fieldset>
<br/>
<fieldset>
  <u>Participant Information</u><br/><br/>
  <label>Name:</label> <?php echo $participant['last_name'],", ",$participant['first_name'];?><br/>
  <label>Type:</label>
    <input type="radio" id="type-1" class="mdl-radio__button" name="type" value="1" <?php echo $type_a;?> onchange="updateMe('update_bib_type',this.value,<?php echo $bib_participant_pk;?>)">
    Individual&nbsp;
    <input type="radio" id="type-2" class="mdl-radio__button" name="type" value="2" <?php echo $type_b;?> onchange="updateMe('update_bib_type',this.value,<?php echo $bib_participant_pk;?>)">
    Group<br/>
  <label>Kits Taken:</label> &nbsp;<input type="number" id="kits_taken" value="<?php echo $bib_participant['kit_count'];?>" onchange="updateMe('update_kit',this.value,<?php echo $bib_participant_pk;?>)"/><br/>
  <label>Total Capital:</label> &nbsp;<input type="number" id="capital" value="<?php echo $bib_participant['capital'];?>" onchange="updateMe('update_capital',this.value,<?php echo $bib_participant_pk;?>)"/><br/>
  <label>Balance:</label> <?php echo $x_balance;?><br/>
	<label>Class:</label> <?php echo $class;?><br/>
</fieldset>
<br/>
<fieldset>
  <u>Payment History</u><br/><br/>
	<table border="1" width="100%">
		<tr>
			<th width="15%">Transaction ID</th>
			<th width="15%">Week</th>
			<th width="20%">Sale</th>
			<th width="20%">Cash</th>
			<th width="20%">Non-Cash</th>
			<th width="10%">Remove</th>
		</tr>
			<?php
				$sale_a = "";
				$sale_b = "";
				$query = getBIB_participant_payments_onKit($bib_participant_pk,$week_id);
				while($payment_log = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
					$sale_a = "";
					$sale_b = "";
					$payment_pk = "";
					$payment_pk = $payment_log['id'];
					if($payment_log['sale'] == "t") {
						$sale_a = "checked";
					}
					else if($payment_log['sale'] == "f") {
						$sale_b = "checked";
					}
					$week_entry = $payment_log['week_entry'];
					$cash = $payment_log['payment_cash'];
					$noncash = $payment_log['payment_noncash'];
					$week_id = $payment_log['week_id'];
					$payment_class = $payment_log['class'];
					echo "<tr>
						<td align='center'>$payment_pk</td>
						<td align='center'>Week $week_entry</td>
						<td align='center'>
							<label class='mdl-radio mdl-js-radio' for='sale-1-$payment_pk'>
						    <input type='radio' id='sale-1-$payment_pk' class='mdl-radio__button' name='sale-$payment_pk' value='t' $sale_a onchange='updateThingy(\"update_payment_sale\",this.value,$payment_pk)'>
						    <span class='mdl-radio__label'>Yes</span>
						  </label>
						  <label class='mdl-radio mdl-js-radio' for='sale-2'>
						    <input type='radio' id='sale-2-$payment_pk' class='mdl-radio__button' name='sale-$payment_pk' value='f' $sale_b onchange='updateThingy(\"update_payment_sale\",this.value,$payment_pk)'>
						    <span class='mdl-radio__label'>No</span>
						  </label>&nbsp;
						</td>
						<td align='right'><input style='width:100%;text-align:right;' type='number' min='0' id='capital' value='$cash' onchange='updateThingy(\"update_payment_cash\",this.value,$payment_pk)'/></td>
						<td align='right'><input style='width:100%;text-align:right;' type='number' min='0' id='capital' value='$noncash' onchange='updateThingy(\"update_payment_noncash\",this.value,$payment_pk)'/></td>
						<td align='center'><button onclick='deletePayment($payment_pk)'>x</button></a></td>
					</tr>";
				}
			?>
	</table>
<br/>
</fieldset>
<br/>
<fieldset>
	<u>Additional Information</u><br/><br/>
	<label>Tag:</label> <?php echo $bib_participant['tag'];?><br/>
	<label>Last Updated By:</label> <?php echo $bib_participant['updated_by'];?><br/>
	<label>Last Updated Date:</label> <?php echo $bib_participant['updated_date'];?><br/>
</fieldset>
<br/>
<button class="btn btn-embossed btn-primary" onclick="back()">Back</button>
</html>

<script>
function back() {
	location.href = "bib_view.php?a=<?php echo $application_pk."&b=".$week."&c=".$bib_community_pk;?>";
}

function updateMe(what,value,bib_participant_pk) {
	var username = "<?php echo $username;?>";
  //window.location.href = '_action.php?command=update_bib_participant&what='+what+'&bib_participant_pk='+bib_participant_pk+'&value='+value+'&username='+username;
  var xmlhttp = null;

	if(typeof XMLHttpRequest != 'udefined') {
		xmlhttp = new XMLHttpRequest();
	}
	else if(typeof ActiveXObject != 'undefined') {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	else {
		throw new Error('You browser doesn\'t support ajax');
	}

	xmlhttp.open('GET', '_action.php?command=update_bib_participant&what='+what+'&bib_participant_pk='+bib_participant_pk+'&value='+value+'&username='+username, true);
	xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		document.getElementById('notice').innerHTML = xmlhttp.responseText;
    setTimeout("location.reload(true);",5000);
	};
	xmlhttp.send(null);
}

function updateThingy(what,value,payment_pk) {
	var username = "<?php echo $username;?>";
  //window.location.href = '_action.php?command=update_payment&what='+what+'&payment_pk='+payment_pk+'&value='+value+'&username='+username;
	var xmlhttp = null;

	if(typeof XMLHttpRequest != 'udefined') {
		xmlhttp = new XMLHttpRequest();
	}
	else if(typeof ActiveXObject != 'undefined') {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	else {
		throw new Error('You browser doesn\'t support ajax');
	}

	xmlhttp.open('GET', '_action.php?command=update_payment&what='+what+'&payment_pk='+payment_pk+'&value='+value+'&username='+username, true);
	xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		location.reload();
	};
	xmlhttp.send(null);
}

function deletePayment(payment_pk) {
	var c = confirm("Are you sure you want to delete transaction "+payment_pk+"?");
	if(c == true) {
    var xmlhttp = null;

  	if(typeof XMLHttpRequest != 'udefined') {
  		xmlhttp = new XMLHttpRequest();
  	}
  	else if(typeof ActiveXObject != 'undefined') {
  		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  	}
  	else {
  		throw new Error('You browser doesn\'t support ajax');
  	}

  	xmlhttp.open('GET', '_action.php?command=delete_payment&payment_pk='+payment_pk, true);
  	xmlhttp.onreadystatechange = function (){
  			if(xmlhttp.readyState == 4 && xmlhttp.status==200)
  		document.getElementById('notice').innerHTML = xmlhttp.responseText;
      setTimeout("location.reload(true);",5000);
  	};
  	xmlhttp.send(null);
	}
}

function countdown() {
    var i = document.getElementById('counter');
    i.innerHTML = parseInt(i.innerHTML)-1;
}
setInterval(function(){ countdown(); },1000);
</script>
