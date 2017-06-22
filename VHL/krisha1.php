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
  include "../Thrive/v2.0/_ptrFunctions.php";

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

<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
	padding: 10px 10px 10px 10px;

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
<br/>
<?php

	$result = base_hhid($selected_base,"community_id",$selected_batch);
	$x = 1;
	while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$application_pk = $row['id'];
		$base_id = $row['base_id'];
    $pastor_pk = $row['fk_pastor_pk'];
    $pastor = getPastorDetails($pastor_pk);
    if($pastor_pk != 0)
      $pastor_string = $pastor['firstname']." ".$pastor['middlename']." ".$pastor['lastname'];
    else
      $pastor_string = $row['pastor_first_name']." ".$row['pastor_middle_initial']." ".$row['pastor_last_name'];

		echo "
			<table border='1' width='950'>
				<tr><td colspan='6'>$x. Community ".$row['community_id'].": $pastor_string</td></tr>
				<tr>
					<td>#</td>
					<td>HHID</td>
					<td>Name</td>
					<td>Class</td>
					<td>Tag</td>
					<td>Graduate</td>
				</tr>";

		$participant = base_participants($application_pk);
		$i = 1;
    //checks double lessons for the application vs participant
    $dl_query = get_double_lesson_weeks($application_pk);
    while ($dl = pg_fetch_array($dl_query,NULL,PGSQL_BOTH)) {
        $week_number = $dl['week_number'];
        $value = strtolower(chr(64 + $week_number));
        $dl_arr[] = $value;
    }

		while($person = pg_fetch_array($participant,NULL,PGSQL_BOTH)) {
      $participant_pk = $person['id'];
      $attendance_set = check_graduate_participant($participant_pk);
      $attendance_count = strlen($attendance_set);
      $tag = $person['tag'];
      $extra_att = 0;
      foreach ($dl_arr as $key => $instance) {
        if(strpos($attendance_set, $instance) == true)
          $extra_att++;
      }

      $attendance_count = $attendance_count + $extra_att;
      if (8 == $base_id) {
        if (6 == $tag) {
          $is_graduate = "Yes";
        }
        else {
          $is_graduate = "No";
        }
      }
      else {
        $is_graduate = ($attendance_count >= 10 && ($tag != 4 && $tag != 9) ? "Yes" : "No");
      /*  if($is_graduate == "Yes")
          updateParticipantTag($participant_pk,6,"SYSTEM");
        else if($is_graduate == "No")
          updateParticipantTag($participant_pk,5,"SYSTEM");*/
      }
			echo "
					<tr>
						<td>$i</td>
						<td>".$person['participant_id']."</td>
						<td>".$person['last_name'].", ".$person['first_name']." ".$person['middle_name']."</td>
						<td>".getParticipant_class($person['category'])."</td>
						<td>".participant_tag_string($tag)."</td>
						<td align='center'>$is_graduate</td>

					</tr>";
					$i++;
		}
			echo "</table><br/><br/>";
			$x++;
	}
?>
