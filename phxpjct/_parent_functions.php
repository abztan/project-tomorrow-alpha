<?php
  $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

  function get_base_string($base_id) {
    global $db_connect;
    $result = pg_query($db_connect, "SELECT * FROM list_base WHERE id = $base_id");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      $row = pg_fetch_assoc($result);
      return $row['name'];
    }
  }

  function flag_date_accuracy($set_date) {
    $date_time = new DateTime();
    $today = $date_time->format('Y-m-d');
    $set_year = substr($set_date, 0, 4);
    $current_year = $date_time->format('Y');
    $year_gap = $current_year - $set_year;
    if (strtotime($set_date) > strtotime($today)) {
      $result = "Call NASA, we've gone to the future!";
    }
    else if ($year_gap > 20 && $set_year != "") {
      $result = "Call Einstein, we've gone back in time!";
    }
    else {
      $result = "";
    }
    return $result;
  }

  function get_bases($account_base) {
    global $db_connect;
    if (90 < $account_base) {
      $result = pg_query($db_connect, "SELECT * FROM list_base");
    }
    else {
      $result = pg_query($db_connect, "SELECT * FROM list_base WHERE id = $account_base");
    }

    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      return $result;
    }
  }
?>
