<?php
  include_once "_select_functions.php";
  $base_id = 1;
  $month = $_GET['month'];
?>
<html>
  <body>
    <div><a href="schedule.php">Return</a></div>
    <div>
      <br/>
      <div><?php echo get_base_string($base_id),": ",$month; ?></div>
      <?php
        $result = get_thrive_district_by_base($base_id);
        while ($district = pg_fetch_assoc($result)) {
          $district_pk = $district['district_id'];
          $district_base = $district['base_id'];
          $district_name = $district['alternate_name'];

          echo "<a href='district_view.php?district_pk=$district_pk'>$district_pk: $district_name</a><br/>";
        }
      ?>
      <div></div>
    </div>
  </body>
</html>
