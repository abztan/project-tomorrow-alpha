<?php
	session_start();
	if(empty($_SESSION['username']))
		header('location: /ICM/Login.php?a=2');
	else {
		$username = $_SESSION['username'];
		$access_level = $_SESSION['accesslevel'];
		$account_base = $_SESSION['baseid'];
	}

	include "../_css/bareringtonbear.css";
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
	$is_hidden = "yes";

	//sorting mechanism
	if(isset($_GET['a'])) {
		$sort_by = $_GET['a'];
		$selected_base = $_GET['b'];
	}
	else
		$sort_by = "application_id";

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
		if($selected_base == "0")
			$query = getApplication_byTag(21,9,$sort_by);
		else
			$query = getApplication_byTag($selected_base,9,$sort_by);

		$is_hidden = "no";
	}
	else {
		$query = getApplication_byTag($account_base,9,$sort_by);
		$is_hidden = "yes";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Project Tomorrow</title>
</head>

<body>
	<header>
	  <h2>Upcoming Batch</h2>
	  <nav class = "menu">
			<?php include "../controller.php";?>
		</nav>
	</header>

	<form name = "form1" action = "" method = "POST">
		<article id = "content">
			<?php
				if($is_hidden == "no")
				echo "<select id = 'sup' name = 'base_display' onchange = 'form.submit()'>
							<option $option_a value = '0'>All</option>
							<option $option_b value = '1'>1 - Bacolod</option>
							<option $option_c value = '2'>2 - Bohol</option>
							<option $option_d value = '3'>3 - Dumaguete</option>
							<option $option_e value = '4'>4 - General Santos</option>
							<option $option_f value = '5'>5 - Koronadal</option>
							<option $option_g value = '6'>6 - Palawan</option>
							<option $option_h value = '7'>7 - Dipolog</option>
							<option $option_i value = '8'>8 - Iloilo</option>
							<option $option_j value = '9'>9 - Cebu</option>
							<option $option_k value = '10'>10 - Roxas</option>
							</select>";
			?>
			<section id = "col1">
				<br/><br/>
				<table border="0" id="listtable">
				<tr>
					<th></th>
				  <th><a href="list_upcoming_batch.php?a=community_id&b=<?php echo $selected_base; ?>">Community ID</a></th>
				  <th><a href="list_upcoming_batch.php?a=application_type&b=<?php echo $selected_base; ?>">Program</a></th>
					<th><a href="list_upcoming_batch.php?a=application_city&b=<?php echo $selected_base; ?>">Location</a></th>
				  <th><a href="list_upcoming_batch.php?a=pastor_last_name&b=<?php echo $selected_base; ?>">Pastor</a></th>
				  <th>People</th>
				  <th>Action</th>
				</tr>

				<?php
					$result = pg_query($dbconn, $query);

					while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))	{
						$application_id = $row['application_id'];
						$community_id = $row['community_id'];
						$c_start = substr($community_id, 0, 6);
						$c_end = substr($community_id, -2);
						$program_type = $row['application_type'];
						$base_id = $row['base_id'];
						$city = $row['application_city'];
						$barangay = $row['application_barangay'];
						$application_pk = $row['id'];
						$pastor_id = $row['pastor_id'];
						$pastor_string = $row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial'];
						$tag = $row['tag'];
						$note = $row['location_note'];
						$tag_string = getApplication_Status($tag);
						$base_string = getBaseName($base_id);
						$application_string = getApplication_String($program_type);

						if($city != "" && $barangay != "") {
							$location = $base_string." - ".$city.", ".$barangay;
						}
						else if($city != "" && $barangay == "") {
							$location = $base_string." - ".$city;
						}
						else {
							$location = $base_string;
						}
						if($note != "")
							$location = $location." (".$note.")";

						echo "<tr id='tr_style1'>
									<td align='center' id ='numeric'>$count</td>
									<td align='center' id ='numeric'>$c_start-$c_end</td>
									<td align='left'>".getProgram($program_type)."</td>
									<td align='left'>$location</td>";

						if($pastor_id != 0)
							echo "<td><a href='/ICM/Thrive/viewPastor.php?a=$pastor_id'>$pastor_string</a></td>";
						else
							echo "<td>$pastor_string</td>";

						echo "<td align='center'>".countParticipantTotal($application_pk)."</td>";
						echo "<td align='center'>
										<a href='view_upcoming_batch.php?a=$application_pk'>View</a>
										<a onclick='delete_application($application_pk)'>Delete</a>
									</td></tr>";

						$count++;
					}
				?>
			</section>

			<section id = "col2">
			</section>
		</article>
	</form>
</body>

<script src = "default.js"></script>
<script>
	function delete_application(application_pk) {
		//window.location.href = '_delete_participant.php?a='+participant_pk;

		var message = confirm("Are you sure you want to delete this application? This will also delete all particpants within the community.");
		if(message == true) {
			var xmlhttp = null;
			if(typeof XMLHttpRequest != 'udefined'){
					xmlhttp = new XMLHttpRequest();
			}else if(typeof ActiveXObject != 'undefined'){
					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
			}else
					throw new Error('You browser doesn\'t support ajax');

			xmlhttp.open('GET', '_insertvalues.php?command=delete_application&application_pk='+application_pk, true);
		  xmlhttp.onreadystatechange = function (){
		      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
		     location.reload();
		  };
		  xmlhttp.send(null);
		}
	}

	//sticky menu
	$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
	scrollIntervalID = setInterval(stickIt, 10);
</script>

</html>