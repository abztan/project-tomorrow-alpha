
<?php
	//session
	session_start();
	if(empty($_SESSION['username']))
		header('location: /ICM/Login.php?a=2');
	else {
		$username = $_SESSION['username'];
		$accesslevel = $_SESSION['accesslevel'];
	}
	
include "../dbconnect.php";
include "../_parentFunctions.php";
include "_tnsFunctions.php";

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
	<input type="text" placeholder="Type Community/HH/Child ID" name="search_id" value="<?php echo $search_id;?>">
	<input type="submit" class="btn btn-embossed btn-warning" name="search" value = "Search" >
	<br/>
	<br/>

	<?php
			if(isset($_POST['search']) && $_POST['search_id'] != "")
			{
				$search_id = $_POST['search_id'];
				$query = search_ID($search_id, "community");
				$result = pg_query($dbconn, $query);

				echo "<table border = '1'>";
				echo "<tr><th>ID</th><th>Type</th><th>Name</th><th>Action</th></tr>";
				while($community = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					$community_pk = $community['id'];
					$community_id = $community['community_id'];
					$last_name = $community['pastor_last_name'];
					$first_name = $community['pastor_first_name'];
					echo "<tr><td>$community_id</td><td>Community ID</td><td>$last_name, $first_name</td><td>View</td>";
				}

				$query = search_ID($search_id, "people");
				$result = pg_query($dbconn, $query);
				while($particpant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
					$application_pk = $particpant['fk_entry_id'];
					$participant_pk = $particpant['id'];
					$particpant_id = $particpant['participant_id'];
					$last_name = $particpant['last_name'];
					$first_name = $particpant['first_name'];
					echo "<tr><td>$particpant_id</td><td>Partcipant ID</td><td>$last_name, $first_name</td><td><a href='editParticipant.php?a=$application_pk&b=$participant_pk'>View</a></td>";
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
