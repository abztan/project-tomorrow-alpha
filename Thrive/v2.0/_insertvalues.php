<?php
include_once "_ptrFunctions.php";
include_once "../../_parentFunctions.php";

if(isset($_GET['command']))
	$command = $_GET['command'];
else
	$command = "";

	if($command == "search_pastor") {
		$total = 0;
		$base_id = $_GET['base_id'];
		$search = $_GET['value'];
		$clean = preg_replace('/\s+/', ' ',$search);
		$count = count(explode(" ",$clean));
		$words = explode(" ",$clean);
		echo "Search Results:<br/><br/>";
		for($i=0;$i<$count;$i++) {
			$x = $words[$i];
			if(strlen($x)>1) {
				$query = goSearch($x,$base_id);
				$result = pg_query($dbconn, $query);
				while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
				{
					$pastor_pk = $row['id'];
					$pastor_id_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
					$firstname = $row['firstname'];
					$lastname = $row['lastname'];
					$middlename = $row['middlename'];
					echo "<a href='profile_view_search.php?a=$pastor_pk' target='_blank'>$pastor_id_string</a> - $lastname, $firstname $middlename<br/>";
					$total++;
				}
			}
		}
		echo "<br/>$total total search results";
	}

if($command == "add_profile")
{
	$first_name = trim(ucwords(strtolower($_GET['fname'])));
	$middle_name = trim(ucwords(strtolower($_GET['mname'])));
	$last_name = trim(ucwords(strtolower($_GET['lname'])));
	$church_name = trim(ucwords(strtolower($_GET['church'])));
	$base = $_GET['base'];

	if($base == "0")
		echo "Sorry but you must select a base!";
	else {
		if($first_name != "" && $middle_name != "" && $last_name != "") {
			$match_first_name = str_replace('-','', $first_name);
			$match_first_name = str_replace('.','', $match_first_name);
			$match_first_name = str_replace(' ','', $match_first_name);
			$match_first_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_first_name);
			$match_first_name = strtolower($match_first_name);

			$match_middle_name = substr($middle_name,0,2);
			$match_middle_name = strtolower($match_middle_name);

			$match_last_name = str_replace('-','', $last_name);
			$match_last_name = str_replace('.','', $match_last_name);
			$match_last_name = str_replace(' ','', $match_last_name);
			$match_last_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_last_name);
			$match_last_name = strtolower($match_last_name);

			$match_name = $match_last_name.$match_first_name.$match_middle_name;

			if(checkDuplicate_pastor($match_name) == "") {
				$status = $_GET['status'];
				$gender = $_GET['gender'];
				$birthday = $_GET['bday'];
				if($birthday == "")
					$birthday = NULL;
				$education_level = $_GET['ed_lvl'];
				$education_graduate = $_GET['ed_grd'];
				$seminary = $_GET['sem'];
				$pastor_province = $_GET['pa_prv'];
				$pastor_city = $_GET['pa_cty'];
				$pastor_barangay = $_GET['pa_bgy'];
				$pastor_address = trim($_GET['pa_add']);
				$contact_1 = $_GET['cnt_1'];
				$contact_2 = $_GET['cnt_2'];
				$contact_3 = $_GET['cnt_3'];
				$email = $_GET['email'];
				$base = $_GET['base'];
				$district_id = $_GET['dist'];
				$position = $_GET['position'];
				$username = $_GET['username'];
				//add
				addPastor($first_name,$middle_name,$last_name,$status,$gender,$birthday,$education_level,$education_graduate,$seminary,$pastor_province,$pastor_city,$pastor_barangay,
				$pastor_address,$contact_1,$contact_2,$contact_3,$email,$base,$district_id,$position,$match_name,$username);

				//if church is specified, add church profile
				if($church_name != "") {
					$church_province = trim(ucwords(strtolower($_GET['ch_prv'])));
					$church_city = trim(ucwords(strtolower($_GET['ch_cty'])));
					$church_barangay = trim(ucwords(strtolower($_GET['ch_bgy'])));

					$match_church = str_replace('-','', $church_name);
					$match_church = str_replace('.','', $match_church);
					$match_church = str_replace(' ','', $match_church);
					$match_church = preg_replace('/[^A-Za-z0-9\-]/', '', $match_church);
					$match_church = strtolower($match_church);

					$match_prov = str_replace('-','', $church_province);
					$match_prov = str_replace('.','', $match_prov);
					$match_prov = str_replace(' ','', $match_prov);
					$match_prov = preg_replace('/[^A-Za-z0-9\-]/', '', $match_prov);
					$match_prov = strtolower($match_prov);

					$match_city = str_replace('-','', $church_city);
					$match_city = str_replace('.','', $match_city);
					$match_city = str_replace(' ','', $match_city);
					$match_city = preg_replace('/[^A-Za-z0-9\-]/', '', $match_city);
					$match_city = strtolower($match_city);

					$match_brgy = str_replace('-','', $church_barangay);
					$match_brgy = str_replace('.','', $match_brgy);
					$match_brgy = str_replace(' ','', $match_brgy);
					$match_brgy = preg_replace('/[^A-Za-z0-9\-]/', '', $match_brgy);
					$match_brgy = strtolower($match_brgy);

					$match_church = $match_church.$match_prov.$match_city.$match_brgy;
					//if not duplicate create new instance, then assign ID
					if(checkDuplicate_church($match_church) == "") {
						$denomination = $_GET['denom'];
						if($denomination == "99") {
							$denom_spec = trim(ucwords(strtolower($_GET['d_spec'])));
							if($denom_spec == "")
								$denom_spec = "Empty";
						}
						else
							$denom_spec = "-";
						$church_address = trim(ucwords(strtolower($_GET['ch_add'])));
						$planted = $_GET['planted'];
						addChurch($church_name,$denomination,$denom_spec,$church_province,$church_city,$church_barangay,$church_address,$planted,$match_church,$username,2);
					}
					$cid=getChurchId($match_church);
					$pid=getPastorId($match_name);
					setChurch($pid,$cid);
				}
				echo "SUCCESS: This profile has been added.";
			}
			else
				echo "FAILED: This profile already exists.";
		}
		else
			echo "FAILED: Need complete names to proceed with profile encoding.";
	}
}

if($command == "update_profile") {
	$pastor_pk = $_GET['pastor_pk'];
	$base = $_GET['base'];

	if($base == "0")
		echo "Sorry but you must select a base!";
	else {
		if($first_name != "" && $middle_name != "" && $last_name != "") {
			$match_first_name = str_replace('-','', $first_name);
			$match_first_name = str_replace('.','', $match_first_name);
			$match_first_name = str_replace(' ','', $match_first_name);
			$match_first_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_first_name);
			$match_first_name = strtolower($match_first_name);

			$match_middle_name = substr($middle_name,0,2);
			$match_middle_name = strtolower($match_middle_name);

			$match_last_name = str_replace('-','', $last_name);
			$match_last_name = str_replace('.','', $match_last_name);
			$match_last_name = str_replace(' ','', $match_last_name);
			$match_last_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_last_name);
			$match_last_name = strtolower($match_last_name);

			$match_name = $match_last_name.$match_first_name.$match_middle_name;

			$first_name = trim(ucwords(strtolower($_GET['fname'])));
			$middle_name = trim(ucwords(strtolower($_GET['mname'])));
			$last_name = trim(ucwords(strtolower($_GET['lname'])));
			$church_name = trim(ucwords(strtolower($_GET['church'])));
			$status = $_GET['status'];
			$gender = $_GET['gender'];
			$birthday = $_GET['bday'];
			if($birthday == "")
				$birthday = NULL;
			$education_level = $_GET['ed_lvl'];
			$education_graduate = $_GET['ed_grd'];
			$seminary = $_GET['sem'];
			$pastor_province = $_GET['pa_prv'];
			$pastor_city = $_GET['pa_cty'];
			$pastor_barangay = $_GET['pa_bgy'];
			$pastor_address = trim($_GET['pa_add']);
			$contact_1 = $_GET['cnt_1'];
			$contact_2 = $_GET['cnt_2'];
			$contact_3 = $_GET['cnt_3'];
			$email = $_GET['email'];
			$base = $_GET['base'];
			$district_id = $_GET['dist'];
			$position = $_GET['position'];
			$username = $_GET['username'];

			updatePastor_Profile($pastor_pk,$last_name,$first_name,$middle_name,$gender,$birthday,$status,$pastor_address,$pastor_province,$pastor_city,$pastor_barangay,$contact_1,$contact_2,
						$contact_3,$email,$education_level,$education_graduate,$seminary,$position,$district_id,$_SESSION['username'],$base,$match_name);

			//if church is specified, add church profile
			if($church_name != "") {
				$church_province = trim(ucwords(strtolower($_GET['ch_prv'])));
				$church_city = trim(ucwords(strtolower($_GET['ch_cty'])));
				$church_barangay = trim(ucwords(strtolower($_GET['ch_bgy'])));
				$church_address= trim(ucwords(strtolower($_GET['ch_add'])));
				$denomination = $_GET['denom'];
				if($denomination == "99") {
					if($denom_spec == "")
						$denom_spec = "Empty";
				}
				$denom_spec = trim(ucwords(strtolower($_GET['d_spec'])));
				$planted = $_GET['planted'];

				$match_church = str_replace('-','', $church_name);
				$match_church = str_replace('.','', $match_church);
				$match_church = str_replace(' ','', $match_church);
				$match_church = preg_replace('/[^A-Za-z0-9\-]/', '', $match_church);
				$match_church = strtolower($match_church);

				$match_prov = str_replace('-','', $church_province);
				$match_prov = str_replace('.','', $match_prov);
				$match_prov = str_replace(' ','', $match_prov);
				$match_prov = preg_replace('/[^A-Za-z0-9\-]/', '', $match_prov);
				$match_prov = strtolower($match_prov);

				$match_city = str_replace('-','', $church_city);
				$match_city = str_replace('.','', $match_city);
				$match_city = str_replace(' ','', $match_city);
				$match_city = preg_replace('/[^A-Za-z0-9\-]/', '', $match_city);
				$match_city = strtolower($match_city);

				$match_brgy = str_replace('-','', $church_barangay);
				$match_brgy = str_replace('.','', $match_brgy);
				$match_brgy = str_replace(' ','', $match_brgy);
				$match_brgy = preg_replace('/[^A-Za-z0-9\-]/', '', $match_brgy);
				$match_brgy = strtolower($match_brgy);

				$match_church = $match_church.$match_prov.$match_city.$match_brgy;

				//if not duplicate create new instance, then assign ID
				if(checkDuplicate_church($match_church) == "") {
					addChurch($church_name,$denomination,$denom_spec,$church_province,$church_city,$church_barangay,$church_address,$planted,$match_church,$username,2);
				}
				else {
					$church_pk = getChurchId($match_church);
					updateChurch_Profile($church_pk,$church_name,$denomination,$denom_spec,$church_province,$church_city,$church_barangay,$church_address,$planted,$match_church,$username);
				}
				$cid=getChurchId($match_church);
				//update pastor's church id
				setChurch($pastor_pk,$cid);
			}
			echo "SUCCESS: This profile has been updated.";
		}
		else
			echo "FAILED: Need complete names to proceed with profile update.";
	}
}

if($command == "check_dup_card") {
	$date = strtotime(trim($_GET['date']));
	$month = date("m",$date);
	$year = date("Y",$date);
	$pastor_pk = trim($_GET['pastor_id']);
	if(checkDuplicate_card($pastor_pk,$month,$year)!="")
		echo "WARNING: This card has already been encoded.";
}

if($command == "check_dup_card_sd") {
	$date = strtotime(trim($_GET['date']));
	$month = date("m",$date);
	$year = date("Y",$date);
	$pastor_pk = trim($_GET['pastor_id']);
	if(checkDuplicate_card_sd($pastor_pk,$month,$year)!="")
		echo "WARNING: This card has already been encoded.";
}

if($command == "add_card") {
	$date = strtotime(trim($_GET['date']));
	$entry_date = date("Y-m-d",$date);
 	$month = date("m",$date);
	$month_string = date("M",$date);
 	$year = date("Y",$date);
	$pastor_id = trim($_GET['pastor_id']);
	$account_base = $_GET['base'];
	$username = $_GET['username'];
	$pastor_pk_string = "P".str_pad($pastor_id, 6, 0, STR_PAD_LEFT);

	if($date == "" || $pastor_id == "0" || $pastor_id == "") {
		echo "FAILED: Date and Pastor ID cannot be empty!";
	}
	else if(checkDuplicate_card($pastor_id,$month,$year)!="") {
		echo "FAILED: This card has already been encoded.";
	}
	else {
		$pastor = getPastorDetails($pastor_id);
		$pastor_pk = $pastor['id'];
		if($pastor_pk != "") {
			$pastor_base = $pastor['baseid'];
			$thrive_id = $pastor['thriveid'];
			if($account_base == $pastor_base || $account_base > 97) {
				$update = $_GET['update'];
				$att_adt = trim($_GET['att_adt']);
				$att_kid = trim($_GET['att_kid']);
				$tithe = trim($_GET['tithe']);
				addCard($pastor_pk,$thrive_id,$year,$month,$att_adt,$att_kid,$tithe,$username,$update,$entry_date);
				echo "SUCCESS: $pastor_pk_string $month_string thrive data card has been added.";
			}
			else {
				echo "FAILED: Pastor belongs to another base. Cannot continue.";
			}
		}
		else {
			echo "FAILED: Input ID is invalid. Cannot continue.";
		}
	}
}

if($command == "set_district")
{
	$base = $_GET['base'];
	$query = "SELECT*
			  FROM list_thrive_district
			  WHERE base_id = '$base'
			  ORDER BY district_id ASC";

	$result = pg_query($dbconn, $query);

	if (!$result)
	{
		echo "An error occurred.\n";
		exit;
	}

	 echo "<option disabled selected value='0'>Please Choose</option>";
	 while ($row = pg_fetch_array($result))
	 {
     $district_id = $row['district_id'];
     $district_name = $row['alternate_name'];
		 echo "<option value='$district_id'>($district_id) $district_name</option>";
	 }
}

if($command == "check_duplicate_pastor")
{
	$first_name = $_GET['fname'];
	$middle_name = $_GET['mname'];
	$last_name = $_GET['lname'];

	if($first_name != "" && $middle_name != "" && $last_name != "") {
		$match_first_name = str_replace('-','', $first_name);
		$match_first_name = str_replace('.','', $match_first_name);
		$match_first_name = str_replace(' ','', $match_first_name);
		$match_first_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_first_name);
		$match_first_name = strtolower($match_first_name);

		$match_middle_name = substr($middle_name,0,2);
		$match_middle_name = strtolower($match_middle_name);

		$match_last_name = str_replace('-','', $last_name);
		$match_last_name = str_replace('.','', $match_last_name);
		$match_last_name = str_replace(' ','', $match_last_name);
		$match_last_name = preg_replace('/[^A-Za-z0-9\-]/', '', $match_last_name);
		$match_last_name = strtolower($match_last_name);

		$match_name = $match_last_name.$match_first_name.$match_middle_name;
		if(checkDuplicate_pastor($match_name) == "")
			echo "";
		else
			echo "This profile already exists!";
	}
}

if($command == "get_city")
{
	$province = $_GET['province'];
	$query = "SELECT DISTINCT city
			  FROM list_barangay
			  WHERE province = '$province'
			  ORDER BY city ASC";

	$result = pg_query($dbconn, $query);

	if (!$result)
	{
		echo "An error occurred.\n";
		exit;
	}

	 echo "<option disabled selected value='Empty'>Please Choose</option>";
	 echo "<option value='Empty'>Not Indicated</option>";
	 while ($row = pg_fetch_row($result))
	 {
		 echo "<option value='$row[0]'>$row[0]</option>";
	 }
}

if($command == "get_barangay")
{
	$province = $_GET['province'];
	$city = $_GET['city'];
	$query = "SELECT DISTINCT barangay
			  FROM list_barangay
			  WHERE province = '$province'
			  AND city = '$city'
			  ORDER BY barangay ASC";

	$result = pg_query($dbconn, $query);

	if (!$result)
	{
		echo "An error occurred.\n";
		exit;
	}

	 echo "<option disabled selected value='Empty'>Please Choose</option>";
	 echo "<option value='Empty'>Not Indicated</option>";
	 while ($row = pg_fetch_row($result))
	 {
		 echo "<option value='$row[0]'>$row[0]</option>";
	 }
}

if($command == "get_p_name")
{
	$pastor_id = $_GET['pastor_id'];
	$account_base = $_GET['base'];
	$pastor = getPastorDetails($pastor_id);
	$pastor_pk = $pastor['id'];
	$pastor_base = $pastor['baseid'];
	if($pastor_id=="") {
		echo "<i>(Fill up Pastor ID to generate)</i>";
	}
	else if(!empty($pastor_pk))
	{
		if($account_base < 98 && $account_base != $pastor_base)
			echo "Sorry but this pastor is not from your base.";
		else
			echo $pastor_name = $pastor['lastname'].", ".$pastor['firstname']." ".$pastor['middlename'];
	}

	else {
		echo "<i>This ID does not exist yet.</i>";
	}
}

if($command == "get_p_district") {
	$pastor_id = $_GET['pastor_id'];
	if(!empty($pastor_id)) {
		$account_base = $_GET['base'];
		$pastor = getPastorDetails($pastor_id);
		$pastor_pk = $pastor['id'];
		$pastor_base = $pastor['baseid'];
		$pastor_base_string = getBaseName($pastor_base);
		if(!empty($pastor_pk)) {
			if($account_base < 98 && $account_base != $pastor_base)
				echo "-$pastor_base";
			else
				echo "(".$pastor_base_string.") ".$pastor['thriveid'];
		}
		else {
			echo "-";
		}
	}
}

if($command == "load_attendance") {
	$selected_year = $_GET['attendance_year'];
  $selected_month = $_GET['attendance_month'];
  $district_pk = $_GET['district_pk'];
  $query = getPastor_attendance_byThrive_byYear_byMonth($district_pk,$selected_year,$selected_month);
  $result = pg_query($dbconn, $query);
	$total = 0;
	$a_avg = 0;
	$c_avg = 0;
	$t_avg = 0;

  while($attendance = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$total++;
    $attendance_pk = $attendance['id'];
    $pastor_id = $attendance['fk_pastor_pk'];
    $att_adult = $attendance['attendance_adult'];
    $att_child = $attendance['attendance_child'];
    $att_tithe = $attendance['amount_tithe'];
    $pastor = getPastorDetails($pastor_id);
    $pastor_name = $pastor['lastname'].", ".$pastor['firstname'];
    $pastor_id_string = "P".str_pad($pastor_id, 6, 0, STR_PAD_LEFT);

		$a_avg = $a_avg + $att_adult;
		$c_avg = $c_avg + $att_child;
		$t_avg = $t_avg + $att_tithe;

    echo "
			<tr>
      <td class='mdl-data-table__cell--non-numeric'>$pastor_id_string</td>
      <td class='mdl-data-table__cell--non-numeric'>".$pastor_name."</td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input placeholder='Adults' class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='ADT".$attendance_pk."' name='ADT".$attendance_pk."' onchange='updateAttendanceData(\"ADT\",\"$attendance_pk\")' value='$att_adult' />
        <label class='mdl-textfield__label' for='ADT".$attendance_pk."'></label>
        </div>
      </td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input placeholder='Children' class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='CHD".$attendance_pk."' name='CHD".$attendance_pk."' onchange='updateAttendanceData(\"CHD\",\"$attendance_pk\")' value='$att_child' />
        <label class='mdl-textfield__label' for='CHD".$attendance_pk."'></label>
        </div>
      </td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input placeholder='Tithe/Offering' class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='TTH".$attendance_pk."' name='TTH".$attendance_pk."' onchange='updateAttendanceData(\"TTH\",\"$attendance_pk\")' value='$att_tithe' />
        <label class='mdl-textfield__label' for='TTH".$attendance_pk."'></label>
        </div>
      </td>
      <td><button type='button' class='mdl-button mdl-js-button mdl-button--icon mdl-button--colored' name='remove' id='remove' onClick='removePastor(\"$attendance_pk\",\"$pastor_name\",\"$pastor_id\")'>
      <i class='material-icons'>clear</i></button></td>
    </tr>";
  }

	if($total != 0) {
		$a_avg = round($a_avg/$total,1);
		$c_avg = round($c_avg/$total,1);
		$t_avg = round($t_avg/$total,2);
	}
	echo "<br/>";
	echo "<p>Total <b>$total</b></p>";
	echo "<p>AVG Adult Attendance <b>$a_avg</b></p>";
	echo "<p>AVG Child Attendance <b>$c_avg</b></p>";
	echo "<p>AVG Tithe <b>$t_avg</b></p>";
}

if($command == "remove_attendance")	{
	$attendance_pk = $_GET['attendance_pk'];
	$username = $_GET['username'];
	updateAttendance_tag($attendance_pk,0,$username);
}

//adding pastor attendance
if(isset($_GET['pastor_pk']) && isset($_GET['district_pk']) && isset($_GET['year']) && isset($_GET['month']) && isset($_GET['username'])) {
	$pastor_pk = $_GET['pastor_pk'];
	$district_pk = $_GET['district_pk'];
	$year = $_GET['year'];
	$month = $_GET['month'];
	$username = $_GET['username'];

	echo $attendance_pk = getAttendance_ID($year,$month,$pastor_pk);
		if($attendance_pk == "")
			addPastor_attendance($year,$month,$pastor_pk,$district_pk,$username);
		else {
			if(checkAttendance_tag($attendance_pk,$pastor_pk) == 0) {
				updateAttendance_tag($attendance_pk,1);
			}
			else
				$notice = "The selected pastor is already in the list";
		}
	}

else
	$notice = "Please select a month and a pastor to add.";

//updating pastor attendance
if(isset($_GET['attendance_pk']) && isset($_GET['classification']) && isset($_GET['value']) && isset($_GET['username'])) {
	$attendance_pk = $_GET['attendance_pk'];
	$type = $_GET['classification'];
	$value = $_GET['value'];
	$username = $_GET['username'];

	if($type=="ADT") {
		$column_name = "attendance_adult";
	}
	else if($type=="CHD")	{
		$column_name = "attendance_child";
	}
	else if($type=="TTH")	{
		$column_name = "amount_tithe";
	}

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "UPDATE log_thrive_attendance
						SET $column_name = '$value', updated_by = '$username'
						WHERE id = '$attendance_pk'";

	$result = pg_query($dbconn, $query);
}


?>
