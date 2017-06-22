<?php

$connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
$db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

$profileless = 0;
$mismatch_type_a = 0;
$mismatch_type_b = 0;
$good = 0;
$complicated = 0;

$result = pg_query($db_connect, "select * from list_transform_application");
while($row = pg_fetch_assoc($result)) {
  $id_a = $row['pastor_id'];
  $id_b = $row['fk_pastor_pk'];

  if($id_a == 0 && $id_b == 0) {
    echo "no profiles \n";
    $profileless++;
  }
  else if($id_a == $id_b) {
    echo "good \n";
    $good++;
  }
  else if($id_a != $id_b && $id_a == 0) {
    echo "mismatch $id_a - $id_b\n";
    $mismatch_type_a++;
  }
  else if($id_a != $id_b && $id_b == 0) {
    echo "mismatch $id_a - $id_b\n";
    $mismatch_type_b++;
  }
  else {
    echo "complicated. $id_a - $id_b\n";
    $complicated++;
  }
  echo "<br/>";


}

echo "<br/>-breakdown- profileless: $profileless\n mistmatch_a: $mismatch_type_a\n mistmatch_b: $mismatch_type_b\n good: $good \n complicated: $complicated\n";
?>
