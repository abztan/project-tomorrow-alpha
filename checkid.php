
<?php

include "/_css/bareringtonbear.css";
include "dbconnect.php";
include "_parentFunctions.php";
include "/VHL/_tnsFunctions.php";

$search_id = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>
<form name='form1' action='' method='POST'>
<article id="content">

<section id="col1">
	<input type="text" placeholder="Type Community ID" name="search_id" value="<?php echo $search_id;?>">
	<input type="submit" class="btn btn-embossed btn-warning" name="search" value = "Search" >
	<br/>
	<br/>

	<?php
			if(isset($_POST['search']) && $_POST['search_id'] != "")
			{
				$community_id = $_POST['search_id'];
				$application = getApplication_Data_byHHID($community_id);
				$application_pk = $application['id'];
				$query = getParticipant_forApplication_byTag(intval($application_pk),5,'last_name, first_name ASC');
				$result = pg_query($dbconn, $query);

				$pastor_name = $application['pastor_last_name'].", ".$application['pastor_first_name']." ".$application['pastor_middle_initial'];
				$pastor_id = $application['pastor_id'];
				$pastor_id = "P".str_pad($pastor_id, 5, 0, STR_PAD_LEFT);

				$i = 1;
				echo "Community: <h1>$community_id</h1>";
				echo "<table>
				<th>Type</th><th>Name</th><th>ID</th>
				<tr id='tr_style1'><td>Pastor</td><td>$pastor_name</td><td>$pastor_id</td></tr>";

				while($participant = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
					$participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];
					$participant_id = $participant['participant_id'];

					echo "
					<tr id='tr_style1'><td>Particpant $i</td><td>$participant_name</td><td align ='right'>$participant_id</td></tr>";

					$i++;
				}
				echo "</table>";
			}
		?>

</section>

<section id="col2">
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>
