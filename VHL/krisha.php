<style>
body {font-size: 18px;}
td {
  padding: 4 4 4 4;

}
table {
  border-collapse: collapse;
  border-spacing: 0;
	padding: 10px 10px 10px 10px;

  font-family: 'Hind', sans-serif;
	font-size: 17px
}
@media print {
    body {font-size: 8px;}
    table { page-break-inside:avoid;
    transform: scale(1,1);}
    td {font-size: 8pt;}
    .no-print, .no-print *
    {
        display: none !important;
    }
    @page { margin: 10 10 10 10; }
    tr    { page-break-inside:auto; page-break-after:auto };
}

</style>
<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";
	include "../_reportFunctions.php";

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
	$is_hidden = "yes";
	$selected_batch = date("Y").getBatch("current","");

	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "application_id";

	//selected community
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

		$is_hidden = "no";

  //echo $selected_base;
  //echo "S:".$selected_batch."B";
  //echo $list_batch;
?>
<div class='no-print'>
<form name = "form1" action = "" method = "POST" >
    Batch
    <select id = 'sup' name = 'batch_display' onchange = 'form.submit()'>
      <?php
        $result = getBatch_list();
        while($batch_of_year = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
          $year = $batch_of_year['year'];
          $batch = $batch_of_year['batch'];
          if($selected_batch == $year.$batch)
            $is_selected = "selected";
          else
            $is_selected = "";
          echo "<option $is_selected value='".$year.$batch."'>$batch of $year</option>";
        }
      ?>
    </select>
    Base
    <?php
      echo "<select id = 'sup' name = 'base_display' onchange = 'form.submit()'>
            <option $option_a disabled value = '00'>(Select Base)</option>
            <option $option_b value = '01'>Bacolod</option>
            <option $option_c value = '02'>Bohol</option>
            <option $option_d value = '03'>Dumaguete</option>
            <option $option_e value = '04'>General Santos</option>
            <option $option_f value = '05'>Koronadal</option>
            <option $option_g value = '06'>Palawan</option>
            <option $option_h value = '07'>Dipolog</option>
            <option $option_i value = '08'>Iloilo</option>
            <option $option_j value = '09'>Cebu</option>
            <option $option_k value = '10'>Roxas</option>
            </select>";
    ?>
</form>
<br/>
<br/></div>
<?php
	$result = base_hhid($selected_base,"community_id",$selected_batch);
	$x = 1;
	while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$application_pk = $row['id'];
    $pastor_name = $row['pastor_first_name']." ".$row['pastor_last_name'];
		echo "
			<table border='1' width='100%'>
				<tr align='center'><td colspan='12'>$x. Community: ".$row['community_id']." - $pastor_name</td></tr>
				<tr align='center'>
					<td width='3%'>#</td>
					<td width='14%'>HHID</td>
					<td width='25%'>Name</td>
					<td width='5%'>Class</td>
          <td width='5%'>Tag</td>
					<td width='4%' rowspan='80'></td>
					<td width='5%'>Date (mm/dd/yyyy)</td>
					<td width='13%'>Successful</td>
					<td width='26%'>Unsuccessful</td>
				</tr>";

		$participant = base_participants($application_pk);
		$i = 1;
		while($person = pg_fetch_array($participant,NULL,PGSQL_BOTH)) {
      $category = $person['category'];
      if($category >= 1 && $category <=6) {
        $class = "Participant";

        echo "
  					<tr>
  						<td align='center'>$i</td>
  						<td align='center'>".$person['participant_id']."</td>
  						<td>".$person['last_name'].", ".$person['first_name']." ".$person['middle_name']."</td>
              <td align='center'>$class</td>
              <td align='center'>".$person['tag']."</td>
  						<td bgcolor='white'></td>
  						<td align='center'><div style='width:8px;height:8px;border:1px solid #000;'></div></td>
  						<td align='center'></div></td>";
  					$i++;
      }
      if($category >= 20 && $category <= 22)
        $class = "Counselor";


		}
			echo "</table><br/><br/>";
			$x++;
	}
?>
