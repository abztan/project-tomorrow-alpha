  <select id="base">
  <option disabled selected value="0">Please Choose</option>
  <option value="1">Bacolod</option>
  <option value="2">Bohol</option>
  <option value="3">Dumaguete</option>
  <option value="4">General Santos</option>
  <option value="5">Koronadal</option>
  <option value="6">Palawan</option>
  <option value="7">Dipolog</option>
  <option value="8">Iloilo</option>
  <option value="9">Cebu</option>
  <option value="10">Roxas</option>
</select>

<select id="year">
  <option disabled selected value="0">Please Choose</option>
  <option value="1">2015</option>
  <option value="2">2016</option>
</select>

<select id="batch">
  <option disabled selected value="0">Please Choose</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
</select>

<br/>
<?php
  $connect_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password connect_timeout=5";
  $db_connect = pg_connect($connect_string) or die("Can't connect to database".pg_last_error());


$result = pg_query($db_connect, "SELECT * FROM list_transform_application WHERE community_id ilike '16___2%' ORDER BY community_id");
while($row = pg_fetch_assoc($result)) {
  $application_pk = $row['id'];
  $community_id = $row['community_id'];
  $pastor_id_one = $row['pastor_id'];
  $pastor_id_two = $row['fk_pastor_pk'];
  if ($pastor_id_one != $pastor_id_two) {
    $x = "x";
  }
  else {
    $x = "o";
  }
  $application_base = $row['base_id'];
  $application_lname = $row['pastor_last_name'];
  $application_fname = $row['pastor_first_name'];
  $application_mname = $row['pastor_middle_initial'];
  $application_short_lname = strtolower(substr($application_lname,0,3));
  $application_short_fname = strtolower(substr($application_fname,0,3));
  $application_short_mname = strtolower(substr($application_mname,0,1));

  echo "$community_id<br/>";
  echo "<div>($application_pk) $application_base. $application_lname, $application_fname $application_mname [$pastor_id_one] [$pastor_id_two] $x</div>";
  $query = "SELECT * FROM list_pastor WHERE lastname ilike '$application_short_lname%' AND firstname ilike '$application_short_fname%' AND middlename ilike '$application_short_mname%' ORDER BY id";
  $result_1 = pg_query($db_connect, $query);
  while($pastor = pg_fetch_assoc($result_1)) {
    $pastor_pk = $pastor['id'];
    $pastor_lname = $pastor['lastname'];
    $pastor_fname = $pastor['firstname'];
    $pastor_mname = $pastor['middlename'];
    $pastor_base = $pastor['baseid'];
    if ($pastor_id_one == $pastor_pk) {
      $hue = "green";
    }
    else {
      $hue = "red";
    }
    echo "<div style='color:$hue;'>$pastor_base. $pastor_lname, $pastor_fname $pastor_mname ($pastor_pk) <button onclick='inherit_id($application_pk,$pastor_pk)'>Update</button> <span id='$pastor_pk'></span></div>";
  }

  echo "<br/>";
}
?>

<script>
function inherit_id(application_pk,pastor_pk) {
  var xmlhttp = null;
  if(typeof XMLHttpRequest != 'udefined'){
      xmlhttp = new XMLHttpRequest();
  }else if(typeof ActiveXObject != 'undefined'){
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  }else
      throw new Error('You browser doesn\'t support ajax');

  xmlhttp.open('GET', 'test_script.php?application_pk='+application_pk+'&pastor_pk='+pastor_pk, true);
  xmlhttp.onreadystatechange = function (){
      if(xmlhttp.readyState == 4 && xmlhttp.status==200)
        if(xmlhttp.status == 200){
         document.getElementById(xmlhttp.responseText).innerHTML = "Updated!";
        }else
            throw new Error('Server has encountered an error\n'+
                'Error code = '+xmlhttp.status);
  };
  xmlhttp.send(null);
}
</script>
