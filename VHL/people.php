<?php
  session_start();
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];

  include "../_css/bareringtonbear.css";
  include "../dbconnect.php";
  include "../_parentFunctions.php";
  include "_tnsFunctions.php";

  $application_pk = $_GET['a'];
  if(isset($_GET['b']))
    $sort_by = $_GET['b'];
  else
    $sort_by = "last_name";
  $application = getApplication_Data_byID($application_pk);
  $pastor_id = $application['pastor_id'];
  $pastor_name = $application['pastor_last_name'].", ".$application['pastor_first_name']." ".$application['pastor_middle_initial'];
  if(!empty($pastor_id))
    $pastor_id = "P".str_pad($pastor_id, 5, 0, STR_PAD_LEFT);
  else
    $pastor_id = "-";
?>

</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Project Tomorrow</title>
</head>

<body>
<form name="form1" action="" method="POST">
<article id="content">

<section id="col1">
  <a href="addPeople.php?a=<?php echo $application_pk; ?>">+People</a>
  <table border=1 width="825px">
    <tr align="center">
      <td rowspan="100" bgcolor='#1abc9c' width="20%">Active</td>
      <td width="10%">ID</td>
      <td width="15%">Name</td>
      <td width="10%">Score</td>
      <td width="20%">Category</td>
      <td width="25%">Action</td>
    </tr>
    <tr>
      <td id="numeric"><?php echo $pastor_id; ?></td>
      <td><?php echo $pastor_name; ?></td>
      <td></td>
      <td>Program Pastor</td>
      <td align="center"><?php if($pastor_id != '-') echo "<a href='/ICM/Thrive/viewPastor.php?a=$pastor_id'>";?>VIEW</a></td>
    </tr>
    <?php
      $query = getActive_Participants($application_pk,"participant_id");
      $result = pg_query($dbconn, $query);

    	while($participant = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
        $gender_highlight = "";
        $participant_pk = $participant['id'];
        $participant_id = $participant['participant_id'];
        $gender = $participant['gender'];
        $hh_start = substr($participant_id, 0, 8);
        $hh_end = substr($participant_id, -2);
        $participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];
        $category = getParticipant_class($participant['category']);
        $participant_score = $participant['variable2'];
        if($participant_score=="")
          $participant_score = "-";
        if($gender == "Male")
          $gender_highlight = "#8aa7ff";
        else if($gender == "Female")
          $gender_highlight = "#ff7885";
        else
          $gender_highlight = "";

        echo "<tr>
          <td id='numeric'>$hh_start-$hh_end</td>
          <td bgcolor='$gender_highlight'>$participant_name</td>
          <td id='numeric' align='center'>$participant_score</td>
          <td>$category</td>

          <td align='center'>
            <a href='quickedit.php?a=$application_pk&b=$participant_pk'>VIEW</a>&nbsp;&nbsp;|&nbsp;
            <a onClick=\"javascript: return confirm('Are you sure you want to drop this participant?');\" href='_applicationaction.php?a=8&b=$participant_pk&c=$username&d=$application_pk'>DROP</a></td>
          </tr>";

          //<td id='numeric'>$participant_score</td>
      }
    ?></tr>
  </table>
  <br/>
  <table border=1 width="825px">
  <tr align="center">
    <td rowspan="100" bgcolor='#FF7260' width="20%">Dropped</td>
    <td width="10%">ID</td>
    <td width="15%">Name</td>
    <td width="10%">Score</td>
    <td width="20%">Category</td>
    <td width="25%">Action</td>
  </tr>
    <?php
      $query = getParticipant_forApplication_byTag($application_pk,9,"participant_id","=");

    	while($participant = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
        $gender_highlight = "";
        $participant_id = $participant['participant_id'];
        $participant_pk = $participant['id'];
        $gender = $participant['gender'];
        $hh_start = substr($participant_id, 0, 8);
        $hh_end = substr($participant_id, -2);
        $participant_name = $participant['last_name'].", ".$participant['first_name']." ".$participant['middle_name'];
        $category = getParticipant_class($participant['category']);
        $participant_score = $participant['variable2'];
        if($participant_score=="")
          $participant_score = "-";
        if($participant_score=="")
          $participant_score = "-";
        if($gender == "Male")
          $gender_highlight = "#8aa7ff";
        else if($gender == "Female")
          $gender_highlight = "#ff7885";
        else
          $gender_highlight = "";

        echo "<tr>
          <td id='numeric'>$hh_start-$hh_end</td>
          <td bgcolor='$gender_highlight'>$participant_name</td>
          <td id='numeric' align='center'>$participant_score</td>
          <td>$category</td>
          <td align='center'><a href='quickedit.php?a=$application_pk&b=$participant_pk'>VIEW</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='_applicationaction.php?a=9&b=$participant_pk&c=$username&d=$application_pk'>RETURN</a></td></tr>";
      }
    ?>
  </table>
</section>
</article>
</form>

<script src="default.js"></script>
</body>

</html>
