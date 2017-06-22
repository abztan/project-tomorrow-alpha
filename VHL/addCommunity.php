<?php
session_start();

if(empty($_SESSION['username']))
	header('location: Login.php?a=2');

	include "pastor_functions.php";
	$checker = "";

	if(isset($_POST['add']))
	{
		$city="";
		$geotype1="";
		$code=$_POST['code'];
		$category=$_POST['category'];
		$province=$_POST['province'];
		if(isset($_POST['city']))
		$city=$_POST['city'];
		if(isset($_POST['barangay']))
		$barangay=$_POST['barangay'];
		$geotype=$_POST['geotype'];
		$pastor=$_POST['pastor'];
		$address="";
		$lat=$_POST['latitude'];
		$lng=$_POST['longitude'];

		$checker = addCommunity($code,$category,$province,$city,$barangay,$geotype,$pastor,$address,$lat,$lng);
		//header("location: _setvalues.php?call_function=addCommunity&a='+category+'&b='+code+'&c='+province+'&d='+city+'&e='+geotype+'&f='+geotype1+'&g='+pastor+'&h='+address+'&i='+lat+'&j='+lng",true);
	}

	include "../_css/bareringtonbear.css";
	include "../dbconnect.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST' >
<article id="content">

<section id="col1">
	<legend>Community Information</legend><h3><?php echo $checker;?></h3>
	<label>Code</label>
	<input class="form-control input-sm" placeholder="" type="text" name="code">
	<br/>

	<label>Category</label>
	<select id="category" name="category">
	<option value="Transform">Transform</option>
	<option value="Jumpstart">Jumpstart</option>
	</select>

	<br/>

		<?php
			 $query = getProvinceList();
			 $result = pg_query($dbconn, $query);
		?>

	<label>Province</label>
	<select id="province" name="province" onChange="window.loadCityList()">
	<option disabled selected>Please Choose</option>
		<?php
			 while ($row = pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<br/>

	<label>City/Municipality</label>
	<select id='provinceContent' name='city' onChange='window.loadBarangayList()'>
	<option disabled selected>Please Choose</option>
	</select>
	<br/>

	<label>Barangay</label>
	<select id='barangayContent' name='barangay'>
	<option disabled selected>Please Choose</option>
	</select>
	<br/>

	<label>Geotype</label>
	<select id="geotype" name="geotype"  onChange="window.loadGeotype()">
	<option disabled selected>Please Choose</option>
	<option value="Coastal">Coastal</option>
	<option value="Urban Slum">Urban Slum</option>
	<option value="Urban Mountain">Urban Mountain</option>
	<option value="Rural Plain">Rural Plain</option>
	<option value="Rural Mountain">Rural Mountain</option>
	</select>
	<br/>

	<!--<div id='geotypeContent'></div>-->

	<label>Pastor</label> <input class="form-control input-sm" placeholder="" type="text" name="pastor">
	<br/>

	<!--<label>Address</label> <input class="form-control input-sm" placeholder="" type="text" name="address">
	<br/>-->

	<label>Latitude</label>
	<input class="form-control input-sm" placeholder="" type="number" step="any" name="latitude" id="clicklat">
	<br/>

	<label>Longitude</label>
	<input class="form-control input-sm" placeholder="" type="number" step="any" name="longitude" id="clicklng">
	<br/>
	<br/>
	<br/>
	<button class="btn btn-embossed btn-primary" type = "submit" name = "add">Done</button>
</section>

<section id="mapcol">
	<legend>Coordinates</legend>
	<div id="mapdiv"></div>
	Lat: <span id="latspan"></span>
	Lng: <span id="lngspan"></span>
	</section>
</article>
</form>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false"></script>
<script src='default.js'></script>
</body>

</html>
