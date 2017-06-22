<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}

	include "../dbconnect.php";
	include "../_parentFunctions.php";
	include "_tnsFunctions.php";

	//defaults
	$application_pk = $_GET['a'];
  $community =  getApplicationDetails($application_pk);
  $community_id = $community['community_id'];
  $pastor_name = $community['pastor_last_name'].", ".$community['pastor_first_name']." ".$community['pastor_middle_initial'];
  $application_type = getProgram($community['application_type']);
?>

<form name = "form1" action = "" method = "POST">

<div>Community ID: <?php echo $community_id;?></div>
<div>Pastor Name: <?php echo $pastor_name;?></div>
<div>Program: <?php echo $application_type;?></div>
<br/>
<table border="1">
  <th>#</th>
  <th>CLASS</th>
  <th>4P</th>
  <th>NGO</th>
  <th>MFI</th>
  <th>B.CERT</th>
  <th>ATT CH</th>
  <th>BAP</th>
  <th>H2H WK1</th>
  <th>H2H WK2</th>
  <th>H2H WK3</th>
  <th>H2H WK4</th>
  <th>WK1</th>
  <th>WK2</th>
  <th>WK3</th>
  <th>WK4</th>
  <th>WK5</th>
  <th>WK6</th>
  <th>WK7</th>
  <th>WK8</th>
  <th>WK9</th>
  <th>WK10</th>
  <th>WK11</th>
  <th>WK12</th>
  <th>WK13</th>
  <th>WK14</th>
  <th>WK15</th>
  <th>WK16</th>

<?php
  for($i=0;$i<13;$i++) {
    if($i == "1") {
      echo "<tr><td colspan='28'>Participants</td></tr>";
      $i_string = "Active";
      $tag = '5';
      $count = attendance_people_count($application_pk,$tag,1) +
               attendance_people_count($application_pk,$tag,2) +
               attendance_people_count($application_pk,$tag,3) +
               attendance_people_count($application_pk,$tag,4) +
               attendance_people_count($application_pk,$tag,5) +
               attendance_people_count($application_pk,$tag,6);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 1);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 1);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 1);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 1);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 1);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 1);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 2)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 2)."</td>";
      }
      echo "</tr>";
    }
    else if($i == "2") {
      $i_string = "Graduated";
      $tag = '6';
      $count = attendance_people_count($application_pk,$tag,1) +
               attendance_people_count($application_pk,$tag,2) +
               attendance_people_count($application_pk,$tag,3) +
               attendance_people_count($application_pk,$tag,4) +
               attendance_people_count($application_pk,$tag,5) +
               attendance_people_count($application_pk,$tag,6);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 1);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 1);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 1);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 1);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 1);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 1);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 2)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 2)."</td>";
      }
      echo "</tr>";
    }
    else if($i == "3") {
      $i_string = "Dropped";
      $tag = '9';
      $count = attendance_people_count($application_pk,$tag,1) +
               attendance_people_count($application_pk,$tag,2) +
               attendance_people_count($application_pk,$tag,3) +
               attendance_people_count($application_pk,$tag,4) +
               attendance_people_count($application_pk,$tag,5) +
               attendance_people_count($application_pk,$tag,6);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 1);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 1);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 1);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 1);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 1);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 1);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 2)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 2)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 2)."</td>";
      }
      echo "</tr>";
    }
    else if($i == "4") {
      echo "<tr><td colspan='28'>Counselors</td></tr>";
      $i_string = "Active";
      $tag = '5';
      $count = attendance_people_count($application_pk,$tag,20) +
               attendance_people_count($application_pk,$tag,21) +
               attendance_people_count($application_pk,$tag,22);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 3);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 3);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 3);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 3);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 3);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 3);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 4)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 4)."</td>";
      }
      echo "</tr>";
    }
    else if($i == "5") {
      $i_string = "Graduated";
      $tag = '6';
      $count = attendance_people_count($application_pk,$tag,20) +
               attendance_people_count($application_pk,$tag,21) +
               attendance_people_count($application_pk,$tag,22);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 3);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 3);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 3);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 3);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 3);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 3);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 4)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 4)."</td>";
      }
      echo "</tr>";
    }
    else if($i == "6") {
      $i_string = "Dropped";
      $tag = '9';
      $count = attendance_people_count($application_pk,$tag,20) +
               attendance_people_count($application_pk,$tag,21) +
               attendance_people_count($application_pk,$tag,22);
      $four_p = attendance_breakdown($application_pk, 1, "variable_1", $tag, 3);
      $ngo = attendance_breakdown($application_pk, 1, "variable_2", $tag, 3);
      $mfi = attendance_breakdown($application_pk, 1, "variable_3", $tag, 3);
      $birth_cert = attendance_breakdown($application_pk, 1, "variable_4", $tag, 3);
      $church = attendance_breakdown($application_pk, 1, "variable_5", $tag, 3);
      $baptised = attendance_breakdown($application_pk, 1, "variable_6", $tag, 3);

      echo "<tr>";
      echo "<td>$count</td>";
      echo "<td>$i_string</td>";
      echo "<td>$four_p</td>";
      echo "<td>$ngo</td>";
      echo "<td>$mfi</td>";
      echo "<td>$birth_cert</td>";
      echo "<td>$church</td>";
      echo "<td>$baptised</td>";
      echo "<td>".attendance_breakdown($application_pk, 'a', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'b', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'c', "variable_7", $tag, 4)."</td>";
      echo "<td>".attendance_breakdown($application_pk, 'd', "variable_7", $tag, 4)."</td>";
      for($a='a';$a<'q';$a++) {
        echo "<td>".attendance_breakdown($application_pk, $a, "attendance_set", $tag, 4)."</td>";
      }
      echo "</tr>";
    }

    else if($i == "7") {
      echo "<tr><td colspan='28'>Weekly</td></tr>";
      $i_string = "Visitor";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "count_visitor";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }

    else if($i == "8") {
      $i_string = "Children";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "count_child";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }

    else if($i == "9") {
      echo "<tr><td colspan='28'>Nutripack Distribution</td></tr>";
      $i_string = "Pastor/Counselor";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "nutripack_pastor";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }
    else if($i == "10") {
      $i_string = "Participant";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "nutripack_participant";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }
    else if($i == "11") {
      $i_string = "Visitor";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "nutripack_visitor";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }
    else if($i == "12") {
      $i_string = "Total";
      echo "<tr>";
      echo "<td></td>";
      echo "<td>$i_string</td>";
      echo "<td colspan='10'></td>";
      for ($a = '1'; $a < '17'; $a++) {
          $class_v = "nutripack_other";
          $c_array = getTransform_weekly_value($a,$application_pk);
          $c = $c_array[$class_v];
          echo "<td>$c</td>";
      }
      echo "</tr>";
    }
  }
?>
</table>
</form>
