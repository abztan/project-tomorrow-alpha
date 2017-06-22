<?php
  include_once "_select_functions.php";
  $base_id = 1;
  $account_base = 99;
  $i = 1;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script src="thrive_script.js"></script>
    <link rel="stylesheet" type="text/css" href="../tokyo.css"/>
  </head>

  <body>
    <div>International Care Ministries</div>
    <ul id="menu">
      <li><a href="index.php">Return</a></li>
    </ul>

    <div>
      <br/>
      <?php
        $parent_result = get_bases($account_base);
        while ($base = pg_fetch_assoc($parent_result)) {
          $base_pk = $base['id'];
          $base_name = $base['name'];

          echo "
            <div href='#' class='expand'>$base_name ($base_pk)</div>
            <div class='toggle_area'>";

          $result = get_thrive_district_by_base($base_pk);

          while ($district = pg_fetch_assoc($result)) {
            $district_pk = $district['district_id'];
            $district_base = $district['base_id'];
            $district_name = $district['alternate_name'];

            echo "<a href='district_view.php?district=$district_pk'>$district_pk: $district_name</a><br/>";
          }
          echo "</div>";
          $i++;
        }
      ?>
    </div>
  </body>
</html>
