<?php

function checkMatch($pk, $string1, $string2, $string3, $string4)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select *
	from list_pastor
	where type1 = '$string1'
	OR type2 = '$string2'
	OR type2 = '$string3'
	OR type2 = '$string4'";
	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$match = $row['id'];

	if($match!="")
	{
		$query = "UPDATE list_transform_application SET pastor_id = '$match' WHERE id = '$pk'";
		$result = pg_query($dbconn, $query);
	}
}

if(isset($_POST['setpastormatch']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "select *
	from list_pastor";
	$result = pg_query($dbconn, $query);


	while($row = pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
		$pk = $row['id'];
		$fname = $row['firstname'];
		$lname = $row['lastname'];
		$mname = $row['middlename'];

		$uni_f = strtolower($fname);
		$uni_f = str_replace('-','', $uni_f);
		$uni_f = str_replace('.','', $uni_f);
		$uni_f = preg_replace('/[^A-Za-z0-9\-]/', '', $uni_f);

		$uni_l = strtolower($lname);
		$uni_l = str_replace('-','', $uni_l);
		$uni_l = str_replace('.','', $uni_l);
		$uni_l = preg_replace('/[^A-Za-z0-9\-]/', '', $uni_l);

		$uni_m = strtolower($mname);
		$uni_m = substr($uni_m, 0, 1);

		$type1 = $uni_l.$uni_f;
		$type2 = $uni_f.$uni_l;
		$type3 = $uni_f.$uni_m.$uni_l;
		$type4 = $uni_l.$uni_m.$uni_f;

		$query1 = "UPDATE list_pastor SET type1 = '$type1' WHERE id = '$pk'";
		$result1 = pg_query($dbconn, $query1);

		$query2 = "UPDATE list_pastor SET type2 = '$type2' WHERE id = '$pk'";
		$result2 = pg_query($dbconn, $query2);

		$query3 = "UPDATE list_pastor SET type3 = '$type3' WHERE id = '$pk'";
		$result3 = pg_query($dbconn, $query3);
		$result2 = pg_query($dbconn, $query2);

		$query4 = "UPDATE list_pastor SET type4 = '$type4' WHERE id = '$pk'";
		$result4 = pg_query($dbconn, $query4);
	}
}



if(isset($_POST['match_programs']))
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$tp_query = "select *
	from list_transform_application
	where pastor_id = 0";
	$tp_result = pg_query($dbconn, $tp_query);


	while($tp_row = pg_fetch_array($tp_result,NULL,PGSQL_BOTH))
	{
		$pk = $tp_row['id'];
		$tp_fname = $tp_row['pastor_first_name'];
		$tp_lname = $tp_row['pastor_last_name'];
		$tp_mname = $tp_row['pastor_middle_initial'];

		$tp_uni_f = strtolower($tp_fname);
		$tp_uni_f = str_replace('-','', $tp_uni_f);
		$tp_uni_f = str_replace('.','', $tp_uni_f);
		$tp_uni_f = preg_replace('/[^A-Za-z0-9\-]/', '', $tp_uni_f);

		$tp_uni_l = strtolower($tp_lname);
		$tp_uni_l = str_replace('-','', $tp_uni_l);
		$tp_uni_l = str_replace('.','', $tp_uni_l);
		$tp_uni_l = preg_replace('/[^A-Za-z0-9\-]/', '', $tp_uni_l);

		$tp_uni_m = strtolower($tp_mname);
		$tp_uni_m = substr($tp_uni_m, 0, 1);

		$type1 = $tp_uni_l.$tp_uni_f;
		$type2 = $tp_uni_f.$tp_uni_l;
		$type3 = $tp_uni_f.$tp_uni_m.$tp_uni_l;
		$type4 = $tp_uni_l.$tp_uni_m.$tp_uni_f;

		checkMatch($pk, $type1, $type2, $type3, $type4);
	}
}
?>
<form name='form1' action='' method='POST'>
<button class="btn btn-embossed btn-primary" type = "submit" name = "setpastormatch">Set Pastor Matches</button>
<button class="btn btn-embossed btn-primary" type = "submit" name = "match_programs">Match Pastor</button>
</form>
