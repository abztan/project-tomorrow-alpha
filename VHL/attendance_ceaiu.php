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
  $p_m_sum = 0;
  $p_f_sum = 0;
  $p_b_sum = 0;
  $p_m_drop_sum = 0;
  $p_f_drop_sum = 0;
  $p_b_drop_sum = 0;
  $p_m_grad_sum = 0;
  $p_f_grad_sum = 0;
  $p_b_grad_sum = 0;
  $c_m_sum = 0;
  $c_f_sum = 0;
  $c_b_sum = 0;
  $c_m_drop_sum = 0;
  $c_f_drop_sum = 0;
  $c_b_drop_sum = 0;
  $c_m_grad_sum = 0;
  $c_f_grad_sum = 0;
  $c_b_grad_sum = 0;

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

<table border="1" width = "90%">
  <th><div>Community ID</div></th>
  <th><div>Gender</div></th>
  <th><div>PARTICIPANT: Attended</div></th>
  <th><div>PARTICIPANT: Active</div></th>
  <th><div>PARTICIPANT: Dropped</div></th>
  <th><div>PARTICIPANT: Graduated</div></th>
  <th><div>COUNSELOR: Attended</div></th>
  <th><div>COUNSELOR: Active</div></th>
  <th><div>COUNSELOR: Dropped</div></th>
  <th><div>COUNSELOR: Graduated</div></th>

 <?php

 $query = getApplication_byTag_byBatch($selected_base,5,"community_id",$selected_batch);
 $result = pg_query($dbconn, $query);

 while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
	$application_pk = $community['id'];
	$community_id = $community['community_id'];

	$p_m_actv = countCEAIU_people($application_pk,'Male','Participant','5');
	$p_m_drop = countCEAIU_people($application_pk,'Male','Participant','9');
	$p_m_grad = countCEAIU_people($application_pk,'Male','Participant','6');
	$p_m_total = $p_m_actv + $p_m_drop + $p_m_grad;
	$p_f_actv = countCEAIU_people($application_pk,'Female','Participant','5');
	$p_f_drop = countCEAIU_people($application_pk,'Female','Participant','9');
	$p_f_grad = countCEAIU_people($application_pk,'Female','Participant','6');
	$p_f_total = $p_f_actv + $p_f_drop + $p_f_grad;
	$p_b_actv = countCEAIU_people($application_pk,'','Participant','5');
	$p_b_drop = countCEAIU_people($application_pk,'','Participant','9');
	$p_b_grad = countCEAIU_people($application_pk,'','Participant','6');
	$p_b_total = $p_b_actv + $p_b_drop + $p_b_grad;

	$c_m_actv = countCEAIU_people($application_pk,'Male','Counselor','5');
	$c_m_drop = countCEAIU_people($application_pk,'Male','Counselor','9');
	$c_m_grad = countCEAIU_people($application_pk,'Male','Counselor','6');
	$c_m_total = $c_m_actv + $c_m_drop + $c_m_grad;
	$c_f_actv = countCEAIU_people($application_pk,'Female','Counselor','5');
	$c_f_drop = countCEAIU_people($application_pk,'Female','Counselor','9');
	$c_f_grad = countCEAIU_people($application_pk,'Female','Counselor','6');
	$c_f_total = $c_f_actv + $c_f_drop + $c_f_grad;
	$c_b_actv = countCEAIU_people($application_pk,'','Counselor','5');
	$c_b_drop = countCEAIU_people($application_pk,'','Counselor','9');
	$c_b_grad = countCEAIU_people($application_pk,'','Counselor','6');
	$c_b_total = $c_b_actv + $c_b_drop + $c_b_grad;

	echo "<tr>
		  <td rowspan='3'>$community_id</td>
		  <td>Male</td>
		  <td align='center'>$p_m_total</td>
		  <td align='center'>$p_m_actv</td>
		  <td align='center'>$p_m_drop</td>
		  <td align='center'>$p_m_grad</td>
		  <td align='center'>$c_m_total</td>
		  <td align='center'>$c_m_actv</td>
		  <td align='center'>$c_m_drop</td>
		  <td align='center'>$c_m_grad</td>
		  </tr>
		  <tr>
		  <td>Female</td>
		  <td align='center'>$p_f_total</td>
		  <td align='center'>$p_f_actv</td>
		  <td align='center'>$p_f_drop</td>
		  <td align='center'>$p_f_grad</td>
		  <td align='center'>$c_f_total</td>
		  <td align='center'>$c_f_actv</td>
		  <td align='center'>$c_f_drop</td>
		  <td align='center'>$c_f_grad</td>
	</tr>
		  <tr>
		  <td>Blank</td>
		  <td align='center'>$p_b_actv</td>
		  <td align='center'>$p_b_drop</td>
		  <td align='center'>$p_b_grad</td>
		  <td align='center'>$c_b_actv</td>
		  <td align='center'>$c_b_drop</td>
		  <td align='center'>$c_b_grad</td>
	</tr>";

  $p_m_sum = $p_m_sum + $p_m_total;
  $p_f_sum = $p_f_sum + $p_f_total;
  $p_b_sum = $p_b_sum + $p_b_total;
  $p_m_actv_sum = $p_m_actv_sum + $p_m_actv;
  $p_f_actv_sum = $p_f_actv_sum + $p_f_actv;
  $p_b_actv_sum = $p_b_actv_sum + $p_b_actv;
  $p_m_drop_sum = $p_m_drop_sum + $p_m_drop;
  $p_f_drop_sum = $p_f_drop_sum + $p_f_drop;
  $p_b_drop_sum = $p_b_drop_sum + $p_b_drop;
  $p_m_grad_sum = $p_m_grad_sum + $p_m_grad;
  $p_f_grad_sum = $p_f_grad_sum + $p_f_grad;
  $p_b_grad_sum = $p_b_grad_sum + $p_b_grad;

  $c_m_sum = $c_m_sum + $c_m_total;
  $c_f_sum = $c_f_sum + $c_f_total;
  $c_b_sum = $c_b_sum + $c_b_total;
  $c_m_actv_sum = $c_m_actv_sum + $c_m_actv;
  $c_f_actv_sum = $c_f_actv_sum + $c_f_actv;
  $c_b_actv_sum = $c_b_actv_sum + $c_b_actv;
  $c_m_drop_sum = $c_m_drop_sum + $c_m_drop;
  $c_f_drop_sum = $c_f_drop_sum + $c_f_drop;
  $c_b_drop_sum = $c_b_drop_sum + $c_b_drop;
  $c_m_grad_sum = $c_m_grad_sum + $c_m_grad;
  $c_f_grad_sum = $c_f_grad_sum + $c_f_grad;
  $c_b_grad_sum = $c_b_grad_sum + $c_b_grad;

  $p_m_actv = 0;
	$p_m_drop = 0;
	$p_m_grad = 0;
	$p_m_total = 0;
	$p_f_actv = 0;
	$p_f_drop = 0;
	$p_f_grad = 0;
	$p_f_total = 0;
	$p_b_actv = 0;
	$p_b_drop = 0;
	$p_b_grad = 0;
	$p_b_total = 0;

	$c_m_actv = 0;
	$c_m_drop = 0;
	$c_m_grad = 0;
	$c_m_total = 0;
	$c_f_actv = 0;
	$c_f_drop = 0;
	$c_f_grad = 0;
	$c_f_total = 0;
	$c_b_actv = 0;
	$c_b_drop = 0;
	$c_b_grad = 0;
	$c_b_total = 0;
}
?>
</table>

<br/><br/>
<table width="70%" border="1">
<tr align="center">
  <td></td>
  <td colspan="3">Attended</td>
  <td colspan="3">Active</td>
  <td colspan="3">Dropped</td>
  <td colspan="3">Graduated</td>
</tr>
<tr align="center">
  <td></td>
  <td>Male</td>
  <td>Female</td>
  <td>Blank</td>
  <td>Male</td>
  <td>Female</td>
  <td>Blank</td>
  <td>Male</td>
  <td>Female</td>
  <td>Blank</td>
  <td>Male</td>
  <td>Female</td>
  <td>Blank</td>
</tr>
<tr align="center">
  <td>Participant</td>
  <?php echo "
  <td>$p_m_sum</td>
  <td>$p_f_sum</td>
  <td>$p_b_sum</td>
  <td>$p_m_actv_sum</td>
  <td>$p_f_actv_sum</td>
  <td>$p_b_actv_sum</td>
  <td>$p_m_drop_sum</td>
  <td>$p_f_drop_sum</td>
  <td>$p_b_drop_sum</td>
  <td>$p_m_grad_sum</td>
  <td>$p_f_grad_sum</td>
  <td>$p_b_grad_sum</td>";
  ?>
</tr>
<tr align="center">
  <td>Counselor</td>
  <?php echo "
  <td>$c_m_sum</td>
  <td>$c_f_sum</td>
  <td>$c_b_sum</td>
  <td>$c_m_actv_sum</td>
  <td>$c_f_actv_sum</td>
  <td>$c_b_actv_sum</td>
  <td>$c_m_drop_sum</td>
  <td>$c_f_drop_sum</td>
  <td>$c_b_drop_sum</td>
  <td>$c_m_grad_sum</td>
  <td>$c_f_grad_sum</td>
  <td>$c_b_grad_sum</td>";
  ?>
</tr>
</table>
</form>
