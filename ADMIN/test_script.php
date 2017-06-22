<?php
  $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

  $query = "SELECT id, pastor_id, fk_pastor_pk FROM list_transform_application";
  $execute = pg_query($db_connect,$query);

  while($result = pg_fetch_assoc($execute)) {
    $application_pk = $result['id'];
    $pastor_id = $result['pastor_id'];
    $pastor_pk = $result['fk_pastor_pk'];

    if($pastor_id === $pastor_pk) {
      echo "ok<br/>";
    }
    else
      echo "nokay$application_pk<br/>";
    /*
    echo $query_x = "UPDATE list_transform_application SET fk_pastor_pk = '$pastor_id' WHERE id = '$application_pk'";
    $update_pastor = pg_query($db_connect,$query_x);*/
  }

  echo "done.";


?>
