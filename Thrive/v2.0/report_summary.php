<?php
session_start();
if(empty($_SESSION['username']))
  header('location: /ICM/Login.php?a=2');
else {
  $username = $_SESSION['username'];
  $access_level = $_SESSION['accesslevel'];
  $account_base = $_SESSION['baseid'];
}
  include_once "_ptrFunctions.php";
	//defaults
	$count = 1;

  function countCard_data($base_id,$month) {
    $conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
  	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
    $base_id = str_pad($base_id, 2, 0, STR_PAD_LEFT);
    $query = "SELECT count(*)
              FROM log_thrive_card
              WHERE fk_thrive_pk ilike 'TD$base_id%'";
    $result = pg_query($dbconn,$query);
    $row = pg_fetch_array($result,NULL,PGSQL_BOTH);
    $return = $row[0];
    return $return;
  }
?>

<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  white-space: nowrap;
  font-family: 'Hind', sans-serif;
	font-size: 17px
}
</style>
<form name = "form" action = "" method = "POST">
<?php
   $query = "select distinct(year)
             from log_thrive_card order by year";
   $result=pg_query($dbconn, $query);
?>
    <select id="year" name="year" onChange="window.loadMonth_table()">
    <option disabled selected value='Empty'>Year</option>
    <?php
       while ($row=pg_fetch_row($result)) {
         echo "<option value='$row[0]'>$row[0]</option>";
       }
    ?>
    </select>
<br/>
<br/>

<table border="1" width="100%">
  <?php
    echo "<th colspan='2'></th>";
    for($i=1;$i<=12;$i++){
      echo "<th>$i</th>";
    }

    $result = getBase_list();
    while($base = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
      $base_id = $base['id'];
      $base_string = $base['name'];

      echo "
      <tr>
        <td rowspan='3'>$base_string</td>
        <td>Non Member</td>";
        for($i=1;$i<=12;$i++){
          echo "<td>$i</td>";
        }

      echo "
      </tr>
      <tr>
        <td>Member</td>";
        for($i=1;$i<=12;$i++){
          echo "<td>$i</td>";
        }

      echo "
      </tr>
      <tr>
        <td>Total</td>";
        for($i=1;$i<=12;$i++){
          echo "<td>$i</td>";
        }

      echo "</tr>";
  }
  ?>
</table>

</form>
