
<?php
	include "_ptrFunctions.php";
	include "../dbconnect.php";
	include "../_parentFunctions.php";

	session_start();
	$username = $_SESSION['username'];
	$access_level = $_SESSION['accesslevel'];
	$account_base = $_SESSION['baseid'];
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

	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "district_id";

	//selected community
	if(isset($_POST['base_display'])) {
		$selected_base = $_POST['base_display'];
	}

	if($selected_base == "1")
		$option_b = "selected";
	else if($selected_base == "2")
		$option_c = "selected";
	else if($selected_base == "3")
		$option_d = "selected";
	else if($selected_base == "4")
		$option_e = "selected";
	else if($selected_base == "5")
		$option_f = "selected";
	else if($selected_base == "6")
		$option_g = "selected";
	else if($selected_base == "7")
		$option_h = "selected";
	else if($selected_base == "8")
		$option_i = "selected";
	else if($selected_base == "9")
		$option_j = "selected";
	else if($selected_base == "10")
		$option_k = "selected";
	else
		$option_a = "selected";

	//check access level
	if($account_base == "99" || $account_base == "98") {
		if($selected_base == "0")
			$query = getDistrict_List(21, $sort_by);
		else
			$query = getDistrict_List($selected_base, $sort_by);
				$is_hidden = "no";
	}
	else {
		$query = getDistrict_List($account_base, $sort_by);
		$is_hidden = "yes";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="/ICM/_css/material.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>

<form name = "form1" action = "" method = "POST">
<div  class="mdl-grid mdl-cell--2-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E0E0E0;">
	<i class="material-icons md-18">&#xE7F1;</i>&nbsp;Display Base&nbsp;&nbsp;&nbsp;
	<?php if($is_hidden == "no")
	echo "<select style='width:automatic;' name = 'base_display' onchange = 'form.submit()'>
				<option $option_a value = '0'>All</option>
				<option $option_b value = '1'>Bacolod</option>
				<option $option_c value = '2'>Bohol</option>
				<option $option_d value = '3'>Dumaguete</option>
				<option $option_e value = '4'>General Santos</option>
				<option $option_f value = '5'>Koronadal</option>
				<option $option_g value = '6'>Palawan</option>
				<option $option_h value = '7'>Dipolog</option>
				<option $option_i value = '8'>Iloilo</option>
				<option $option_j value = '9'>Cebu</option>
				<option $option_k value = '10'>Roxas</option>
				</select>";?>
</div>
</form>
<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center" width="50%" style="background:#FAFAFA;">
	<thead>
		<tr>
			<th class="mdl-data-table__cell--non-numeric"><a href="listDistrict.php?a=district_id&b=<?php echo $selected_base; ?>">ID</a></th>
			<th class="mdl-data-table__cell--non-numeric">Name</th>
			<th class="mdl-data-table__cell--non-numeric">Base</th>
			<th>Population</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$result = pg_query($dbconn, $query);

			while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
			{
				$district_id = $row['district_id'];
				$base_id = $row['base_id'];
				$district_name = $row['alternate_name'];
				$base = getBaseName($base_id);
				$population = countPopulation_byTHID($district_id);

				echo "<tr>
						  <td class='mdl-data-table__cell--non-numeric'>$district_id</td>
						  <td class='mdl-data-table__cell--non-numeric'>$district_name</td>
						  <td class='mdl-data-table__cell--non-numeric'>$base</td>
						  <td>$population</td>
						  <td><a href='viewDistrict.php?a=$district_id'>View</a></td>
							</tr>";
			}
		?>
	</tbody>
</table>
<br/>
<div align="center">--</div>
<br/>
</html>
