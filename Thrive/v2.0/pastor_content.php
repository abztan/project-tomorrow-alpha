
<?php
	include "_ptrFunctions.php";

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
		$sort_by = "id";

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
			$query = getPastor_list_byBase(21, $sort_by);
		else
			$query = getPastor_list_byBase($selected_base, $sort_by);
				$is_hidden = "no";
	}
	else {
		$query = getPastor_list_byBase($account_base, $sort_by);
		$is_hidden = "yes";
	}
?>

<style>
	.mdl-select__input {
	  border: none;
	  border-bottom: 1px solid rgba(0,0,0, 0.12);
	  display: inline-block;
	  font-size: 14px;
	  margin: 0;
	  padding: 4px 0;
	  width: 140px;
	  background: 14px;
	  text-align: left;
	  color: inherit;
	}

	#label_style {
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 15px;
		font-weight: 700;
		color: #a1a1a1;
		padding: 5px 8px 5px 8px;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="/ICM/_css/material.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>

<form name="form1" action="" method="POST">
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--4-col">
  <button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Back"><i class="material-icons">add</i></button>
  <button onClick="editPage()" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Edit"><i class="material-icons">search</i></button>
  </div>
</div>
<div  class="mdl-grid mdl-cell--2-col mdl-cell--stretch mdl-cell--middle mdl-shadow--2dp" style="background:#E0E0E0;">
</div>
</form>

<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" align="center" width="50%" style="background:#FAFAFA;">
	<thead>
		<tr>
			<th class="mdl-data-table__cell--non-numeric"><a href="pastor_list.php?a=id&b=<?php echo $selected_base; ?>">ID</a></th>
			<th class="mdl-data-table__cell--non-numeric">Name</th>
			<th class="mdl-data-table__cell--non-numeric">Base</th>
			<th class="mdl-data-table__cell--non-numeric">District</th>
			<th>Contact Numbers</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$result = pg_query($dbconn, $query);

			while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
			{
				$pastor_pk = $row['id'];
				$pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
				$pastor_name = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
				$base_id = $row['baseid'];
				$district_pk = $row['thriveid'];
				$district_name = getDistrict_alternateName($district_pk);
				$base = getBaseName($base_id);
				$contact = $row['contact1'];
				if($contact == "") {
					$contact = "-";
				}

				echo "<tr>
						  <td class='mdl-data-table__cell--non-numeric'>$pastor_pk_string</td>
						  <td class='mdl-data-table__cell--non-numeric'>$pastor_name</td>
						  <td class='mdl-data-table__cell--non-numeric'>$base</td>
						  <td class='mdl-data-table__cell--non-numeric'>($district_pk) $district_name</td>
						  <td>$contact</td>
						  <td><a href='profile_view.php?a=$pastor_pk'>View</a></td>
							</tr>";
			}
		?>
	</tbody>
</table>
<br/>
<div align="center">--</div>
<br/>

<script type="text/javascript">
document.getElementById('content2').style.display = "none";

function contentChange(a) {
	if(a == 1) {
		document.getElementById('content2').style.display = "none";
		document.getElementById('content1').style.display = "";
	}
	else if(a == 2) {
		document.getElementById('content1').style.display = "none";
		document.getElementById('content2').style.display = "";
	}
}
</script>
</html>
