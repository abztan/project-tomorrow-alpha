<?php
  include_once "_tnsFunctions.php";
?>
<html>
<title>BIB Kits Report</title>
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto' rel='stylesheet' type='text/css'>
<style>
  body {font-family: 'Roboto', sans-serif;}
  table {
    border-collapse: collapse;
    overflow: hidden;
    border-spacing: 0;
    white-space: nowrap;
    font-family: 'Roboto Mono';
  	font-size: 14px;
  }
  td {padding:10 10 10 10;}

</style>

<h2>BIB KIT DISPERSAL</h2>

<table border="1">
<tr>
  <th>BIB Kits</th>
  <th>BAC</th>
  <th>BOH</th>
  <th>DGT</th>
  <th>GNS</th>
  <th>KOR</th>
  <th>PWN</th>
  <th>DPG</th>
  <th>ILO</th>
  <th>CEB</th>
  <th>RXS</th>
</tr>

<?php
  $query = getBIB_kit();
  while($row = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
    $kit_name = $row['kit_name'];
    $kit_id = $row['id'];
    $start = 1;

    for($i=1;$i<11;$i++) {
      $base_id = str_pad($i, 2, 0, STR_PAD_LEFT);
      $target = countBIB_community_kit_target_dispersal($kit_id,"15",$base_id,"3");
      $actual = countBIB_community_kit_actual_dispersal($kit_id,"15",$base_id,"3");
      $t_arr[] = $target;
      $a_arr[] = $actual;
    }

    if(array_sum($t_arr) != 0 && array_sum($a_arr) != 0) {
      if($start == 1) {
        echo "<tr align='center'>
                <td align='left'>$kit_name</td>";
      }
      foreach($t_arr as $index => $val) {
        $a = $t_arr[$index];
        $b = $a_arr[$index];
        echo "<td><strong>",($b > 0 ? $b : "-"),"</strong><br/>",($a > 0 ? $a : "-"),"</td>";
      }
    }

    echo "</tr>";
    $start++;
    unset($t_arr);
    unset($a_arr);
  }
?>
</table>

<h2>BIB KIT BALANCE</h2>

<table border="1">
<tr>
  <th>BIB Kits</th>
  <th>BAC</th>
  <th>BOH</th>
  <th>DGT</th>
  <th>GNS</th>
  <th>KOR</th>
  <th>PWN</th>
  <th>DPG</th>
  <th>ILO</th>
  <th>CEB</th>
  <th>RXS</th>
</tr>

<?php
  $query = getBIB_kit();
  while($row = pg_fetch_array($query,NULL,PGSQL_BOTH)) {
    $kit_name = $row['kit_name'];
    $kit_id = $row['id'];
    $start = 1;

    for($i=1;$i<11;$i++) {
      $base_id = str_pad($i, 2, 0, STR_PAD_LEFT);
      $target = number_format(countBIB_community_kit_target_payment($kit_id,"15",$base_id,"3"),2,'.',',');
      $actual = number_format(countBIB_community_kit_actual_payment($kit_id,"15",$base_id,"3"),2,'.',',');
      $t_arr[] = $target;
      $a_arr[] = $actual;
    }

    if(array_sum($t_arr) != 0 && array_sum($a_arr) != 0) {
      if($start == 1) {
        echo "<tr align='center'>
                <td align='left'>$kit_name</td>";
      }
      foreach($t_arr as $index => $val) {
        $a = $t_arr[$index];
        $b = $a_arr[$index];
        echo "<td><strong>",($b > 0 ? $b : "-"),"</strong><br/>",($a > 0 ? $a : "-"),"</td>";
      }
    }

    echo "</tr>";
    $start++;
    unset($t_arr);
    unset($a_arr);
  }
?>
</table>
</html>
