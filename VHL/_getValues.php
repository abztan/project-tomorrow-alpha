<?php
include "../dbconnect.php";

    if(isset($_GET['province']))
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

		 echo "<label>City</label>
		 <select id='city'>
		 <option disabled selected>Please Choose</option>";
		 
		 while ($row = pg_fetch_row($result))
		 {
			 echo "<option value='$row[0]'>$row[0]</option>";
		 }			 
		echo "</select>";	
	}
	
	/*
	if(isset($_GET['geotype']))
	{
		if($_GET['geotype'] != "Coastal")
		{
			echo '<label>&nbsp;</label> 
			<select  id="geotype1" name="geotype1">
			<option disabled selected>Please Choose</option>
			<option value="Rural">Rural</option>
			<option value="Urban">Urban</option>
			</select>';
		}
	}*/
?>