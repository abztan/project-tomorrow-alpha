<html>
  <form name='form' action='' method='POST'>
  <input placeholder="Year: 2015,2016" type="text" name="year">
  <input placeholder="Batch: 1,2,3" type="text" name="batch">
  <button type="submit" name="search">Search</button><br/>
  </form>
  <table border="1" width="100%">
    <tr>
      <th>Base</th>
      <th>Community</th>
      <th>Community ID</th>
      <th>Name</th>
    </tr>

  <?php
    $year = "2016";
    $batch = "2";
    $last_base_id = "";
    $color = "yellow";

    $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
    $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());

    function getBaseName($a)
    {
    	if($a == "1")
    	  $base = "Bacolod";
    	else if($a == "2")
    	  $base = "Bohol";
    	else if($a == "3")
    	  $base = "Dumaguete";
    	else if($a == "4")
    	  $base = "General Santos";
    	else if($a == "5")
    	  $base = "Koronadal";
    	else if($a == "6")
    	  $base = "Palawan";
    	else if($a == "7")
    	  $base = "Dipolog";
    	else if($a == "8")
    	  $base = "Iloilo";
    	else if($a == "9")
    	  $base = "Cebu";
    	else if($a == "10")
    	  $base = "Roxas";
    	else if($a == "99")
    	  $base = "Hong Kong";
    	else if($a == "98")
    	  $base = "Manila";
    	else
    	  $base = "Undefined";

    	return $base;
    }

    function get_community_list($year,$program,$batch,$tag) {
      global $db_connect;
      $year = substr($year,2,2);
      if (90 < $program) {
        $program = "_";
      }

      $community_identity = $year."__".$program.$batch.'%';

      if (90 < $tag) {
        $tag_insert = "";
      }
      else {
        $tag_insert = "AND tag = '$tag'";
      }

      $query = "SELECT * FROM list_transform_application WHERE community_id ilike '$community_identity' $tag_insert
      ORDER BY base_id, community_id";
      $result = pg_query($db_connect, $query);
      if (!$result) {
        echo "A result error occurred.\n";
        exit;
      }
      else {
        return $result;
      }
    }

    if(isset($_POST['search']))
  	{
      $year=$_POST['year'];
      $batch=$_POST['batch'];
  	}

    $result = get_community_list($year,99,$batch,99);
    $i = 1;
    while($row = pg_fetch_array($result,NULL,PGSQL_BOTH)) {
      $base_id = $row['base_id'];

      if($last_base_id != $base_id) {
        $color = ($color == "yellow") ? "" : "yellow";
        $i = 1;
      }

      $base_name = getBaseName($base_id);
      $community_id = $row['community_id'];
      $location = $row['application_province'].", ".$row['application_city']." ".$row['application_barangay']." ".$row['location_note'];
      $pastor_name = $row['pastor_last_name'].", ".$row['pastor_first_name'];
      echo "
            <tr style='background-color:$color;'>
              <td align='center'>$i. $base_name</td>
              <td>$location</td>
              <td align='center'>$community_id</td>
              <td>$pastor_name</td>
            </tr>";

      $last_base_id = $base_id;
      $i++;
    }
  ?>
  </table>
</html>
