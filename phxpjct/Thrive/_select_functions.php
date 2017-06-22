<?php
  $root = realpath($_SERVER['DOCUMENT_ROOT']);
  include_once $root."/PHX/_parent_functions.php";

  $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

  function get_thrive_district_by_base($base_pk) {
    global $db_connect;
    $result = pg_query($db_connect, "SELECT * FROM list_thrive_district WHERE base_id = $base_pk ORDER BY district_id");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      return $result;
    }
  }

  function get_thrive_district_pastor($district_pk) {
    global $db_connect;
    $result = pg_query($db_connect, "SELECT * FROM list_pastor WHERE thriveid = '$district_pk' ORDER BY lastname");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      return $result;
    }
  }

  function get_thrive_district_pastor_by_condition($district_pk,$condition,$value) {
    if ("all" == $condition) {
      $add_on = "";
    }
    else if("member" == $condition) {
      $add_on = "AND member = '$value'";
    }

    global $db_connect;
    $result = pg_query($db_connect, "SELECT * FROM list_pastor WHERE thriveid = '$district_pk' $add_on ORDER BY lastname");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      return $result;
    }
  }

  function get_pastor_profile($pastor_pk) {
    global $db_connect;
    $result = pg_query($db_connect, "SELECT * FROM list_pastor WHERE id = '$pastor_pk'");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      $row = pg_fetch_assoc($result);
      return $row;
    }
  }

  function count_thrive_district_pastor_by_condition($district_pk,$condition,$value) {
    if ("all" == $condition) {
      $add_on = "";
    }
    else if ("member" == $condition) {
      $add_on = "AND member = '$value'";
    }
    else if ("active" == $condition) {
      $add_on = "AND active = '$value'";
    }

    global $db_connect;
    $result = pg_query($db_connect, "SELECT count(*) FROM list_pastor WHERE thriveid = '$district_pk' $add_on");
    if (!$result) {
      echo "A result error occurred.\n";
      exit;
    }
    else {
      $row = pg_fetch_assoc($result);
      return $row['count'];
    }
  }
?>
