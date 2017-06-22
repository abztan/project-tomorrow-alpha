.<?php
include_once "_ptrFunctions.php";
include_once "../../_parentFunctions.php";

if(isset($_GET['command']))
	$command = $_GET['command'];
else
	$command = "";

//1 - Delete Pastor
if($command == "delete_profile") {
  $pastor_pk = $_GET['pastor_pk'];
  $username = $_GET['username'];
  $from = $_GET['from'];
	$pastor = getPastorDetails($pastor_pk);
	$district_pk = $pastor['thriveid'];
  updatePastorTag(0, $pastor_pk, $username);
	if($from == "search")
  	header('location: _actionresult.php?a=1_temp&b='.$pastor_pk.'&c='.$district_pk);
	else
  	header('location: _actionresult.php?a=1&b='.$pastor_pk.'&c='.$district_pk);
}

if($command == "get_attendance_summary")
{
	$program = $_GET['program'];
	$range = $_GET['range'];

	echo "<table border='1'><tr>";
	echo "<th>BASE</th>";

	for($i=$range-1;$i>0;$i--) {
		$month = date('M', strtotime("-$i month"));
		$year = date('Y', strtotime("-$i month"));
	  echo "<th>$month $year</th>";
	}
	$month = date('M');
	$year = date('Y');
	echo "<th>$month $year</th>";
	$total = 0;

	for($base_id=1;$base_id<11;$base_id++) {
		echo "<tr><td>".getBaseName($base_id)."</td>";
		for($i=$range-1;$i>0;$i--) {
				$month = date('m', strtotime("-$i month"));
				$year = date('Y', strtotime("-$i month"));

				if($program == "1")
					$count = countThrive_card_byBase($base_id,$month,$year);
				else if($program == "2")
					$count = countThrive_card_byBase_sd($base_id,$month,$year);

				$total = $total + $count;
			  echo "<td align='right'><div onclick='show_details($base_id,$month,$year)'>$count</div></td>";
		}
		$month = date('m');
		$year = date('Y');

		if($program == "1")
			$count = countThrive_card_byBase($base_id,$month,$year);
		else if($program == "2")
			$count = countThrive_card_byBase_sd($base_id,$month,$year);

		$total = $total + $count;
		echo "<td align='right'><div onclick='show_details($base_id,$month,$year)'>$count</div></td>";
		echo "</tr>";
	}


	echo "<tr><td colspan='13'></td></tr>";
	echo "<tr><td>AVERAGE(per district)</td>";
	for($i=$range-1;$i>0;$i--) {
			$month = date('m', strtotime("-$i month"));
			$year = date('Y', strtotime("-$i month"));

			if($program == "1")
				$value = countThrive_card_byMonth($month,$year);
			else if($program == "2")
				$value = countThrive_card_byMonth_sd($month,$year);

			$average = round($value/86,0);
		  echo "<td align='right' title='$value/86'>$average</td>";
	}
	$month = date('m');
	$year = date('Y');

	if($program == "1")
		$average = round(countThrive_card_byMonth($month,$year)/86,0);
	else if($program == "2")
		$average = round(countThrive_card_byMonth_sd($month,$year)/86,0);


	echo "<td align='right'>$average</td>";
	echo "</tr>";
	echo "<tr><td>TOTAL</td>";
	for($i=$range-1;$i>0;$i--) {
			$month = date('m', strtotime("-$i month"));
			$year = date('Y', strtotime("-$i month"));

			if($program == "1")
				echo "<td align='right'>".countThrive_card_byMonth($month,$year)."</td>";
			else if($program == "2")
				echo "<td align='right'>".countThrive_card_byMonth_sd($month,$year)."</td>";

	}
	$month = date('m');
	$year = date('Y');

	if($program == "1")
		echo "<td align='right'>".countThrive_card_byMonth($month,$year)."</td>";
	else if($program == "2")
		echo "<td align='right'>".countThrive_card_byMonth_sd($month,$year)."</td>";

	echo "</tr>";
}

$final = 0;

if($command == "get_attendance_details") {
	$base = $_GET['base'];
	$month = $_GET['month'];
	$program = $_GET['program'];

	$month_string = date('F', mktime(0, 0, 0, $month, 10));
	$year = $_GET['year'];
	echo "<br/><strong>Details for ".getBaseName($base)." $month_string $year</strong><br/><br/>";
	echo "<table border='1'><th style='background-color:#2cd6d2;'>DISTRICT</th><th style='background-color:#2cd6d2;'>MEMBER</th><th style='background-color:#2cd6d2;'>NON-MEMBER</th><th style='background-color:#2cd6d2;'>SUBTOTAL</th>";
	$query = getDistrict_List($base,"district_id");
	$result = pg_query($dbconn, $query);
	while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
		$district_id = $row['district_id'];
		$members = countThrive_card_byDistrict_byMembership($program,$month,$year,$district_id,"t");
		$nonmembers = countThrive_card_byDistrict_byMembership($program,$month,$year,$district_id,"f");
		$total = $members + $nonmembers;
		echo "<tr onclick='show_me_the_money(\"$district_id\",\"$month\",\"$year\")'><td>$district_id</td><td align='right'>$members</td><td align='right'>$nonmembers</td><td align='right'>$total</td></tr>";
		$final = $final + $total;
	}

	echo "<tr><td colspan='3' align='right'>TOTAL</td><td align='right'>$final</td></tr>";
	echo "</table>";
}

if($command == "get_attendance_detail_plus") {
	$district_pk = $_GET['district_pk'];
	$base = (substr($district_pk,2,2));
	$base = (int)$base;
	$month = $_GET['month'];
	$month_string = date('F', mktime(0, 0, 0, $month, 10));
	$program = $_GET['program'];
	$year = $_GET['year'];

	$i = 1;
	echo "<br/><strong>Pastor List for ".getBaseName($base)." $district_pk $month_string $year</strong><br/><br/><table border='1'>
	<th style='background-color:#dda858;'>Name (ID)</th>";
	echo ($program == 1 ? "
	<th style='background-color:#dda858;'>Adult Attendance</th>
	<th style='background-color:#dda858;'>Child Attendance</th>
	<th style='background-color:#dda858;'>Offering/Tithe</th>" : "")."
	<th style='background-color:#dda858;'>Updated By</th>
	<th style='background-color:#dda858;'>Updated Date</th>";

	$query = get_district_attendee_bySchedule($district_pk,$program,$year,$month);
	while($people = pg_fetch_assoc($query)) {
		$pastor_pk = $people['fk_pastor_pk'];
		$pastor_name = $people['lastname'].", ".$people['firstname']." ".$people['middlename'];
		$updated_by = $people['entry_updated_by'];
		$updated_date = $people['entry_updated_date'];
		//$attendance_pk = $people['att_pk'];

		echo "<tr><td>$i.$pastor_name ($pastor_pk)</td>";
		if ($program == 1) {
			$attendance_adult = $people['attendance_adult'];
			$attendance_child = $people['attendance_child'];
			$attendance_tithe = $people['amount_tithe'];
			echo "<td align='right'>$attendance_adult</td><td align='right'>$attendance_child</td><td align='right'>$attendance_tithe</td>";
		}
		echo "<td align='right'>$updated_by</td><td align='right'>$updated_date</td></tr>";
		$i++;
	}

	echo "</table>";
}

if($command == "get_attendance_rate")
{
	$instances = $_GET['instances'];
	$program = $_GET['program'];

	$range_display = $instances-1;
	echo ($program == 1 ? "Thrive First Day" : "Thrive Second Day");
	echo " from ".date('M')." ".date('Y')." - ".date('M', strtotime("-$range_display month"))." ".date('Y', strtotime("-$range_display month"));
	echo "<br/>";
	echo "<table border='1'><tr>";
	echo "<th>BASE</th>";
	for($i=1;$i<$instances+1;$i++) {
		$header = round(($i/$instances)*100,0);
	  echo "<th>$header%</th>";
	}

	for($base_id=1;$base_id<11;$base_id++) {
		echo "<tr><td>".getBaseName($base_id)."</td>";
		for($i=0;$i<$instances;$i++) {
			$total = 0;
			$number = $i+1;
			$result = list_thrive_attendance_rate($base_id, $program, $instances);
			while($row=pg_fetch_array($result,NULL,PGSQL_BOTH)) {
				$match_with = $row['count'];
				if($number == $match_with)
					$total++;
			}
			echo "<td align='right'><div>$total</div></td>";
			//$total.$number = $total+$total;
		}

		echo "</tr>";
	}
/*
	echo "<tr><td colspan='13'></td></tr>";
	echo "<tr><td>AVERAGE(per district)</td>";
	for($i=1;$i<$instances;$i++) {
			$average = round($value/86,0);
		  echo "<td align='right' title='$value/86'>$average</td>";
	}

	echo "<td align='right'>$average</td>";
	echo "</tr>";
	echo "<tr><td>Total</td>";
		for($i=1;$i<$instances+1;$i++) {
				$final = 	$total.$i;
			  echo "<td align='right' title='$value/86'>$final</td>";
		}
	echo "</tr>*/
	echo "</table>";
}


if($command == "get_attendance_overlap")
{
	$range = $_GET['range'];

	echo "<table border='1'><tr>";
	echo "<th>BASE</th>";

	for($i=$range-1;$i>0;$i--) {
		$month = date('M', strtotime("-$i month"));
		$year = date('Y', strtotime("-$i month"));
	  echo "<th>$month $year</th>";
	}
	$month = date('M');
	$year = date('Y');
	echo "<th>$month $year</th>";
	$total = 0;

	for($base_id=1;$base_id<11;$base_id++) {
		echo "<tr><td>".getBaseName($base_id)."</td>";
		for($i=$range-1;$i>-1;$i--) {
				$month = date('m', strtotime("-$i month"));
				$year = date('Y', strtotime("-$i month"));
				$count = count_thrive_overlap_rate($base_id, $month, $year);
			  echo "<td align='right'><div>$count</div></td>";
		}

		echo "</tr>";
	}
	echo "</table>";
}

if($command == "add_profile")
{
	$first_name = trim(ucwords(strtolower($_GET['fname'])));
	$middle_name = trim(ucwords(strtolower($_GET['mname'])));
	$last_name = trim(ucwords(strtolower($_GET['lname'])));
	$church_name = trim(ucwords(strtolower($_GET['church'])));
	$base = $_GET['base'];

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
		$pid=getPastorId($match_name);

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
			setChurch($pid,$cid);
		}
		header('location: _actionresult.php?a=2&b='.$pid);
	}
	else
		header('location: _actionresult.php?a=3');
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
	$update = $_GET['update'];

	if(checkDuplicate_card($pastor_id,$month,$year)!="") {
		//already exists
		header('location: _actionresult.php?a=5&b=1&c='.$pastor_id.'&d='.$entry_date);
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
				header('location: _actionresult.php?a=4&b='.$pastor_id.'&c='.$entry_date.'&d='.$update);
			}
			else {
				//belongs to another base
				header('location: _actionresult.php?a=5&b=2&c='.$pastor_id.'&d='.$entry_date);
			}
		}
		else {
			//error
			header('location: _actionresult.php?a=5&b=3&c='.$pastor_id.'&d='.$entry_date);
		}
	}
}

if($command == "add_card_sd") {
	$date = strtotime(trim($_GET['date']));
	$account_base = $_GET['base'];
	$entry_date = date("Y-m-d",$date);
 	$month = date("m",$date);
	$month_string = date("M",$date);
 	$year = date("Y",$date);
	$pastor_id = trim($_GET['pastor_id']);
	$username = $_GET['username'];
	$pastor_pk_string = "P".str_pad($pastor_id, 6, 0, STR_PAD_LEFT);

	if(checkDuplicate_card_sd($pastor_id,$month,$year)!="") {
		//already exists
		header('location: _actionresult.php?a=6&b=4&c='.$pastor_id.'&d='.$entry_date);
	}
	else {
		$pastor = getPastorDetails($pastor_id);
		$pastor_pk = $pastor['id'];
		if($pastor_pk != "") {
			$pastor_base = $pastor['baseid'];
			$thrive_id = $pastor['thriveid'];
			if($account_base == $pastor_base || $account_base > 97) {
				addCard_sd($pastor_pk,$thrive_id,$year,$month,$username,$entry_date);
				header('location: _actionresult.php?a=6&b=1&c='.$pastor_id.'&d='.$entry_date);
			}
			else {
				//belongs to another base
				header('location: _actionresult.php?a=6&b=2&c='.$pastor_id.'&d='.$entry_date);
			}
		}
		else {
			//error
			header('location: _actionresult.php?a=6&b=3&c='.$pastor_id.'&d='.$entry_date);
		}
	}
}
?>
