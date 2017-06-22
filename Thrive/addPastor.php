<?php
session_start();
$_SESSION['previouspage']=$_SESSION['currentpage'];

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];
	include "_ptrFunctions.php";

	$lname = "";
	$fname = "";
	$mname = "";
	$gender = "";
	$status = "";
	$bday = "";
	$education = "";
	$seminary = "";
	$province = "";
	$city = "";
	$barangay = "";
	$notice = "";

	if(isset($_POST['add']))
	{
		$lname=ucwords(strtolower($_POST['lname']));
		$fname=ucwords(strtolower($_POST['fname']));
		$mname=ucwords(strtolower($_POST['mname']));

		if(isset($_POST['gender']))
		  $gender=$_POST['gender'];
		else
		  $gender="";

		if(isset($_POST['bday']))
		  $bday=$_POST['bday'];
		else
		  $bday="";

		if(isset($_POST['status']))
		  $status=$_POST['status'];
		else
		  $status="";

		$contact1=$_POST['contact1'];
		$contact2=$_POST['contact2'];
		$contact3=$_POST['contact3'];
		$email=$_POST['email'];

		if(isset($_POST['educ']))
		  $education=$_POST['educ'];
		else
		  $education="";

		if(isset($_POST['seminary']))
		  $seminary=$_POST['seminary'];
		else
		  $seminary="";

		$position=ucwords(strtolower($_POST['position']));
		$th_area=strtoupper($_POST['th_area']);
		if(isset($_POST['province']))
		  $province=$_POST['province'];
		else
		  $province="";
		if(isset($_POST['city']))
		  $city=$_POST['city'];
		else
		  $city="";
		if(isset($_POST['barangay']))
		  $barangay=$_POST['barangay'];
		else
		  $barangay="";

		$address=$_POST['address'];

		$cname=ucwords(strtolower($_POST['c_name']));
		$denomination=ucwords(strtolower($_POST['c_denomination']));

		if(isset($_POST['c_province']))
		  $cprovince=$_POST['c_province'];
		else
		  $cprovince="";

		if(isset($_POST['c_city']))
		  $ccity=$_POST['c_city'];
		else
		  $ccity="";

		if(isset($_POST['c_barangay']))
		  $cbarangay=$_POST['c_barangay'];
		else
		  $cbarangay="";

		  $caddress=$_POST['c_address'];

		if(isset($_POST['c_planted']))
		  $isPlanted=$_POST['c_planted'];
		else
		  $isPlanted="";

		if(isset($_POST['base']))
		  $baseid=$_POST['base'];
		else
		  $baseid="";

		if($lname == "" || $fname == "" || $mname == "" || $gender == "" || $bday == "" || $status == "" || $education  == "" || $seminary == "" || $baseid  == "" || $province == "" || $city == "" || $barangay == "")
		  $notice = "There is a blank required* field.";


		else
		{
			//second level duplication test
			$unique_last = strtolower($lname);
			$unique_last = str_replace('-','', $unique_last);
			$unique_last = preg_replace('/[^A-Za-z0-9\-]/', '', $unique_last);

			$unique_first = strtolower($fname);
			$unique_first = str_replace('-','', $unique_first);
			$unique_first = preg_replace('/[^A-Za-z0-9\-]/', '', $unique_first);

			$unique_mi = strtolower($mname);
			$unique_mi = str_replace(' ','', $unique_mi);
			$unique_mi = substr($unique_mi, 0,1);

			$unique_birthday = $bday;
			$unique_birthday = date("mdY",strtotime(str_replace('-','', $unique_birthday)));

			$unique = $unique_first.$unique_last.$unique_mi.$unique_birthday;
			$uid_check = checkDuplicateByUniqueId($unique);

			$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);

			if($uid_check=="")
			{
				addPastor($lname,$fname,$mname,$gender,$bday,$status,$address,$province,$city,$barangay,$contact1,$contact2,
				$contact3,$email,$education,$seminary,$position,$th_area,$unique,$username,$baseid);

				if($cid=="")
				{
					addChurch($cname,$denomination,$cprovince,$ccity,$cbarangay,$caddress,$isPlanted,$username);
					$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
					$cid=getChurchId($cname,$cprovince,$ccity,$cbarangay);
				}
				else
				{
					$pid=getPastorId($lname,$fname,$mname,$bday,$gender);
				}

				setChurch($pid,$cid);
				addLogPastorProgram($pid);

				$notice = "Entry Successfully Added!";
			}
			else
				$notice="Entry already exists.";
		}
	}

	include "../_css/bareringtonbear.css";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		input, select {width: 225px;}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>

<nav id="navstyle">
<?php include "../controller.php"; ?>
</nav>

<form name='form1' action='' method='POST'>
<article id="content">
<section id="col1">
<h3><?php echo $notice;?></h3>
	<legend>Personal Information</legend>
	<label>Name*</label><br/>
	  <input class="form-control input-sm" placeholder="First Name/s" type="text" name="fname">
	  <input class="form-control input-sm" placeholder="Middle" type="text" name="mname">
	  <input class="form-control input-sm" placeholder="Last" type="text" name="lname">
	<br/>
	<label>Status*</label><br/>
	  <select id="status" name="status">
	  <option disabled selected>Please Choose</option>
	  <option value="Single">Single</option>
	  <option value="Married">Married</option>
	  <option value="Widowed">Widowed</option>
	  <option value="Separated">Separated</option>
	  </select>
	<br/>
	<label>Gender*</label><br/>
	  <select id="gender" name="gender">
	  <option disabled selected>Please Choose</option>
	  <option value="Male">Male</option>
	  <option value="Female">Female</option>
	  </select>
	<br/>
	<label>Birthday*</label><br/>
	  <input class="form-control input-sm" type="date" name="bday">
	<br/>
	<label>Contact Number</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Contact Number 1" maxlength="11" type="text" name="contact1">
	  <input class="form-control input-sm" placeholder="Contact Number 2" maxlength="11" type="text" name="contact2">
	  <input class="form-control input-sm" placeholder="Contact Number 3" maxlength="11" type="text" name="contact3">
	<br/>
	<label>Email</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Email Address" type="email" name="email">
	<br/>
	<label>Education*</label>
	<br/>
	  <select id="educ" name="educ">
	  <option disabled selected>Please Choose</option>
	  <option value="None">None</option>
	  <option value="Elementary">Elementary</option>
	  <option value="High School">High School</option>
	  <option value="College">College</option>
	  <option value="Post College">Post College</option>
	  <option value="Empty">Not Indicated</option>
	  </select>
	<br/>
	<label>Attended Seminary*</label>
	<br/>
	  <select id="seminary" name="seminary">
	  <option disabled selected>Please Choose</option>
	  <option value="True">Yes</option>
	  <option value="False">No</option>
	  <option value="Null">Not Indicated</option>
	  </select>
	 <br/>
	 <label>Base - Thrive Area*</label>
	<br/>
	  <select id="base" name="base" onChange="window.loadDistrictList()">
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
	  </select>

		<select id='districtContent' name='th_area'>
		<option disabled selected>District</option>
		</select>

	 <br/>
		<?php
			 $query=getProvinceList();
			 $result=pg_query($dbconn, $query);
		?>
	<label>Address*</label>
	<br/>
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

	<label>Other Info</label><br/>
	<input class="form-control input-sm" placeholder="Street Name, Etc" type="text" name="address">
	<br/><br/>

<legend>Church Information</legend>
	<label>Name &#40;no abbreviations&#41;</label><br/>
	  <input class="form-control input-sm" placeholder="Church Name" type="text" name="c_name">
	<br/>
	<label>Denomination</label><br/>
	 <input class="form-control input-sm" placeholder="Church Denomination" type="text" name="c_denomination">
	<br/>
	<label>Church Position</label>
	<br/>
	  <input class="form-control input-sm" placeholder="Indicate Church Position or Role" type="text" name="position">
	<br/>

	<?php
			 $query=getProvinceList();
			 $result=pg_query($dbconn, $query);
	?>

	<label>Address</label><br/>
	<select id="c_province" name="c_province" onChange="window.loadCityList1()">
	<option disabled selected>Province</option>
		<?php
			 while ($row=pg_fetch_row($result)) {
				 echo "<option value='$row[0]'>$row[0]</option>";
			 }
		?>
	</select>
	<select id='provinceContent1' name='c_city' onChange='window.loadBarangayList1()'>
	<option disabled selected>City/Municipality</option>
	</select>
	<select id='barangayContent1' name='c_barangay'>
	<option disabled selected>Barangay</option>
	</select>
	<br/>

	<label>Other Info</label><br/>
	<input class="form-control input-sm" placeholder="Street Name, Etc" type="text" name="c_address">
	<br/>

	<label>Planted Through ICM?</label>
	<br/>
	  <select id="c_planted" name="c_planted">
	  <option disabled selected>Please Choose</option>
	  <option value="Yes">Yes</option>
	  <option value="No">No</option>
	  <option value="Empty">Not Indicated</option>
	  </select>
	<br/><br/><br/><br/>
	<button class="btn btn-embossed btn-primary" type = "submit" name = "add">Done</button>
</section>
</article>
</form>

<script type="text/javascript">
	function loadCityList()
	{
	    var formName = 'form1';
	    var province = document[formName]['province'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertProvince(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadBarangayList()
	{
	    var formName = 'form1';
		var province = document[formName]['province'].value;
	    var city = document[formName]['city'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertBarangay(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadDistrictList()
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
				window.insertDistrict(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadCityList1()
	{
	    var formName = 'form1';
	    var province = document[formName]['c_province'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertProvince1(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function loadBarangayList1()
	{
	    var formName = 'form1';
		var province = document[formName]['c_province'].value;
	    var city = document[formName]['c_city'].value;

	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				window.insertBarangay1(xmlhttp);
	    };
	    xmlhttp.send(null);
	}

	function insertProvince(xhr){
	    if(xhr.status == 200){
	        document.getElementById('provinceContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertBarangay(xhr){
	    if(xhr.status == 200){
	        document.getElementById('barangayContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertDistrict(xhr){
	    if(xhr.status == 200){
	        document.getElementById('districtContent').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertProvince1(xhr){
	    if(xhr.status == 200){
	        document.getElementById('provinceContent1').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}

	function insertBarangay1(xhr){
	    if(xhr.status == 200){
	        document.getElementById('barangayContent1').innerHTML = xhr.responseText;
	    }else
	        throw new Error('Server has encountered an error\n'+
	            'Error code = '+xhr.status);
	}
</script>
<script src='default.js'></script>
</body>

</html>
