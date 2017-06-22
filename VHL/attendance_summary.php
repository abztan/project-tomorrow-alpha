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
  $list_batch = "";
	$is_hidden = "yes";
	$selected_batch = date("Y").getBatch("current","");
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
		$list_year = substr($selected_batch,2,2);
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

td { text-align: right;}
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
    </select>
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

<table border="1">
  <th><div>Class</div></th>
  <th id="content_1"><div><span>4P</span></div></th>
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
  <th id="content_2"><div><span>TOTAL</span></div></th>
<?php
  //$query = getApplication_byTag_byBatch($selected_base,5,"community_id",$selected_batch);
  //$result = pg_query($dbconn, $query);

  foreach($class as $class) {
    $group_string = $list_year.str_pad($selected_base,2,0,STR_PAD_LEFT)."_".$list_batch;

    echo "<tr>
            <td align='left'>".getTransform_class($class)." - 5</td>
            <td>".countClass_data($class,"5",$group_string,"variable_1","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_2","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_3","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_4","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_5","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_6","=","1")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_7","ilike","%a%")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_7","ilike","%b%")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_7","ilike","%c%")."</td>
            <td>".countClass_data($class,"5",$group_string,"variable_7","ilike","%d%")."</td>";
          $start = "a";
          for($i=1;$i<17;$i++) {
            echo "<td>".countClass_data($class,"5",$group_string,"attendance_set","ilike","%$start%")."</td>";
            $start++;
          }
    echo "<td>".countClass_instances($class,"5",$group_string)."</td>
          </tr>";

    echo "<tr>
            <td align='left'>".getTransform_class($class)." - 6</td>
            <td>".countClass_data($class,"6",$group_string,"variable_1","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_2","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_3","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_4","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_5","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_6","=","1")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_7","ilike","%a%")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_7","ilike","%b%")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_7","ilike","%c%")."</td>
            <td>".countClass_data($class,"6",$group_string,"variable_7","ilike","%d%")."</td>";
          $start = "a";
          for($i=1;$i<17;$i++) {
            echo "<td>".countClass_data($class,"6",$group_string,"attendance_set","ilike","%$start%")."</td>";
            $start++;
          }
    echo "<td>".countClass_instances($class,"6",$group_string)."</td>
          </tr>";

    echo "<tr>
            <td align='left'>".getTransform_class($class)." - 9</td>
            <td>".countClass_data($class,"9",$group_string,"variable_1","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_2","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_3","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_4","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_5","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_6","=","1")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_7","ilike","%a%")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_7","ilike","%b%")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_7","ilike","%c%")."</td>
            <td>".countClass_data($class,"9",$group_string,"variable_7","ilike","%d%")."</td>";
          $start = "a";
          for($i=1;$i<17;$i++) {
            echo "<td>".countClass_data($class,"9",$group_string,"attendance_set","ilike","%$start%")."</td>";
            $start++;
          }
    echo "<td>".countClass_instances($class,"9",$group_string)."</td></tr>";
  }

?>
</table>

5 - Active <br/>
6 - Graduated <br/>
9 - Dropped <br/>

<script>
function hideContent(a) {
  document.getElementById('content_1').style.display = "none";
}
</script>
</form>
