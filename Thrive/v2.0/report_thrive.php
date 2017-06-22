<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}
  include_once "_ptrFunctions.php";
	//defaults
	$count = 1;
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
  //class array
  $class = array("1","2","3","4","5","6","20","21","22");
	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "application_id";

	//selected batch
	if(isset($_POST['batch_display'])) {
		$selected_batch = $_POST['batch_display'];
		$list_year = substr($selected_batch,2,4);
		$list_batch = substr($selected_batch,-1);
	}

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
		$is_hidden = "no";
	}
	else {
		$is_hidden = "yes";
	}
?>

<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  white-space: nowrap;
  font-family: 'Hind', sans-serif;
	font-size: 17px
}
</style>
<form name = "form1" action = "" method = "POST">
    Report
    <?php
      echo "
      <select id = 'x' name = 'report_diplay' onchange='form.submit()'>
        <option $option_a value = '0' disabled>(Select One)</option>
        <option $option_b value = '1'>Thrive Attendance</option>
        <option $option_c value = '2'>Profiles & Membership</option>
      </select>";
    ?>
    Base
    <?php
      if($is_hidden == "no")
      echo "<select id = 'sup' name = 'base_display' onchange = 'form.submit()'>
            <option $option_a value = '0' disabled>(Select One)</option>
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
            </select>";
    ?>
<br/>
<br/>

<table border="1" width="100%">
  <?php
    $year = "2015";
    echo "<th colspan='2'></th>";
    for($i=1;$i<=12;$i++){
      echo "<th>$i</th>";
    }

    $query = getDistrict_List($selected_base,"district_id");
    $result = pg_query($dbconn, $query);
    while($summary = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
      $district = $summary['district_id'];
      echo "
      <tr>
        <td rowspan='3'>$district</td>
        <td>Non Member</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Member</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Total</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>";
  }
  ?>
</table>

</form>
