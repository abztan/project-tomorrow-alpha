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

	//check access level
	if($account_base == "99" || $account_base == "98") {
		$disabled = "";
	}
	else {
		$disabled = "disabled";
    $selected_base = $account_base;
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
  //echo $selected_base;
  //echo "S:".$selected_batch."B";
  //echo $list_batch;
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
    Batch
    <select id = 'sup' name = 'batch_display' onchange = 'form.submit()'>
      <option disabled value = '0'>(Select One)</option>
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
      echo "<select id = 'sup' $disabled name = 'base_display' onchange = 'form.submit()'>
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
            </select>";
    ?>
<br/>
<br/>

<table border="1">
  <th><div><span>Community ID</span></div></th>
  <th><div><span>Pastor</span></div></th>
  <th><div><span>Location</span></div></th>
  <th><div><span>Program</span></div></th>
  <th>Size</th>
  <th>Participant Graduates</th>
  <th>Counselor Graduates</th>
  <th>Visitor Graduates</th>
  <th id="content_1" onclick="hideContent(1)"><div><span>4P</span></div></th>
  <th id="content_1"><div><span>NGO</span></div></th>
  <th id="content_1"><div><span>MFI</span></div></th>
  <th id="content_1"><div><span>B.CERT</span></div></th>
  <th id="content_1"><div><span>ATT CH</span></div></th>
  <th id="content_1"><div><span>BAP</span></div></th>
  <th id="content_1"><div><span>H2H WK1</span></div></th>
  <th id="content_1"><div><span>H2H WK2</span></div></th>
  <th id="content_1"><div><span>H2H WK3</span></div></th>
  <th id="content_1"><div><span>H2H WK4</span></div></th>
  <th id="content_2"><div><span>WK 1</span></div></th>
  <th id="content_2"><div><span>WK 2</span></div></th>
  <th id="content_2"><div><span>WK 3</span></div></th>
  <th id="content_2"><div><span>WK 4</span></div></th>
  <th id="content_2"><div><span>WK 5</span></div></th>
  <th id="content_2"><div><span>WK 6</span></div></th>
  <th id="content_2"><div><span>WK 7</span></div></th>
  <th id="content_2"><div><span>WK 8</span></div></th>
  <th id="content_2"><div><span>WK 9</span></div></th>
  <th id="content_2"><div><span>WK 10</span></div></th>
  <th id="content_2"><div><span>WK 11</span></div></th>
  <th id="content_2"><div><span>WK 12</span></div></th>
  <th id="content_2"><div><span>WK 13</span></div></th>
  <th id="content_2"><div><span>WK 14</span></div></th>
  <th id="content_2"><div><span>WK 15</span></div></th>
  <th id="content_2"><div><span>WK 16</span></div></th>
  <th id="content_2"><div><span>Action</span></div></th>
<?php
  $query = getApplication_byTag_byBatch($selected_base,5,"community_id",$selected_batch);
  $result = pg_query($dbconn, $query);

  while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $application_pk = $community['id'];
    $community_id = $community['community_id'];
    $pastor_name = $community['pastor_first_name']." ".$community['pastor_middle_initial']." ".$community['pastor_last_name'];
    $location = $community['application_barangay'].", ".$community['application_city']." - ".$community['application_province'];
    $application_type = getProgram($community['application_type']);
    $total_people = countParticipantTag($application_pk,5) + countParticipantTag($application_pk,6) + countParticipantTag($application_pk,9);
    $total_graduate = countParticipantTag($application_pk,6);
    $four_p = attendance_variable_count($application_pk, 1, "4ps");
    $ngo = attendance_variable_count($application_pk, 1, "ngo");
    $mfi = attendance_variable_count($application_pk, 1, "mfi");
    $birth_cert = attendance_variable_count($application_pk, 1, "birth_cert");
    $church = attendance_variable_count($application_pk, 1, "church");
    $baptised = attendance_variable_count($application_pk, 1, "baptised");
    $visitor_graduate = $community['visitor_graduate'];;
    $participant_graduate = attendance_people_count($application_pk,6,1)+attendance_people_count($application_pk,6,2)+attendance_people_count($application_pk,6,3)+attendance_people_count($application_pk,6,4)+attendance_people_count($application_pk,6,5)+attendance_people_count($application_pk,6,6);
    $counselor_graduate = attendance_people_count($application_pk,6,20)+attendance_people_count($application_pk,6,21)+attendance_people_count($application_pk,6,22);

    echo "<tr>
          <td>$community_id</td>
          <td>$pastor_name</td>
          <td>$location</td>
          <td>$application_type</td>
          <td>$total_people</td>
          <td>$participant_graduate</td>
          <td>$counselor_graduate</td>
          <td>$visitor_graduate</td>
          <td>$four_p</td>
          <td>$ngo</td>
          <td>$mfi</td>
          <td>$birth_cert</td>
          <td>$church</td>
          <td>$baptised</td>
          <td>".attendance_variable_count($application_pk, 'a', "h2h")."</td>
          <td>".attendance_variable_count($application_pk, 'b', "h2h")."</td>
          <td>".attendance_variable_count($application_pk, 'c', "h2h")."</td>
          <td>".attendance_variable_count($application_pk, 'd', "h2h")."</td>";
    for($a='a';$a<'q';$a++) {
      echo "<td>".attendance_variable_count($application_pk, $a, "attendance")."</td>";
    }
    echo "<td><a href='attendance_report_breakdown.php?a=$application_pk'>View</a></td></tr>";
  }

?>
</table>

<script>
function hideContent(a) {
  document.getElementById('content_1').style.display = "none";
}
</script>
</form>
