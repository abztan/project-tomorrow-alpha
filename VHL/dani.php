<?php


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
  $list_batch = "";
	$is_hidden = "yes";
	$selected_batch = date("Y").getBatch("current","");
  $list_year = "";
  $selected_program = 1;

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

	//selected program
	if(isset($_POST['program_display'])) {
		$selected_program = $_POST['program_display'];
	}

  if($selected_program == "1")
    $option_b = "selected";
  else if($selected_program == "2")
    $option_c = "selected";
  else if($selected_program == "3")
    $option_e = "selected";
  else if($selected_program == "4")
    $option_f = "selected";
  else if($selected_program == "5")
    $option_g = "selected";
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
    Program
    <?php
      echo "<select id = 'sup' name = 'program_display' onchange = 'form.submit()'>
            <option $option_a value = '0' disabled>(Select One)</option>
            <option $option_b value = '1'>Transform - Regular</option>
            <option $option_c value = '2'>Transform - Jumpstart Parents</option>
            <option $option_d value = '3'>Transform - OSY</option>
            <option $option_e value = '4'>Transform - SLP</option>
            <option $option_f value = '5'>Transform - PBSP</option>
            </select>";
    ?>
<br/>
<br/>

<table border="1">
  <th><div>Class</div></th>
  <th id="content_1"><div><span>BAC</span></div></th>
  <th id="content_1"><div><span>BOH</span></div></th>
  <th id="content_1"><div><span>DGT</span></div></th>
  <th id="content_1"><div><span>GEN</span></div></th>
  <th id="content_1"><div><span>KOR</span></div></th>
  <th id="content_1"><div><span>PAL</span></div></th>
  <th id="content_1"><div><span>DPG</span></div></th>
  <th id="content_1"><div><span>ILO</span></div></th>
  <th id="content_1"><div><span>CEB</span></div></th>
  <th id="content_1"><div><span>ROX</span></div></th>
<?php

    echo "<tr>
            <td align='left'>Participants Registered Original</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countTotal_participant(1,$group_string);
      echo "<td>$value</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Registered Replacement</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countTotal_participant(2,$group_string)+countTotal_participant(3,$group_string)+countTotal_participant(4,$group_string)+countTotal_participant(5,$group_string)+countTotal_participant(6,$group_string);
      echo "<td>$value</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Total</td>";

    for($i=1;$i<=10;$i++) {
      $total = 0;
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countTotal_participant(1,$group_string)+countTotal_participant(2,$group_string)+countTotal_participant(3,$group_string)+countTotal_participant(4,$group_string)+countTotal_participant(5,$group_string)+countTotal_participant(6,$group_string);
      $total = $total + $value;
      echo "<td>$total</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Graduated Original</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,6,$group_string);
      echo "<td>$value</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Graduated Replacement</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(2,6,$group_string)+countClass_instances(3,6,$group_string)+countClass_instances(4,6,$group_string)+countClass_instances(5,6,$group_string)+countClass_instances(6,6,$group_string);
      echo "<td>$value</td>";
    }

    echo "<tr>
            <td align='left'>Total</td>";

    for($i=1;$i<=10;$i++) {
      $total = 0;
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,6,$group_string)+countClass_instances(2,6,$group_string)+countClass_instances(3,6,$group_string)+countClass_instances(4,6,$group_string)+countClass_instances(5,6,$group_string)+countClass_instances(6,6,$group_string);
      $total = $total + $value;
      echo "<td>$total</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Dropped Original</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,9,$group_string);
      echo "<td>$value</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Dropped Replacement</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(2,9,$group_string)+countClass_instances(3,9,$group_string)+countClass_instances(4,9,$group_string)+countClass_instances(5,9,$group_string)+countClass_instances(6,9,$group_string);
      echo "<td>$value</td>";
    }

    echo "<tr>
            <td align='left'>Total</td>";

    for($i=1;$i<=10;$i++) {
      $total = 0;
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,9,$group_string)+countClass_instances(2,9,$group_string)+countClass_instances(3,9,$group_string)+countClass_instances(4,9,$group_string)+countClass_instances(5,9,$group_string)+countClass_instances(6,9,$group_string);
      $total = $total + $value;
      echo "<td>$total</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Activated Original</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,5,$group_string);
      echo "<td>$value</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Participants Activated Replacement</td>";

    for($i=1;$i<=10;$i++) {
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(2,5,$group_string)+countClass_instances(3,5,$group_string)+countClass_instances(4,5,$group_string)+countClass_instances(5,5,$group_string)+countClass_instances(6,5,$group_string);
      echo "<td>$value</td>";
    }

    echo "<tr>
            <td align='left'>Total</td>";

    for($i=1;$i<=10;$i++) {
      $total = 0;
      $group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
      $value = countClass_instances(1,5,$group_string)+countClass_instances(2,5,$group_string)+countClass_instances(3,5,$group_string)+countClass_instances(4,5,$group_string)+countClass_instances(5,5,$group_string)+countClass_instances(6,5,$group_string);
      $total = $total + $value;
      echo "<td>$total</td>";
    }
    echo "</tr>";

    echo "<tr>
            <td align='left'>Visitor Graduates</td>";
      for($i=1;$i<=10;$i++) {
				$group_string = $list_year.str_pad($i,2,0,STR_PAD_LEFT).$selected_program.$list_batch;
	      $value = countTotal_visitor_grad($group_string);
	      if($value == '')
	        $value = 0;

	      echo "<td>$value</td>";
			}

      echo "</tr>";
?>
</table>

</form>
