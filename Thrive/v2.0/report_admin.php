<?php


function checkCommunityID_Duplicate()
{
  $query = "SELECT
      community_id, COUNT(*)
  FROM
      list_transform_application
  GROUP BY
       community_id
  HAVING
      COUNT(*) > 1";

  return $query;
}

function checkParticipantID_Duplicate()
{
  $query = "SELECT
      participant_id, COUNT(*)
  FROM
      list_transform_participant
  GROUP BY
       participant_id
  HAVING
      COUNT(*) > 1";

  return $query;
}

function checkPastor_Duplicate()
{
  $query = "SELECT
      firstname, lastname, COUNT(*)
  FROM
      list_pastor
  GROUP BY
       firstname, lastname
  HAVING
      COUNT(*) > 1";

  return $query;
}

	$community = checkCommunityID_Duplicate();
  $result = pg_query($dbconn, $community);

  $counter = -1;

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
    $counter++;
    if($counter==1)
    echo $community['community_id'];
  }

	$participant = checkCommunityID_Duplicate();
  $result = pg_query($dbconn, $participant);

  $counter1 = -1;

	while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
	{
    $counter1++;
    if($counter1==1)
    echo $participant['participant_id'];
  }


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
<br/>

<?php
  $pastor = checkPastor_Duplicate();
  $result = pg_query($dbconn, $pastor);
  $counter2 = -1;
  while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
    $counter2++;

  echo "$counter2 Duplicate Pastors:<br/><br/>";
  echo "<table border='1'><th>Base</th><th>ID</th><th>Name</th><th>District</th>";

  $pastor = checkPastor_Duplicate();
  $result = pg_query($dbconn, $pastor);
  while($row=pg_fetch_array($result,NULL,PGSQL_BOTH))
  {
    $fname = $row['firstname'];
    $lname = $row['lastname'];

    $query_o = "SELECT *
              FROM list_pastor
              WHERE lastname='$lname' AND
              firstname='$fname'";
    $result_o = pg_query($dbconn, $query_o);
    while($row_o=pg_fetch_array($result_o,NULL,PGSQL_BOTH)) {
     echo "<tr><td>".$row_o['baseid']."</td><td>".$row_o['id']."</td><td>".$row_o['firstname']." ".$row_o['lastname']."</td><td>".$row_o['thriveid']."</td></tr>";
    }
    $counter2++;
  }
  echo "</table>";
?>
</article>
</form>

<script src='default.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
//sticky menu
$('.menu').addClass('original').clone().insertAfter('.menu').addClass('cloned').css('position','fixed').css('top','0').css('margin-top','0').css('z-index','500').removeClass('original').hide();
scrollIntervalID = setInterval(stickIt, 10);
</script>
</body>

</html>
