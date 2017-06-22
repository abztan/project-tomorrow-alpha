<?php
include "../dbconnect.php";
include "_ptrFunctions.php";
include "../_parentFunctions.php";

if(isset($_GET['base']))
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

	 echo "<option disabled selected>Please Choose</option>";
	 echo "<option value='0'>Not Indicated</option>";
	 while ($row = pg_fetch_array($result))
	 {
     $district_id = $row['district_id'];
     $district_name = $row['alternate_name'];
		 echo "<option value='$district_id'>($district_id) $district_name</option>";
	 }
}

if(isset($_GET['province']) && $_GET['tag']=="true")
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

	 echo "<option disabled selected>Please Choose</option>";
	 echo "<option value='0'>Not Indicated</option>";
	 while ($row = pg_fetch_row($result))
	 {
		 echo "<option value='$row[0]'>$row[0]</option>";
	 }
}

if(isset($_GET['city']))
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

	 echo "<option disabled selected>Please Choose</option>";
	 echo "<option value='0'>Not Indicated</option>";
	 while ($row = pg_fetch_row($result))
	 {
		 echo "<option value='$row[0]'>$row[0]</option>";
	 }
}

if(isset($_GET['attendance_month']) && isset($_GET['district_pk']) && isset($_GET['attendance_year']))
{
	$selected_year = $_GET['attendance_year'];
  $selected_month = $_GET['attendance_month'];
  $district_pk = $_GET['district_pk'];
  $query = getPastor_attendance_byThrive_byYear_byMonth($district_pk,$selected_year,$selected_month);
  $result = pg_query($dbconn, $query);

  while($attendance = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
    $attendance_pk = $attendance['id'];
    $pastor_id = $attendance['fk_pastor_pk'];
    $att_adult = $attendance['attendance_adult'];
    $att_child = $attendance['attendance_child'];
    $att_tithe = $attendance['amount_tithe'];
    $pastor = getPastorDetails($pastor_id);
    $pastor_name = $pastor['lastname'].", ".$pastor['firstname'];
    $pastor_id_string = "P".str_pad($pastor_id, 6, 0, STR_PAD_LEFT);

    echo "<tr>
      <td class='mdl-data-table__cell--non-numeric'>$pastor_id_string</td>
      <td class='mdl-data-table__cell--non-numeric'>".$pastor_name."</td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='ADT".$attendance_pk."' name='ADT".$attendance_pk."' onchange='updateAttendanceData(\"ADT\",\"$attendance_pk\")' value='$att_adult' />
        <label class='mdl-textfield__label' for='ADT".$attendance_pk."'>Adults</label>
        </div>
      </td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='CHD".$attendance_pk."' name='CHD".$attendance_pk."' onchange='updateAttendanceData(\"CHD\",\"$attendance_pk\")' value='$att_child' />
        <label class='mdl-textfield__label' for='CHD".$attendance_pk."'>Children</label>
        </div>
      </td>
      <td>
        <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
        <input class='mdl-textfield__input' type='text' pattern='-?[0-9]*(\.[0-9]+)?' id='TTH".$attendance_pk."' name='TTH".$attendance_pk."' onchange='updateAttendanceData(\"TTH\",\"$attendance_pk\")' value='$att_tithe' />
        <label class='mdl-textfield__label' for='TTH".$attendance_pk."'>Tithe/Offering</label>
        </div>
      </td>
      <td><button type='button' class='mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored' name='remove' id='remove' onClick='removePastor(\"$attendance_pk\",\"$pastor_name\",\"$pastor_id\")' value=''>
      <i class='material-icons'>clear</i></button></td>
    </tr>";
  }
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

if(isset($_GET['attendance_pk']) && isset($_GET['username']) && $_GET['delete'] == 1)	{
	$attendance_pk = $_GET['attendance_pk'];
	$username = $_GET['username'];
	updateAttendance_tag($attendance_pk,0,$username);
}

?>
