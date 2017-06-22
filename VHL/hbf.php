<?php
session_start();

if(empty($_SESSION['username']))
	header('location: /ICM/Login.php?a=2');
	$username = $_SESSION['username'];

	include_once "../_parentFunctions.php";
	include_once "_tnsFunctions.php";

	$dt = new DateTime();
	$date = $dt->format('Y-m-d');
	//$today = getDate_details('2015-11-22');
	$today = getDate_details($date);
	$today_week = $today['week_number'];
  $application_pk = $_GET['a'];
  $query = getParticipant_forApplication_byTag($application_pk,"5","participant_id","=");
?>

<style type="text/css">
	.mdl-select__input {
		border: none;
		border-bottom: 1px solid rgba(0,0,0, 0.12);
		display: inline-block;
		font-size: 14px;
		margin: 0;
		width: 160px;
		background: 14px;
		text-align: left;
		color: inherit;
	}

  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

	.material-icons.md-20 { font-size: 20px; }
	.material-icons.md-24 { font-size: 28px; }
	.material-icons.md-18 { font-size: 18px; }
	.material-icons.md-x { font-size: 15px; color: red;}

	#report { border-collapse:collapse;}
  #report th { background:# repeat-x scroll center left; color:#; padding:7px 15px; text-align:left;}
  #report td { background:#fff none repeat-x scroll center left; color:#000; padding:7px 15px; text-align:left;}
  #report tr.odd td { background:#fff repeat-x scroll center left; cursor:pointer; }
  #report div.arrow { background:transparent url(arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
  #report div.up { background-position:0px 0px;}

	#abc {
		width:100%;
		height:100%;
		top:0;
		left:0;
		display:none;
		position:fixed;
		background-color:;
		overflow:auto
	}
	#close {
		position:absolute;
		right:3px;
		top:3px;
		cursor:pointer;
	}
	div#popupContact {
		position:absolute;
		left:50%;
		top:17%;
		margin-left:-202px;
		font-family:'Raleway',sans-serif
	}
	form {
		max-width:450px;
		min-width:450px;
		padding: 20px 25px 20px 25px;
		border-radius:2px;
		background-color:#e5e5e5;
		-webkit-box-shadow: 0px 3px 13px -5px rgba(36,36,36,1);
		-moz-box-shadow: 0px 3px 13px -5px rgba(36,36,36,1);
		box-shadow: 0px 3px 13px -5px rgba(36,36,36,1);
	}

	#label_style {
		font-family: 'Roboto Condensed', sans-serif;
		font-size: 15px;
		font-weight: 700;
		color: #a1a1a1;
		vertical-align: top;
		text-align: right;
		width: 136px;
		background: ;
		height: automatic;
	}

	#input_style {
		width: 160px;
	}

	#content_style {
	/*	font-family: 'Roboto', sans-serif;
		padding-left: 24px;
		min-height: 20px;
		padding-bottom: 0;*/
	}

	#title {
		font-size: 24px;
		font-family: 'Roboto', sans-serif;
		font-weight: 400;
		padding-top: 16px;
		background:;
		height: 32px;
	}

	.mdl-select__input {
	  border: none;
	  border-bottom: 1px solid rgba(0,0,0, 0.12);
	  display: inline-block;
	  font-size: 14px;
	  margin: 0;
	  padding: 0 0 0 0;
	  width: 160px;
	  background: 14px;
	  text-align: left;
	  color: inherit;
	}

  th.rotate {
    /* Something you can count on */
    height: 140px;
    white-space: nowrap;
  	border-bottom: 2px solid #494949;
  	font-family: 'Quicksand', sans-serif;
  	font-weight: normal;
  	font-size: 13px;
  }

  th.rotate > div {
    transform:
      /* Magic Numbers */
      translate(25px, 51px)
      /* 45 is really 360 - 45 */
      rotate(315deg);
    width: 30px;

  }

  th.rotate > div > span {
    border-bottom: 1px solid #494949;
    padding: 5px 10px;
    width: 35px;
  }

    #att_table tr:nth-child(even) {background: }
    #att_table tr:nth-child(odd) {background: }
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>HBF</title>
	<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.indigo-red.min.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<script type="text/javascript">
  $(document).ready(function(){
      $("#report tr:odd").addClass("odd");
      $("#report tr:not(.odd)").hide();
      $("#report tr:first-child").show();

      $("#report tr:odd").click(function(){
          $(this).next("tr").toggle();
          $(this).find(".arrow").toggleClass("up");
      });
  });
</script>
<body>
<br/>


<div class="mdl-grid"><div class="mdl-cell mdl-cell--2-col">
	<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Patients" onclick="contentChange('1')"><i class="material-icons">chrome_reader_mode</i></button>
	<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Add Child" id="popup" onclick="div_show()"><i class="material-icons">person_add</i></button>
  <button name="refresh" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Refresh Page" onclick="location.reload()"><i class="material-icons">refresh</i></button>
  <!--<button name="back" class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored" title="Reports" onclick="contentChange('3')"><i class="material-icons">poll</i></button>-->
</div></div>

<span id="notice"></span>

<div id="abc">
	<div id="popupContact">
		<form action="#" id="form" method="post" name="form">
			<div id="title">Add Child</div><br/>
			  <i id="close" class="material-icons md-18" onclick ="div_hide()">clear</i>

			<label id="label_style">Date of Weighing</label><div id="content_style"><input class="mdl-select__input" type="date" name="weight_date" max="<?php echo date("Y-m-d");?>" id="weight_date"></div><br/>

      <label id='label_style'>Name of Child</label>
      <div id='content_style'>
        <div id='input_style' class='mdl-textfield mdl-js-textfield'>
          <input class='mdl-textfield__input' type='text' pattern='[A-Z,a-z, ]*' id='child_fname' onchange='duplicate_lookup()'/>
          <label class='mdl-textfield__label' for='child_fnam'>First Name</label>
          <span class='mdl-textfield__error'>Alphabetic characters only, no '&ntilde;' or '.' marks!</span>
        </div>

        <div id='input_style' class='mdl-textfield mdl-js-textfield'>
          <input class='mdl-textfield__input' type='text' pattern='[A-Z,a-z, ]*' id='child_lname' onchange='duplicate_lookup()'/>
          <label class='mdl-textfield__label' for='child_lname'>Last Name</label>
          <span class='mdl-textfield__error'>Alphabetic characters only, no '&ntilde;' or '.' marks!</span>
        </div>
      </div>

			<label id="label_style">Date of Birth</label><div id="content_style"><input class="mdl-select__input" type="date" min="" max="<?php echo date("Y-m-d");?>" name="bday" id="bday"></div><br/>

      <label id="label_style">Sex</label>
      <div id="content_style">
        <label class="mdl-radio mdl-js-radio" for="sex-1">
  			  <input type="radio" id="sex-1" class="mdl-radio__button" name="sex" value="1" checked>
  			  <span class="mdl-radio__label">Male</span>
			  </label>&nbsp;&nbsp;&nbsp;
			  <label class="mdl-radio mdl-js-radio" for="sex-2">
  			  <input type="radio" id="sex-2" class="mdl-radio__button" name="sex" value="2">
  			  <span class="mdl-radio__label">Female</span>
				</label>
      </div>
      <br/>

			<label id="label_style">Weight (kg)</label><div id="content_style"><div id="input_style" class="mdl-textfield mdl-js-textfield">
			<input class="mdl-textfield__input" type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" id="weight_value"/>
			<label class="mdl-textfield__label" for="weight_value">Weight</label>
			</div></div>

			<label id="label_style">Height (cm)</label><div id="content_style"><div id="input_style" class="mdl-textfield mdl-js-textfield">
			<input class="mdl-textfield__input" type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" id="height_value"/>
			<label class="mdl-textfield__label" for="height_value">Height</label>
      </div></div>
			<input type="button" onclick="abrakadabra(); check_age();" value="Compute"/>
			<br/><br/>
			<label id="label_style">Result</label>

      <div id="wasting_value"></div>
      <br/>
      <div id= "recumbent_ask">
      <label id="label_style">Recumbent</label>
      <div id="content_style">
        <label class="mdl-radio mdl-js-radio" for="recumbent-1">
  			  <input type="radio" id="recumbent-1" class="mdl-radio__button" name="recumbent" value="t" >
  			  <span class="mdl-radio__label">Yes</span>
			  </label>&nbsp;&nbsp;&nbsp;
			  <label class="mdl-radio mdl-js-radio" for="recumbent-2">
  			  <input type="radio" id="recumbent-2" class="mdl-radio__button" name="recumbent" value="f" checked>
  			  <span class="mdl-radio__label">No</span>
				</label>
      </div>
      </div>
      <br/>

      <div id= "breast_feed_ask">
        <label id="label_style">Breastfeeding?</label>
        <div id="content_style">
          <label class="mdl-radio mdl-js-radio" for="breast_feed-1">
    			  <input type="radio" id="breast_feed-1" class="mdl-radio__button" name="breast_feed" value="t">
    			  <span class="mdl-radio__label">Yes</span>
  			  </label>&nbsp;&nbsp;&nbsp;
  			  <label class="mdl-radio mdl-js-radio" for="breast_feed-2">
    			  <input type="radio" id="breast_feed-2" class="mdl-radio__button" name="breast_feed" value="f" checked>
    			  <span class="mdl-radio__label">No</span>
  				</label>
        </div>
      </div>

      <br/><label id='label_style'>Week</label>
					<div id='content_style'>
						<select id='week_entry' class='mdl-select__input' name='week_entry'>
							<option disabled selected value='Empty'>Please Choose</option>
							<option value='2'>Week 2</option>
							<option value='3'>Week 3</option>
							<option value='4'>Week 4</option>
							<option value='5'>Week 5</option>
							<option value='6'>Week 6</option>
							<option value='7'>Week 7</option>
							<option value='8'>Week 8</option>
							<option value='9'>Week 9</option>
							<option value='10'>Week 10</option>
							<option value='11'>Week 11</option>
							<option value='12'>Week 12</option>
							<option value='13'>Week 13</option>
						</select>
					</div>
					<br/>

			<label id='label_style'>Guardian</label>
			<div id='content_style'>
				<select id='guardian_id' class='mdl-select__input' name='guardian_id' onchange='isGuest(this.value)'>
					<option disabled selected value='Empty'>Please Choose</option>
					<option value='-99'>Guest</option>
					  <?php
					  $query = getParticipant_forApplication_byTag($application_pk,"5","participant_id","=");
						while($participant=pg_fetch_array($query,NULL,PGSQL_BOTH)){
							$participant_pk = $participant['id'];
							$participant_id = $participant['participant_id'];
							$participant_name = $participant['last_name'].', '.$participant['first_name'];
							echo "<option value='$participant_pk'>($participant_id) $participant_name</option>";
						} ?>

				</select>
			<div id='guardian_guest_show'>
				<div id='input_style' class='mdl-textfield mdl-js-textfield'>
					<input class='mdl-textfield__input' type='text' pattern='[A-Z,a-z, ]*' id='guardian_fname' />
					<label class='mdl-textfield__label' for='child_fname'>First Name</label>
					<span class='mdl-textfield__error'>Alphabetic characters only, no '&ntilde;' or '.' marks!</span>
				</div>

				<div id='input_style' class='mdl-textfield mdl-js-textfield'>
					<input class='mdl-textfield__input' type='text' pattern='[A-Z,a-z, ]*' id='guardian_lname' />
					<label class='mdl-textfield__label' for='child_lname'>Last Name</label>
					<span class='mdl-textfield__error'>Alphabetic characters only, no '&ntilde;' or '.' marks!</span>
				</div>

        <br/>
        <label id="label_style">Contact Number</label><div id="content_style"><div  class="mdl-textfield mdl-js-textfield">
  			<input class="mdl-textfield__input" type="number" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" id="contact_number"/>
  			<label class="mdl-textfield__label" for="weight_value">Contact Number</label>
  			</div>
			</div>
      <br/>
      </div>
		</div>
		<br/>


		<span id="notice"></span>
      <br/>
			<span id="note"></span>
      <!--<label id="label_style">RHU Check</label>
      <div id="content_style">
        <label class="mdl-radio mdl-js-radio" for="rhu_check-1">
  			  <input type="radio" id="rhu_check-1" class="mdl-radio__button" name="rhu_check" value="t">
          <span class="mdl-radio__label">Yes</span>
  			</label>&nbsp;&nbsp;&nbsp;
  			<label class="mdl-radio mdl-js-radio" for="rhu_check-2">
  			  <input type="radio" id="rhu_check-2" class="mdl-radio__button" name="rhu_check" value="f">
  			  <span class="mdl-radio__label">No</span>
  			</label>
      </div>-->

			<a href="#" id="submit" onclick="insertPatient()">Save</a>
		</form>
	</div>
</div>
<div class="mdl-grid">
  <table border="1" align="center" width="100%" style="background:#FAFAFA;" border="0"  id="report">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th class="mdl-data-table__cell--non-numeric">HBF ID</th>
			<th class="mdl-data-table__cell--non-numeric">GUARDIAN</th>
			<th class="mdl-data-table__cell--non-numeric">NAME</th>
			<th class="mdl-data-table__cell--non-numeric">AGE</th>
			<th class="mdl-data-table__cell--non-numeric">GENDER</th>
			<th class="mdl-data-table__cell--non-numeric">MEASURE</th>
			<th class="mdl-data-table__cell--non-numeric">INITIAL STATUS</th>
			<th class="mdl-data-table__cell--non-numeric">CURRENT STATUS</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
			<th>7</th>
			<th>8</th>
			<th>9</th>
			<th>10</th>
			<th>11</th>
			<th>12</th>
			<th>13</th>
			<th>14</th>
			<th>15 (Weight)</th>
			<th>15 (Height)</th>
			<th>16 (Weight)</th>
			<th>16 (Height)</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
    <?php
      $result = getHBF_patient_list($application_pk,"Qualified");
      while($hbf = pg_fetch_array($result,NULL,PGSQL_BOTH)) {

        $patient_pk = $hbf['id'];
        $i_date_weight = $hbf['initial_weight_date'];
        $week_entry = $hbf['week_entry'];
        $i_weight = $hbf['initial_weight'];
        $i_height = $hbf['initial_height'];
        $rhu_check = $hbf['rhu_check'];
        $abcdef_check = $hbf['abcdef_check'];
        $appetite_test = $hbf['appetite_test'];
        $patient_id = $hbf['patient_id'];
        $participant_pk = $hbf['fk_participant_pk'];
        $last_name = $hbf['last_name'];
        $first_name = $hbf['first_name'];
        $birthday = $hbf['birthday'];
        $gender = $hbf['gender'];
				$weighing_age = computeHBF_age($i_date_weight,$birthday);
	      $current_age = computeHBF_age("",$birthday);
        $initial_condition = $hbf['initial_condition'];
        $measure = $hbf['measure'];
        $discharge_status = $hbf['discharge_status'];
        $i_wasting_score = $hbf['initial_wasting_status'];
        $final_weight = $hbf['week_15_weight'];
        $final_height = $hbf['week_15_height'];
        $final_weight_16 = $hbf['week_16_weight'];
        $final_height_16 = $hbf['week_16_height'];
        $recumbent = $hbf['recumbent'];
        $breast_feed = $hbf['breast_feed'];
        $contact_number = $hbf['contact_number'];
				$dt = new DateTime();
				$date_now = $dt->format('Y-m-d');

				$c_weight = getHBF_current_weight($patient_pk);
				if($c_weight <= 0)
					$c_weight = $i_weight;
				if($measure == "WHZ") {
					$c_wasting_score = compute_wasting_score($c_weight,$i_height,$gender);

					if($i_wasting_score <= -2 && $i_wasting_score > -3)
						$i_condition = "MAM";
					else if($i_wasting_score <= -3)
						$i_condition = "SAM";
					else if($i_wasting_score == "99999")
						$i_condition = "Undefined";
					else
						$i_condition = "Normal";
				}
				else if($measure == "BMI") {
					$c_wasting_score = compute_bmi_score($birthday,$date_now,$c_weight,$i_height,$gender);
					if($i_wasting_score <= -2 && $i_wasting_score > -3)
						$i_condition = "MAM";
					else if($i_wasting_score <= -3)
						$i_condition = "SAM";
					else if($i_wasting_score == "99999")
						$i_condition = "Undefined";
					else
						$i_condition = "Normal";
				}

        if($gender == "1")
          $sex = "Male";
        else if($gender == "2")
          $sex = "Female";
        $tag = $hbf['tag'];

				if($participant_pk == "-99") {
					$guardian_name = $hbf['guardian_lname'].", ".$hbf['guardian_fname'];
				}
				else {
					$p_row = getParticipantDetails($participant_pk);
					$guardian_name = $p_row['last_name'].", ".$p_row['first_name'];
				}

        if($discharge_status == "1") {
          $c_condition = "RT";
        }
        else if($discharge_status == "2") {
          $c_condition = "R";
        }
        else if($discharge_status == "3") {
          $c_condition = "C";
        }
        else if($discharge_status == "4") {
          $c_condition = "D";
        }
        else if($discharge_status == "5") {
          $c_condition = "X";
        }
        else {
	        	if($c_wasting_score <= -2 && $c_wasting_score > -3)
	        		$c_condition = "MAM";
						else if($c_wasting_score <= -3)
	        		$c_condition = "SAM";
						else if($c_wasting_score == "99999")
							$c_condition = "Undefined";
	        	else
	        		$c_condition = "Normal";

        }

				$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
				$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
				$queryx = "UPDATE list_hbf_patient SET current_condition = '$c_condition' WHERE id = '$patient_pk'";
				$resultx = pg_query($dbconn, $queryx);

        echo "<tr>
    			<td><div class='arrow'></div></td>
          <td style='text-align:left'>($patient_pk) $patient_id</td>
          <td style='text-align:left'>($participant_pk) $guardian_name</td>
    			<td style='text-align:left'>$last_name, $first_name</td>
    			<td style='text-align:center'>$current_age mos.</td>
    			<td style='text-align:center'>$sex</td>
    			<td>$measure</td>
					<td>$i_condition</td>
					<td>$c_condition</td>";

        /*if($c_wasting_score >= 2 || $c_wasting_score <= -5)
        echo "<i class='material-icons md-x'>warning</i>";*/

        for($week=3;$week<15;$week++) {
						$weekly = getHBF_patient_weekly_details($patient_pk,$week);
          	$h2h_checked = ($weekly['h2h'] == "t" ? "checked" : "");
          	$mcn_checked = ($weekly['mcn'] == "t" ? "checked" : "");
          	$value = $weekly['weight'];
          $disable_entry = ($discharge_status > 0 ? "disabled" : "");
          echo "
      			<td>
              H2H <input type='checkbox' $h2h_checked $disable_entry onchange='update_h2h($week,$patient_pk)' value='t'/>
              WT
              <input type='number' max='300' style='width:45px;' $disable_entry onchange='update_week($week,$patient_pk,this.value)' value='$value'/>
              MCN <input type='checkbox' $mcn_checked $disable_entry onchange='update_mcn($week,$patient_pk)' value='t'/>
            </td>";
        }

        $recumbent = ($recumbent == "t" ? "Y" : "N");
        $breast_feed = ($breast_feed == "t" ? "Y" : "N");

        echo "
        <td><input type='number' max='300' value='$final_weight' $disable_entry style='width:45px;' onchange='update_final_weight($patient_pk,this.value)'/></td>
        <td><input type='number' max='300' value='$final_height' $disable_entry style='width:45px;' onchange='update_final_height($patient_pk,this.value)'/></td>
        <td><input type='number' max='300' value='$final_weight_16' $disable_entry style='width:45px;' onchange='update_16_weight($patient_pk,this.value)'/></td>
        <td><input type='number' max='300' value='$final_height_16' $disable_entry style='width:45px;' onchange='update_16_height($patient_pk,this.value)'/></td>
        <td><a href='#' onclick=delete_patient('$patient_pk')>Delete</a>&nbsp;<a href='hbf_update.php?a=$application_pk&b=$patient_pk'>Update</a></td>
        </tr>
        <tr>
            <td colspan='22'>
                <div style='display:inline-block; padding:5 20 20 10; vertical-align:top;'>
    							Screening Data<br/><br/>
                  Week Entry: $week_entry<br/>
                  Birthday: $birthday<br/>
                  Date of Weighing: $i_date_weight<br/>
                  Age of Weighing: $weighing_age<br/>
                  Weight: $i_weight kg<br/>
                  Height: $i_height cm<br/>
                  Initial Wasting Score: $i_wasting_score<br/>
                  Recumbent: $recumbent<br/>
                  Breastfeeding: $breast_feed<br/>
                  Contact number: $contact_number<br/>
                </div>
                <div style='display:inline-block; padding:5 20 20 20; border-left:1px solid #000;height:100%;vertical-align:top;'>
    						  $initial_condition<br/><br/>";

        if($c_condition == "SAM")

        echo "
    							ABCDEF Check: $abcdef_check<br/>
    							Appetite Test: $appetite_test<br/>";

        echo "
    						</div>
            </td>
        </tr>";
      }
    ?>

	</tbody>
  </table>
  <br/>
  <br/>
  <h4>Disqualified</h4>
  <table border="1" align="center" width="100%" style="background:#FAFAFA;border-collapse:collapse;" border="0">
    <th>Guardian's Name</th>
    <th>Child Name</th>
    <th>Birthday</th>
    <th>Age</th>
    <th>Sex</th>
    <th>Weight</th>
    <th>Height</th>
    <th>Wasting Score</th>
    <th>Reason</th>
    <th>Tag</th>
    <th>Action</th>
    <?php
      $query = getHBF_patient_list($application_pk,"Disqualified");
      while($hbf = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
      $patient_pk = $hbf['id'];
      $i_date_weight = $hbf['initial_weight_date'];
      $week_entry = $hbf['week_entry'];
      $i_weight = $hbf['initial_weight'];
      $i_height = $hbf['initial_height'];
      $rhu_check = $hbf['rhu_check'];
      $abcdef_check = $hbf['abcdef_check'];
      $appetite_test = $hbf['appetite_test'];
      $patient_id = $hbf['patient_id'];
      $participant_pk = $hbf['fk_participant_pk'];
      $last_name = $hbf['last_name'];
      $first_name = $hbf['first_name'];
      $birthday = $hbf['birthday'];
      $gender = $hbf['gender'];
      $weighing_age = computeHBF_age($i_date_weight,$birthday);
      $current_age = computeHBF_age("",$birthday);
      $i_wasting_score = $hbf['initial_wasting_status'];
			if($participant_pk == "-99") {
				$guardian_name = $hbf['guardian_lname'].", ".$hbf['guardian_fname'];
			}
			else {
				$p_row = getParticipantDetails($participant_pk);
				$guardian_name = $p_row['last_name'].", ".$p_row['first_name'];
			}

      if($gender == "1")
        $sex = "Male";
      else if($gender == "2")
        $sex = "Female";
      $tag = $hbf['tag'];

			if($i_wasting_score <= -5) {
				$reason = "Remeasure";
			}
			else if($tag == '2')
				$reason = "Disqualified by age";
			else if($tag == '3')
				$reason = "Normal";
			else
				$reason = "" ;

      echo "<tr><td>$guardian_name</td>
        <td>($patient_pk) $last_name, $first_name</td>
        <td>$birthday</td>
				<td>$weighing_age mos.</td><td>$sex</td>
        <td>$i_weight kg</td>
        <td>$i_height cm</td>
        <td>$i_wasting_score</td>
        <td>$reason</td>
        <td>$tag</td>
        <td align='center'>
					<a href='#' onclick=delete_patient('$patient_pk')>
					Delete</a>&nbsp;
					<a href='hbf_update.php?a=$application_pk&b=$patient_pk'>Update</a>
				</td>
        </tr>";
    }?>
  </table>
</div>
</form>
</body>
<script>
//document.getElementById('sam').style.display = "none";
document.getElementById('guardian_guest_show').style.display = "none";
document.getElementById('breast_feed_ask').style.display = "none";
document.getElementById('recumbent_ask').style.display = "none";

function delete_patient(patient_pk) {
	var del = confirm("Are you sure you want to delete "+patient_pk+"?");
	if (del == true) {
    var xmlhttp = null;
  	if(typeof XMLHttpRequest != 'udefined'){
  			xmlhttp = new XMLHttpRequest();
  	}else if(typeof ActiveXObject != 'undefined'){
  			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  	}else
  			throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_action.php?command=delete_patient&patient_pk='+patient_pk, true);
  	xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
  		window.returnResponse(xmlhttp);
    };
    xmlhttp.send(null);
	}
}

function update_h2h(week,patient_pk) {
  var username = '<?php echo $username;?>';
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');
  //window.location.href = '_action.php?command=update_h2h&week_entry='+week+'&patient_pk='+patient_pk+'&username='+username;
  xmlhttp.open('GET', '_action.php?command=update_h2h&week_entry='+week+'&patient_pk='+patient_pk+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      xmlhttp.responseText;
		//window.insertAttendanceLog(xmlhttp);
  };
  xmlhttp.send(null);
}

function update_mcn(week,patient_pk) {
  var username = '<?php echo $username;?>';
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_action.php?command=update_mcn&week_entry='+week+'&patient_pk='+patient_pk+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      xmlhttp.responseText;
		//window.insertAttendanceLog(xmlhttp);
  };
  xmlhttp.send(null);
}

function abrakadabra() {
	var application_pk = '<?php echo $application_pk;?>';
  if(document.getElementById("sex-1").checked) {
    var sex = 1;
  }
  else if(document.getElementById("sex-2").checked) {
    var sex = 2;
  }
  var weight_value = document.getElementById("weight_value").value;
  var height_value = document.getElementById("height_value").value;
  var bday = document.getElementById("bday").value;
  var wday = document.getElementById("weight_date").value;

  if(weight_value != "" && height_value != "" && height_value >= 45) {
  	var xmlhttp = null;
  	if(typeof XMLHttpRequest != 'udefined'){
  			xmlhttp = new XMLHttpRequest();
  	}else if(typeof ActiveXObject != 'undefined'){
  			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  	}else
  			throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_action.php?command=compute_w_score&bday='+bday+'&wday='+wday+'&sex='+sex+'&w_value='+weight_value+'&h_value='+height_value+'&application_pk='+application_pk, true);
  	xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
  		window.returnResult(xmlhttp);
    };
    xmlhttp.send(null);
  }
}

function returnResult(xhr){
    if(xhr.status == 200){
      var result = document.getElementById('wasting_value').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertPatient()
{
  var week_entry = document.getElementById("week_entry").value;
  var participant_pk = document.getElementById("guardian_id").value;
  var child_fname = document.getElementById("child_fname").value;
  var child_lname = document.getElementById("child_lname").value;
  var bday = document.getElementById("bday").value;
  if(document.getElementById("sex-1").checked) {
    var sex = 1;
  }
  else if(document.getElementById("sex-2").checked) {
    var sex = 2;
  }
  if(document.getElementById("recumbent-1").checked) {
    var recumbent = "t";
  }
  else if(document.getElementById("recumbent-2").checked) {
    var recumbent = "f";
  }
  if(document.getElementById("breast_feed-1").checked) {
    var breast_feed = "t";
  }
  else if(document.getElementById("breast_feed-2").checked) {
    var breast_feed = "f";
  }
  var weight_date = document.getElementById("weight_date").value;
  var weight_value = document.getElementById("weight_value").value;
  var height_value = document.getElementById("height_value").value;
  var contact_number = document.getElementById("contact_number").value;

	if(participant_pk == "-99") {
		var guardian_fname = document.getElementById("guardian_fname").value;
	  var guardian_lname = document.getElementById("guardian_lname").value;
	}
	else {
		var guardian_fname = "";
	  var guardian_lname = "";
	}

  var application_pk = "<?php echo $application_pk;?>";
  var username = "<?php echo $username;?>";

  if (week_entry == "Empty" || participant_pk == "Empty" || child_fname == "" || child_lname == "" || bday == "" || weight_date == "" || weight_value == "" || height_value == "" ) {
    alert("Opps, you seemed to have left a field blank.");
  }

  else if (participant_pk == "-99" && guardian_fname == "" && guardian_lname == "") {
    alert("Opps, you seemed to have left a field blank.");
  }
  else {
    var xmlhttp = null;
  	if(typeof XMLHttpRequest != 'udefined'){
  			xmlhttp = new XMLHttpRequest();
  	}else if(typeof ActiveXObject != 'undefined'){
  			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  	}else
  			throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_action.php?command=add_patient&week_entry='+week_entry+
    '&participant_pk='+participant_pk+
    '&child_fname='+child_fname+
    '&child_lname='+child_lname+
    '&bday='+bday+
    '&sex='+sex+
    '&weight_date='+weight_date+
    '&weight_value='+weight_value+
    '&height_value='+height_value+
    '&application_pk='+application_pk+
    '&guardian_lname='+guardian_lname+
    '&guardian_fname='+guardian_fname+
    '&recumbent='+recumbent+
    '&breast_feed='+breast_feed+
    '&contact_number='+contact_number+
    '&username='+username, true);
  	xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
  		window.returnResponse(xmlhttp);
    };
    xmlhttp.send(null);
  }
}

function returnResponse(xhr){
    if(xhr.status == 200){
      var result = document.getElementById('notice').innerHTML = xhr.responseText;
      document.getElementById('abc').style.display = "none";
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function update_week(week,patient_pk,weight) {
  var username = "<?php echo $username;?>";
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

	//window.location.href = '_action.php?command=update_week_weight&week='+week+'&patient_pk='+patient_pk+'&weight='+weight+'&username='+username;

  xmlhttp.open('GET', '_action.php?command=update_week_weight&week='+week+'&patient_pk='+patient_pk+'&weight='+weight+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
    window.insertNote(xmlhttp);
  };
  xmlhttp.send(null);
}

function update_final_height(patient_pk,height) {
  var username = "<?php echo $username;?>";
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_action.php?command=update_final_height&patient_pk='+patient_pk+'&height='+height+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
    window.insertNote(xmlhttp);
  };
  xmlhttp.send(null);
}

function update_16_height(patient_pk,height) {
  var username = "<?php echo $username;?>";
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_action.php?command=update_16_height&patient_pk='+patient_pk+'&height='+height+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
    window.insertNote(xmlhttp);
  };
  xmlhttp.send(null);
}

function update_final_weight(patient_pk,weight) {
  var username = "<?php echo $username;?>";
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_action.php?command=update_final_weight&patient_pk='+patient_pk+'&weight='+weight+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
    window.insertNote(xmlhttp);
  };
  xmlhttp.send(null);
}

function update_16_weight(patient_pk,weight) {
  var username = "<?php echo $username;?>";
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_action.php?command=update_16_weight&patient_pk='+patient_pk+'&weight='+weight+'&username='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
    window.insertNote(xmlhttp);
  };
  xmlhttp.send(null);
}

	function duplicate_lookup()
	{
	  var participant_pk = document.getElementById("guardian_id").value;
    var child_fname = document.getElementById("child_fname").value;
    var child_lname = document.getElementById("child_lname").value;
    var app_pk  = "<?php echo $application_pk;?>";

		if(child_fname != "" && child_lname != "") {
	    var xmlhttp = null;
	    if(typeof XMLHttpRequest != 'udefined'){
	        xmlhttp = new XMLHttpRequest();
	    }else if(typeof ActiveXObject != 'undefined'){
	        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    }else
	        throw new Error('You browser doesn\'t support ajax');

	    xmlhttp.open('GET', '_action.php?command=check_duplicate_patient&l_name='+child_lname+'&f_name='+child_fname+'&participant_pk='+participant_pk+'&application_pk='+app_pk, true);
	    xmlhttp.onreadystatechange = function (){
	        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
				if(xhr.status == 200){
		      var result = document.getElementById('note').innerHTML = xhr.responseText;
		    }else
		        throw new Error('Server has encountered an error\n'+
		            'Error code = '+xhr.status);
	    };
	    xmlhttp.send(null);
		}
	}

  function check_age() {
		var dow = document.getElementById("weight_date").value;
		var dob = document.getElementById("bday").value;
    var w_year = dow.substring(0,4);
		var w_month = dow.substring(5,7);
		var w_day = dow.substring(8);
    var b_year = dob.substring(0,4);
		var b_month = dob.substring(5,7);
		var b_day = dob.substring(8);
		var age_year = (w_year-b_year)*12;
		var age_month = w_month-b_month;
		var age_day = w_day - b_day;

		if(age_day > 30) {
			age_month++;
		}

		age = age_year + age_month;

		if(age == -1)
			age = 0;
		if(age < 24) {
      document.getElementById('breast_feed_ask').style.display = "";
      document.getElementById('recumbent_ask').style.display = "";
    }
    else {
      document.getElementById('breast_feed_ask').style.display = "none";
      document.getElementById('recumbent_ask').style.display = "none";
    }
  }

/*
	function check_empty() {
		if (document.getElementById('name').value == "" || document.getElementById('email').value == "" || document.getElementById('msg').value == "") {
		alert("Fill All Fields !");
		}

		else {
		document.getElementById('form').submit();
		alert("Form Submitted Successfully...");
		}
	}*/
	//Function To Display Popup
	function div_show() {
		document.getElementById('abc').style.display = "block";
	}
	//Function to Hide Popup
	function div_hide(){
		document.getElementById('abc').style.display = "none";
	}

	function isGuest(guardian_value) {
		if(guardian_value=="-99") {
			document.getElementById('guardian_guest_show').style.display = "";
		}
		else {
			document.getElementById('guardian_guest_show').style.display = "none";
		}
	}
</script>
</html>
