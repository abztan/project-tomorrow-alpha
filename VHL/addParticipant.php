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

	$last_name="";
	$first_name="";
	$middle_name="";
	$contact_number="";
	$variable1="";
	$hh_member="";
	$hh_sick="";
	$hh_income="";
	$ps1="";
	$ps2="";
	$ps3="";
	$ps4="";
	$ps5="";
	$ps6="";
	$ps7="";
	$ps8="";
	$ps9="";
	$ps10="";
	$ps11="";
	$ps12="";
	$ps13="";
	$flag_count=0;
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
	Category
	<select id="geotype" name="geotype" onChange="window.loadGeotype()">
		<option disabled selected>Please Choose</option>
		<option value="1">Participant</option>
		<option value="3">Counselor</option>
	</select>

	<table border = "1">
		<tr>
			<td>1*</td>
			<td>First Name</td>
			<td><input placeholder="First Name/s" class="form-control input-sm" type="text" name="f_name" value = "<?php echo $first_name;?>"></td>
		</tr>
		<tr>
			<td>2*</td>
			<td>Middle Name/Initial</td>
			<td><input placeholder="Middle Name" class="form-control input-sm" type="text" name="m_name" value = "<?php echo $middle_name;?>"></td>
		</tr>
		<tr>
			<td>3*</td>
			<td>Last Name</td>
			<td><input placeholder="Last Name" class="form-control input-sm" type="text" name="l_name" value = "<?php echo $last_name;?>"></td>
		</tr>
		<tr>
			<td>4</td>
			<td>Phone Number</td>
			<td><input placeholder="" class="form-control input-sm" type="text" min="0" max=""  maxlength="11" name="contact_number" value = "<?php echo $contact_number;?>"></td>
		</tr>
	</table>



		 <br/><br/><br/>
		<button type = "submit" class="btn btn-embossed btn-primary" name = "add">Add</button>
		<button class="btn btn-embossed btn-primary" name='back'>Back</button>
	</fieldset>
</section>

<section id="col2">
		<table border = "1">
			<tr>
				<td>5*</td>
				<td>Total Household Members</td>
				<td><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_member" value = "<?php echo $hh_member;?>"></td>
			</tr>
			<tr>
				<td>6*</td>
				<td>Chronically Sick Household Members</td>
				<td><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "50" name="hh_sick" value = "<?php echo $hh_sick;?>"></td>
			</tr>
			<tr>
				<td>7*</td>
				<td>Household Income Last Week</td>
				<td><input placeholder="" class="form-control input-sm" type="number"  min="0" max= "" name="hh_income" value = "<?php echo $hh_income;?>"></td>
			</tr>
			<tr>
				<td>8*</td>
				<td>4Ps Member</td>
				<td>
					<select id="four_ps" name="four_ps">
				  <?php
						$a="";
						$b="";

						if($variable1 == "Yes")
						   $a = "selected";
						else if($variable1 == "No")
						   $b = "selected";
						else
						   echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="Yes">Yes</option>
				  <option <?php echo $b;?> value="No">No</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS1</td>
				<td>What is the size of the home/building?</td>
				<td>
					<select id="ps1" name="ps1">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps1 == "a")
						   $a = "selected";
						else if($ps1 == "b")
						   $b = "selected";
						else if($ps1 == "c")
						   $b = "selected";
						else
						   echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Small (&lt;15 sqm)</option>
				  <option <?php echo $b;?> value="b">Medium (&gt;15 sqm &amp; &lt;25 sqm)</option>
				  <option <?php echo $c;?> value="c">Large (&gt;25 sqm)</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS2</td>
				<td>Floor Materials</td>
				<td>
					<select id="ps2" name="ps2">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps2 == "a")
						   $a = "selected";
						else if($ps2 == "b")
						   $b = "selected";
						else if($ps2 == "c")
						   $b = "selected";
						else
						   echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Weak (Dirt)</option>
				  <option <?php echo $b;?> value="b">Moderate (Bamboo/Plyboard)</option>
				  <option <?php echo $c;?> value="c">Strong (Concrete)</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS3</td>
				<td>Roof Materials</td>
				<td>
					<select id="ps3" name="ps3">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps3 == "a")
						  $a = "selected";
						else if($ps3 == "b")
						  $b = "selected";
						else if($ps3 == "c")
						  $b = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Scrap</option>
				  <option <?php echo $b;?> value="b">Old GI Sheet</option>
				  <option <?php echo $c;?> value="c">New GI Sheet</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS4</td>
				<td>Wall Materials</td>
				<td>
					<select id="ps4" name="ps4">
				  <?php
						$a="";
						$b="";
						$c="";
						$d="";
						$e="";
						$f="";

						if($ps4 == "a")
						  $a = "selected";
						else if($ps4 == "b")
						  $b = "selected";
						else if($ps4 == "c")
						  $c = "selected";
						else if($ps4 == "d")
						  $d = "selected";
						else if($ps4 == "e")
						  $e = "selected";
						else if($ps4 == "f")
						  $f = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Scrap</option>
				  <option <?php echo $b;?> value="b">Bamboo</option>
				  <option <?php echo $c;?> value="c">Plywood</option>
				  <option <?php echo $d;?> value="d">Lawanit</option>
				  <option <?php echo $e;?> value="e">Wood</option>
				  <option <?php echo $f;?> value="f">Concrete</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS5</td>
				<td>Land Status</td>
				<td>
					<select id="ps5" name="ps5">
				  <?php
						$a="";
						$b="";
						$c="";
						$d="";
						$e="";
						$f="";

						if($ps5 == "a")
						  $a = "selected";
						else if($ps5 == "b")
						  $b = "selected";
						else if($ps5 == "c")
						  $c = "selected";
						else if($ps5 == "d")
						  $d = "selected";
						else if($ps5 == "e")
						  $e = "selected";
						else if($ps5 == "f")
						  $f = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Squatting</option>
				  <option <?php echo $b;?> value="b">Tenant</option>
				  <option <?php echo $c;?> value="c">Renting</option>
				  <option <?php echo $d;?> value="d">Amortization</option>
				  <option <?php echo $e;?> value="e">Inherited</option>
				  <option <?php echo $f;?> value="f">Owned</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS6</td>
				<td>Water Supply</td>
				<td>
					<select id="ps6" name="ps6">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps6 == "a")
						  $a = "selected";
						else if($ps6 == "b")
						  $b = "selected";
						else if($ps6 == "c")
						  $c = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">None within 50 meters</option>
				  <option <?php echo $b;?> value="b">Shared deep well or faucet within 50 meters</option>
				  <option <?php echo $c;?> value="c">Faucet (gripo) at home or own deep well</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS7</td>
				<td>Furniture (select all that apply)</td>
				<td>
					<input type="checkbox" <?php echo tick_checkbox($ps7, 'a');?> name="ps7[]" value="a" />Sala Set
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'b');?> name="ps7[]" value="b" />Bed
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'c');?> name="ps7[]" value="c" />Dining Set
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'd');?> name="ps7[]" value="d" />TV Cabinet
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'e');?> name="ps7[]" value="e" />Closet
			      <input type="checkbox" <?php echo tick_checkbox($ps7, 'f');?> name="ps7[]" value="f" />Kitchen Cabinet
				</td>
			</tr>
			<tr>
				<td>PS8</td>
				<td>Appliances (select all that apply)</td>
				<td>
					<input type="checkbox" <?php echo tick_checkbox($ps8, 'a');?> name="ps8[]" value="a" />Digital Clock
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'b');?> name="ps8[]" value="b" />Television
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'c');?> name="ps8[]" value="c" />Radio
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'd');?> name="ps8[]" value="d" />Electric Fan
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'e');?> name="ps8[]" value="e" />Washing Machine
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'f');?> name="ps8[]" value="f" />Microwave<br/>
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'g');?> name="ps8[]" value="g" />Computer
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'h');?> name="ps8[]" value="h" />Laptop
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'i');?> name="ps8[]" value="i" />Telephone
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'j');?> name="ps8[]" value="j" />Electric Kettle
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'k');?> name="ps8[]" value="k" />Gas Stove
			      <input type="checkbox" <?php echo tick_checkbox($ps8, 'l');?> name="ps8[]" value="l" />Refrigerator
				</td>
			</tr>
			<tr>
				<td>PS9</td>
				<td>How many telephones/mobile phones does the family own?</td>
				<td>
					<select id="ps9" name="ps9">
					  <?php
						$a="";
						$b="";
						$c="";
						$d="";

						if($ps9 == "a")
						  $a = "selected";
						else if($ps9 == "b")
						  $b = "selected";
						else if($ps9 == "c")
						  $c = "selected";
						else if($ps9 == "d")
						  $d = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">None</option>
				  <option <?php echo $b;?> value="b">One</option>
				  <option <?php echo $c;?> value="c">Two</option>
				  <option <?php echo $d;?> value="d">Three or more</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS10</td>
				<td>Vehicle</td>
				<td>
					<select id="ps10" name="ps10">
				  <?php
						$a="";
						$b="";
						$c="";
						$d="";
						$e="";
						$f="";

						if($ps10 == "a")
						  $a = "selected";
						else if($ps10 == "b")
						  $b = "selected";
						else if($ps10 == "c")
						  $c = "selected";
						else if($ps10 == "d")
						  $d = "selected";
						else if($ps10 == "e")
						  $e = "selected";
						else if($ps10 == "f")
						  $f = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">None</option>
				  <option <?php echo $b;?> value="b">Bicycle</option>
				  <option <?php echo $c;?> value="c">Trisikad</option>
				  <option <?php echo $d;?> value="d">Tricycle</option>
				  <option <?php echo $e;?> value="e">Motorcycle</option>
				  <option <?php echo $f;?> value="f">Jeepney/Car</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS11</td>
				<td>Electricity</td>
				<td>
					<select id="ps11" name="ps11">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps11 == "a")
						  $a = "selected";
						else if($ps11 == "b")
						  $b = "selected";
						else if($ps11 == "c")
						  $c = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">None</option>
				  <option <?php echo $b;?> value="b">Shared Meter</option>
				  <option <?php echo $c;?> value="c">Own Meter</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS12</td>
				<td>Fuel for Cooking</td>
				<td>
					<select id="ps12" name="ps12">
				  <?php
						$a="";
						$b="";
						$c="";

						if($ps12 == "a")
						  $a = "selected";
						else if($ps12 == "b")
						  $b = "selected";
						else if($ps12 == "c")
						  $c = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">Charcoal/Wood</option>
				  <option <?php echo $b;?> value="b">Kerosene</option>
				  <option <?php echo $c;?> value="c">LPG/Electricity</option>
				  </select>
				</td>
			</tr>
			<tr>
				<td>PS13</td>
				<td>Toilet</td>
				<td>
					<select id="ps13" name="ps13">
				  <?php
						$a="";
						$b="";
						$c="";
						$d="";
						$e="";
						$f="";

						if($ps13 == "a")
						  $a = "selected";
						else if($ps13 == "b")
						  $b = "selected";
						else if($ps13 == "c")
						  $c = "selected";
						else if($ps13 == "d")
						  $d = "selected";
						else if($ps13 == "e")
						  $e = "selected";
						else if($ps13 == "f")
						  $f = "selected";
						else
						  echo "<option disabled selected>Please Choose</option>";
				  ?>
				  <option <?php echo $a;?> value="a">None</option>
				  <option <?php echo $b;?> value="b">Shared</option>
				  <option <?php echo $c;?> value="c">Communal</option>
				  <option <?php echo $d;?> value="d">Pit</option>
				  <option <?php echo $e;?> value="e">Manual</option>
				  <option <?php echo $f;?> value="f">Flush</option>
				  </select>
				</td>
			</tr>
		</table>
</section>
</article>
</form>

<script src='default.js'></script>
</body>

</html>
