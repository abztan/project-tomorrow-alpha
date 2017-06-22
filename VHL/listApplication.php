<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];
$_SESSION['currentpage']="listApplication.php";

include "../_css/bareringtonbear.css";
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";
include "../Thrive/_ptrFunctions.php";

if(empty($_SESSION['username']))
header('location: /ICM/Login.php?a=2');
$username = $_SESSION['username'];
$accesslevel = $_SESSION['accesslevel'];


//defaults
if(isset($_GET['a']))
	$message = $_GET['a'];
else
	$message = "";
$count = "1";
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
$hidden = "no";



	if(isset($_POST['base_display']))
	{
		$selected_base=$_POST['base_display'];

		if($selected_base=="1")
			$option_b="selected";
		else if($selected_base=="2")
			$option_c="selected";
		else if($selected_base=="3")
			$option_d="selected";
		else if($selected_base=="4")
			$option_e="selected";
		else if($selected_base=="5")
			$option_f="selected";
		else if($selected_base=="6")
			$option_g="selected";
		else if($selected_base=="7")
			$option_h="selected";
		else if($selected_base=="8")
			$option_i="selected";
		else if($selected_base=="9")
			$option_j="selected";
		else if($selected_base=="10")
			$option_k="selected";
		else
			$option_a="selected";
	}

	if($accesslevel == "5")
	{
		$query1 = getListApplicationByBase_ByTag($_SESSION['baseid'], 1);
		$hidden = "yes";
	}
	else
	{
		if($selected_base == "0")
			$query1 = getListApplicationByTag(1);
		else
			$query1 = getListApplicationByBase_ByTag($selected_base, 1);

		$hidden = "no";
	}

	if(isset($_POST['add']))
	{
		$pastor_id = $_POST['pastor'];
		$pastor = getPastorDetails($pastor_id);
		$last_name=ucwords(strtolower($pastor['lastname']));
		$first_name=ucwords(strtolower($pastor['firstname']));
		$middle_initial=ucwords(strtolower($pastor['middlename']));
		$application_date=$_POST['app_date'];
		$application_type=$_POST['app_type'];
		if(isset($_POST['province']))
			$application_province=$_POST['province'];
		else
			$application_province="";
		if(isset($_POST['city']))
			$application_city=$_POST['city'];
		else
			$application_city="";
		if(isset($_POST['barangay']))
			$application_barangay=$_POST['barangay'];
		else
			$application_barangay="";

		$base_id=$_SESSION['baseid'];
		//$base_id=$_POST['base'];

		/*if(checkApplicationEntry($last_name,$first_name,$middle_initial)!="")
			$message = "Sorry but this entry already exists.";

		else
		{*/
				if($last_name != "" && $first_name != "" && $application_date != "" && $application_type != "")
				{
					$pastor_id=getPastorIdShort($last_name,$first_name,$middle_initial);

					if($pastor_id=="")
						$pastor_id=0;

					$application_id = getNextApplicationId();
					addApplication($pastor_id,$last_name,$first_name,$middle_initial,$application_id,$application_type,$application_province,$application_city,$application_barangay,$application_date,$base_id,$username);

					$message = "Successfully added application!";
				}

				else
					$message = "Please make sure all requested information are provided.";
		}
	//}
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
	<legend>Add Application</legend>
	<label>Application ID:</label>
	<strong><?php echo getNextApplicationId(); ?></strong><br/>

	<label>Base</label>
	<select id="base" name="base" onChange="window.loadPastorList()">
	<option disabled selected>Please Choose</option>
	<option value="1">Bacolod</option>
	<option value="2">Bohol</option>
	<option value="3">Dumaguete</option>
	<option value="4">General Santos</option>
	<option value="5">Koronadal</option>
	<option value="6">Palawan</option>
	<option value="7">Dipolog</option>
	<option value="8">Iloilo</option>
	<option value="9">Cebu</option>
	<option value="10">Roxas</option>
	</select><br/>

	<label>Program Type:</label>
	<select id="app_type" name="app_type">
	<option disabled selected>Please Choose</option>
	<option value="1">Transform</option>
	<option value="2">Jumpstart</option>
	<option value="3">Transform OSY</option>
	<option value="4">Transform SLP</option>
	<option value="5">Transform PBSP</option>
	</select><br/>

	<label>Application Date:</label>
	<input placeholder="Application Date" class="form-control input-sm" type="date" name="app_date"><br/>

	<?php
			 $query=getProvinceList();
			 $result=pg_query($dbconn, $query);
		?>
	<label>Address:</label>
	<select id="province" name="province" onChange="window.loadCityList()">
	<option disabled selected>Province</option>
		<?php
			 while ($row=pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<select id='provinceContent' name='city' onChange='window.loadBarangayList()'>
	<option disabled selected>City/Municipality</option>
	</select>
	<select id='barangayContent' name='barangay'>
	<option disabled selected>Barangay</option>
	</select>
	<br/>

	<label>Pastor Name:</label>
	<select id='pastorContent' name='pastor'>
	<option disabled selected>Pastor</option>
	</select>
	<br/>
	<br/><br/>
	<label><button type = "submit" name = "add"  class="btn btn-embossed btn-primary">Add</button>&nbsp;<?php echo $message;?></label>
	</fieldset>
<br/>
<br/>
<?php
	if($hidden != "yes")
	echo '
	<label>Displayed Base:</label>
	<select id="base_display" name="base_display" onchange="form.submit()">
	<option '.$option_a.' value="0" >All</option>
	<option '.$option_b.' value="1">Bacolod</option>
	<option '.$option_c.' value="2">Bohol</option>
	<option '.$option_d.' value="3">Dumaguete</option>
	<option '.$option_e.' value="4">General Santos</option>
	<option '.$option_f.' value="5">Koronadal</option>
	<option '.$option_g.' value="6">Palawan</option>
	<option '.$option_h.' value="7">Dipolog</option>
	<option '.$option_i.' value="8">Iloilo</option>
	<option '.$option_j.' value="9">Cebu</option>
	<option '.$option_k.' value="10">Roxas</option>
	</select><br/><br/>';?>

	<table id="listtable">
	<tr>
	  <th></th>
	  <th>Application ID</th>
	  <th>Base</th>
	  <th>Program</th>
	  <th>Pastor</th>
	  <th>Application Date</th>
	  <th>Action</th>
	</tr>
	<?php

		$result = pg_query($dbconn, $query1);

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$base_id = $row['base_id'];
		$entry_id = $row['id'];
		$application_type = $row['application_type'];

		$base = getBaseName($base_id);

		if($application_type == "1")
			$application_type = "Transform";
		else if($application_type == "2")
			$application_type = "Jumpstart";

		echo "<tr>
				  <td>".$count."</td>
				  <td align='center'>".$row['application_id']."</td>
				  <td align='center'>".$base."</td>
				  <td align='center'>".$application_type."</td>";

		if($row['pastor_id'] != 0)
		{
			echo "<td><a href='/ICM/Thrive/viewPastor.php?a=".$row['pastor_id']."'>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</a></td>";
		}

		else
		{
			echo "<td>".$row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial']."</td>";
		}

		echo "<td align='center'>".$row['application_date']."</td>
				  <td align='center'><a href='editApplication.php?a=".$row['id']."'>Edit</a>&nbsp;|&nbsp;<a onClick=\"javascript: return confirm('Move to &lsquo;For Ocular Visit&lsquo; list?');\" href='_applicationaction.php?a=1&b=".$row['id']."&c=".$base_id."'>Approve</a>&nbsp;|&nbsp;<a onClick=\"javascript: return confirm('Are you sure you want to delete this application?');\" href='_applicationaction.php?a=10&b=".$row['id']."'>Delete</a></td></tr>";

		$count++;
	}
	?>
</section>

<section id="col2">

</section>
</article>
</form>

<script src='default.js'></script>
<script>
	function loadPastorList()
	{
	  var formName = 'form1';
		var base = document[formName]['base'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?base='+base, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertPastors(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function insertPastors(xhr){
	    if(xhr.status == 200){
	        document.getElementById('pastorContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
</body>

</html>
