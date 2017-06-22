<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="editApplication.php";


include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";
include "../Thrive/_ptrFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];

//defaults
$message = "";
$hidden = "no";
$entry_id = $_GET['a'];
$row = getApplicationDetails($entry_id);

$appid = $row['application_id'];
$base_id = $row['base_id'];
$community_id = $row['community_id'];
$pastor_id = $row['pastor_id'];
$pastor = getPastorDetails($pastor_id);
$fname = $pastor['firstname'];
$mname = $pastor['middlename'];
$lname = $pastor['lastname'];

$pioneer_community = $row['pioneer_community'];
$application_province=$row['application_province'];
$application_city=$row['application_city'];
$application_barangay=$row['application_barangay'];
$app_date = $row['application_date'];
$app_type = $row['application_type'];
$base_id = $row['base_id'];
$a = "";
$b = "";
$c = "";
$d = "";
$e = "";

	if(isset($_POST['update']))
	{
		$application_date=$_POST['app_date'];
		$application_type=$_POST['app_type'];
		$application_province=$_POST['province'];
		$pastor_id=$_POST['pastor'];
		$application_city=$_POST['city'];
		$application_barangay=$_POST['barangay'];
		$pioneer_community=$_POST['pioneer_community'];

		updateApplication_1($entry_id, $pastor_id,$application_type,$application_province,$application_city,$application_barangay,$application_date,$username,$pioneer_community);
		$message = "Successfully updated application! Please refresh page to see changes.";
	}

	if(isset($_POST['back']))
	{
		header('location: /ICM/VHL/new_community_view.php?a='.$entry_id);
	}

	include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav>
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<fieldset style="width:725px;">
	<legend>Edit Application</legend>
	<label>Application ID:</label>
	<strong><?php echo $appid; ?></strong><br/>

	<label>Community ID:</label>
	<strong><?php echo $community_id; ?></strong><br/>

	<label>Base:</label>
	<?php echo getBaseName($base_id); ?>
	</select><br/>

	<?php
		if($app_type == "1")
			$a = "selected";
		else if($app_type == "2")
			$b = "selected";
		else if($app_type == "3")
			$c = "selected";
		else if($app_type == "4")
			$d = "selected";
		else if($app_type == "5")
			$e = "selected";
	?>

	<label>Program Type</label>
	<select id="app_type" name="app_type">
	<option <?php echo $a;?> value="1">Transform</option>
	<option <?php echo $b;?> value="2">Jumpstart</option>
	<option <?php echo $c;?> value="3">Transform OSY</option>
	<option <?php echo $d;?> value="4">Transform SLP</option>
	<option <?php echo $d;?> value="5">Transform PBSP</option>
	</select><br/>

	<label>Pioneering Church</label>
	<select id="pioneer_community" name="pioneer_community">
	<option <?php echo ($pioneer_community == 1 ? "selected" : "");?> value="1">Yes</option>
	<option <?php echo ($pioneer_community == 0 ? "selected" : "");?> value="0">No</option>
	</select><br/>

	<label>Application Date:</label>
	<input placeholder="Application Date" class="form-control input-sm" type="date" name="app_date"value=<?php echo $app_date;?> ><br/>

	<label>Address</label>
		<?php
			 $query = getProvinceListEx($application_province);
			 $result = pg_query($dbconn, $query);
		?>

	<select id="province" name="province" onChange="window.loadCityList()">
	<?php
		if($application_province == "")
			echo "<option selected disabled>Province</option>";
		else
			echo "<option disabled>Province</option><option selected>$application_province</option>";

		while ($row = pg_fetch_row($result)) {
			echo "<option value='$row[0]'>$row[0]</option>";
		}
	?>
	</select>

		<?php
			 $query = getCityListEx($application_province, $application_city);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='provinceContent' name='city' onChange='window.loadBarangayList()'>
	<?php
		if($application_city == "")
			echo "<option selected disabled>City/Municipality</option>";
		else
			echo "<option disabled>City/Municipality</option><option selected>$application_city</option>";

		while ($row = pg_fetch_row($result)) {
			echo "<option value='$row[0]'>$row[0]</option>";
		}
	?>
	</select>

		<?php
			 $query = getBarangayListEx($application_province,$application_city,$application_barangay);
			 $result = pg_query($dbconn, $query);
		?>

	<select id='barangayContent' name='barangay'>
	<?php
		if($application_city == "")
			echo "<option selected disabled>Barangay</option>";
		else
			echo "<option disabled>Barangay</option><option selected>$application_barangay</option>";

		while ($row = pg_fetch_row($result)) {
			echo "<option value='$row[0]'>$row[0]</option>";
		}
	?>
	</select><br/>

	<label>Pastor Name:</label>
	<?php
		$query = "SELECT *
							FROM list_pastor
							WHERE baseid = '$base_id'
							AND id <> '$pastor_id'
							ORDER BY lastname, firstname ASC";

		$result = pg_query($dbconn, $query);
		echo "<select id='pastorContent' name='pastor'>
		<option selected value='$pastor_id'>$lname, $fname $mname</option>";
		while ($row = pg_fetch_array($result)) {
			$id = $row['id'];
			$lname = $row['lastname'];
			$fname = $row['firstname'];
			$middlename = $row['middlename'];
			$mname = $middlename[0];
			echo "<option value='$id'>$lname, $fname $mname</option>";
		}
		echo "</select>";
	?>
	<br/>
	<br/><br/>
	<button type = "submit" name = "update"  class="btn btn-embossed btn-primary">Update</button>&nbsp;
	<button class="btn btn-embossed btn-primary" name='back'>Back</button>&nbsp;<?php echo $message;?>
	</fieldset>
<br/>
<br/>
</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>
