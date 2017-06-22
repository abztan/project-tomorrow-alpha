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

  $application_pk = $_GET['a'];
  $application = getApplication_Data_byID($application_pk);
  $community_id = $application['community_id'];
  $pastor_pk = $application['fk_pastor_pk'];
  $pastor_pk_string = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
  $last_name = $application['pastor_last_name'];
  $first_name = $application['pastor_first_name'];
  $middle_name = $application['pastor_middle_initial'];
  $base_id = $application['base_id'];
  $base = getBaseName($base_id);
  $program = $application['application_type'];
  $program = getApplication_String($program);
  $province = $application['application_province'];
  $city = $application['application_city'];
  $barangay = $application['application_barangay'];
  $application_id = $application['application_id'];
  $application_date = $application['application_date'];
  $created_date = $application['created_date'];
  $updated_date = $application['updated_date'];
  $application_date = date("F j, Y",strtotime(str_replace('-','/', $application_date)));
  $created_date = date("F j, Y",strtotime(str_replace('-','/', $created_date)));
  $updated_date = date("F j, Y",strtotime(str_replace('-','/', $updated_date)));
  $created_by = $application['created_by'];
  $updated_by = $application['updated_by'];
  $application_tag = $application['tag'];
  $status = getApplication_Status($application_tag);
  $comment = "";
  $tab1 = "active";
  $tab2 = "";
  $tab3 = "";
  $tab4 = "";
  $tab5 = "";
  $tab6 = "";

  if(isset($_POST['update'])) {
    if(isset($_POST['pastor_attendance'])) {
      $pastor_array = $_POST['pastor_attendance'];
      $attendance_set = "";

      foreach($pastor_array as $values) {
        $attendance_set = $attendance_set.$values;
      }

      if(checkAttendance_pastor_entry($pastor_pk, $application_pk)==0) {
        insertTransform_pastor_attendance($pastor_pk,$application_pk,$attendance_set,$username);
      }
      else if(checkAttendance_pastor_entry($pastor_pk, $application_pk)==1) {
        updateTransform_pastor_attendance($pastor_pk,$application_pk,$attendance_set,$username);
      }
    }

  	$start_people = 1;
    $max_people = countParticipantTag($application_pk,5)+countParticipantTag($application_pk,6)+1;

  	while($start_people < $max_people) {
		$var_1 = 0;
		$var_2 = 0;
		$var_3 = 0;
		$var_4 = 0;
		$var_5 = 0;
		$var_6 = 0;
		$var_7 = 0;

		$htoh_form = "htoh_a".$start_people;

		if(isset($_POST["four_p".$start_people]))
		{
			$var_1a = $_POST["four_p".$start_people];
			$var_1 = $var_1a['0'];
		}

		if(isset($_POST["ngo".$start_people]))
		{
			$var_2a = $_POST["ngo".$start_people];
			$var_2 = $var_2a['0'];
		}

		if(isset($_POST["mfi".$start_people]))
		{
			$var_3a = $_POST["mfi".$start_people];
			$var_3 = $var_3a['0'];
		}

		if(isset($_POST["birth_c".$start_people]))
		{
			$var_4a = $_POST["birth_c".$start_people];
			$var_4 = $var_4a['0'];
		}

		if(isset($_POST["church_a".$start_people]))
		{
			$var_5a = $_POST["church_a".$start_people];
			$var_5 = $var_5a['0'];
		}

		if(isset($_POST["baptised".$start_people]))
		{
			$var_6a = $_POST["baptised".$start_people];
			$var_6 = $var_6a['0'];
		}

		if(isset($_POST[$htoh_form]))
		{
  			$htoh_array = $_POST[$htoh_form];
  			$htoh_set = "";

  			foreach($htoh_array as $htoh_values)
  			{
  				if($htoh_values != "")
  					$htoh_set = $htoh_set.$htoh_values;
  			}
  	}
    else
		  $htoh_set = "";

  		echo $form = "p".$start_people."_a";

  		if(isset($_POST[$form])) {
  			$participant_array = $_POST[$form];
  			$attendance_set = "";

  			foreach($participant_array as $values)
  			{
  				if($values != $participant_array['0'])
  					$attendance_set = $attendance_set.$values;
  			}
  		 }

  			//print_r($htoh_array);
			//echo "<br/>".$participant_array['0']."=".$attendance_set."=".$var_1."=".$var_2."=".$var_3."=".$var_4."=".$var_5."=".$var_6."=".$htoh_set;
			$insert_pk = $participant_array['0'];
			//no entry yet
			if(checkAttendance_entry($insert_pk, $application_pk)==0) {
				insertTransform_attendance($insert_pk,$application_pk,$attendance_set,$var_1,$var_2,$var_3,$var_4,$var_5,$var_6,$htoh_set,$username);
      }
      else {
				updateTransform_attendance($insert_pk,$application_pk,$attendance_set,$var_1,$var_2,$var_3,$var_4,$var_5,$var_6,$htoh_set,$username);
      }
			$start_people++;
  	}

  	$tab1 = "";
  	$tab2 = "";
  	$tab3 = "active";
  	$tab4 = "";
  	$tab5 = "";
  }

  if(isset($_POST['update2'])) {
    //class: 1=visitor count 2=participant nutripack 3=visitor nutripack 4=pastor and counselor nutripack
    for($class = '1'; $class < '5'; $class++) {
      for($week = '1'; $week < '17'; $week++) {
        $v = $class.$week;
        if($class != 4) {
          $value = $_POST[$v];
        }

        if($class == '1')
          $class_v = "count_visitor";
        else if($class == '2')
          $class_v = "count_child";
        //else if($class == '3')
          //$class_v = "nutripack_participant";
        //else if($class == '4')
          //$class_v = "nutripack_visitor";
        //else if($class == '5')
          //$class_v = "nutripack_pastor";
        else if($class == '3')
          $class_v = "nutripack_other";
        else if($class == '4') {
          $class_v = "double_lesson";
          if(isset($_POST[$v]))
            $value = 1;
          else
            $value = 0;
        }


        insertTransform_weekly($application_pk,$class_v,$week,$value,$username);
      }
    }

    $tab1 = "";
  	$tab2 = "";
  	$tab3 = "";
  	$tab4 = "active";
  	$tab5 = "";
  }
?>
<style>
  #att_table tr:nth-child(even) {background: }
  #att_table tr:nth-child(odd) {background: }

</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>
<header>
  <h2>Community: <?php echo $community_id; ?></h2>
  <nav class = "menu">
    <?php include "../controller.php";?>
  </nav>
</header>

<form name='form1' action='' method='POST'>
<article id="content">
<br/>
<div class="tabs">
  <ul class="tab-links">
      <li class="<?php echo $tab1; ?>"><a href="#tab1">Details</a></li>
      <li><a href="#tab2">People</a></li>
      <?php
        if($accesslevel != "13" || $accesslevel != "14")
        {
          echo "<li class='$tab3'><a href='#tab3'>Attendance</a></li>";
          echo "<li class='$tab4'><a href='#tab4'>Weekly</a></li>";
          echo "<li class='$tab5'><a href='#tab5'>BIB</a></li>";
        }
        if($accesslevel == "13" || $accesslevel == "14" || $accesslevel == "99" || $accesslevel == "1" || $accesslevel == "2" || $accesslevel == "6" || $accesslevel == "11" || $accesslevel == "5")
        {
        	echo "<li class='$tab6'><a href='#tab6'>HBF</a></li>";
        }
      ?>

  </ul>

  <div class="tab-content">
    <div id="tab1" class="tab <?php echo $tab1;?>">
      <table>
      	<tr><td>Community Information</td><td><?php echo "($community_id) ".getProgram($program);?> <a href="editApplication_1.php?a=<?= $application_pk;?>">(edit)</a></td></tr>
      	<tr><td>Pastor</td><td><?php echo $last_name.", ".$first_name." ".$middle_name;?></td></tr>
      	<tr><td>Participants</td><td><?php echo countParticipant_category_tag($application_pk,'all_pType','5')+countParticipant_category_tag($application_pk,'all_pType','6')."/".countParticipant_category_tag($application_pk,'all_pType','9');?></td></tr>
      	<tr><td>Counselors</td><td><?php echo countParticipant_category_tag($application_pk,'all_cType','5')."/".countParticipant_category_tag($application_pk,'all_cType','6');?></td></tr>
      	<tr><td>Base</td><td><?php echo $base;?></td></tr>
      	<tr><td>Status</td><td><?php echo $status;?></td></tr>
      	<tr><td>Location</td><td><?php if(!$province) echo "n/a"; else echo $province." - "; if(!$city) echo "n/a"; else echo $city.", "; if(!$barangay) echo "n/a"; else echo $barangay;?></td></tr>
      	<tr><td>Application ID</td><td><?php echo $application_id;?></td></tr>
      	<tr><td>Application Date</td><td><?php echo $application_date;?></td></tr>
      	<tr><td>Last Updated Date</td><td><?php echo $updated_date;?></td></tr>
      	<tr><td>Last Updated By</td><td><?php echo $updated_by;?></td></tr>
  		<!--<tr><td><textarea name="comment" value=""><?php echo $comment;?></textarea></td></tr>-->
      	<tr><td colspan="2"></td></tr>
      	<tr><td><?php if($access_level == '99' || $access_level == '1' || $access_level == '2' || $access_level == '5') echo '<a onclick="dropCommunity('.$application_pk.')">Drop Community</a>';?></td></tr>
    	</table>
      <div id="linechart_material"></div>
    </div>

    <div id="tab2" class="tab">
      <iframe src="people.php?a=<?php echo $application_pk; ?>" style="overflow:hidden; height:100%;width:100%; border: 0;" height="100%" width="100%"></iframe>
    </div>

    <div id="tab3" class="tab <?php echo $tab3; ?>">
      <table border = "0" id="att_table" class="fixed_headers">
        <thead>
        <tr>
          <th id = "th_style1"></th>
          <th id = "th_style1" class="rotate"><div><span>4P</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>NGO</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>MFI</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>Birth Certificate</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>Attends Church</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>Baptised</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>H2H: WK01-04</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>H2H: WK05-08</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>H2H: WK09-12</span></div></th>
          <th id = "th_style1" class="rotate1"><div><span>H2H: WK13-16</span></div></th>
          <th id = "th_style1" class="rotate2"><div><span id="text_style3">WK01</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK02</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK03</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK04</span></div></th>
          <th id = "th_style1" class="rotate2"><div><span id="text_style3">WK05</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK06</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK07</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK08</span></div></th>
          <th id = "th_style1" class="rotate2"><div><span id="text_style3">WK09</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK10</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK11</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK12</span></div></th>
          <th id = "th_style1" class="rotate2"><div><span id="text_style3">WK13</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK14</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK15</span></div></th>
          <th id = "th_style1" class="rotate"><div><span>WK16</span></div></th>
          <?php if (8 == $base_id)
            echo '<th id = "th_style1" class="rotate2"><div><span id="text_style3">Graduate</span></div></th>';
          ?>
        </tr>
        </thead>
        <tbody>
          <tr bgcolor="#bababa"><td id='td_style3'><?php echo "($pastor_pk_string) $first_name $middle_name $last_name";?></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style3'></td>
            <td align='center' id='td_style2'></td>
        <?php
          $a = "pastor_attendance[]";
          $num = 1;
          $attendance = getPastor_attendanceLog($application_pk,$pastor_pk);

        	for ($i = 'a'; $i < 'q'; $i++) {
        		$b = $i;
            $is_ck = tick_checkbox($attendance['attendance_set'], $i);

        		if($c % 4 == 1)
					    echo "<td align='center' id='td_style1'><input type='checkbox' title='WEEK $num' $is_ck name='pastor_attendance[]' value=$b /></td>";
				    else
					    echo "<td align='center' id='td_style3'><input type='checkbox' title='WEEK $num' $is_ck name='pastor_attendance[]' value=$b /></td>";

    			  $b++;
				    $c++;
            $num++;
          }

          if (8 == $base_id)
          echo "<td align='center' id='td_style1'></td></tr>";

          //legend
          //p is particpant name
          //c is table marker
          //a is checkbox array
		      //b is check box value

    			//$query = getParticipant_forApplication_byTag($application_pk,5,"participant_id","=");
          $query = getActive_Participants($application_pk,"participant_id");
          $result = pg_query($dbconn, $query);

          $c = 1;
    			$x = 1;

          while($participant = pg_fetch_array($result,NULL,PGSQL_BOTH))	{
  				  $ic_a = "";
  				  $ic_b = "";
  				  $ic_c = "";
  				  $ic_d = "";
  				  $ic_e = "";
  				  $ic_f = "";
  				  $ic_g = "";
  				  $ic_h = "";
  				  $ic_i = "";
  				  $ic_j = "";
            $ic_grad = "";

      			$participant_pk = $participant['id'];
  				  $attendance = getParticipant_attendanceLog($participant_pk);

      			if($participant['variable1'] == "Yes")
  					  $ic_a = "checked";
  				  else if($attendance['variable_1'] == "1")
  					  $ic_a = "checked";

  				  if($attendance['variable_2'] == "1")
  					  $ic_b = "checked";
  				  if($attendance['variable_3'] == "1")
  					  $ic_c = "checked";
  				  if($attendance['variable_4'] == "1")
  					  $ic_d = "checked";
  				  if($attendance['variable_5'] == "1")
  					  $ic_e = "checked";
  				  if($attendance['variable_6'] == "1")
  					  $ic_f = "checked";
  				  if($participant['tag'] == "6")
  					  $ic_grad = "checked";

      			$participant_id = $participant['participant_id'];
      			$hh_start = substr($participant_id, 0, 8);
      			$hh_end = substr($participant_id, -2);
      			$participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];
  					$a = "p".$x."_a[]";
  					$htoh = "htoh_a".$x."[]";

      		  echo "<tr id='tr_style1'>
                  <td  id='td_style3'>($participant_id) <span title='$participant_pk'>$participant_name</span><input type='checkbox' hidden checked name=$a value=$participant_pk /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='4P' $ic_a name=four_p".$x."[] value='1' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='NGO' $ic_b name=ngo".$x."[] value='1' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='MFI' $ic_c name=mfi".$x."[] value='1' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='BIRTH CERTIFICATE' $ic_d name=birth_c".$x."[] value='1' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='ATTENDS CHURCH' $ic_e name=church_a".$x."[] value='1' /></td>
  							  <td align='center' id='td_style3'><input type='checkbox' title='BAPTISED' $ic_f name=baptised".$x."[] value='1' /></td>
  							  <td align='center' id='td_style3'><input type='checkbox' title='H2H: WEEK 1-4' ".tick_checkbox($attendance['variable_7'], 'a')." name=$htoh value='a' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='H2H: WEEK 5-8' ".tick_checkbox($attendance['variable_7'], 'b')." name=$htoh value='b' /></td>
      					  <td align='center' id='td_style3'><input type='checkbox' title='H2H: WEEK 9-12' ".tick_checkbox($attendance['variable_7'], 'c')." name=$htoh value='c' /></td>
      					  <td align='center' id='td_style2'><input type='checkbox' title='H2H: WEEK 13-16' ".tick_checkbox($attendance['variable_7'], 'd')." name=$htoh value='d' /></td>";

            $num = 1;
          	for ($i = 'a'; $i < 'q'; $i++) {
          		$b = $i;

          		if($c % 4 == 1)
  					    echo "<td align='center' id='td_style1'><input type='checkbox' title='WEEK $num: $participant_name' ".tick_checkbox($attendance['attendance_set'], $i)." name=$a value=$b /></td>";
  				    else
  					    echo "<td align='center' id='td_style3'><input type='checkbox' title='WEEK $num: $participant_name' ".tick_checkbox($attendance['attendance_set'], $i)." name=$a value=$b /></td>";

      			  $b++;
  				    $c++;
              $num++;
          }

          if (8 == $base_id) {
            $chbx_id = "grad_".$participant_pk;
            echo "<td align='center' id='td_style1'><input type='checkbox' title='GRADUATE' id='$chbx_id' $ic_grad value='1' onchange='updateGraduate($participant_pk)'/></td>";
          }
          echo "</tr>";
          $c = 1;
    		  $x ++;

          }

          $query = getParticipant_forApplication_byTag($application_pk,9,"participant_id","=");

          while($participant = pg_fetch_array($query,NULL,PGSQL_BOTH))	{
				  $ic_a = "";
				  $ic_b = "";
				  $ic_c = "";
				  $ic_d = "";
				  $ic_e = "";
				  $ic_f = "";
				  $ic_g = "";
				  $ic_h = "";
				  $ic_i = "";
				  $ic_j = "";

    			$participant_pk = $participant['id'];
				  $attendance = getParticipant_attendanceLog($participant_pk);

    			if($participant['variable1'] == "Yes")
					$ic_a = "checked";
				  else if($attendance['variable_1'] == "1")
					$ic_a = "checked";

				  if($attendance['variable_2'] == "1")
					$ic_b = "checked";
				  if($attendance['variable_3'] == "1")
					$ic_c = "checked";
				  if($attendance['variable_4'] == "1")
					$ic_d = "checked";
				  if($attendance['variable_5'] == "1")
					$ic_e = "checked";
				  if($attendance['variable_6'] == "1")
					$ic_f = "checked";

    				$participant_id = $participant['participant_id'];
    				$participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];

    		    echo "<tr id='tr_style1'>
                  <td  id='td_style3'>[Dropped] ($participant_id) $participant_name<input type='checkbox' hidden checked disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' $ic_a disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' $ic_b disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' $ic_c disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' $ic_d disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' $ic_e disabled/></td>
							  <td align='center' id='td_style3'><input type='checkbox' $ic_f disabled/></td>
							  <td align='center' id='td_style3'><input type='checkbox' ".tick_checkbox($attendance['variable_7'], 'a')." disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' ".tick_checkbox($attendance['variable_7'], 'b')." disabled/></td>
    						  <td align='center' id='td_style3'><input type='checkbox' ".tick_checkbox($attendance['variable_7'], 'c')." disabled/></td>
    						  <td align='center' id='td_style2'><input type='checkbox' ".tick_checkbox($attendance['variable_7'], 'd')." disabled/></td>";

        		for ($i = 'a'; $i < 'q'; $i++) {
        			$b = $i;

        		if($c % 4 == 1)
					  echo "<td align='center' id='td_style1'><input type='checkbox' ".tick_checkbox($attendance['attendance_set'], $i)." disabled/></td>";
				  else
					  echo "<td align='center' id='td_style3'><input type='checkbox' ".tick_checkbox($attendance['attendance_set'], $i)." disabled/></td>";

    			$b++;
				$c++;
            }

            echo "</tr>";
            $c = 1;
    		$x ++;
          }
          ?>

          </tbody>
        </table>
        <button type = "submit" class="btn btn-embossed btn-primary" name = "update">Update</button>
      </div>

  <div id="tab4" class="tab <?php echo $tab4; ?>">
    <table border = "0">
      <th id = "th_style1"></th>
      <th id = "th_style1" class="rotate3"><div><span>WK01</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK02</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK03</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK04</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK05</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK06</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK07</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK08</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK09</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK10</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK11</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK12</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK13</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK14</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK15</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>WK16</span></div></th>
      <th id = "th_style1" class="rotate3"><div><span>Graduate</span></div></th>

      <?php
        //legend
        //z is row counter and identifier
        //a is checkbox array
	      //b is check box value
        $z = 1;
        while($z <= 4)	{
          echo "<tr>";
        //  if($z == 3)
            //$title = "Participant Nutripacks";
          //else if($z == 4)
            //$title = "Visitor Nutripacks";
        //  else if($z == 5)
          //  $title = "Pastor & Counselor Nutripacks";

          if($z == 1)
            $title = "Visitor Count";
          else if($z == 2)
            $title = "Children Count";
          else if($z == 3)
            $title = "Overall Nutripacks";
          else if($z == 4)
            $title = "Double Lesson";

          echo "<td align='left' id='td_style3'>$title</td>";

      		for ($i = '1'; $i < '17'; $i++) {
              $b = $z.$i;
              if($z == '1')
                $class_v = "count_visitor";
              else if($z == '2')
                $class_v = "count_child";
              //else if($z == '3')
                //$class_v = "nutripack_participant";
              //else if($z == '4')
                //$class_v = "nutripack_visitor";
              //else if($z == '5')
                //$class_v = "nutripack_pastor";
              else if($z == '3')
                $class_v = "nutripack_other";
              else if($z == '4')
                $class_v = "double_lesson";

              $c_array = getTransform_weekly_value($i,$application_pk);
              $c = $c_array[$class_v];
              if($class_v == "nutripack_other" && $c == "")
                $c = $c_array['nutripack_pastor'] + $c_array['nutripack_participant'] + $c_array['nutripack_visitor'];

              if($class_v == "double_lesson")
                echo "<td align='center' id='td_style3'><input type='checkbox' ".tick_checkbox(1, $c)." name='$b' value='$c'/></td>";
              else
  			        echo "<td align='center' id='td_style3'><input type='number'  min='0' style='width:40px;' name='$b' value='$c'></td>";

          }

          if($z != 4 && $i >= 16  && $z == 1) {
            $v_grad = $application['visitor_graduate'];
            echo "<td align='center' id='td_style3'><input type='number'  min='0' style='width:40px;' value='$v_grad' onchange='updateGraduate_visitor($application_pk,this.value)'></td>";
          }
          echo "</tr>";
          $z++;
        }
      ?>
      <tr><td><button type = "submit" class="btn btn-embossed btn-primary" name = "update2">Update</button><td></tr>
      </table>
    </div>

    <?php
      echo '
       <div id="tab5" class="tab">
        <iframe src="bib_list.php?a='.$application_pk.'" style="overflow:hidden; height:100%;width:100%; border: 0;" height="100%" width="100%"></iframe>
       </div>';

      echo '
       <div id="tab6" class="tab">
        <iframe src="hbf.php?a='.$application_pk.'" style="overflow:hidden; height:100%;width:100%; border: 0;" height="100%" width="100%"></iframe>
       </div>';
    ?>
  </div>
</div>
</article>
</form>
<script>

function updateGraduate(participant_pk) {

var part_pk = ''+participant_pk
var chbx_id = "grad_"+part_pk;

  if(document.getElementById(chbx_id).checked) {
      var tag = '6';
    }
    else
      var tag = '5';

  var username = '<?php echo $username;?>';
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues.php?command=update_participant_tag&b='+participant_pk+'&c='+tag+'&d='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      xmlhttp.responseText;
		//window.insertAttendanceLog(xmlhttp);
  };
  xmlhttp.send(null);
}

function updateGraduate_visitor(application_pk,value) {
  var username = '<?php echo $username;?>';
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', '_insertvalues.php?command=update_visitor_grad&b='+application_pk+'&c='+value+'&d='+username, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
      xmlhttp.responseText;
		//window.insertAttendanceLog(xmlhttp);
  };
  xmlhttp.send(null);
}

function dropCommunity(application_pk) {
    var reason = prompt("Doing this will drop ALL participants in this community. Please state the reason for dropping this community:");

	if (reason == null)
	{
		document.getElementById("notice").innerHTML = "";
	}

    else if (reason != "")
	{
		 window.location.href = '_applicationaction.php?a=7&b='+application_pk+'&c='+reason;
    }

	else
	{
		alert("A reason must be provided to drop a community.");
	}

}
</script>
<script src='default.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
jQuery(document).ready(function() {
  jQuery('.tabs .tab-links a').on('click', function(e)  {
      var currentAttrValue = jQuery(this).attr('href');

      // Show/Hide Tabs
      jQuery('.tabs ' + currentAttrValue).fadeIn(800).siblings().hide();

      // Change/remove current tab to active
      jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
      e.preventDefault();
  });
});

//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);

//chart
google.load('visualization', '1.1', {packages: ['line']});
google.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('number', 'Week');
    data.addColumn('number', 'Counselor');
    data.addColumn('number', 'Participant');

    data.addRows([
    <?php
      $week = "a";
      for($i=1;$i<17;$i++) {
        $att_counselor = getAttendance_total_byCommunity_byClass($application_pk,$week,"counselor");
        $att_participant = getAttendance_total_byCommunity_byClass($application_pk,$week,"participant");
        echo "[$i,$att_counselor,$att_participant]";
        if($i!=16) {
          echo ",";
        }
        $week++;
    }?>
    ]);

    var options = {
    width: 1200,
    height: 300,
    legend: { position: 'none' }
  };

  var chart = new google.charts.Line(document.getElementById('linechart_material'));

  chart.draw(data, options);
  }
</script>
</body>

</html>
