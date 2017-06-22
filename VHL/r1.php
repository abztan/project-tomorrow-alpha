<html>
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto+Condensed' rel='stylesheet' type='text/css'>
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
  $h2h_a_total = 0;
  $h2h_b_total = 0;
  $h2h_c_total = 0;
  $h2h_d_total = 0;
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



  //echo $selected_base;
  //echo "S:".$selected_batch."B";
  //echo $list_batch;
?>

<style>
body {
  font-family: 'Roboto Condensed', sans-serif;
}

table {
  border-collapse: collapse;
  overflow: hidden;
  border-spacing: 0;
  white-space: nowrap;
  font-family: 'Roboto Mono';
	font-size: 14px;
}

td {
  padding: 7px;
  position: relative;
}

th {
  font-family: 'Roboto Condensed', sans-serif;
  padding: 5px;
  position: relative;
  background-color: #545454;
  color: white;
}

#text {
  font-family: 'Roboto Condensed', sans-serif;
  font-size: 12px;
}

tr:hover {
  background-color: #ffd5d5;
}

td:hover::after,
th:hover::after {
  content: "";
  position: absolute;
  background-color: #ffd5d5;
  left: 0;
  top: -5000px;
  height: 10000px;
  width: 100%;
  z-index: -1;
}

td:focus::after,
th:focus::after {
  content: '';
  background-color: lightblue;
  position: absolute;
  left: 0;
  height: 10000px;
  top: -5000px;
  width: 100%;
  z-index: -1;
}

td:focus::before {
  background-color: lightblue;
  content: '';
  height: 100%;
  top: 0;
  left: -5000px;
  position: absolute;
  width: 10000px;
  z-index: -1;
}
</style>

<form name = "form1" action = "" method = "POST">
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
    </select><br/>
    Base
    <?php
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

<?php
  if($selected_base != '0') {
    echo "
    <table border='1' width='875px'>
      <th></th>
      <th>Community ID</th>
      <th>Pastor</th>
      <th>Location</th>
      <th>H2H WK1</th>
      <th>H2H WK2</th>
      <th>H2H WK3</th>
      <th>H2H WK4</th>
      <th>WK 1</th>
      <th>WK 2</th>
      <th>WK 3</th>
      <th>WK 4</th>
      <th>WK 5</th>
      <th>WK 6</th>
      <th>WK 7</th>
      <th>WK 8</th>
      <th>WK 9</th>
      <th>WK 10</th>
      <th>WK 11</th>
      <th>WK 12</th>
      <th>WK 13</th>
      <th>WK 14</th>
      <th>WK 15</th>
      <th>WK 16</th>";

      $i = 1;
      $query = getApplication_byTag_byBatch($selected_base,5,"community_id",$selected_batch);
      $result = pg_query($dbconn, $query);

      while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
        $application_pk = $community['id'];
        $community_id = $community['community_id'];
        $pastor_name = $community['pastor_first_name']." ".$community['pastor_middle_initial']." ".$community['pastor_last_name'];
        $location = $community['application_barangay'].", ".$community['application_city'];
        $application_type = getProgram($community['application_type']);
        $h2h_a = attendance_variable_count($application_pk, 'a', "h2h");
        $h2h_b = attendance_variable_count($application_pk, 'b', "h2h");
        $h2h_c = attendance_variable_count($application_pk, 'c', "h2h");
        $h2h_d = attendance_variable_count($application_pk, 'd', "h2h");
        $h2h_a_total = $h2h_a_total + $h2h_a;
        $h2h_b_total = $h2h_b_total + $h2h_b;
        $h2h_c_total = $h2h_c_total + $h2h_c;
        $h2h_d_total = $h2h_d_total + $h2h_d;

        echo "<tr>
              <td align='center'>$i</td>
              <td align='center' title='$application_type'>$community_id</td>
              <td class='text'>$pastor_name</td>
              <td class='text'>$location</td>
              <td align='right'>$h2h_a</td>
              <td align='right'>$h2h_b</td>
              <td align='right'>$h2h_c</td>
              <td align='right'>$h2h_d</td>";
        for($a='a';$a<'q';$a++) {
          echo "<td align='right'>".attendance_variable_count($application_pk, $a, "attendance")."</td>";
        }
        echo "</tr>";
        $i++;
      }

      echo "</table><br/><br/>
      <table border='1'>";

      $i--;

      //$h2h_a_total = count_attendance($selected_batch,$selected_base,"a","h2h");

      echo "<tr>
              <th>SUMMARY</th>
              <th>H2H WK1</th>
              <th>H2H WK2</th>
              <th>H2H WK3</th>
              <th>H2H WK4</th>
              <th>WK 1</th>
              <th>WK 2</th>
              <th>WK 3</th>
              <th>WK 4</th>
              <th>WK 5</th>
              <th>WK 6</th>
              <th>WK 7</th>
              <th>WK 8</th>
              <th>WK 9</th>
              <th>WK 10</th>
              <th>WK 11</th>
              <th>WK 12</th>
              <th>WK 13</th>
              <th>WK 14</th>
              <th>WK 15</th>
              <th>WK 16</th>
            </tr>
            <tr>
              <td>TOTAL</td>
              <td align='right'>$h2h_a_total</td>
              <td align='right'>$h2h_b_total</td>
              <td align='right'>$h2h_c_total</td>
              <td align='right'>$h2h_d_total</td>";
                for($a='a';$a<'q';$a++) {
                  $attendance = count_attendance($selected_batch,$selected_base,$a,"general");
                  echo "<td align='right'>$attendance</td>";
              }
      echo "</tr>";

      echo "<tr>
              <td>AVERAGE</td>
              <td align='right'>".round($h2h_a_total/$i)."</td>
              <td align='right'>".round($h2h_b_total/$i)."</td>
              <td align='right'>".round($h2h_c_total/$i)."</td>
              <td align='right'>".round($h2h_d_total/$i)."</td>";
                for($a='a';$a<'q';$a++) {
                  $attendance = count_attendance($selected_batch,$selected_base,$a,"general");
                  echo "<td align='right'>".round($attendance/$i)."</td>";
              }
      echo "</tr></table>";
  }
?>
</form>
</html>
