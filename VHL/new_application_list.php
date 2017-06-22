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
			$query = getApplication_List(21, $sort_by);
		else
			$query = getApplication_List($selected_base, $sort_by);
				$is_hidden = "no";
	}
	else {
		$query = getApplication_List($account_base, $sort_by);
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
	  <h2>Application List</h2>
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
			<section id = "col1">
				<br/><br/>
				<table id="listtable">
				<tr>
				  <th><a href="new_application_list.php?a=application_id&b=<?php echo $selected_base; ?>">APP ID</a></th>
				  <th><a href="new_application_list.php?a=application_type&b=<?php echo $selected_base; ?>">Program</a></th>
				  <th><a href="new_application_list.php?a=base_id&b=<?php echo $selected_base; ?>">Base</a></th>
				  <th><a href="new_application_list.php?a=pastor_last_name&b=<?php echo $selected_base; ?>">Pastor</a></th>
				  <th><a href="new_application_list.php?a=tag&b=<?php echo $selected_base; ?>">Status</a></th>
				  <th>Action</th>
				</tr>

				<?php
					$result = pg_query($dbconn, $query);

					while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
					{
						$application_id = $row['application_id'];
						$program_type = $row['application_type'];
						$base_id = $row['base_id'];
						$city = $row['application_city'];
						$barangay = $row['application_barangay'];
						$entry_id = $row['id'];
						$pastor_id = $row['pastor_id'];
						$pastor_string = $row['pastor_last_name'].", ".$row['pastor_first_name']." ".$row['pastor_middle_initial'];
						$tag = $row['tag'];
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

						echo "<tr id='tr_style1'>
									<td align='center' id = 'numeric'>$application_id</td>
									<td align='center'>$application_string</td>
									<td align='left'>$location</td>";

						if($pastor_id != 0)
							echo "<td><a href='/ICM/Thrive/viewPastor.php?a=$pastor_id'>$pastor_string</a></td>";
						else
							echo "<td>$pastor_string</td>";

						echo "<td align='center'>$tag_string</td>
									<td align='center'>";

						if($tag == "5")
							echo "<a href='#'>";
						else
							echo "<a href='viewApplication.php?&a=$entry_id'>";

						echo "View</a></td></tr>";

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
	//sticky menu
	$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
	scrollIntervalID = setInterval(stickIt, 10);
</script>

</html>
